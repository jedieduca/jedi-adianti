<?php
/**
 * FormSerieEscolarList
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos
 * @copyright  Copyright (c) 2021
 * @license    http://www.adianti.com.br/framework-license
 */
class FormSerieEscolarList extends TStandardList
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
        parent::setActiveRecord('SerieEscolar');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('descricao', 'like', 'descricao'); // filterField, operator, formField
        parent::addFilterField('idnivelensino', 'like', 'idnivelensino'); // filterField, operator, formField
    
        // creates the form
        $this->form = new BootstrapFormBuilder('form_serie_escolar_list');
        $this->form->setFormTitle('Série Escolar');
        

        // create the form fields
        $id = new TEntry('id');
        $descricao = new TEntry('descricao');
        $idNivelEnsino = new TDBCombo('idNivelEnsino','jedieduca','NivelEnsino','id','descricao');

        //Layout::Formulario();
        
        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel('Descrição')], [$descricao] );
        $this->form->addFields( [new TLabel('Nível de Ensino')], [$idNivelEnsino] );

        
        $id->setSize('30%');
        $descricao->setSize('70%');
        $idNivelEnsino->setSize('70%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('SystemUser_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction(array('FormSerieEscolar', 'onEdit')), 'fa:plus blue');
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        $column_descricao = new TDataGridColumn('descricao', 'Descrição', 'left');
        $column_nivelEnsino = new TDataGridColumn('idnivelensino', 'Nível de Ensino', 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_nivelEnsino);
        $column_nivelEnsino->setTransformer( function($value, $object, $row) {
            $nivel = new NivelEnsino($value);
            return $nivel->descricao;
        });

        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        $order_descricao = new TAction(array($this, 'onReload'));
        $order_descricao->setParameter('order', 'descricao');
        $column_descricao->setAction($order_descricao);
 
        $order_nivelEnsino = new TAction(array($this, 'onReload'));
        $order_nivelEnsino->setParameter('order', 'idnivelensino');
        $column_nivelEnsino->setAction($order_nivelEnsino);

        // create EDIT action
        $action_edit = new TDataGridAction(array('FormSerieEscolar', 'onEdit'));
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
        $dropdown->setButtonClass('btn waves-effect dropdown-toggle');
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

        function onDelete($param)   
    {
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead
        
        new TQuestion(_t('Do you really want to delete ?'), $action);
    }
    
    function Delete($param)
    {
        //echo '<pre>'; print_r($param); echo '</pre>'; return;
        try
        {
            $key=$param['key'];
            TTransaction::open('jedieduca');
            $conn = TTransaction::get();
            $sql='select count(*) as total from turma where idserieescolar='.$param['key'];
            $result = $conn->query($sql);
            $row = $result->fetch();
            $count = $row['total'];
            if ($count > 0) {
                new TMessage('error', 'Não é possível excluir esta série escolar, pois existem turmas associadas a ela.');
                return;
            }
            $conn = TTransaction::get();
            // run query
            $sql='delete FROM serieescolar ';
            $sql.='WHERE id='.$key;
            //echo '<pre>'; print_r($sql); echo '</pre>';
            $conn->query($sql);

            TTransaction::close();
                
            $this->onReload();
            new TMessage('info', _t("Record deleted"));

        }
        catch (Exception $e)
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }
    
}
