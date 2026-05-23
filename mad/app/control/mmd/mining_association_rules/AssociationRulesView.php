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
        parent::addFilterField('id', '=', 'id');                         // filterField, operator, formField
        parent::addFilterField('escola', 'like', 'name');                // filterField, operator, formField
        parent::addFilterField('turma', 'like', 'turma');                // filterField, operator, formField
        parent::addFilterField('jogador', 'like', 'jogador');            // filterField, operator, formField
        parent::addFilterField('capacidade_critica', '=', 'capacidade'); // filterField, operator, formField
        parent::setLimit(TSession::getValue(__CLASS__ . '_limit') ?? 10);

        parent::setAfterSearchCallback( [$this, 'onAfterSearch' ] );

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_AssociationRules');
        $this->form->setFormTitle(_t('Association Rules'));

        // create the form fields
        $id         = new TEntry('id');
        $turma      = new TEntry('turma');
        $jogador    = new TEntry('jogador');
        $capacidade = new TCombo('capacidade');
        $capacidade->addItems( [
            'AUMENTOU' => 'AUMENTOU',
            'MANTEVE'  => 'MANTEVE',
            'DIMINUIU' => 'DIMINUIU',
        ] );

        // $id->setEditable(false);
        $id->setSize('30%');
        $turma->setSize('100%');
        $turma->style = 'margin-right:4px;';
        $jogador->setSize('100%');
        $capacidade->setSize('100%');

        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel(_t('Class'))], [$turma] );
        $this->form->addFields( [new TLabel(_t('Player'))], [$jogador] );
        $this->form->addFields( [new TLabel(_t('Critical Capacity'))], [$capacidade] );

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__ . '_filter_data') );

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
        $col_id       = new TDataGridColumn('id', 'Id', 'center', 50);
        $col_school   = new TDataGridColumn('escola', _t('School'), 'left');
        $col_class    = new TDataGridColumn('turma', _t('Class'), 'left');
        $col_player   = new TDataGridColumn('jogador', _t('Player'), 'left');
        $col_gameDate = new TDataGridColumn('dt_jogo', _t('Game Date'), 'left');
        $col_age      = new TDataGridColumn('idade', _t('Age'), 'right');
        $col_capacity = new TDataGridColumn('capacidade_critica', _t('Critical Capacity'), 'left');

        // $col_school->enableAutoHide(500);
        // $col_class->enableAutoHide(500);
        // $col_age->enableAutoHide(500);

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
        $order_player->setParameter('order', 'jogador');
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
        $dropdown->addAction( _t('Apriori'), new TAction(['AprioriView', 'onShow'], ['filtros' => TSession::getValue(get_class($this). '_filter_data'), 'data' => $this->datagrid]), 'fa:file-lines fa-fw blue');
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

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($panel);
        
        parent::add($container);
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
}