<?php

use Adianti\Base\TStandardList;
use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Core\AdiantiCoreApplication;
use Adianti\Registry\TSession;
use Adianti\Widget\Container\THBox;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Datagrid\TDataGrid;
use Adianti\Widget\Datagrid\TDataGridAction;
use Adianti\Widget\Datagrid\TDataGridColumn;
use Adianti\Widget\Datagrid\TPageNavigation;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Form\TButton;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Util\TDropDown;
use Adianti\Widget\Util\TImage;
use Adianti\Widget\Util\TXMLBreadCrumb;
use Adianti\Widget\Wrapper\TDBCombo;
use Adianti\Wrapper\BootstrapDatagridWrapper;
use Adianti\Wrapper\BootstrapFormBuilder;

class SchoolProfilesView extends TStandardList
{
    protected $form;
    protected $panelImagem;
    protected $imageContainer;    
    protected $datagrid;       // listing
    protected $pageNavigation; // Page Navigation
    protected $filter_label;

    /**
    * Page constructor
    */
    public function __construct()
    {
        parent::__construct();

        parent::setDatabase('jedi');                           // defines the database
        parent::setActiveRecord('SchoolProfiles');             // defines the active record
        parent::setDefaultOrder('id', 'asc');                  // defines the default order
        parent::addFilterField('id', '=', 'id');               // filterField, operator, formField
        parent::addFilterField('escola', '=', 'escola');       // filterField, operator, formField

        // FILTRO DE SEGURANÇA NO GRID POR PERFIL (CONSUMO DA SERVICE)
        parent::setCriteria(ClassesSchoolService::getSecurityCriteria());

        parent::setLimit(TSession::getValue(__CLASS__ . '_limit') ?? 10);

        parent::setAfterSearchCallback( [$this, 'onAfterSearch' ] );

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_SchoolProfiles');
        $this->form->setFormTitle(_t('School Profiles'));

        // create the form fields
        $id          = new TEntry('id');
        $escola      = new TCombo('escola');  // Novo campo TCombo para Escola

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
        //$options_turmas  = ClassesSchoolService::getOptionsTurmas($selected_escola);
        // $turma->addItems($options_turmas);

        TTransaction::close();        

        // $id->setEditable(false);
        $id->setSize('30%');
        $escola->setSize('70%');

        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel(_t('School'))], [$escola] );

        // keep the form filled during navigation with session data
        $this->form->setData(TSession::getValue(__CLASS__ . '_filter_data') );

        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';

        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->width = '100%';
        $this->datagrid->setHeight(320);

        // creates the datagrid columns
        $col_id              = new TDataGridColumn('id', 'Id', 'center', 50);
        $col_escola          = new TDataGridColumn('escola', _t('School'), 'left');
        $col_num_turmas      = new TDataGridColumn('num_turmas', _t('Class'), 'right');
        $col_num_discentes   = new TDataGridColumn('num_discentes', _t('Number of Students'), 'right');
        $col_num_docentes    = new TDataGridColumn('num_docentes', _t('Number of Teachers'), 'right');
        $col_num_gestores    = new TDataGridColumn('num_gestores', _t('Number of Educational Managers'), 'right');
        $col_num_secretarios = new TDataGridColumn('num_secretarios', _t('Number of Educational Managers'), 'right');
        $col_total           = new TDataGridColumn('total', _t('Total'), 'right');

        // format the columns in the DataGrid
        $col_total->setTransformer( function($value, $object, $row) {
            return number_format($value, 0, ',', '.');
        });

        // add the columns to the DataGrid
        $this->datagrid->addColumn($col_id);
        $this->datagrid->addColumn($col_escola);
        $this->datagrid->addColumn($col_num_turmas);
        $this->datagrid->addColumn($col_num_discentes);
        $this->datagrid->addColumn($col_num_docentes);
        $this->datagrid->addColumn($col_num_gestores);
        $this->datagrid->addColumn($col_num_secretarios);
        $this->datagrid->addColumn($col_total);

        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $col_id->setAction($order_id);

        $order_escola = new TAction(array($this, 'onReload'));
        $order_escola->setParameter('order', 'escola');
        $col_escola->setAction($order_escola);

        $order_num_turmas = new TAction(array($this, 'onReload'));
        $order_num_turmas->setParameter('order', 'num_turmas');
        $col_num_turmas->setAction($order_num_turmas);

        $order_num_discentes = new TAction(array($this, 'onReload'));
        $order_num_discentes->setParameter('order', 'num_discentes');
        $col_num_discentes->setAction($order_num_discentes);

        $order_num_docentes = new TAction(array($this, 'onReload'));
        $order_num_docentes->setParameter('order', 'num_docentes');
        $col_num_docentes->setAction($order_num_docentes);

        $order_num_gestores = new TAction(array($this, 'onReload'));
        $order_num_gestores->setParameter('order', 'num_gestores');
        $col_num_gestores->setAction($order_num_gestores);

        $order_num_secretarios = new TAction(array($this, 'onReload'));
        $order_num_secretarios->setParameter('order', 'num_secretarios');
        $col_num_secretarios->setAction($order_num_secretarios);

        $order_total = new TAction(array($this, 'onReload'));
        $order_total->setParameter('order', 'total');
        $col_total->setAction($order_total);

        // create EDIT action
        $action_view = new TDataGridAction(array('SchoolProfilesForm', 'onView'), ['register_state' => 'false'] );
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
        $this->panelImagem->style = 'text-align: center; width: 100%; height: auto; overflow: visible;';

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        //$container->add($this->form);
        $container->add($panel);
        $container->add($this->panelImagem);
        
        parent::add($container);
    }

    public function onReload($param = NULL)
    {
        // Carrega os dados do Banco de Dados local (Padrão TStandardList)
        parent::onReload($param);

        try {
            // Recupera os dados do filtro que o Adianti salvou na sessão
            $filterData = TSession::getValue(__CLASS__ . '_filter_data');

            $params = [];
            if (!empty($filterData)) {
                // Convertemos o objeto de dados do formulário em um array para o service
                // Ajuste as chaves abaixo para baterem com o que o seu FastAPI espera
                $params['id']          = $filterData->id ?? null;
                $params['escola']      = $filterData->escola ?? null;

                // Removemos campos vazios para não enviar "?escola=&turma="
                $params = array_filter($params);
            }
            
            // ==========================================
            // FILTRAGEM AUTOMÁTICA DE ESCOLA PARA NÃO-ADMIN
            // ==========================================
            if (empty($params['escola']))
            {
                $profile = ClassesSchoolService::getUserProfile();

                // Se NÃO for administrador, busca a escola atrelada ao usuário
                if (!$profile['is_admin'])
                {
                    TTransaction::open('jedi');
                    
                    $usuario_escolas = SchoolsUser::where('id_usuario', '=', $profile['system_user_id'])->load();
                    
                    if ($usuario_escolas)
                    {
                        // Pega o primeiro vínculo do usuário
                        $primeira_escola = reset($usuario_escolas);
                        $objEscola = Schools::find($primeira_escola->id_escola);

                        if ($objEscola && !empty($objEscola->nome))
                        {
                            $params['escola'] = $objEscola->nome;
                        }
                    }

                    TTransaction::close();
                }
            }

            // 3. Montamos a Query String
            $queryString = !empty($params) ? '?' . http_build_query($params) : '';
            $apiData = (array) JediEducaRestService::getData('/estatisticas/perfil_escolas'. $queryString);

            if (isset($apiData['link_imagem']->grafico_perfil_escolas)){
                // Componente de Imagem
                $image = new TImage($apiData['link_imagem']->grafico_perfil_escolas);
                $image->style = 'width: clamp(320px, 90vw, 1024px); height: auto; display: block; margin: 0 auto 20px auto; border: 1px solid #ddd; object-fit: contain;';
                $this->panelImagem->add($image);
            } else {
                $this->panelImagem->add(new TLabel('Nenhum gráfico disponível para os filtros selecionados.'));     

            }
    
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onShow()
    {
        $this->onReload();
        parent::show();
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
     * Ação executada ao alterar a escola no formulário de busca
     * Recarrega a combo de turmas dinamicamente
     */
    public static function onChangeEscola($param)
    {
        try
        {
            TTransaction::open('jedi');

            $escola_nome    = $param['escola'] ?? null;
            $options_turmas = ClassesSchoolService::getOptionsTurmas($escola_nome);

            TTransaction::close();

            // Recarrega o combo 'turma' do formulário atual
            TCombo::reload('form_search_NumMatches', 'turma', $options_turmas, true);
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }    
}