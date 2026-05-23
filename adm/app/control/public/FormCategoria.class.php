<?php
/**
 * categoria Form
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormCategoria extends TPage
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
        $this->form = new BootstrapFormBuilder('form_categoria');
        $this->form->setFormTitle( 'Categoria' );
        
        // create the form fields
        $id            = new THidden('id');
        $nome     = new TEntry('nome');


        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addActionLink( _t('Back'), new TAction(array('FormCategoriaList','onReload')), 'far:arrow-alt-circle-left blue');
        
        // define the sizes
        $id->setSize('50%');
        $nome->setSize('70%');
        
        // outros
        $id->setEditable(false);
        
        // validations
        $nome->addValidation('Nome', new TRequiredValidator);

        //Layout::Formulario();
        
        $this->form->addFields( [$id] );
        $this->form->addFields( [new TLabel('Nome')], [$nome] );
              
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'FormCategoriaList'));
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
            
            $object = new Categoria;
            $object->fromArray( (array) $data );
            $object->store();
            
            $data = new stdClass;
            $data->id = $object->id;
            TForm::sendData('form_categoria', $data);
            
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
                
                // instantiates object categoria
                $object = new Categoria($key);
                              
                
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