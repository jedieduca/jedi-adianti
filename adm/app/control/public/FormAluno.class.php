<?php

use Adianti\Widget\Form\TPassword;

error_reporting(0);
/**
 * FormAluno
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos
 * @copyright  Copyright (c) 2026 JEDI Educa
 */
class FormAluno extends TPage
{
    protected $form; // form
    protected $program_list;
    
    public $dirFotos;

    //webcam
    private $frameFoto;
    private $frameFotoWebCan;
    //
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();

        $ini  = AdiantiApplicationConfig::get();
        $this->dirFotos =  $ini['dir']['fotos'];

        TPage::include_js('app/lib/components/webcam/webcam.min.js');
        TPage::include_js('app/lib/components/webcam/webcam.config.js');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_aluno');
        $this->form->setFormTitle( 'Cadastro de Alunos' );
        
        // create the form fields
        $id = new THidden('id');
        //$id->setEditable(false);
        /*$cpf = new TEntry('cpf');
        $cpf->setSize('20%');
        $cpf->addValidation('CPF', new TRequiredValidator);
        $cpf->setMask('999.999.999-99', true);*/
        $nome = new TEntry('name');
        $nome->setSize('100%');
        $nome->addValidation('Nome', new TRequiredValidator);
        $senha = new TPassword('password');
        $senha->setSize('70%');
        //$senha->addValidation('Senha', new TRequiredValidator);
        /*
        $criteria=OfertaTurma::TurmaAno(date("Y"));
		$turma = new TDBCombo('idTurma','jedieduca','OfertaTurma','id','denominacao', 'denominacao', $criteria);
        $turma->setSize('30%');*/

		$escola = new TDBCombo('idEscola','jedieduca','Colegio','id','nome');
        $escola->setSize('30%');

        /*$dtNasc = new TDate('dtNasc');
        $dtNasc->setMask('dd/mm/yyyy');
        $dtNasc->setDatabaseMask('yyyy-mm-dd');
        $dtNasc->setSize('20%');
        $dtNasc->setOption('triggerEvent', 'dblclick');*/
        /*$dtReg = new TDate('dtRegistro');
        $dtReg->setMask('dd/mm/yyyy');
        $dtReg->setDatabaseMask('yyyy-mm-dd');
        $dtReg->setSize('20%');
        $dtReg->setValue(date("d/m/Y"));*/
        $login = new TEntry('login');
        $login->setSize('30%');
        $login->addValidation('Login', new TRequiredValidator);
        $email = new TEntry('email');
        $email->setSize('30%');
        $email->addValidation('E-mail', new TEmailValidator);
        /*$telefone = new TEntry('telefone');
        $telefone->setSize('30%');
        $telefone->setMask('(99) 99999-9999',true);*/


        /*
        //webcam
        $nome_arq_foto = new TFile('nome_arq_foto');
        $nome_arq_foto->setAllowedExtensions( ['png', 'jpg'] );
        // complete upload action
        $nome_arq_foto->setCompleteAction( new TAction( array( $this, 'onCompleteFoto' ) ) );
        $nome_arq_foto->setSize('100%');
        //
        */

        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addActionLink( _t('Back'), new TAction(array('FormAlunoList','onReload')), 'far:arrow-alt-circle-left blue');
        
        $this->form->addAction(_t('New'),  new TAction(array('FormAluno', 'onEdit')), 'fa:plus green');
        
        // validations


        $this->form->addFields( [$id] );
        //$this->form->addFields( [new TLabel('CPF')], [$cpf] );
        $this->form->addFields( [new TLabel('Nome')], [$nome] );
        $this->form->addFields( [new TLabel('Senha')], [$senha] );
        $this->form->addFields( [new TLabel('Login')], [$login] );
        $this->form->addFields( [new TLabel('E-mail')], [$email] );
        $this->form->addFields( [new TLabel('Escola')], [$escola] );
        
        //$this->form->addFields( [new TLabel('Data de Nascimento')], [$dtNasc] );
        //$this->form->addFields( [new TLabel('Telefone')], [$telefone] );
        //$this->form->addFields( [new TLabel('Data do Registro')], [$dtReg] );


