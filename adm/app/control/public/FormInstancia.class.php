<?php
/**
 * FormInstancia
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @license    http://www.adianti.com.br/framework-license
 */
class FormInstancia extends TPage
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
        $this->form = new BootstrapFormBuilder('form_instancia');
        $this->form->setFormTitle( 'Instância' );
        
        // create the form fields
        $id         = new THidden('id');
        $nome       = new TEntry('nome');
        $instanciaPai  = new TDBCombo('instancia_gestora_pai','jedieduca','InstanciaGestora','id','nome');

        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addActionLink( _t('Back'), new TAction(array('FormInstanciaList','onReload')), 'far:arrow-alt-circle-left blue');
        
        // define the sizes
        $id->setSize('20%');
        $nome->setSize('70%');
        $instanciaPai->setSize('70%');
        
        // outros
        $id->setEditable(false);
        
        // validations
        $nome->addValidation('Nome', new TRequiredValidator);
        $instanciaPai->addValidation('Instância Gestora Pai', new TRequiredValidator);

        //Layout::Formulario();
        
        $this->form->addFields( [$id] );
        $this->form->addFields( [new TLabel('Nome')], [$nome] );
        $this->form->addFields( [new TLabel('Instância Pai')], [$instanciaPai] );

         
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'FormInstanciaList'));
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
            
            $object = new InstanciaGestora;
            $object->fromArray( (array) $data );

            //$message = 'Id: '           . $data->id . '<br>';
            //$message.= 'instancia : ' . $data->idinstanciagestora . '<br>';
 
            $object->store();
            
            $data = new stdClass;
            $data->id = $object->id;
            TForm::sendData('form_instancia', $data);
            
            // close the transaction
            TTransaction::close();
            
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
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                
                // open a transaction with database 'jedieduca'
                TTransaction::open('jedieduca');
                
                // instantiates object System_user
                $object = new InstanciaGestora($key);
                              
                
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