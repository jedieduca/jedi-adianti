<?php
/**
 * FormTemaList
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @copyright  Copyright (c) 2021 Memore. (http://www.memore-net.com)
 * @license    http://www.memore-net.com/license
 */
class FormTemaList extends TStandardList
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
        parent::setActiveRecord('Tema');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('nome', '=', 'nome'); // filterField, operator, formField
        parent::addFilterField('idarea', '=', 'idarea'); // filterField, operator, formField
    
        // creates the form
        $this->form = new BootstrapFormBuilder('form_tema_list');
        $this->form->setFormTitle('Tema');
        

        // create the form fields
        $id         = new TEntry('id');
        $idArea     = new TDBCombo('idarea','jedieduca','Area','id','descricao');
        $nome       = new TEntry('nome');
 
        
        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel('Nome')], [$nome] );
        $this->form->addFields( [new TLabel('Área')], [$idArea] );

        
        $id->setSize('50%');
        $nome->setSize('70%');
        $idArea->setSize('70%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('SystemUser_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction(array('FormTema', 'onEdit')), 'fa:plus green');
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_area = new TDataGridColumn('idarea', 'Área', 'left');
        $column_visibilidade = new TDataGridColumn('visibilidade', 'Visibilidade', 'center');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_area);
        $this->datagrid->addColumn($column_visibilidade);
        $column_visibilidade->setTransformer( function($value, $object, $row) {
            $class = ($value=='R') ? 'danger' : 'success';
            $label = ($value=='R') ? 'Restrita' : 'Pública';
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });

       
        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'nome');
        $column_nome->setAction($order_nome);
 
        $order_area = new TAction(array($this, 'onReload'));
        $order_area->setParameter('order', 'idarea');
        $column_area->setAction($order_area);

        // create EDIT action
        $action_edit = new TDataGridAction(array('FormTema', 'onEdit'));
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

    function onReload($param = NULL)
    {
        parent::onReload();
        
        $ini  = AdiantiApplicationConfig::get(); 

        $limit = 10;
        TTransaction::open('jedieduca');

        $conn = TTransaction::get();
        // run query
        $sql='DROP VIEW IF EXISTS tema2view ';
        $conn->query($sql);

        $sql='CREATE VIEW tema2view AS select *';
        $sql.='FROM tema2 ';
        if (strlen(array_search(1,TSession::getValue('usergroupids')))==0)
            $sql.='where idautor='.TSession::getValue('userid');

        $result = $conn->query($sql);
        $repository = new TRepository('TemaView');                
        $criteria = new TCriteria;
        $limit = 10; 
        $criteria->setProperties($param); // order, offset
        $criteria->setProperty('limit', $limit);
        //$criteria->setProperty('order', 'titulo');  
        
        $objects = $repository->load($criteria);

        $this->datagrid->clear();
        if ($objects)
        {
            foreach ($objects as $object)
            {
                //echo '<pre>'; print_r($object->id); echo '</pre>';
                $this->datagrid->addItem($object);
            }
        }
        $criteria->resetProperties();
        $count= $repository->count($criteria);
       
        $this->pageNavigation->setCount($count); // count of records
        $this->pageNavigation->setProperties($param); // order, page
        $this->pageNavigation->setLimit($limit); // limit
        //TTransaction::close();

    }

    public function VerificaPergunta($id)
    {
        TTransaction::open('jedieduca');
        $conn = TTransaction::get();
        // run query
        $sql='select count(idtema) cont FROM pergunta2 ';
        $sql.='WHERE idtema='.$id;
        $result = $conn->query($sql);
        $resulte = $result->fetchAll(PDO::FETCH_ASSOC);
        return $resulte[0]['cont'];
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
            
            if ($this->VerificaPergunta($key)!=0) 
            {
                new TMessage('info', "Tema não pode ser removido! Existe perguntas com esse tema.");
                return;
            }
            TTransaction::open('jedieduca');
            $object = new Tema($key);
            $object->delete();

            TTransaction::close();
            
            $this->onReload();

            new TMessage('info', _t('Record deleted'));
        }
        catch (Exception $e)
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }
    
}
