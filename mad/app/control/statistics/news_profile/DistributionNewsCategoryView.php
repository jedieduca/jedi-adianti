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

class DistributionNewsCategoryView extends TStandardList
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
        parent::setActiveRecord('DistributionNewsCategory');             // defines the active record
        parent::setDefaultOrder('id', 'asc');                            // defines the default order
        parent::addFilterField('id', '=', 'id');                         // filterField, operator, formField
        parent::addFilterField('categoria', '=', 'categoria');           // filterField, operator, formField
        parent::setLimit(TSession::getValue(__CLASS__ . '_limit') ?? 10);

        parent::setAfterSearchCallback( [$this, 'onAfterSearch' ] );

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_DistributionNewsCategory');
        $this->form->setFormTitle(_t('Distribution of News by Category'));

        // create the form fields
        $id        = new TEntry('id');
        $categoria = new TDBCombo('categoria', 'jedi', 'DistributionNewsCategory', 'categoria', 'categoria');

        // $id->setEditable(false);
        $id->setSize('30%');
        $categoria->setSize('70%');

        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel(_t('Category'))], [$categoria] );

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
        $col_id            = new TDataGridColumn('id', 'Id', 'center', 50);
        $col_categoria     = new TDataGridColumn('categoria', _t('Category'), 'left');
        $col_fake_qt       = new TDataGridColumn('fake_qt', 'Fake (Qt.)', 'right');
        $col_fake_perc     = new TDataGridColumn('fake_perc', 'Fake (%)', 'right');
        $col_nao_fake_qt   = new TDataGridColumn('nao_fake_qt', 'Não Fake (Qt.)', 'right');
        $col_nao_fake_perc = new TDataGridColumn('nao_fake_perc', 'Não Fake (%)', 'right');

        // format the columns in the DataGrid
        $col_fake_perc->setTransformer( function($value, $object, $row) {
            return number_format($value, 2, ',');
        });

        $col_nao_fake_perc->setTransformer( function($value, $object, $row) {
            return number_format($value, 2, ',');
        });

        // add the columns to the DataGrid
        $this->datagrid->addColumn($col_id);
        $this->datagrid->addColumn($col_categoria);
        $this->datagrid->addColumn($col_fake_qt );
        $this->datagrid->addColumn($col_fake_perc);
        $this->datagrid->addColumn($col_nao_fake_qt);
        $this->datagrid->addColumn($col_nao_fake_perc);

        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $col_id->setAction($order_id);

        $order_categoria = new TAction(array($this, 'onReload'));
        $order_categoria->setParameter('order', 'categoria');
        $col_categoria->setAction($order_categoria);

        $order_fake_qt = new TAction(array($this, 'onReload'));
        $order_fake_qt->setParameter('order', 'fake_qt');
        $col_fake_qt->setAction($order_fake_qt);

        $order_fake_perc = new TAction(array($this, 'onReload'));
        $order_fake_perc->setParameter('order', 'fake_perc');
        $col_fake_perc->setAction($order_fake_perc);

        $order_nao_fake_qt = new TAction(array($this, 'onReload'));
        $order_nao_fake_qt->setParameter('order', 'nao_fake_qt');
        $col_nao_fake_qt->setAction($order_nao_fake_qt);

        $order_nao_fake_perc = new TAction(array($this, 'onReload'));
        $order_nao_fake_perc->setParameter('order', 'nao_fake_perc');
        $col_nao_fake_perc->setAction($order_nao_fake_perc);

        // create EDIT action
        $action_view = new TDataGridAction(array('DistributionNewsCategoryForm', 'onView'), ['register_state' => 'false'] );
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
                $params['categoria'] = $filterData->categoria ?? null;
                
                // Removemos campos vazios para não enviar "?escola=&turma="
                $params = array_filter($params);
            }

            // Montamos a Query String
            $queryString = !empty($params) ? '?' . http_build_query($params) : '';

            $apiData = (array) JediEducaRestService::getData('/estatisticas/perfil_noticia'. $queryString);

            if (isset($apiData['link_imagem']->grafico_perfil_noticia)){
                // Componente de Imagem
                $this->imageContainer = new TImage($apiData['link_imagem']->grafico_perfil_noticia);
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