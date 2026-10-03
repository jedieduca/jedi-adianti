<?php

use Adianti\Base\TStandardList;
use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Core\AdiantiCoreApplication;
use Adianti\Registry\TSession;
use Adianti\Widget\Base\TElement;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Datagrid\TDataGrid;
use Adianti\Widget\Datagrid\TDataGridAction;
use Adianti\Widget\Datagrid\TDataGridColumn;
use Adianti\Widget\Datagrid\TPageNavigation;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Form\TButton;
use Adianti\Widget\Form\TCombo;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TForm;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Util\TDropDown;
use Adianti\Widget\Util\TXMLBreadCrumb;
use Adianti\Wrapper\BootstrapDatagridWrapper;
use Adianti\Wrapper\BootstrapFormBuilder;

class AssociationRulesView extends TStandardList
{
    protected $container;
    protected $form;
    protected $datagrid;       // listing
    protected $pageNavigation; // Page Navigation
    protected $filter_label;

    /**
    * Page constructor
    */
    public function __construct()
    {
        parent::__construct();

        parent::setDatabase('jedi');                                     // defines the database
        parent::setActiveRecord('AssociationRule');                      // defines the active record
        parent::setDefaultOrder('id', 'asc');                            // defines the default order

        parent::addFilterField('id', '=', 'id');                                 // filterField, operator, formField
        parent::addFilterField('escola', '=', 'escola');                         // filterField, operator, formField
        parent::addFilterField('turma', '=', 'turma');                           // filterField, operator, formField
        parent::addFilterField('dt_jogo', '>=', 'dt_jogo_ini');                  // filterField, operator, formField
        parent::addFilterField('dt_jogo', '<=', 'dt_jogo_fim');                  // filterField, operator, formField
        parent::addFilterField('capacidade_critica', '=', 'capacidade_critica'); // filterField, operator, formField

        // FILTRO DE SEGURANÇA NO GRID POR PERFIL (CONSUMO DA SERVICE)
        parent::setCriteria(ClassesSchoolService::getSecurityCriteria());

        parent::setLimit(TSession::getValue(__CLASS__ . '_limit') ?? 10);

        parent::setAfterSearchCallback( [$this, 'onAfterSearch' ] );

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_AssociationRules');
        $this->form->setFormTitle(_t('Mining Association Rules'));

        // create the form fields
        $id     = new TEntry('id');
        $escola = new TCombo('escola');  // Novo campo TCombo para Escola
        $turma  = new TCombo('turma');   // Alterado de TEntry para TCombo        
        
        $dt_jogo_ini = new TDate('dt_jogo_ini');
        $dt_jogo_ini->setMask('dd/mm/yyyy');
        $dt_jogo_ini->setDatabaseMask('yyyy-mm-dd');
        $dt_jogo_fim = new TDate('dt_jogo_fim');
        $dt_jogo_fim->setMask('dd/mm/yyyy');
        $dt_jogo_fim->setDatabaseMask('yyyy-mm-dd');

        $capacidade_critica = new TCombo('capacidade_critica');
        $capacidade_critica->addItems( [
            'AUMENTOU' => 'AUMENTOU',
            'MANTEVE'  => 'MANTEVE',
            'DIMINUIU' => 'DIMINUIU',
        ] );

        // Define a ação de alteração da escola para atualizar as turmas via AJAX
        $escola->setChangeAction(new TAction([__CLASS__, 'onChangeEscola']));

        // 1. LER OS DADOS ANTERIORMENTE PESQUISADOS DA SESSÃO
        $filter_data = TSession::getValue(__CLASS__ . '_filter_data');

        // --- LÓGICA DE CARREGAMENTO DAS ESCOLAS (Perfil Admin vs Padrão) ---
        TTransaction::open('jedi');

        // Intercepta a query e exibe diretamente na saída padrão
        // TTransaction::setLogger(new TLoggerSTD);

        // 1. Carrega as Escolas e desabilita a combo se não for admin
        ClassesSchoolService::loadEscolas($escola);

        // 2. Determina a escola selecionada e carrega as Turmas
        $selected_escola = $filter_data->escola ?? $escola->getValue();
        $options_turmas  = ClassesSchoolService::getOptionsTurmas($selected_escola);
        $turma->addItems($options_turmas);

        TTransaction::close();

        // $id->setEditable(false);
        $id->setSize('30%');
        $escola->setSize('100%');
        $turma->setSize('100%');
        $turma->style = 'margin-right:4px;';
        $dt_jogo_ini->setSize('100%');
        $dt_jogo_fim->setSize('100%');
        $capacidade_critica->setSize('100%');

        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel(_t('School'))], [$escola] ); // Campo Escola
        $this->form->addFields( [new TLabel(_t('Class'))], [$turma] );
        $this->form->addFields( [new TLabel(_t('Initial Date'))], [$dt_jogo_ini] );
        $this->form->addFields( [new TLabel(_t('Final Date'))], [$dt_jogo_fim] );
        $this->form->addFields( [new TLabel(_t('Critical Capacity'))], [$capacidade_critica] );

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__ . '_filter_data') );

        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';

        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->width = '100%';
        $this->datagrid->setHeight(320);

        // creates the datagrid columns
        $col_id       = new TDataGridColumn('id', 'Id', 'center', 50);
        $col_school   = new TDataGridColumn('escola', _t('School'), 'left');
        $col_class    = new TDataGridColumn('turma', _t('Class'), 'left');
        $col_player   = new TDataGridColumn('nome', _t('Player'), 'left');
        $col_gameDate = new TDataGridColumn('dt_jogo', _t('Game Date'), 'left');
        $col_age      = new TDataGridColumn('idade', _t('Age'), 'right');
        $col_capacity = new TDataGridColumn('capacidade_critica', _t('Critical Capacity'), 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($col_id);
        $this->datagrid->addColumn($col_school);
        $this->datagrid->addColumn($col_class);
        $this->datagrid->addColumn($col_player);
        $this->datagrid->addColumn($col_gameDate);
        $this->datagrid->addColumn($col_age);
        $this->datagrid->addColumn($col_capacity);

        // format the columns in the DataGrid
        $col_gameDate->setTransformer( function($value, $object, $row) {
            $date = new DateTime($value);
            return $date->format('d/m/Y');
        });

        $col_capacity->setTransformer( function($value, $object, $row) {
            $class = ($value == 'AUMENTOU') ? 'success' : (($value == 'MANTEVE') ? 'warning' : 'danger');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:10pt;";
            $div->add($value);
            return $div;
        });

        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $col_id->setAction($order_id);

        $order_school = new TAction(array($this, 'onReload'));
        $order_school->setParameter('order', 'escola');
        $col_school->setAction($order_school);

        $order_class = new TAction(array($this, 'onReload'));
        $order_class->setParameter('order', 'turma');
        $col_class->setAction($order_class);

        $order_player = new TAction(array($this, 'onReload'));
        $order_player->setParameter('order', 'nome');
        $col_player->setAction($order_player);

        $order_gameDate = new TAction(array($this, 'onReload'));
        $order_gameDate->setParameter('order', 'dt_jogo');
        $col_gameDate->setAction($order_gameDate);

        $order_age = new TAction(array($this, 'onReload'));
        $order_age->setParameter('order', 'idade');
        $col_age->setAction($order_age);

        $order_capacity = new TAction(array($this, 'onReload'));
        $order_capacity->setParameter('order', 'capacidade_critica');
        $col_capacity->setAction($order_capacity);

        // create EDIT action
        $action_view = new TDataGridAction(array('AssociationRulesForm', 'onView'), ['register_state' => 'false'] );
        $action_view->setButtonClass('btn btn-default');
        $action_view->setLabel(_t('See more'));
        $action_view->setImage('fa:eye orange');
        $action_view->setField('id');
        $this->datagrid->addAction($action_view);

        // create the datagrid model
        $this->datagrid->createModel();

        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup();
        $panel->add($this->datagrid)->style = 'overflow-x:auto';
        $panel->addFooter($this->pageNavigation);
       
        $this->filter_label = $panel->addHeaderActionLink(_t('Filters'), new TAction([$this, 'onShowCurtainFilters']), 'fa:filter fa-fw');
        # $panel->addHeaderActionLink(_t('Apriori'), new TAction([$this, 'onShowCurtainApriori']), 'fa:sitemap fa-fw');

        // header actions
        $dropdown = new TDropDown(_t('Algorithms'), 'fa:file-lines');
        $dropdown->style = 'height:37px; margin-left:4px; margin-right:4px;';
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');

        // Cria a ação do dropdown sem tentar ler a sessão antecipadamente
        $dropdown->addAction(_t('Apriori'), new TAction(['AprioriView', 'onShow']), 'fa:file-lines fa-fw blue');

        $panel->addHeaderWidget( $dropdown );

        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->style = 'height:37px;';
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table fa-fw blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf fa-fw red' );
        $dropdown->addAction( _t('Save as XML'), new TAction([$this, 'onExportXML'], ['register_state' => 'false', 'static'=>'1']), 'fa:code fa-fw green' );
        $panel->addHeaderWidget( $dropdown );

        // header actions
        $dropdown = new TDropDown( TSession::getValue(__CLASS__ . '_limit') ?? '10', '');
        $dropdown->style = 'height:37px';
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( 10,   new TAction([$this, 'onChangeLimit'], ['register_state' => 'false', 'static'=>'1', 'limit' => '10']) );
        $dropdown->addAction( 20,   new TAction([$this, 'onChangeLimit'], ['register_state' => 'false', 'static'=>'1', 'limit' => '20']) );
        $dropdown->addAction( 50,   new TAction([$this, 'onChangeLimit'], ['register_state' => 'false', 'static'=>'1', 'limit' => '50']) );
        $dropdown->addAction( 100,  new TAction([$this, 'onChangeLimit'], ['register_state' => 'false', 'static'=>'1', 'limit' => '100']) );
        $dropdown->addAction( 1000, new TAction([$this, 'onChangeLimit'], ['register_state' => 'false', 'static'=>'1', 'limit' => '1000']) );
        $panel->addHeaderWidget( $dropdown );

        if (TSession::getValue(get_class($this).'_filter_counter') > 0)
        {
            $this->filter_label->class = 'btn btn-primary';
            $this->filter_label->setLabel(_t('Filters') . ' ('. TSession::getValue(get_class($this).'_filter_counter').')');
        }

        // Panel que armazena o gráfico
        $this->panelImagem = new TPanelGroup();
        // $this->panelImagem->style = 'text-align: center; width: 100%; max-height: 850px; overflow-y: auto; overflow-x: auto;';
        $this->panelImagem->style = 'text-align: center; width: 100%; height: auto; overflow: visible;';

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($panel);
        $container->add($this->panelImagem);
        
        parent::add($container);
    }

    public function onReload($param = NULL)
    {
        // Carrega os dados do Banco de Dados local (Padrão TStandardList)
        // parent::onReload($param);
        $objects = parent::onReload($param);

        if (str_starts_with($_REQUEST['method'] ?? '', 'onExport')) {
            return $objects;
        }

        try {
            // Recupera os dados do filtro que o Adianti salvou na sessão
            $filterData = TSession::getValue(__CLASS__ . '_filter_data');

            $params = [];
            if (!empty($filterData)) {
                // Convertemos o objeto de dados do formulário em um array para o service
                // Ajuste as chaves abaixo para baterem com o que o seu FastAPI espera
                $params['id']          = $filterData->id ?? null;
                $params['escola']      = $filterData->escola ?? null;
                $params['turma']       = $filterData->turma ?? null;
                $params['dt_jogo_ini'] = $filterData->dt_jogo_ini ?? null;
                $params['dt_jogo_fim'] = $filterData->dt_jogo_fim ?? null;
                
                $params['capacidade_critica'] = $filterData->capacidade_critica ?? null;

                // Removemos campos vazios para não enviar "?escola=&turma="
                $params = array_filter($params);
            }
            
            // FILTRO DE SEGURANÇA NOS PARÂMETROS DA API POR PERFIL (CONSUMO DA SERVICE)
            $params = ClassesSchoolService::applySecurityParams($params);

            if ($params === null)
            {
                $this->panelImagem->add(new TLabel('Nenhum gráfico disponível para os filtros selecionados.'));
                return;
            }

            // 3. Montamos a Query String
            $queryString = !empty($params) ? '?' . http_build_query($params) : '';
            $apiData = (array) JediEducaRestService::getData('/estatisticas/capacidade_critica'. $queryString);

            if (isset($apiData['link_imagem']->grafico_capacidade_critica)){
                // Componente de Imagem
                $image = new TImage($apiData['link_imagem']->grafico_capacidade_critica);
                $image->style = 'width: clamp(320px, 90vw, 1024px); height: auto; display: block; margin: 0 auto 20px auto; border: 1px solid #ddd; object-fit: contain;';
                $this->panelImagem->add($image);
            } else {
                $this->panelImagem->add(new TLabel('Nenhum gráfico disponível para os filtros selecionados.'));     

            }
    
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Sobrescreve a exportação CSV do framework:
     * usa ";" como separador e grava o BOM UTF-8 (compatível com Excel pt-BR)
     */
    protected function exportToCSV($output)
    {
        $this->limit = 0;                 // exporta todos os registros, não só a página atual
        $objects = $this->onReload([]);

        $handler = @fopen($output, 'w');
        if ($handler === false) {
            throw new Exception("Permissão negada: {$output}");
        }

        // BOM UTF-8 para o Excel exibir acentos corretamente
        fwrite($handler, "\xEF\xBB\xBF");

        TTransaction::openFake($this->database);

        // Cabeçalho
        $row = [];
        foreach ($this->datagrid->getColumns() as $column) {
            if ($column->isPrintable()) {
                $row[] = $column->getLabel();
            }
        }
        fputcsv($handler, $row, ';', '"', '', "\n");

        // Linhas
        if ($objects) {
            foreach ($objects as $object) {
                $row = [];
                foreach ($this->datagrid->getColumns() as $column) {
                    if ($column->isPrintable()) {
                        $column_name = $column->getName();

                        if (isset($object->$column_name)) {
                            $row[] = is_scalar($object->$column_name) ? $object->$column_name : '';
                        } else if (method_exists($object, 'render')) {
                            $column_name = (strpos($column_name, '{') === false) ? ('{' . $column_name . '}') : $column_name;
                            $row[] = $object->render($column_name);
                        } else {
                            $row[] = '';
                        }
                    }
                }
                fputcsv($handler, $row, ';', '"', '', "\n");
            }
        }

        fclose($handler);
        TTransaction::close();
    }



    public static function onChangeLimit($param)
    {
        TSession::setValue(__CLASS__ . '_limit', $param['limit'] );
        AdiantiCoreApplication::loadPage(__CLASS__, 'onReload');
    }

    /**
     *
     */
    public function onAfterSearch($datagrid, $options)
    {
        if (TSession::getValue(get_class($this) .'_filter_counter') > 0)
        {
            $this->filter_label->class = 'btn btn-primary';
            $this->filter_label->setLabel(_t('Filters') . ' ('. TSession::getValue(get_class($this) . '_filter_counter').')');
        }
        else
        {
            $this->filter_label->class = 'btn btn-default';
            $this->filter_label->setLabel(_t('Filters'));
        }    

    }

    /**
     *
    */
    public static function onShowCurtainFilters($param = null)
    {
        try
        {
            // create empty page for right panel
            $page = TPage::create();
            $page->setTargetContainer('adianti_right_panel');
            $page->setProperty('override', 'true');
            $page->setPageName(__CLASS__);
            
            $btn_close = new TButton('closeCurtain');
            $btn_close->onClick = "Template.closeRightPanel();";
            $btn_close->setLabel("Fechar");
            $btn_close->setImage('fas:times');
            
            // instantiate self class, populate filters in construct 
            $embed = new self;
            $embed->form->addHeaderWidget($btn_close);
            
            // embed form inside curtain
            $page->add($embed->form);
            $page->show();
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    /**
     *
     */
    public static function onShowCurtainApriori($param = null)
    {
        try
        {
            // create empty page for right panel
            $page = TPage::create();
            $page->setTargetContainer('adianti_right_panel');
            $page->setProperty('override', 'true');
            // $page->setPageName(__CLASS__);
            $page->setPageName('Apriori');
            
            $btn_close = new TButton('closeCurtain');
            $btn_close->onClick = "Template.closeRightPanel();";
            $btn_close->setLabel("Fechar");
            $btn_close->setImage('fas:times');
            
            // instantiate self class, populate filters in construct
            $embed = new self;
            $embed->form->addHeaderWidget($btn_close);
            
            // embed form inside curtain
            $page->add($embed->form);
            $page->show();
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    /**
     * Ação executada ao alterar a escola no formulário de busca
     * Recarrega a combo de turmas dinamicamente
     */
    public static function onChangeEscola($param)
    {
        try
        {
            TTransaction::open('jedi');

            $escola_nome = $param['escola'] ?? null;
            $options     = ClassesSchoolService::getOptionsTurmas($escola_nome);

            TTransaction::close();

            TCombo::reload('form_search_AssociationRules', 'turma', $options, true);
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    } 

    /**
     * Sobrescreve a ação de busca para validar o intervalo de datas
     */
    public function onSearch($param = null)
    {
        // 1. Obtém os dados submetidos pelo formulário
        $data = $this->form->getData();

        // 2. Verifica se AMBOS os campos de data estão preenchidos
        if (!empty($data->dt_jogo_ini) && !empty($data->dt_jogo_fim))
        {
            // Converte para timestamps para comparar com precisão (considera formato YYYY-MM-DD do TDate)
            $dt_ini = strtotime($data->dt_jogo_ini);
            $dt_fim = strtotime($data->dt_jogo_fim);

            // 3. Aplica a crítica: Data Final menor que Data Inicial
            if ($dt_fim < $dt_ini)
            {
                // Re-alimenta o formulário com os dados digitados pelo usuário
                $this->form->setData($data);

                // Exibe mensagem de erro e bloqueia a continuação
                new TMessage('error', 'A **Data Final** não pode ser menor que a **Data Inicial**.');
                return;
            }
        }

        // 4. Se a validação passar (ou se um dos campos estiver vazio), executa a busca padrão
        parent::onSearch($param);
    }
}