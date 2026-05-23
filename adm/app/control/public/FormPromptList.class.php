<?php
/**
 * FormPromptList
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @copyright  Copyright (c) 2021 Memore. (http://www.memore-net.com)
 * @license    http://www.memore-net.com/license
 */
class FormPromptList extends TStandardList
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
        parent::setActiveRecord('Prompt');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('system_prompt', '=', 'system_prompt'); // filterField, operator, formField
        parent::addFilterField('user_prompt', '=', 'user_prompt'); // filterField, operator, formField
        parent::addFilterField('id_tema', '=', 'id_tema'); // filterField, operator, formField
    
        // creates the form
        $this->form = new BootstrapFormBuilder('form_prompt_list');
        $this->form->setFormTitle('Cadastro de Prompt');
        

        // create the form fields
        $id         = new TEntry('id');
        $id->setSize('10%');
        $idTema     = new TDBCombo('id_tema','jedieduca','Tema','id','nome');
        $idTema->setSize('30%');
        /*$systemPrompt   = new TEntry('system_prompt');
        $systemPrompt->setSize('70%');
        $userPrompt   = new TEntry('user_prompt');
        $userPrompt->setSize('70%');*/
 
        
        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel('Tema')], [$idTema] );
        //$this->form->addFields( [new TLabel('System Prompt')], [$systemPrompt] );
        //$this->form->addFields( [new TLabel('User Prompt')], [$userPrompt] );

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('SystemUser_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction(array('FormPrompt', 'onEdit')), 'fa:plus green');
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        $column_tema = new TDataGridColumn('id_tema', 'Área', 'left');
        $column_system = new TDataGridColumn('system_prompt', 'System Prompt', 'left');
        $column_user1 = new TDataGridColumn('user_prompt1', 'User Prompt (Passo 1)', 'left');
        $column_user2 = new TDataGridColumn('user_prompt2', 'User Prompt (Passo 2)', 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_tema);
        $this->datagrid->addColumn($column_system);
        $column_system->setTransformer( function($prompt) {
            $str=strip_tags($prompt);
            $str=strlen($str)>200?substr($str,0,200).'...':$str;
            return $str;
        });
        $this->datagrid->addColumn($column_user1);
        $column_user1->setTransformer( function($prompt) {
            $str=strip_tags($prompt);
            $str=strlen($str)>200?substr($str,0,200).'...':$str;
            return $str;
        });
        $this->datagrid->addColumn($column_user2);
        $column_user2->setTransformer( function($prompt) {
            $str=strip_tags($prompt);
            $str=strlen($str)>200?substr($str,0,200).'...':$str;
            return $str;
        });

        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        

        $order_tema = new TAction(array($this, 'onReload'));
        $order_tema->setParameter('order', 'id_tema');
        $column_tema->setAction($order_tema);

        // create EDIT action
        $action_edit = new TDataGridAction(array('FormPrompt', 'onEdit'));
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
        $sql='DROP VIEW IF EXISTS promptview ';
        $conn->query($sql);

        $sql='CREATE VIEW promptview AS select p.id, p.id_tema, p.user_prompt1, p.user_prompt2, p.system_prompt ';
        $sql.='FROM prompt p ';
        $sql.='LEFT JOIN tema2 t ON p.id_tema=t.id  ';
        if (strlen(array_search(1,TSession::getValue('usergroupids')))==0)
            $sql.='where t.idautor='.TSession::getValue('userid');

        $result = $conn->query($sql);
        $repository = new TRepository('PromptView');                
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
            $object = new Prompt($key);
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
