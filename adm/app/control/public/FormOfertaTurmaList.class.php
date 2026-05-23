<?php
error_reporting(0);
/**
 * FormOfertaTurmaList
 *
 * @version    1.0
 * @package    control
 * @subpackage public
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @copyright  Copyright (c) 2021 Memore.
 */
class FormOfertaTurmaList extends TStandardList
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
        TTransaction::close();
        parent::__construct();
        
        parent::setDatabase('jedieduca');            // defines the database
        parent::setActiveRecord('OfertaTurmaView');   // defines the active record

        // creates the form
        $this->form = new BootstrapFormBuilder('form_OfertaTurma');
        $this->form->setFormTitle('Oferta de Turma');
        

        // create the form fields and instance property
        $denominacao = new TEntry('denominacao');
        $denominacao->setSize('70%');
        //$turma = new TEntry('idturma');
        //$turma->setSize('70%');
        //$anoLetivo  = new TDBCombo('anoletivo','memore','AnoLetivo','descricao','descricao');
        $anoLetivo  = new TCombo('anoletivo');
        
        TTransaction::open('jedieduca');
        $conn = TTransaction::get(); // get PDO connection        
        // run query
        $sql='select distinct tof.anoletivo as descricao ';
        $sql.='FROM turma t, escola e, turmaoferta tof ';
        $sql.='where t.idescola=e.id ';
        $sql.='and t.id=tof.idturma ';
        $sql.='and e.idinstanciagestora='.TSession::getValue('userunitid');
        $result = $conn->query($sql);
        //echo '<pre>'; print_r($sql); echo '</pre>';
        $items = array();       
        foreach ($result as $row) 
        { 
            $items[$row['descricao']] = $row['descricao'];
        } 
        
        $anoLetivo->addItems($items);
        $anoLetivo->setSize('40%'); 

        //Layout::Formulario();
        
        // add the fields
        $this->form->addFields( [new TLabel('Denominação')], [$denominacao] );
        //$this->form->addFields( [new TLabel('Turma')], [$turma] );
        $this->form->addFields( [new TLabel('Ano Letivo')], [$anoLetivo] );

      
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('ofertaturmaList_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch2')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction(array('FormOfertaTurma', 'onEdit')), 'fa:plus blue');
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $column_denominacao = new TDataGridColumn('denominacao', 'Denominação da Turma', 'left');
        $column_escola = new TDataGridColumn('nome', 'Escola', 'left');
        $column_anoLetivo = new TDataGridColumn('descricao', 'Ano Letivo', 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_denominacao);
        $this->datagrid->addColumn($column_escola);
        $this->datagrid->addColumn($column_anoLetivo);

        
        // creates the datagrid column actions      
        $order_denominacao = new TAction(array($this, 'onReload'));
        $order_denominacao->setParameter('order', 'denominacao');
        $column_denominacao->setAction($order_denominacao);  
        
        $order_anoLetivo = new TAction(array($this, 'onReload'));
        $order_anoLetivo->setParameter('order', 'anoletivo');
        $column_anoLetivo->setAction($order_anoLetivo); 
        
        // create EDIT action
        $action_edit = new TDataGridAction(array('FormOfertaTurma', 'onEdit'));
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

        /**
     * Register the filter in the session
     */
    public function onSearch2()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // clear session filters
        TSession::setValue('ofertaturmaList_filter_denominacao',   NULL);
        TSession::setValue('ofertaturmaList_filter_anoLetivo',   NULL);

        if (isset($data->denominacao) AND ($data->denominacao)) {
            $filter = new TFilter('denominacao', 'like', "%{$data->denominacao}%"); // create the filter
            TSession::setValue('ofertaturmaList_filter_denominacao',   $filter); // stores the filter in the session
        }

        if (isset($data->anoletivo) AND ($data->anoletivo)) {
            $filter = new TFilter('descricao', '=', $data->anoletivo); // create the filter
            TSession::setValue('ofertaturmaList_filter_anoLetivo',   $filter); // stores the filter in the session
        }
       
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('ofertaturmaList_filter_data', $data);
        
        $param=array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }

    function onReload($param = NULL) 
    {
        //parent::onReload();

        $limit = 10;
        TTransaction::open('jedieduca');

        //tem que ser gesror e ter a mesma instância gestora

        $conn = TTransaction::get();
        $sql='DROP VIEW IF EXISTS ofertaturmaview ';
        $conn->query($sql);
        // run query

        //query copiada para alteração
        $sql='CREATE VIEW ofertaturmaview AS select tof.id, e.nome, tof.denominacao, tof.anoletivo as descricao ';
        $sql.='FROM turma t, escola e, turmaoferta tof ';
        $sql.='where t.idescola=e.id ';
        $sql.='and t.id=tof.idturma ';
        //$sql.='and e.idinstanciagestora='.TSession::getValue('userunitid');
        //echo '<pre>'; print_r(TSession::getValue('userunitid')); echo '</pre>';
        //echo '<pre>'; print_r(strlen(array_search(1,TSession::getValue('usergroupids')))); echo '</pre>';
        //echo '<pre>'; print_r(strlen(array_search(3,TSession::getValue('usergroupids')))); echo '</pre>';
        //echo '<pre>'; print_r($sql); echo '</pre>';
        $result = $conn->query($sql);
        $repository = new TRepository('OfertaTurmaView');                
        $criteria = new TCriteria;
        $limit = 10; 
        $criteria->setProperties($param); // order, offset
        $criteria->setProperty('limit', $limit);
        //$criteria->setProperty('order', 'titulo');  

        if (TSession::getValue('ofertaturmaList_filter_denominacao')) {
            $criteria->add(TSession::getValue('ofertaturmaList_filter_denominacao')); // add the session filter
        }

        if (TSession::getValue('ofertaturmaList_filter_anoLetivo')) {
            $criteria->add(TSession::getValue('ofertaturmaList_filter_anoLetivo')); // add the session filter
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
            foreach ($objects as $object)
            {
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

    function onDelete($param)   
    {
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead
        
        new TQuestion(_t('Do you really want to delete ?'), $action);
    }
    
    function Delete($param)
    {
        try
        {
            $conn = TTransaction::get();
            $sql='DROP VIEW IF EXISTS ofertaturmaview ';
            $conn->query($sql);

            $key=$param['key'];
            //echo '<pre>'; print_r($param); echo '</pre>';
            TTransaction::open('jedieduca');

                 $conn = TTransaction::get();
                // run query
                $sql='delete FROM turmaoferta ';
                $sql.='WHERE id='.$key;
                echo '<pre>'; print_r($sql); echo '</pre>';
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
