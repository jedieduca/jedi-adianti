<?php
error_reporting(0);

/**
 * FormTurma2
 *
 * @version    1.0
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @copyright  Copyright (c) 2021 Memore
 */

use Adianti\Registry\TSession;
use Adianti\Widget\Form\THidden;

$GLOBALS['key']=0;

class FormTurma2 extends TPage
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
        
        $this->form = new BootstrapFormBuilder('form_turma');
        $this->form->setFormTitle('Turmas');
        $this->form->setFieldSizes('100%');
        $this->form->generateAria(); // automatic aria-label
        $this->form->appendPage('Oferta Turma');
        
        $id         = new THidden('id');
        $escola     = new TDBCombo('idescola','jedieduca','Colegio','id','nome');
        $serie      = new TDBCombo('idserieescolar','jedieduca','SerieEscolar','id','descricao');
        $identificacao = new TEntry('identificacao');
        $anoLetivo  = new TSpinner('ano');
        $anoLetivo->setRange(date('Y')-2, date('Y')+2, 1);
        $anoLetivo->setValue( date('Y') );
        $anoLetivo->setSize('10%');

        // validations
        $escola->addValidation('Escola', new TRequiredValidator);
        $serie->addValidation('Serie', new TRequiredValidator);
        $identificacao->addValidation('Identificação', new TRequiredValidator);
        $anoLetivo->addValidation('Ano Letivo', new TRequiredValidator);

        $this->form->addFields( [$id] );
        $row = $this->form->addFields( [new TLabel('Escola')], [$escola] );
        $row->layout = ['col-sm-2 control-label', 'col-sm-4'];
        $row = $this->form->addFields( [new TLabel('Série')], [$serie] );
        $row->layout = ['col-sm-2 control-label', 'col-sm-3'];
        $row = $this->form->addFields( [new TLabel('Identificação')], [$identificacao] );
        $row->layout = ['col-sm-2 control-label', 'col-sm-3'];
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
        $key = $this->getTurmaKey($param);
        $idEscola = $this->getEscolaTurmaSelecionada($param, $key);  //64 id da turmaoferta
        $items = $this->getAlunosEscola($idEscola);
        //echo '<pre>'; print_r($_GET['key']); echo '</pre>';
        $this->userList->addItems($items);
        TTransaction::close();

        //$this->form->addAction('Send', new TAction(array($this, 'onSend')), 'far:check-circle green');
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        //$btn->style = 'background-color: #245c00';
        
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addActionLink( _t('Back'), new TAction(array('FormTurmaList','onReload')), 'far:arrow-alt-circle-left blue');
              
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', 'FormTurmaList'));
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
        //echo '<pre>'; print_r('onSave'); echo '</pre>';
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

                $object = new Turma;
                $object->fromArray( (array) $data );
                $object->store();

                if (empty($data->id))
                  $data->id=$object->id;
                //$message = 'Id: '. $data->id . '<br>';
            }
            catch (Exception $e) // in case of exception
            {
            }

            //$this->RemoveAlunos($object->id);
            TurmaAluno::RemoveAlunos($object->id);
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
            TForm::sendData('form_turma', $data);
        
            $param=array();
            $param['key']=$object->id;
            $param['id']=$object->id;

            AdiantiCoreApplication::gotoPage('FormTurmaList', 'onEdit', $param);

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
                TSession::setValue('idTurma',$key);
                // open a transaction with database 'jedieduca'
                TTransaction::open('jedieduca');
                
                // instantiates object System_user
                $object = new Turma($key); 
                $object->id=$key; 

                //TCombo::reload('form_oferta_turma', 'idturma', $this->itens);                               
                $user_ids = array();
                foreach ($object->getTurmaUsers() as $user)
                {
                    $user_ids[] = $user->id;
                }
                //echo '<pre>'; print_r($user_ids); echo '</pre>';
                $object->user_list = $user_ids;
                TTransaction::close();

                // fill the form with the active record data
                $this->form->setData($object);
                TForm::sendData('form_turma', $object);
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

    /*public function RemoveAlunos($id)
    {
        $conn = TTransaction::get();
        // run query
        $sql='delete FROM turmaaluno ';
        $sql.='WHERE idTurma='.$id;
        $conn->query($sql);
        //echo '<pre>'; print_r($sql); echo '</pre>';
    }*/

    private function getTurmaKey($param)
    {
        if (!empty($param['key']))
        {
            return (int) $param['key'];
        }

        if (!empty($_GET['key']))
        {
            return (int) $_GET['key'];
        }

        return (int) TSession::getValue('idTurma');
    }

    private function getEscolaTurmaSelecionada($param, $key = NULL)
    {
        if (!empty($param['id']))
        {
            $turma = new Turma($param['id']);
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
