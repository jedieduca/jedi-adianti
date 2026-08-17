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
        
        parent::setDatabase('platia');            // defines the database
        parent::setActiveRecord('Prompt');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('system_prompt', '=', 'system_prompt'); // filterField, operator, formField
        parent::addFilterField('user_prompt', '=', 'user_prompt'); // filterField, operator, formField
        parent::addFilterField('id_eixo', '=', 'id_eixo'); // filterField, operator, formField
    
        // creates the form
        $this->form = new BootstrapFormBuilder('form_prompt_list');
        $this->form->setFormTitle('Cadastro de Prompt');
        

        // create the form fields
        $id         = new TEntry('id');
        $id->setSize('10%');
        $idEixo    = new TCombo('id_eixo');
        $idEixo->setSize('30%');
        $itensEixo = array();
        $itensEixo['1'] = 'Eixo 1 - Cultura digital';
        $itensEixo['2'] = 'Eixo 2 - Pensamento computacional';
        $itensEixo['3'] = 'Eixo 3 - Mundo digital';
        $idEixo->addItems($itensEixo);

        /*$systemPrompt   = new TEntry('system_prompt');
        $systemPrompt->setSize('70%');
        $userPrompt   = new TEntry('user_prompt');
        $userPrompt->setSize('70%');*/
 
        
        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel('Eixo da Computação')], [$idEixo] );
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
        $column_eixo = new TDataGridColumn('id_eixo', 'Eixo da Computação', 'left');
        $column_system1 = new TDataGridColumn('system_prompt1', 'System Prompt 1', 'left');
        $column_user1 = new TDataGridColumn('user_prompt1', 'User Prompt (Passo 1)', 'left');
        $column_system2 = new TDataGridColumn('system_prompt2', 'System Prompt 2', 'left');
        $column_user2 = new TDataGridColumn('user_prompt2', 'User Prompt (Passo 2)', 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_eixo);
        $column_eixo->setTransformer(function($value, $object, $row)
        {
            if ($value == '1')
            {
                $label = 'Cultura digital';
                $color = '#0d6efd'; // azul
            }
            else if ($value == '2')
            {
                $label = 'Pensamento Computacional';
                $color = '#6c757d'; // cinza
            }
            else if ($value == '3')
            {
                $label = 'Mundo Digital';
                $color = '#17a2b8'; // ciano
            }
            else
            {
                $label = '-';
                $color = '#999999';
            }

            $div = new TElement('span');
            $div->style = "
                background-color: {$color};
                color: white;
                padding: 4px 8px;
                border-radius: 4px;
                font-size:12px;
                font-weight:lighter;
                text-shadow:none;
            ";

            $div->add($label);

            return $div;
        });
        $this->datagrid->addColumn($column_system1);
        $column_system1->setTransformer( function($prompt) {
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
        $this->datagrid->addColumn($column_system2);
        $column_system2->setTransformer( function($prompt) {
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
        

        $order_eixo = new TAction(array($this, 'onReload'));
        $order_eixo->setParameter('order', 'id_eixo');
        $column_eixo->setAction($order_eixo);

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
        TTransaction::open('platia');

        $conn = TTransaction::get();
        // run query
        $sql='DROP VIEW IF EXISTS promptview ';
        $conn->query($sql);

        $sql='CREATE VIEW promptview AS select p.id, p.id_eixo, p.user_prompt1, p.user_prompt2, p.system_prompt1, p.system_prompt2 ';
        $sql.='FROM prompt p ';
        /*$sql.='LEFT JOIN tema2 t ON p.id_tema=t.id  ';
        if (strlen(array_search(1,TSession::getValue('usergroupids')))==0)
            $sql.='where t.idautor='.TSession::getValue('userid');*/

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
            
            TTransaction::open('platia');
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
