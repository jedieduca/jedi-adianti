<?php
/**
 * FormEscolaList
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @copyright  Copyright (c) 2021
 * @license    http://www.adianti.com.br/framework-license
 */
class FormEscolaList extends TStandardList
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
        parent::setActiveRecord('Colegio');  // defines the active record - Nome da Classe
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        parent::addFilterField('numalunos', '=', 'numalunos'); // filterField, operator, formField
        parent::addFilterField('numprofs', '=', 'numprofs'); // filterField, operator, formField
        //parent::addFilterField('conceitoprograma', '=', 'conceitoprograma'); // filterField, operator, formField
        parent::addFilterField('idinstanciagestora', '=', 'idinstanciagestora'); // filterField, operator, formField
        parent::addFilterField('ismarcoreferencial', '=', 'ismarcoreferencial'); // filterField, operator, formField
        parent::addFilterField('idmunicipio', '=', 'idmunicipio'); // filterField, operator, formField
        parent::addFilterField('zonalocalizacao', '=', 'zonalocalizacao'); // filterField, operator, formField
   
        // creates the form
        $this->form = new BootstrapFormBuilder('form_escola_list');
        $this->form->setFormTitle('Escola');
        

        // create the form fields
        $id         = new TEntry('id');
        $nome       = new TEntry('nome');
        $numAlunos  = new TEntry('numalunos');
        $numProfs   = new TEntry('numprofs');
        //$conceito   = new TEntry('conceitoprograma');
        //$instanciaGestora  = new TDBCombo('idinstanciagestora','jedieduca','InstanciaGestora','id','nome');
        //$marcoReferencial  = new TDBCombo('ismarcoreferencial','jedieduca','MarcoReferencial','id','titulo');
        $municipio  = new TDBCombo('idmunicipio','jedieduca','Municipio','id','nome');
        //$zonaLocalizacao  = new TDBCombo('zonalocalizacao','jedieduca','Local','id','descricao');
        $zonaLocalizacao  = new TCombo('zonalocalizacao');
        $zonaLocalizacao->addItems(array('Rural'=>'Rural','Urbana'=>'Urbana'));
 
        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel('Nome')], [$nome] );
        $this->form->addFields( [new TLabel('Nº de Alunos')], [$numAlunos], [new TLabel('Nº de Professores')], [$numProfs] );
        //$this->form->addFields( [new TLabel('Conceito Programa')], [$conceito] );
        //$this->form->addFields( [new TLabel('Instância Gestora')], [$instanciaGestora] );
        //$this->form->addFields( [new TLabel('Marco Referêncial')], [$marcoReferencial]);
        $this->form->addFields( [new TLabel('Município')], [$municipio], [new TLabel('Zona de Localização')], [$zonaLocalizacao] );
        
        $id->setSize('20%');
        $nome->setSize('70%');
        $numAlunos->setSize('20%');
        $numProfs->setSize('20%');
        //$conceito->setSize('30%');
        //$instanciaGestora->setSize('75%');
        //$marcoReferencial->setSize('75%');
        $municipio->setSize('50%');
        $zonaLocalizacao->setSize('35%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('SystemUser_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction(array('FormEscola', 'onEdit')), 'fa:plus blue');
        //$this->form->addAction('Estatística',  new TAction(array('FormEscolaEstatistica','onView')), 'fa:chart-bar');
       
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        //$column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        $column_nome = new TDataGridColumn('nome', 'Escola', 'left');
        $column_numAlunos = new TDataGridColumn('numalunos', 'Nº de Alunos', 'left');
        $column_numProfs = new TDataGridColumn('numprofs', 'Nº de Professores', 'left');
        $column_zona = new TDataGridColumn('zonalocalizacao', 'Zona de Localização', 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_numAlunos);
        $this->datagrid->addColumn($column_numProfs);
        $this->datagrid->addColumn($column_zona);

       
        // creates the datagrid column actions
        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'nome');
        $column_nome->setAction($order_nome);
        
        $order_numAlunos = new TAction(array($this, 'onReload'));
        $order_numAlunos->setParameter('order', 'numalunos');
        $column_numAlunos->setAction($order_numAlunos);
 
        $order_numProfs = new TAction(array($this, 'onReload'));
        $order_numProfs->setParameter('order', 'numprofs');
        $column_numProfs->setAction($order_numProfs);

        $order_zona = new TAction(array($this, 'onReload'));
        $order_zona->setParameter('order', 'zonalocalizacao');
        $column_zona->setAction($order_zona);

        // create EDIT action
        $action_edit = new TDataGridAction(array('FormEscola', 'onEdit'));
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
    
}
