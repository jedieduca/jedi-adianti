<?php

use Adianti\Widget\Form\TSpinner;

/**
 * FormTurma
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @license    http://www.adianti.com.br/framework-license
 */
class FormTurma extends TPage
{
    protected $form; // form
    protected $program_list;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_turma');
        $this->form->setFormTitle( 'Turma' );
        
        // create the form fields
        $id         = new THidden('id');
        $escola     = new TDBCombo('idescola','jedieduca','Colegio','id','nome');
        $serie      = new TDBCombo('idserieescolar','jedieduca','SerieEscolar','id','descricao');
        $identificacao = new TEntry('identificacao');
        $anoLetivo  = new TSpinner('ano');
        $anoLetivo->setRange(date('Y')-2, date('Y')+2, 1);
        //$anoLetivo->setValue( date('Y') );

        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addActionLink( _t('Back'), new TAction(array('FormTurmaList','onReload')), 'far:arrow-alt-circle-left blue');
        
        // define the sizes
        $id->setSize('50%');
        $escola->setSize('70%');
        $serie->setSize('70%');
        $identificacao->setSize('70%');
        $anoLetivo->setSize('10%');
        
        // outros
        $id->setEditable(false);
        
        // validations
        $escola->addValidation('Escola', new TRequiredValidator);
        $serie->addValidation('Serie', new TRequiredValidator);
        $identificacao->addValidation('Identificação', new TRequiredValidator);
        $anoLetivo->addValidation('Ano Letivo', new TRequiredValidator);

        
        $this->form->addFields( [$id] );
        $this->form->addFields( [new TLabel('Escola')], [$escola] );
        $this->form->addFields( [new TLabel('Série')], [$serie] );
        $this->form->addFields( [new TLabel('Identificação')], [$identificacao] );
        $this->form->addFields( [new TLabel('Ano Letivo')], [$anoLetivo] );
              
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'FormTurmaList'));
        $container->add($this->form);

        // add the container to the page
        parent::add($container);
    }

    /**
     * Save user data
     */
    public function onSave($param)
    {
        try
        {
            // open a transaction with database 'jedieduca'
            TTransaction::open('jedieduca');
            
            $data = $this->form->getData();
            $this->form->setData($data);
            
            $object = new Turma;
            $object->fromArray( (array) $data );

            /*$message = 'Id: '           . $data->id . '<br>';
            $message.= 'Description : ' . $data->descricao . '<br>';
            $message.= 'nivel : ' . $data->idnivelensino . '<br>';*/

            $object->store();
            
            $data = new stdClass;
            $data->id = $object->id;
            TForm::sendData('form_turma', $data);
            
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
        try
        {
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                
                // open a transaction with database 'jedieduca'
                TTransaction::open('jedieduca');
                
                // instantiates object System_user
                $object = new Turma($key);
                              
                
                // fill the form with the active record data
                $this->form->setData($object);
                
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