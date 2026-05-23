<?php

use Adianti\Database\TTransaction;

/**
 * FormAlunoList
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos 
 * @copyright  Copyright (c) 2026 JEDI Educa
 */
class FormAlunoList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;

    public $dirFotos;
    private $pdf;
    
    /**
     * Page constructor
     */
    public function __construct($param)
    {
        if (isset($this->form)) return;
        //echo '<pre>'; print_r($param); echo '</pre>';
        if (isset($param['tipo']))
            TSession::setValue("situacaoAluno",$param['tipo']);

        parent::__construct();

        $ini  = AdiantiApplicationConfig::get();
        $this->dirFotos =  $ini['dir']['fotos'];
        
        parent::setDatabase('jedieduca');            // defines the database
        parent::setActiveRecord('SystemUser');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('name', 'like', 'name'); // filterField, operator, formField

        // creates the form
        $this->form = new BootstrapFormBuilder('form_aluno_list');
        if (TSession::getValue("situacaoAluno")=='Cadastro')
            $this->form->setFormTitle('Cadastro de Alunos');
        else if (TSession::getValue("situacaoAluno")=='Ativo')
            $this->form->setFormTitle('Listagem de Alunos em Curso');
        else if (TSession::getValue("situacaoAluno")=='Desligado')
            $this->form->setFormTitle('Listagem de Alunos Desligados');

        // create the form fields
        $id = new THidden('id');
        $nome = new TEntry('nome');
 
        
        // add the fields
        $this->form->addFields( [$id] );
        $this->form->addFields( [new TLabel('Nome')], [$nome] );

        
        $id->setSize('30%');
        $nome->setSize('70%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Aluno_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        
        $this->form->addAction(_t('New'),  new TAction(array('FormAluno', 'onEdit')), 'fa:plus green');
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        //$column_cpf = new TDataGridColumn('cpf', 'CPF', 'center', 50);
        $column_nome = new TDataGridColumn('name', 'Nome', 'left');
        $column_login = new TDataGridColumn('login', 'Login', 'left');
        $column_email = new TDataGridColumn('email', 'Email', 'left');
        $column_turma = new TDataGridColumn('turma', 'Turma', 'left');
        
        //if (TSession::getValue("situacaoAluno")=='Cadastro')
            $column_active = new TDataGridColumn('situacao', _t('Active'), 'center');

        // add the columns to the DataGrid
        //$this->datagrid->addColumn($column_cpf);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_login);
        $this->datagrid->addColumn($column_email);
        $this->datagrid->addColumn($column_turma);
        //if (TSession::getValue("situacaoAluno")=='Cadastro')
        {
            $this->datagrid->addColumn($column_active);
            $column_active->setTransformer( function($value, $object, $row) {
                $class = ($value=='N') ? 'danger' : 'success';
                $label = ($value=='N') ? _t('No') : _t('Yes');
                $div = new TElement('span');
                $div->class="label label-{$class}";
                $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
                $div->add($label);
                return $div;
            });
        }
        /*else if (TSession::getValue("situacaoAluno")=='Ativo')
        {
            $criteria=Aluno::StatusAluno("Y");
            parent::onReload();
        }*/

       
        // creates the datagrid column actions
        /*$order_cpf = new TAction(array($this, 'onReload'));
        $order_cpf->setParameter('order', 'cpf');
        $column_cpf->setAction($order_cpf);*/
        
        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'name');
        $column_nome->setAction($order_nome);
        

        //if (TSession::getValue("situacaoAluno")=='Cadastro')
        {
            // create EDIT action
            $action_edit = new TDataGridAction(array('FormAluno', 'onEdit'));
            $action_edit->setButtonClass('btn btn-default');
            $action_edit->setLabel(_t('Edit'));
            $action_edit->setImage('far:edit blue');
            $action_edit->setField('id');
            $this->datagrid->addAction($action_edit);

            // create ONOFF action
            $action_onoff = new TDataGridAction(array($this, 'onTurnOnOff'));
            $action_onoff->setButtonClass('btn btn-default');
            $action_onoff->setLabel(_t('Activate/Deactivate'));
            $action_onoff->setImage('fa:power-off orange');
            $action_onoff->setField('id');
            $this->datagrid->addAction($action_onoff);
        
            // create DELETE action
            $action_del = new TDataGridAction(array($this, 'onDelete'));
            $action_del->setButtonClass('btn btn-default');
            $action_del->setLabel(_t('Delete'));
            $action_del->setImage('far:trash-alt red');
            $action_del->setField('id');
            //$action_del->setFields(['id', 'cpf']);
            $this->datagrid->addAction($action_del);
        }
        
        
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
        
        if (TSession::getValue("situacaoAluno")!='Cadastro')
        {
            // header actions
            $dropdown = new TDropDown(_t('Export'), 'fa:list');
            $dropdown->setPullSide('right');
            $dropdown->setButtonClass('btn waves-effect dropdown-toggle');
            $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table fa-fw blue' );
            $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf fa-fw red' );
            $panel->addHeaderWidget( $dropdown );
        }
      
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'FormAlunoList'));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }

    function onReload_($param = NULL)
    {
        //parent::onReload();

        $ini  = AdiantiApplicationConfig::get(); 
        $anoLetivo = $ini['general']['anoLetivo'];

        $data = $this->form->getData();

        $limit = 10;
        TTransaction::open('jedieduca');

        $conn = TTransaction::get();
        // run query

        $repositorio = new TRepository('Aluno');
        $criteria = new TCriteria();
        /*if (TSession::getValue("situacaoAluno")=='Ativo')
            $criteria->add(new TFilter("situacao", "=", "Y"));
        else if (TSession::getValue("situacaoAluno")=='Desligado')
            $criteria->add(new TFilter("situacao", "=", "N"));*/

        /*if (!empty($data->id))
            $criteria->add(new TFilter("id", "=", $data->id));*/
        if (!empty($data->nome))    
            $criteria->add(new TFilter("nome", "like", "%{$data->nome}%"));

        $criteria->add(new TFilter("dtRegistro", "like", "%{$anoLetivo}%"));
        $limit = 10; 
        $criteria->setProperties($param); // order, offset
        $criteria->setProperty('limit', $limit);
        //$criteria->setProperty('order', 'titulo');  
        

        $objects = $repositorio->load($criteria);

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
        $count= $repositorio->count($criteria);
       
        $this->pageNavigation->setCount($count); // count of records
        $this->pageNavigation->setProperties($param); // order, page
        $this->pageNavigation->setLimit($limit); // limit
        //TTransaction::close();

    }

    function onReload_old($param = NULL)   //feito para adicionar a turma do aluno
    {
        //echo '<pre>'; print_r($param); echo '</pre>';
        parent::onReload();
        
        $ini  = AdiantiApplicationConfig::get(); 

        $limit = 10;
        TTransaction::open('jedieduca');

        $conn = TTransaction::get();
        // run query
        $sql='DROP VIEW IF EXISTS alunoview ';
        $conn->query($sql);

        //$sql='CREATE VIEW tema2view AS select *';
        $sql='CREATE VIEW alunoview AS select a.id, a.cpf, a.nome, t.identificacao as turma ';
        $sql.='FROM aluno a ';
        $sql.='LEFT JOIN alunoturma alunot ON a.cpf=alunot.cpf ';
        $sql.='LEFT JOIN turma t ON alunot.idTurma=t.id ';
        //echo '<pre>'; print_r($sql); echo '</pre>';

        $result = $conn->query($sql);
        $repository = new TRepository('AlunoView');                
        $criteria = new TCriteria;
        $limit = 10; 
        $criteria->setProperties($param); // order, offset
        $criteria->setProperty('limit', $limit);
        //$criteria->setProperty('order', 'titulo');  

        if (TSession::getValue('formAlunoList_filter_nome')) {
            $criteria->add(TSession::getValue('formAlunoList_filter_nome')); // add the session filter
        }

        if (TSession::getValue('formAlunoList_filter_turma')) {
            $criteria->add(TSession::getValue('formAlunoList_filter_turma')); // add the session filter
        }

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

    function onReload($param = NULL)   //feito para adicionar a turma do aluno
    {
        //echo '<pre>'; print_r($param); echo '</pre>';
        parent::onReload();
        
        $ini  = AdiantiApplicationConfig::get(); 

        $limit = 10;
        TTransaction::open('jedieduca');

        $conn = TTransaction::get();
        // run query
        $sql='DROP VIEW IF EXISTS alunoview ';
        $conn->query($sql);

        //$sql='CREATE VIEW tema2view AS select *';
        $sql='CREATE VIEW alunoview AS select su.id,  su.name, su.login, su.email, tof.denominacao as turma ';
        $sql.='FROM system_user su ';
        $sql.='LEFT JOIN system_user_group sug ON su.id=sug.system_user_id ';
        $sql.='LEFT JOIN ofertaturmaaluno ota ON su.id=ota.idaluno ';
        $sql.='LEFT JOIN turmaoferta tof ON ota.idofertaturma=tof.id ';
        $sql.='LEFT JOIN turma t ON tof.idturma=t.id ';
        $sql.='WHERE sug.system_group_id=4 '; // grupo aluno
        //echo '<pre>'; print_r($sql); echo '</pre>';

        $result = $conn->query($sql);
        $repository = new TRepository('AlunoView');                
        $criteria = new TCriteria;
        $limit = 10; 
        $criteria->setProperties($param); // order, offset
        $criteria->setProperty('limit', $limit);
        //$criteria->setProperty('order', 'titulo');  

        if (TSession::getValue('formAlunoList_filter_nome')) {
            $criteria->add(TSession::getValue('formAlunoList_filter_nome')); // add the session filter
        }

        if (TSession::getValue('formAlunoList_filter_turma')) {
            $criteria->add(TSession::getValue('formAlunoList_filter_turma')); // add the session filter
        }

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

    /**
     * Turn on/off an user
     */
    public function onTurnOnOff($param)
    {
        try
        {
            TTransaction::open('jedieduca');
            $aluno = Aluno::find($param['id']);
            if ($aluno instanceof Aluno)
            {
                $aluno->situacao = $aluno->situacao == 'Y' ? 'N' : 'Y';
                $aluno->store();
            }
            
            TTransaction::close();
            
            $this->onReload($param);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    function onDelete($param)   
    {
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead
        
        new TQuestion(_t('Do you really want to delete ?'), $action);
    }

    function Delete($param)
    {
        //echo '<pre>'; print_r($param); echo '</pre>';
        //return;
        try
        {
            $key=$param['key'];
            $cpf=$param['cpf'];
            TTransaction::open('jedieduca');

            $conn = TTransaction::get();
            // run query
            $sql='delete FROM system_user ';
            $sql.='WHERE id='.$key;
            $conn->query($sql);
            
            // run query
            $sql='delete FROM system_user_group ';
            $sql.='WHERE system_user_id='.$key;
            $conn->query($sql);

            // run query
            $sql='delete FROM ofertaturmaaluno ';
            $sql.='WHERE idaluno='.$key;
            $conn->query($sql);

            $arquivo = $this->dirFotos.$cpf.".jpg";
            if (file_exists( $arquivo ))
                unlink($arquivo);

            TTransaction::close();
                
            parent::onReload();
            new TMessage('info', _t("Record deleted"));

        }
        catch (Exception $e)
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    } 

    function Delete_($param)
    {
        //echo '<pre>'; print_r($param); echo '</pre>';
        //return;
        try
        {
            $key=$param['key'];
            $cpf=$param['cpf'];
            TTransaction::open('jedieduca');

            $conn = TTransaction::get();
            // run query
            $sql='delete FROM aluno ';
            $sql.='WHERE id='.$key;
            $conn->query($sql);
            
            // run query
            $sql='delete FROM alunoturma ';
            $sql.='WHERE cpf='.$cpf;
            $conn->query($sql);

            $arquivo = $this->dirFotos.$cpf.".jpg";
            if (file_exists( $arquivo ))
                unlink($arquivo);

            TTransaction::close();
                
            parent::onReload();
            new TMessage('info', _t("Record deleted"));

        }
        catch (Exception $e)
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }    
}
