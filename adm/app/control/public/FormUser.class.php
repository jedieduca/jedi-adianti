<?php

use Adianti\Registry\TSession;

/**
 * SystemDocumentForm
 *
 * @version    1.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormUser extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_user');
        $this->form->setFormTitle('Usuário');
        
        // create the form fields
        $id         = new THidden('id');
        $nome       = new TEntry('nome');
        $login      = new TEntry('login');
        $senha      = new TPassword('senha');
        $radioAdm   = new TRadioGroup('radioAdm');
        //$colegio_id = new TDBCombo('comboColegio', 'adianti_cadjogos', 'Colegio', 'id', 'nome');
        //$turma_id   = new TDBCombo('comboTurma', 'adianti_cadjogos', 'Turma', 'id', 'identificacao');

        //$colegio_id->setSize('100%');
        //$turma_id->setSize('100%');
        $radioAdm->setLayout('horizontal');
        $items = ['1'=>'Sim', '0'=>'Não'];
        $radioAdm->addItems($items);
        
        // add the fields
        $this->form->addFields( [$ln=new TLabel('Nome')], [$nome] );
        $this->form->addFields( [$ll=new TLabel('Login')], [$login] );
        $this->form->addFields( [$ls=new TLabel('Senha')], [$senha] );
        $this->form->addFields( [$la=new TLabel('Administrador')], [$radioAdm] );
        //$this->form->addFields( [$lc=new TLabel('Colégio')], [$colegio_id] );
        //$this->form->addFields( [$lt=new TLabel('Turma')], [$turma_id] );

        $nome->addValidation( 'Nome', new TRequiredValidator );
        $login->addValidation( 'Login', new TRequiredValidator );
        $senha->addValidation( 'Senha', new TRequiredValidator );
        
        $ln->setFontColor('blue');
        $ll->setFontColor('blue');
        $ls->setFontColor('blue');
        $la->setFontColor('blue');
        //$lc->setFontColor('blue');
        //$lt->setFontColor('blue');
        
        
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('Clear'),  new TAction(array($this, 'onClear')), 'fa:eraser red');
        $this->form->addActionLink( _t('Back'), new TAction(array('FormUserList','onReload')), 'far:arrow-alt-circle-left blue');
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        //$container->add(new TXMLBreadCrumb('menu.xml', 'SystemDocumentUploadForm'));
        $container->add($this->form);
        
        parent::add($container);

    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        $key = TSession::getValue('userId');
        //echo '<pre>'; print_r($key); echo '</pre>';
        //echo '<pre>'; print_r($param); echo '</pre>';
        try
        {
            TTransaction::open('jedieduca'); // open a transaction
            $this->form->validate(); // validate form data

            $data = $this->form->getData(); // get form data as array
            $this->form->setData($data);
            
            if (empty($key))
                $object = new Usuario;
            else
                $object = new Usuario($key);  

            $object->fromArray( (array) $data); // load the object with data
            $object->criador = TSession::getValue('login');
            $object->idturma = 0;
            $object->store(); // save the object
            
            UsuarioTema::removeAllUsuarioTema($object->id);
            $objUT = new UsuarioTema();
            $objUT->userid = $object->id;
            $objUT->idtema = 17; // tema default
            $objUT->store();
           
            // get the generated login
            //$data->login = $object->login;           
            //$this->form->setData($data); // fill form data

            $data = new stdClass;
            $data->login = $object->login;
            TForm::sendData('form_user', $data);

            TTransaction::close(); // close the transaction
            
            //$action = new TAction;
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear();
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        //echo '<pre>'; print_r($param); echo '</pre>';
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TSession::setValue('userId', $key);
                TTransaction::open('jedieduca'); // open a transaction
                $object = new Usuario($key); // instantiates the Active Record
                $object->radioAdm = $object->administrador;
                //$object->comboColegio = $object->codColegio;
                //$object->comboTurma = $object->idTurma;

                $this->form->setData($object); // fill the form
                /*if ($object->system_user_id == TSession::getValue('userid') OR TSession::getValue('login') === 'admin')
                {
                    $object->user_ids = $object->getSystemUsersIds();
                    $object->group_ids = $object->getSystemGroupsIds();
                    $this->form->setData($object); // fill the form
                }
                else
                {
                    throw new Exception(_t('Permission denied'));
                }*/
                TTransaction::close(); // close the transaction
                
            }
            else
            {
                TSession::setValue('userId', '');
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
}
