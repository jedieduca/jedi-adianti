<?php
/**
 * SystemDocumentList
 *
 * @version    1.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormUserList extends TPage
{
    protected $form; // form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $loaded;
    protected $deleteButton;
    protected $transformCallback;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_user_list');
        $this->form->setFormTitle('Usuários');
        
        // create the form fields
        $nome       = new TEntry('nome');
        $login      = new TEntry('login');
        $radioAdm   = new TRadioGroup('radioAdm');
        //$colegio_id = new TDBCombo('comboColegio', 'adianti_cadjogos', 'Colegio', 'id', 'nome');
        //$turma_id   = new TDBCombo('comboTurma', 'adianti_cadjogos', 'Turma', 'id', 'identificacao');
        //$criador    = usuarioLogado;

        //$colegio_id->setSize('100%');
        //$turma_id->setSize('100%');
        $radioAdm->setLayout('horizontal');
        $items = ['1'=>'Sim', '0'=>'Não'];
        $radioAdm->addItems($items);
        
        $this->form->addFields( [new TLabel('Nome')], [$nome] );
        $this->form->addFields( [new TLabel('Login')], [$login] );
        $this->form->addFields( [new TLabel('Administrador')], [$radioAdm] );
        //$this->form->addFields( [new TLabel('Colégio')], [$colegio_id] );
        //$this->form->addFields( [new TLabel('Turma')], [$turma_id] );

         
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('user_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction(array('FormUser', 'onEdit')), 'fa:plus green');
        //$this->form->addAction(_t('New'),  new TAction(array('FormUser', 'onNew')), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $column_login = new TDataGridColumn('login', 'Login', 'left', 50);
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        //$column_category_id = new TDataGridColumn('category->name', _t('Category'), 'center');
        //$column_submission_date = new TDataGridColumn('submission_date', _t('Date'), 'center', 100);

        $column_login->setTransformer(function($value, $object, $row) {
            if ($object->archive_date)
            {
                $row->style= 'text-shadow:none; color:#8c8484';
            }
            return $value;
        });
        
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_login);
        $this->datagrid->addColumn($column_nome);
        //$this->datagrid->addColumn($column_category_id);
        //$this->datagrid->addColumn($column_submission_date);
        
        /*if (TSession::getValue('login') == 'admin')
        {
            $column_user = new TDataGridColumn('system_user->name', _t('User'), 'left');
            $this->datagrid->addColumn($column_user);
        }*/
        
        // creates the datagrid column actions
        $order_login = new TAction(array($this, 'onReload'));
        $order_login->setParameter('order', 'login');
        $column_login->setAction($order_login);
        
        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'nome');
        $column_nome->setAction($order_nome);
        
        /*$order_category_id = new TAction(array($this, 'onReload'));
        $order_category_id->setParameter('order', 'category_id');
        $column_category_id->setAction($order_category_id);
        
        $order_submission = new TAction(array($this, 'onReload'));
        $order_submission->setParameter('order', 'submission_date');
        $column_submission_date->setAction($order_submission);*/
        
        
        // create EDIT action
        $action_edit = new TDataGridAction(array('FormUser', 'onEdit'));
        //$action_edit->setUseButton(TRUE);
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('far:edit blue');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        //$action_del->setUseButton(TRUE);
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('far:trash-alt red');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $panel = new TPanelGroup;
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }
        
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        //echo '<pre>'; print_r($data); echo '</pre>';
        
        // clear session filters
        TSession::setValue('UserList_filter_login',   NULL);
        TSession::setValue('UserList_filter_nome',   NULL);
        TSession::setValue('UserList_filter_adm',   NULL);

        if (isset($data->login) AND ($data->login)) {
            $filter = new TFilter('login', 'like', "%{$data->login}%"); // create the filter
            TSession::setValue('UserList_filter_login',   $filter); // stores the filter in the session
        }

        if (isset($data->nome) AND ($data->nome)) {
            $filter = new TFilter('nome', 'like', "%{$data->nome}%"); // create the filter
            TSession::setValue('UserList_filter_nome',   $filter); // stores the filter in the session
        }

        if (isset($data->radioAdm) AND ($data->radioAdm)) {
            $filter = new TFilter('administrador', '=', "{$data->radioAdm}"); // create the filter
            TSession::setValue('UserList_filter_adm',   $filter); // stores the filter in the session
        }

        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('user_filter_data', $data);
        
        $param=array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'communication'
            TTransaction::open('jedieduca');
            
            // creates a repository for SystemDocument
            $repository = new TRepository('Usuario');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'login';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            
            if (TSession::getValue('login') !== 'admin')
            {
                $criteria->add(new TFilter('login', '<>', 'NULL'));
            }
            
            if (TSession::getValue('UserList_filter_login')) {
                $criteria->add(TSession::getValue('UserList_filter_login')); // add the session filter
            }

            if (TSession::getValue('UserList_filter_nome')) {
                $criteria->add(TSession::getValue('UserList_filter_nome')); // add the session filter
            }

            if (TSession::getValue('UserList_filter_adm')) {
                $criteria->add(TSession::getValue('UserList_filter_adm')); // add the session filter
            }            
                       
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            if (is_callable($this->transformCallback))
            {
                call_user_func($this->transformCallback, $objects, $param);
            }
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * Ask before deletion
     */
    public function onDelete($param)
    {
        // define the delete action
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * Delete a record
     */
    public function Delete($param)
    {
        try
        {
            $key=$param['key']; // get the parameter $key
            TTransaction::open('jedieduca'); // open a transaction with database
            $object = new Usuario($key, FALSE); // instantiates the Active Record
            if ($object->criador == TSession::getValue('login') OR TSession::getValue('login') === 'admin')
            {
                $object->delete(); // deletes the object from the database

                // delete related items
                UsuarioTema::removeAllUsuarioTema($key);
            }
            else
            {
                throw new Exception(_t('Permission denied'));
            }
            TTransaction::close(); // close the transaction
            $this->onReload( $param ); // reload the listing
            new TMessage('info', AdiantiCoreTranslator::translate('Record deleted')); // success message
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }
}
