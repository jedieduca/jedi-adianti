<?php
/**
 * FormTurmaList
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @copyright  Copyright (c) 2021
 * @license    http://www.adianti.com.br/framework-license
 */
class FormTurmaList extends TStandardList
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
        parent::setActiveRecord('Turma');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('idescola', '=', 'idescola'); // filterField, operator, formField
        parent::addFilterField('idserieescolar', '=', 'idserieescolar'); // filterField, operator, formField
        parent::addFilterField('identificacao', 'like', 'identificacao'); // filterField, operator, formField
    
        // creates the form
        $this->form = new BootstrapFormBuilder('form_turma_list');
        $this->form->setFormTitle('Turma');
        

        // create the form fields
        //$id         = new TEntry('id');
        $escola     = new TDBCombo('idescola','jedieduca','Colegio','id','nome');
        $serie      = new TDBCombo('idserieescolar','jedieduca','SerieEscolar','id','descricao');
        $identificacao = new TEntry('identificacao');
 
        
        // add the fields
        //$this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel('Escola')], [$escola] );
        $this->form->addFields( [new TLabel('Série')], [$serie] );
        $this->form->addFields( [new TLabel('Identificação')], [$identificacao] );
        
        //$id->setSize('50%');
        $escola->setSize('40%');
        $serie->setSize('30%');
        $identificacao->setSize('30%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('SystemUser_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction(array('FormTurma2', 'onEdit')), 'fa:plus blue');
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        $column_id->setVisibility(false);
        $column_escola = new TDataGridColumn('idescola', 'Escola', 'left');
        $column_escola->setTransformer( function($value, $object, $row) {
            $colegio= new Colegio($value);
            return $colegio->nome;
        });
        $column_serie = new TDataGridColumn('idserieescolar', 'Série', 'left');
        $column_identificacao = new TDataGridColumn('identificacao', 'Identificação', 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_escola);
        $this->datagrid->addColumn($column_serie);
        $column_serie->setTransformer( function($value, $object, $row) {
            $serie = new SerieEscolar($value);
            return $serie->descricao;
        });
        $this->datagrid->addColumn($column_identificacao);

       
        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        $order_escola = new TAction(array($this, 'onReload'));
        $order_escola->setParameter('order', 'idescola');
        $column_escola->setAction($order_escola);
 
        $order_serie = new TAction(array($this, 'onReload'));
        $order_serie->setParameter('order', 'idserieescolar');
        $column_serie->setAction($order_serie);

        $order_identificacao = new TAction(array($this, 'onReload'));
        $order_identificacao->setParameter('order', 'identificacao');
        $column_identificacao->setAction($order_identificacao);

        // create EDIT action
        $action_edit = new TDataGridAction(array('FormTurma2', 'onEdit'));
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('far:edit blue');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('far:trash-alt red');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);
        
        
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
        $panel->addHeaderWidget( $dropdown );
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }

        public function onDelete($param)
    {
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead
        
        new TQuestion(_t('Do you really want to delete ?'), $action);
    }
    
    function Delete($param)
    {
        try
        {
            $key=$param['key'];
            
            TTransaction::open('jedieduca');
            $object = new Turma($key);
            $object->delete();

            TurmaAluno::RemoveAlunos($key);

            TTransaction::close();
            
            new TMessage('info', _t('Record deleted'));
        }
        catch (Exception $e)
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }
    
}
