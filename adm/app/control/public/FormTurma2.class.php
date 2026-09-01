<?php
error_reporting(0);

/**
 * FormTurma2
 *
 * @version    1.0
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @copyright  Copyright (c) 2021 Memore
 */

use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;
use Adianti\Registry\TSession;
use Adianti\Widget\Form\THidden;
use Adianti\Widget\Wrapper\TDBMultiSearch;

if (!class_exists('TDBMultiCombo'))
{
    class TDBMultiCombo extends TDBMultiSearch
    {
        public function __construct($name, $database, $model, $key, $value, $orderColumn = NULL, TCriteria $criteria = NULL)
        {
            parent::__construct($name, $database, $model, $key, $value, $orderColumn, $criteria);
        }
    }
}

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
        $escola_criteria = new TCriteria;
        $escola_criteria->add(new TFilter('id', '=', TSession::getValue('userEscolaId')));
        $escola     = new TDBCombo('id_escola','jedieduca','Colegio','id','nome', null, $escola_criteria);
        $serie      = new TDBCombo('id_serie_escolar','jedieduca','SerieEscolar','id','descricao');
        $identificacao = new TEntry('identificacao');
        $anoLetivo  = new TSpinner('ano');
        $anoLetivo->setRange(date('Y')-2, date('Y')+2, 1);
        $anoLetivo->setValue( date('Y') );
        $anoLetivo->setSize('10%');

        $docenteCriteria = $this->getDocenteCriteria();
        $id_professores = new TDBMultiCombo('id_professores', 'jedieduca', 'SystemUser', 'id', 'name', 'name', $docenteCriteria);
        $id_professores->setMinLength(0);
        $id_professores->setMaxSize(10);
        $id_professores->setSize('100%');

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
        $row = $this->form->addFields( [new TLabel('Professores')], [$id_professores] );
        $row->layout = ['col-sm-2 control-label', 'col-sm-10'];
      
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
        //echo '<pre>'; print_r($idEscola); echo '</pre>';
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
                $data->id_professores = $this->form->getField('id_professores')->getPostData();
                $this->form->setData($data); //A função setData preenche o formulário com os valores informados.
                /*O setData() é mais recomendado, pois o sendData() gera Javascript, logo mais código.
                O sendData() só precisa ser usado quando o formulário já está na tela.*/

                $object = new Turma;
                $object->fromArray( (array) $data );
                $object->store();

                if (empty($data->id))
                  $data->id=$object->id;
                //$message = 'Id: '. $data->id . '<br>';

                $professores = is_array($data->id_professores) ? $data->id_professores : array_filter((array) $data->id_professores);
                TurmaProfessor::where('id_turma', '=', $object->id)->delete();
                foreach ($professores as $professor_id)
                {
                    if (empty($professor_id))
                    {
                        continue;
                    }

                    $professor = new TurmaProfessor;
                    $professor->id_turma = $object->id;
                    $professor->id_professor = $professor_id;
                    $professor->store();
                }
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
                //echo '<pre>'; print_r($object->id_escola); echo '</pre>';

                //TCombo::reload('form_oferta_turma', 'idturma', $this->itens);                               
                $user_ids = array();
                foreach ($object->getTurmaUsers() as $user)
                {
                    $user_ids[] = $user->id;
                }
                $professor_ids = array();
                foreach (TurmaProfessor::where('id_turma', '=', $key)->load() as $professor)
                {
                    $professor_ids[] = $professor->id_professor;
                }
                //echo '<pre>'; print_r($user_ids); echo '</pre>';
                $object->user_list = $user_ids;
                $object->id_professores = $professor_ids;
                TTransaction::close();

                // fill the form with the active record data
                $this->form->setData($object);
                TForm::sendData('form_turma', $object);
            }
            else
            {
                $this->form->clear();

                $data = new stdClass;
                $data->ano = date('Y');
                $this->form->setData($data);
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

    private function getDocenteCriteria()
    {
        $criteria = new TCriteria;
        $escolaId = TSession::getValue('userEscolaId');

        if (empty($escolaId))
        {
            $criteria->add(new TFilter('id', 'IN', '(SELECT system_user_id FROM system_user_group WHERE system_group_id = 6)'));
            return $criteria;
        }

        $criteria->add(new TFilter('id', 'IN', '(SELECT DISTINCT su.id
            FROM system_user su
            INNER JOIN system_user_group sug ON sug.system_user_id = su.id
            INNER JOIN usuario_escola ue ON ue.id_usuario = su.id
            WHERE sug.system_group_id = 6
            AND ue.id_escola = ' . (int) $escolaId . ')'));
        return $criteria;
    }

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
            if (!empty($turma->id_escola))
            {
                $colegio = new Colegio($turma->id_escola);
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
        $sql .= 'inner join usuario_escola ue on ue.id_usuario = psu.id ';
        $sql .= 'inner join escola e on e.id = ue.id_escola ';
        $sql .= 'left join turma_aluno ta on ta.id_aluno = psu.id ';
        $sql .= 'left join turma t on ta.id_turma = t.id ';
        $sql .= 'where (e.id = '.(int) $idEscola.' '; 
        $sql .= 'or (t.id_escola = '.(int) $idEscola.' and ta.id_turma = t.id)) ';
        $sql .= 'and psug.system_group_id = '.(int) $ini['permission']['default_groups'].' ';
        $sql .= 'order by psu.name ';

        //echo '<pre>'; print_r($sql); echo '</pre>';

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
