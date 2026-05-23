<?php
/**
 * FormInstanciaList
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @copyright  Copyright (c) 2021
 * @license    http://www.adianti.com.br/framework-license
 */
class FormInstanciaList extends TStandardList
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
        
        parent::setDatabase('jedieduca');       // defines the database
        parent::setActiveRecord('InstanciaGestora');  // defines the active record - Nome da Classe
        parent::setDefaultOrder('nome', 'asc');         // defines the default order
        parent::addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        parent::addFilterField('instancia_gestora_pai', '=', 'instancia_gestora_pai'); // filterField, operator, formField
   
        // creates the form
        $this->form = new BootstrapFormBuilder('form_instancia_list');
        $this->form->setFormTitle('Instância Gestora');
        

        // create the form fields
        $nome          = new TEntry('nome');
        $instanciaPai  = new TDBCombo('instancia_gestora_pai','jedieduca','InstanciaGestora','id','nome');
       
        //Layout::Formulario();

        // add the fields
        $this->form->addFields( [new TLabel('Nome')], [$nome] );
        $this->form->addFields( [new TLabel('Instância Pai')], [$instanciaPai] );
     
        $nome->setSize('70%');
        $instanciaPai->setSize('70%');
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('SystemUser_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction(array('FormInstancia', 'onEdit')), 'fa:plus blue');
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        //$column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        $column_nome = new TDataGridColumn('nome', 'Instância Gestora', 'left');
        $column_instancia_gestora_pai = new TDataGridColumn('pai', 'Instância Gestora Pai', 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_instancia_gestora_pai);

       
        // creates the datagrid column actions
        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'nome');
        $column_nome->setAction($order_nome);
        
        $order_instancia_gestora_pai = new TAction(array($this, 'onReload'));
        $order_instancia_gestora_pai->setParameter('order', 'instancia_gestora_pai');
        $column_instancia_gestora_pai->setAction($order_instancia_gestora_pai);
 
        // create EDIT action
        $action_edit = new TDataGridAction(array('FormInstancia', 'onEdit'));
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

    /*function onSearch()
    {
        $data = $this->form->getData();
        
		if (isset($data->nome) && !empty($data->nome))
		{
			$filter = new TFilter('nome','like', '%'.$data->nome.'%');
            TSession::setValue('InstanciaGestoraSearch_nome_filter', $filter);
            TSession::setValue('InstanciaGestoraSearch_nome', $data->nome);

		} else if (empty($data->nome)) 
		{
            TSession::setValue('InstanciaGestoraSearch_nome', $data->nome);
		}

		if (isset($data->instanciagestorapai) && !empty($data->instanciagestorapai))
		{
			$filter = new TFilter('instancia_gestora_pai','=', $data->instanciagestorapai);
            TSession::setValue('InstanciaGestoraSearch_instanciagestorapai_filter', $filter);
            TSession::setValue('InstanciaGestoraSearch_instanciagestorapai', $data->instanciagestorapai);

		} else if (empty($data->instanciagestorapai)) 
		{
            TSession::setValue('InstanciaGestoraSearch_instanciagestorapai', $data->instanciagestorapai);
		}

		$this->form->setData($data);

        $param=array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }*/

    /*function onReload($param = NULL)
    {
        try
        {
            TTransaction::open('memore');
            $repository = new TRepository('InstanciaGestora');
            $limit = 10;
            
            $criteria = new TCriteria;
            $criteria->setProperties($param);
            $criteria->setProperty('limit', $limit);
            $criteria->setProperty('order', 'id');
            
			if (TSession::getValue('InstanciaGestoraSearch_nome_filter') && !empty(TSession::getValue('InstanciaGestoraSearch_nome')))
            {
                $criteria->add(TSession::getValue('InstanciaGestoraSearch_nome_filter'));
            }
            
            if (TSession::getValue('InstanciaGestoraSearch_instanciagestorapai_filter') && !empty(TSession::getValue('InstanciaGestoraSearch_instanciagestorapai')))
            {
                $criteria->add(TSession::getValue('InstanciaGestoraSearch_instanciagestorapai_filter'));
            }

			$objects = $repository->load($criteria);
            
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
            
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }*/

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
            $key=$param['key'];
            TTransaction::open('jedieduca');

            //Remove as respostaopcoes
            //$object = new RespostaOpcoes($key);
            //$object->delete($key);

            //select * FROM questao q, spquestao spq WHERE q.id = spq.idquestao and q.id=8
            $conn = TTransaction::get();
            // run query

            $sql='select * FROM instanciagestora ig ';
            $sql.='WHERE ig.id in (select idinstanciagestora from escola e where e.idinstanciagestora='.$key.') ';
            $sql.='or ig.id in (select idinstanciagestora from usuarioinstanciagestora uig where uig.idinstanciagestora='.$key.') ';
            $result=$conn->query($sql);

            if ($result->rowCount()>0)
            {
                $this->onReload();
                new TMessage('info', "Instância Gestora não pode ser removida por estar relacionado a uma escola ou usuário!");
            }
            else
            {
                $conn = TTransaction::get();
                // run query
                $sql='delete FROM instanciagestora ';
                $sql.='WHERE id='.$key;
                $conn->query($sql);

                TTransaction::close();
                
                parent::onReload();
                new TMessage('info', _t("Record deleted"));
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }
    
}
