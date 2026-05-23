<?php

use Adianti\Database\TTransaction;
use Adianti\Registry\TSession;

/**
 * FormUserTemaList
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @copyright  Copyright (c) 2021 Memore. (http://www.memore-net.com)
 * @license    http://www.memore-net.com/license
 */
class FormUsuarioTemaList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('jedieduca');            // defines the database
        parent::setActiveRecord('Usuario');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        parent::addFilterField('login', 'like', 'login'); // filterField, operator, formField
        //parent::addFilterField('active', '=', 'active'); // filterField, operator, formField
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_usuariotema_list');
        $this->form->setFormTitle(_t('Users'));
        

        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $login = new TEntry('login');
        
        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel(_t('Name'))], [$nome] );
        $this->form->addFields( [new TLabel('login')], [$login] );
        
        $id->setSize('30%');
        $nome->setSize('70%');
        $login->setSize('70%');
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('SystemUser_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        //$this->form->addAction('Temas',  new TAction(array('FormUsuarioTema', 'onEdit')), 'fa:plus green');

        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        $column_name = new TDataGridColumn('nome', _t('Name'), 'left');
        $column_login = new TDataGridColumn('login', _t('Login'), 'left');
        
        $column_login->enableAutoHide(500);

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_name);
        $this->datagrid->addColumn($column_login);

        
        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        $order_name = new TAction(array($this, 'onReload'));
        $order_name->setParameter('order', 'name');
        $column_name->setAction($order_name);
        
        $order_login = new TAction(array($this, 'onReload'));
        $order_login->setParameter('order', 'login');
        $column_login->setAction($order_login);
             
        
        // create EDIT action
        //$action_edit = new TDataGridAction(array('FormUsuarioTema', 'onView'));
        $action_edit = new TDataGridAction(array('FormUsuarioTema', 'onEdit'));
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('far:edit blue');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);
            
                     
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $panel = new TPanelGroup;
        $panel->add($this->datagrid)->style = 'overflow-x:auto';
        $panel->addFooter($this->pageNavigation);
        
        // header actions
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table fa-fw blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf fa-fw red' );
        $dropdown->addAction( _t('Save as XML'), new TAction([$this, 'onExportXML'], ['register_state' => 'false', 'static'=>'1']), 'fa:code fa-fw green' );
        $panel->addHeaderWidget( $dropdown );
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }
    
}
