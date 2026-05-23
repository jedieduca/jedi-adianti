<?php
error_reporting(0);

/**
 * FormOfertaTurma
 *
 * @version    1.0
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @copyright  Copyright (c) 2021 Memore
 */

use Adianti\Registry\TSession;
use Adianti\Widget\Form\THidden;

$GLOBALS['key']=0;

class FormOfertaTurma extends TPage
{
    private $form;
    public $ofertaId;
    public $itens;
    public $userList;
    
    /**
     * Class constructor
     * Creates the page
     */
    public function __construct($param)
    {
        //echo '<pre>'; print_r(TSession::getValue('userunitid')); echo '</pre>';
        //echo '<pre>'; print_r(TSession::getValue('userid')); echo '</pre>';

        parent::__construct();
        
        $this->form = new BootstrapFormBuilder('form_oferta_turma');
        $this->form->setFormTitle('Oferta de Turmas');
        $this->form->setFieldSizes('100%');
        $this->form->generateAria(); // automatic aria-label
        $this->form->appendPage('Oferta Turma');
        
        $id = new THidden('id');
        $denominacao  = new TEntry('denominacao');
        $dtIni    = new TDate('dtini');
        $dtFim    = new TDate('dtfim');
        $turno    = new TCombo('turno');
        //$turma      = new TDBCombo('idturma','jedieduca','Turma','id','identificacao');
        $turma  = new TCombo('idturma');

        TTransaction::open('jedieduca');        
        $conn = TTransaction::get();
        // run query    
        $sql='SELECT t.* ';
        $sql.='FROM turma t, escola e, instanciagestora ig ';
        $sql.='WHERE t.idescola=e.id and e.idinstanciagestora=ig.id ';
        if (TSession::getValue('userid') > 1)
        {
            $sql.='and ig.id='. TSession::getValue('userunitid');
        }
        $result = $conn->query($sql);
        $this->itens  = array();
        $this->itens[0] = "";
        foreach ($result as $row) 
            $this->itens[$row['id']] = $row['identificacao'];

        TCombo::reload('form_oferta_turma', 'idturma', $this->itens);
        TTransaction::close();

        //$anoLetivo  = new TDBCombo('idanoletivo','jedieduca','AnoLetivo','id','descricao');
        $anoLetivo  = new TSpinner('anoletivo');
        $anoLetivo->setSize('10%');
        $anoLetivo->setRange(2026, 2050, 1);
        //O primeiro parâmetro da TDBCombo deve ser o nome do campo no formulário e deve coincidir com o nome da coluna no model/banco de dados
        //$radioDocente = new TRadioGroup('radioDocente');
        //$city         = new TDBUniqueSearch('city', 'samples', 'City', 'id', 'name');

        $dtIni->setMask('dd/mm/yyyy');
        $dtIni->setDatabaseMask('yyyy-mm-dd');
        $dtIni->setSize('50%');
        $dtFim->setMask('dd/mm/yyyy');
        $dtFim->setDatabaseMask('yyyy-mm-dd');
        $dtFim->setSize('50%');
        $turno->addItems( ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite', 'I' => 'Integral'] );
        $turno->enableSearch();
        $turma->enableSearch();
        //$anoLetivo->enableSearch();

        //Layout::Formulario();

        $this->form->addFields( [$id] );
        $this->form->addFields( [new TLabel('Denominação')], [$denominacao] );
        $this->form->addFields( [new TLabel('Data Início')], [$dtIni] );
        $this->form->addFields( [new TLabel('Data Término')], [$dtFim] );
        $this->form->addFields( [new TLabel('Turno')], [$turno] );
        $this->form->addFields( [new TLabel('Turma')], [$turma] );
        $row = $this->form->addFields( [new TLabel('Ano Letivo')], [$anoLetivo] );
        $row->layout = ['col-sm-2 control-label', 'col-sm-1'];
      
        $this->form->appendPage('Associar Alunos');
        $this->userList = new TCheckList('user_list');
        $this->userList->setIdColumn('id');
        $this->userList->addColumn('id',    'ID',    'center',  '10%');
        $col_descr = $this->userList->addColumn('name', 'Nome',    'left',   '50%');

        $this->userList->setHeight(260);
        $this->userList->makeScrollable();
        $this->userList->setSelectAction( new TAction( [$this, 'onSelect'] ) );

        $col_descr->enableSearch();
        $search_name = $col_descr->getInputSearch();
        $search_name->placeholder = _t('Search');
        $search_name->style = 'width:50%;margin-left: 4px; border-radius: 4px';
    
        //$this->form->addFields( [new TFormSeparator('Alunos')] );
        $this->form->addFields( [$this->userList] );
        
        //TTransaction::open('permission');
        //$this->userList->addItems( SystemUser::get() );

        TTransaction::open('jedieduca');
        $key = $this->getOfertaKey($param);
        $idEscola = $this->getEscolaTurmaSelecionada($param, $key);  //64 id da turmaoferta
        $items = $this->getAlunosEscola($idEscola);
        //echo '<pre>'; print_r($_GET['key']); echo '</pre>';
        $this->userList->addItems($items);
        TTransaction::close();

        //TTransaction::close();
        
        /*$this->form->appendPage('Associar Oferta Turma Sucessora');

        $this->turmalist = new TCheckList('turma_list');
        $this->turmalist->setIdColumn('id');
        $this->turmalist->addColumn('id',    'ID',    'center',  '10%');
        $col_name    = $this->turmalist->addColumn('identificacao', 'Turmas',    'left',   '50%');
        //$col_program = $this->program_list->addColumn('controller', _t('Menu path'),    'left',   '40%');
        //$col_program->enableAutoHide(500);
        $this->turmalist->setHeight(150);
        $this->turmalist->makeScrollable();

        $this->form->addFields( [new TFormSeparator('Turmas')] );
        $this->form->addFields( [$this->turmalist] );
        
        TTransaction::open('memore');
        $this->turmalist->addItems( Turma::get() );
        TTransaction::close();
        */
        
        //$this->form->addAction('Send', new TAction(array($this, 'onSend')), 'far:check-circle green');
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        //$btn->style = 'background-color: #245c00';
        
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addActionLink( _t('Back'), new TAction(array('FormOfertaTurmaList','onReload')), 'far:arrow-alt-circle-left blue');
              
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', 'FormOfertaTurmaList'));
        $vbox->add($this->form);

        parent::add($vbox);
    }

    public static function onSelect($param)
    {
        //new TMessage('info', str_replace(',', '<br>', json_encode($param)));
        TSession::setValue('selected_users', $param['user_list']);
    }

    /**
     * Save user data
     */
    public function onSave($param)
    {
        //echo '<pre>'; print_r(TSession::getValue('selected_users')); echo '</pre>'; return;
        try
        {
            // open a transaction with database 'jedieduca'
            try
            {
                TTransaction::open('jedieduca');
                
                $data = $this->form->getData();
                $data->user_list = $this->userList->getPostData();
                $this->form->setData($data); //A função setData preenche o formulário com os valores informados.
                /*O setData() é mais recomendado, pois o sendData() gera Javascript, logo mais código.
                O sendData() só precisa ser usado quando o formulário já está na tela.*/

                $object = new OfertaTurma;  
                $object->fromArray( (array) $data );                
    
                $object->store();

                if (empty($data->id))
                  $data->id=$object->id;
                //$message = 'Id: '. $data->id . '<br>';
            }
            catch (Exception $e) // in case of exception
            {
            }

            $this->RemoveAlunos($object->id);
            //echo '<pre>'; print_r(var_dump($data)); echo '</pre>';
            $vetAlunos = TSession::getValue('selected_users');
            if (!empty($vetAlunos))
            {
                foreach ($vetAlunos as $aluno)
                {
                    $object->addSystemUser($aluno);
                    //$object->addSystemUser( new SystemUser( $user_id ) );
                }
            }

            TTransaction::close();
            
            $data = new stdClass;
            $data->id = $object->id;
            TForm::sendData('form_oferta_turma', $data);
        
            $param=array();
            $param['key']=$object->id;
            AdiantiCoreApplication::gotoPage('FormOfertaTurma', 'onEdit', $param);

            // shows the success message
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
            //new TMessage('info', $message);
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit($param)
    {       
        try
        {
            //echo '<pre>'; print_r('-'); echo '</pre>';
            //echo '<pre>'; print_r($param); echo '</pre>';
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                $GLOBALS['key']=$param['key'];
                TSession::setValue('idOfertaTurma',$key);
                // open a transaction with database 'jedieduca'
                TTransaction::open('jedieduca');
                
                // instantiates object System_user
                $object = new OfertaTurma($key); 
                $object->id=$key; 

                //TCombo::reload('form_oferta_turma', 'idturma', $this->itens);                               
                $user_ids = array();
                foreach ($object->getOfertaTurmaUsers() as $user)
                {
                    $user_ids[] = $user->id;
                }
                //echo '<pre>'; print_r($user_ids); echo '</pre>';
                $object->user_list = $user_ids;
                TTransaction::close();

                // fill the form with the active record data
                $this->form->setData($object);
                TForm::sendData('form_oferta_turma', $object);
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
                             
        //new TMessage('info', $object->id);
        TTransaction::close();
        
    }

    public function RemoveAlunos($id)
    {
        $conn = TTransaction::get();
        // run query
        $sql='delete FROM ofertaturmaaluno ';
        $sql.='WHERE idofertaturma='.$id;
        $conn->query($sql);
        //echo '<pre>'; print_r($sql); echo '</pre>';
    }

    private function getOfertaKey($param)
    {
        if (!empty($param['key']))
        {
            return (int) $param['key'];
        }

        if (!empty($_GET['key']))
        {
            return (int) $_GET['key'];
        }

        return (int) TSession::getValue('idOfertaTurma');
    }

    private function getEscolaTurmaSelecionada($param, $key = NULL)
    {
        $idTurma = NULL;

        if (!empty($param['idturma']))
        {
            $idTurma = (int) $param['idturma'];
        }
        else if (!empty($param['id']))
        {
            $ofertaTurma = new OfertaTurma($key);
            $idTurma = (int) $ofertaTurma->idturma;
        }
        else if (!empty($key))
        {
            $ofertaTurma = new OfertaTurma($key);
            $idTurma = (int) $ofertaTurma->idturma;
        }

        if (!empty($idTurma))
        {
            $turma = new Turma($idTurma);
            if (!empty($turma->idescola))
            {
                $colegio = new Colegio($turma->idescola);
                return (int) $colegio->id;
            }
        }

        return NULL;
    }

    private function getAlunosEscola($idEscola)
    {
        $items = array();

        if (empty($idEscola))
        {
            return $items;
        }

        $ini = AdiantiApplicationConfig::get();
        $conn = TTransaction::get();

        $sql  = 'select distinct psu.id, psu.name ';
        $sql .= 'from system_user psu ';
        $sql .= 'inner join system_user_group psug on psu.id = psug.system_user_id ';
        $sql .= 'inner join alunoescola ae on ae.idAluno = psu.id ';
        $sql .= 'inner join escola e on e.id = ae.idEscola ';
        $sql .= 'where e.id = '.(int) $idEscola.' ';
        $sql .= 'and psug.system_group_id = '.(int) $ini['permission']['default_groups'].' ';
        $sql .= 'order by psu.name ';

        $result = $conn->query($sql);
        foreach ($result as $row) 
        {
            $item = new StdClass;
            $item->id = $row['id'];
            $item->name = $row['name'];
            $items[] = $item;
        }

        return $items;
    }
    
}
