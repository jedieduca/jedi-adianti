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

class StatisticsAvaliationView extends TStandardList
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

        parent::setDatabase('jedi');                                     // defines the database
        parent::setActiveRecord('StatisticsAvaliation');                 // defines the active record
        parent::setDefaultOrder('id', 'asc');                            // defines the default order
        parent::addFilterField('id', '=', 'id');                         // filterField, operator, formField
        parent::addFilterField('escola', '=', 'escola');                         // filterField, operator, formField
        parent::addFilterField('turma', '=', 'turma');                         // filterField, operator, formField
        parent::addFilterField('avaliacao', 'like', 'avaliacao');             // filterField, operator, formField
        parent::setLimit(TSession::getValue(__CLASS__ . '_limit') ?? 10);

        parent::setAfterSearchCallback( [$this, 'onAfterSearch' ] );

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_StatisticsAvaliation');
        $this->form->setFormTitle(_t('Distribution of Results by Self-Assessment Levels'));

        // create the form fields
        $id        = new TEntry('id');
        $escola    = new TDBCombo('escola', 'jedi', 'StatisticsAvaliation', 'escola', 'escola');
        $turma     = new TDBCombo('turma', 'jedi', 'StatisticsAvaliation', 'turma', 'turma');
        $avaliacao = new TDBCombo('avaliacao', 'jedi', 'StatisticsAvaliation', 'avaliacao', 'avaliacao');

        // $id->setEditable(false);
        $id->setSize('30%');
        $escola->setSize('70%');
        $turma->setSize('70%');
        $avaliacao->setSize('70%');

        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel(_t('School'))], [$escola] );
        $this->form->addFields( [new TLabel(_t('Class'))], [$turma] );
        $this->form->addFields( [new TLabel(_t('Assessment'))], [$avaliacao] );
        // $this->form->addFields( [new TLabel(_t('Self-assessment'))], [$autoavaliacao] );
        // $this->form->addFields( [new TLabel(_t('Game review'))], [$avaliacao_jogo] );

        // keep the form filled during navigation with session data
        $this->form->setData(TSession::getValue(__CLASS__ . '_filter_data') );

        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';

        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        // $this->datagrid->datatable = 'true';
        $this->datagrid->width = '100%';
        //$this->datagrid->enablePopover('Detalhes: ', '<b>ID: </b> {id} <br> <b>Escola: </b> {escola} <br>');
        $this->datagrid->setHeight(320);

        // creates the datagrid columns
        $col_id             = new TDataGridColumn('id', 'Id', 'center', 50);
        $col_escola         = new TDataGridColumn('escola', _t('School'), 'left');
        $col_turma          = new TDataGridColumn('turma', _t('Class'), 'left');
        $col_avaliacao      = new TDataGridColumn('avaliacao', _t('Assessment'), 'left');
        $col_autoavaliacao  = new TDataGridColumn('autoavaliacao', _t('Self-assessment'), 'right');
        $col_avaliacao_jogo = new TDataGridColumn('avaliacao_jogo', _t('Game review'), 'right');

        // format the columns in the DataGrid
        $col_autoavaliacao->setTransformer( function($value, $object, $row) {
            return number_format($value, 2, ',');
        });

        $col_avaliacao_jogo->setTransformer( function($value, $object, $row) {
            return number_format($value, 2, ',');
        });

        // add the columns to the DataGrid
        $this->datagrid->addColumn($col_id);
        $this->datagrid->addColumn($col_escola);
        $this->datagrid->addColumn($col_turma);
        $this->datagrid->addColumn($col_avaliacao);
        $this->datagrid->addColumn($col_autoavaliacao);
        $this->datagrid->addColumn($col_avaliacao_jogo);

        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $col_id->setAction($order_id);

        $order_escola = new TAction(array($this, 'onReload'));
        $order_escola->setParameter('order', 'escola');
        $col_escola->setAction($order_escola);

        $order_turma = new TAction(array($this, 'onReload'));
        $order_turma->setParameter('order', 'turma');
        $col_turma->setAction($order_turma);

        $order_avaliacao = new TAction(array($this, 'onReload'));
        $order_avaliacao->setParameter('order', 'avaliacao');
        $col_avaliacao->setAction($order_avaliacao);

        $order_autoavaliacao = new TAction(array($this, 'onReload'));
        $order_autoavaliacao->setParameter('order', 'autoavaliacao');
        $col_autoavaliacao->setAction($order_autoavaliacao);

        $order_avaliacao_jogo = new TAction(array($this, 'onReload'));
        $order_avaliacao_jogo->setParameter('order', 'avaliacao_jogo');
        $col_avaliacao_jogo->setAction($order_avaliacao_jogo);

        // create EDIT action
        $action_view = new TDataGridAction(array('StatisticsAvaliationForm', 'onView'), ['register_state' => 'false'] );
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
        // $panel->addHeaderActionLink(_t('Apriori'), new TAction([$this, 'onShowCurtainApriori']), 'fa:sitemap fa-fw');

        // header actions
        // $dropdown = new TDropDown(_t('Algorithms'), 'fa:file-lines');
        // $dropdown->style = 'height:37px; margin-left:4px; margin-right:4px;';
        // $dropdown->setPullSide('right');
        // $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        // $dropdown->addAction( _t('Apriori'), new TAction(['AprioriView', 'onEdit'], ['filtros' => TSession::getValue(get_class($this). '_filter_data'), 'data' => $this->datagrid]), 'fa:file-lines fa-fw blue');
        // $panel->addHeaderWidget( $dropdown );

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
        $this->panelImagem->style = 'text-align: center; width: 100%;'; 
        $this->imageContainer = new THBox;
        $this->imageContainer->style = 'width: 100%; margin-bottom: 20px; text-align: center;';        

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
                $params['id']        = $filterData->id ?? null;
                $params['escola']    = $filterData->escola ?? null;
                $params['turma']     = $filterData->turma ?? null;
                $params['avaliacao'] = $filterData->avaliacao ?? null;
                
                // Removemos campos vazios para não enviar "?escola=&turma="
                $params = array_filter($params);
            }

            // Montamos a Query String
            $queryString = !empty($params) ? '?' . http_build_query($params) : '';

            $apiData = (array) JediEducaRestService::getData('/estatisticas/avaliacao'. $queryString);

            if (isset($apiData['link_imagem']->grafico_avaliacao)){
                // Componente de Imagem
                $this->imageContainer = new TImage($apiData['link_imagem']->grafico_avaliacao);
                $this->imageContainer->style = 'width: 85%; height: auto; margin-bottom: 20px; border: 1px solid #ddd';                
                $this->panelImagem->add($this->imageContainer);
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
    
        /*
        if (!empty(TSession::getValue(get_class($this). '_filter_data')))
        {
            $obj = new stdClass;
            $obj->name = TSession::getValue(get_class($this).'_filter_data')->name;
            TForm::sendData('form_search_name', $obj);
        }
        */
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
}