        /*
        //webcam
        $divisor = new TFormSeparator('Foto <span class="fa fa-long-arrow-down" aria-hidden="true"></span>', '#333333;margin-bottom:0', '18', '#000;margin-bottom:0'); 
        $divisor->style = 'background-color: #b1d4e8; padding-top: 1px';
        $this->form->addContent( [ $divisor ] );
        // campo para fazer upload de uma imagem qualquer (caso não use a webcam)
        $row = $this->form->addFields( [ new TLabel('Foto <b>(PNG ou JPG)</b>'), $nome_arq_foto ] );
        // div onde fica a foto se existir ou a camera apos clicar no botão Acessar Webcam
        $this->frameFoto = new TElement( 'div' );
        $this->frameFoto->id = 'user_foto_frame';
        $this->frameFoto->style = '';
        // div onde fica a foto tirada apos clicar no botão Bater Foto
        $this->frameFotoWebCan = new TElement( 'div' );
        $this->frameFotoWebCan->id = 'user_foto_frameWebCam';
        $this->frameFotoWebCan->style = '';
        // label que fica acima da div
        $labelframeFotoWebCan = new TLabel('Foto Tirada');
        $labelframeFotoWebCan->id = 'labelframeFotoWebCan';
        $labelframeFotoWebCan->style = 'display:none';
        
        // botão para acessar a webcam
        $btnWebcam = new TButton('btnWebcam');
        $btnWebcam->addFunction("setup(); $(this).hide(); $( '#labelAcessarWebcam' ).css( 'display', 'none' ); $( '#btnWebcamBaterFoto' ).css( 'display', '' ); $( '#labelWebcamBaterFoto' ).css( 'display', '' )");
        $btnWebcam->setImage('fa:camera');
        $btnWebcam->setLabel('Acessar Webcam');
        $btnWebcamLabel = new TLabel('&nbsp&nbsp');
        $btnWebcamLabel->id = 'labelAcessarWebcam';

        // botão para bater a foto
        $btnWebcamBaterFoto = new TButton('btnBaterFoto');
        $btnWebcamBaterFoto->addFunction("take_snapshot(); $( '#labelframeFotoWebCan' ).css( 'display', '' )");
        $btnWebcamBaterFoto->setImage('fa:camera');
        $btnWebcamBaterFoto->id = 'btnWebcamBaterFoto';
        $btnWebcamBaterFoto->style = 'display:none';
        $btnWebcamBaterFoto->setLabel('Bater Foto');
        $btnWebcamBaterFotoLabel = new TLabel('&nbsp&nbsp');
        $btnWebcamBaterFotoLabel->id = 'labelWebcamBaterFoto';
        $btnWebcamBaterFotoLabel->style = 'display:none';

        // adiciona os campos no form
        $row = $this->form->addContent( [ new TLabel('Formato 300x300 (Recomendado)'), $this->frameFoto ],
        [ $labelframeFotoWebCan, $this->frameFotoWebCan ],
        [ $btnWebcamLabel, $btnWebcam ],
        [ $btnWebcamBaterFotoLabel, $btnWebcamBaterFoto ] );
        $row->layout = ['col-sm-4', 'col-sm-4', 'col-sm-2', 'col-sm-2'];
        // campo text para receber a datauri da foto tirada (uso este campo para gerar/salvar a foto)
        $fotoWebcam = new TText('fotoWebcam');
        $fotoWebcam->id = 'idFotoWebcam';
        $fotoWebcam->style = 'display:none;';
        $this->form->addFields( [ $fotoWebcam ] );
        //
        */
              
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'FormAlunoList'));
        $container->add($this->form);

        // add the container to the page
        parent::add($container);
    }

    /**
     * On complete upload
     */
    public static function onCompleteFoto( $param ) {
        //new TMessage('info', 'Upload completed: '.$param['nome_arq_foto']);
        //var_dump($param);
        //echo '<pre>'; print_r($param); echo '</pre>';
        // refresh photo_frame
        TScript::create( "$('#user_foto_frame').html('')" );
        TScript::create( "$('#user_foto_frame').append(\"<img style='max-width: 100%;' src='tmp/{$param[ 'nome_arq_foto' ]}'>\");" );
    }

    /*public function RemoveAlunoTurma($cpf)
    {
        $ano = date("Y");
        $conn = TTransaction::get();
        // run query
        $sql="delete FROM alunoturma ";
        $sql.="WHERE cpf='{$cpf}'";
        //$sql.="WHERE cpf='{$cpf}' and ano={$ano}";
        $conn->query($sql);
    }*/

    public function RemoveAlunoTurma($id)
    {
        $ano = date("Y");
        $conn = TTransaction::get();
        // run query
        $sql="delete FROM ofertaturmaaluno ";
        $sql.="WHERE idaluno='{$id}'";
        $conn->query($sql);
        //echo '<pre>'; print_r($sql); echo '</pre>';
    }

    public function RemoveAlunoEscola($id)
    {
        $ano = date("Y");
        $conn = TTransaction::get();
        // run query
        $sql="delete FROM alunoescola ";
        $sql.="WHERE idaluno='{$id}'";
        $conn->query($sql);
        //echo '<pre>'; print_r($sql); echo '</pre>';
    }

    public function addAlunoTurma($turma, $id)
    {
        $object = new OfertaTurmaAluno;
        $object->idofertaturma = $turma;
        $object->idaluno = $id;
        //$object->ano = date('Y');
        $object->store();
    }

    public function addAlunoEscola($escola, $id)
    {
        $object = new AlunoEscola;
        $object->idEscola = $escola;
        $object->idAluno = $id;
        //$object->ano = date('Y');
        $object->store();
    }

    public function VerificaExistenciaLogin($login)
    {
        $conn = TTransaction::get();
        // run query
        $sql="select * FROM system_user ";
        $sql.="WHERE login='{$login}'";
        $result = $conn->query($sql);
        return $result->rowCount();
    }

    /**
     * Save user data
     */
    public function onSave($param)
    {
        //echo '<pre>'; print_r($param); echo '</pre>';
        try
        {
            $this->form->validate();

            TScript::create( "Webcam.reset ( ) ;" );

            // open a transaction with database 'jedieduca'
            TTransaction::open('jedieduca');
            
            $data = $this->form->getData();
            //$data->senha = md5( $data->senha );
            $data->dtRegistro = date("Y-m-d");
            $this->form->setData($data);
            //echo '<pre>'; print_r($data); echo '</pre>';

            if (($this->VerificaExistenciaLogin($data->login)>0) && (empty($data->id)))
            {
                new TMessage('info', 'Login já cadastrado no sistema!');
                TTransaction::close();
                return;
            }

            // foto webcam
            if ( $data->fotoWebcam != '' )
            {
                $arquivo = $this->dirFotos.$data->cpf.".jpg";
                if (file_exists( $arquivo ))
                    unlink($arquivo);

                $img = $data->fotoWebcam;
                $img = str_replace('data:image/jpeg;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $dataIm = base64_decode($img);
                //var_dump($data);
                $f = $data->matricula.'.jpg';
                
                $success = file_put_contents('fotos/' . $f, $dataIm);
                
                /*if ($success)
                {
                    $data->nome_arq_foto = $f;
                    $targetUserFoto = 'fotos/' . $data->nome_arq_foto;
                    //var_dump($object->nome_arq_foto);
                }*/
                // limpa o campo
                $data->fotoWebcam = ' ';
            }

            //echo '<pre>'; print_r($data); echo '</pre>';
            /*$object = new Aluno;
            $object->fromArray( (array) $data );
            $object->store();*/
            $object = new SystemUser();
            $object->fromArray( (array) $data );
            if ( !empty( $data->password ) ) {
                $object->password = md5( $data->password );
            }
            $object->frontpage_id = '41';
            $object->active = 'Y';
            $object->store();

            $object->clearParts(); // limpa os grupos atuais
            $object->addSystemUserGroup( new SystemGroup(4) );  //aluno

            //Grava Aluno Turma   
            /*$this->RemoveAlunoTurma($object->id);  //data->cpf                   
            $this->addAlunoTurma($data->idTurma, $object->id);*/

            //Grava Aluno Escola   
            //echo '<pre>'; print_r($object->id); echo '</pre>';
            $this->RemoveAlunoEscola($object->id);  //data->cpf                   
            $this->addAlunoEscola($data->idEscola, $object->id);
            
            $data = new stdClass;
            $data->id = $object->id;
            TForm::sendData('form_aluno', $data);
            
            // close the transaction
            TTransaction::close();
            
            // shows the success message
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
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
        //echo '<pre>'; print_r($param); echo '</pre>';

        try
        {
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                
                // open a transaction with database 'permission'
                TTransaction::open('jedieduca');

                // instantiates object SystemUser
                $object = new SystemUser($key);

                // instantiates object OfertaTurmaAluno
                $objAlunoEscola = AlunoEscola::getAluno($object->id);
                $object->idEscola = $objAlunoEscola->idEscola; 
                
                // fill the form with the active record data
                $this->form->setData($object);

                // mostra imagem se existir
                if ( file_exists('fotos/' . $object->cpf.'.jpg') )
                {
                    $image = new TImage( 'fotos/' . $object->cpf.'.jpg');
                    $image->style = 'max-width: 100%;';
                    $this->frameFoto->add( $image );
                }
                
                // close the transaction
                TTransaction::close();
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
    }
}