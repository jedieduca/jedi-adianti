<?php
/**
 * FormEscola
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @license    http://www.adianti.com.br/framework-license
 */
class FormEscola extends TPage
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
        $this->form = new BootstrapFormBuilder('form_escola');
        $this->form->setFormTitle( 'Escola' );
        
        // create the form fields
        $id         = new THidden('id');
        $nome       = new TEntry('nome');
        $numAlunos  = new TEntry('numalunos');
        $numProfs   = new TEntry('numprofs');
        //$conceito   = new TEntry('conceitoprograma');
        //$instanciaGestora  = new TDBCombo('idinstanciagestora','jedieduca','InstanciaGestora','id','nome');
        //$marcoReferencial  = new TDBCombo('ismarcoreferencial','jedieduca','MarcoReferencial','id','titulo');
        $municipio  = new TDBCombo('idmunicipio','jedieduca','Municipio','id','nome');
        $zonaLocalizacao  = new TCombo('zonalocalizacao');
        $zonaLocalizacao->addItems(array('Rural'=>'Rural','Urbana'=>'Urbana'));


        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addActionLink( _t('Back'), new TAction(array('FormEscolaList','onReload')), 'far:arrow-alt-circle-left blue');
        
        // define the sizes
        $id->setSize('20%');
        $nome->setSize('70%');
        $numAlunos->setSize('20%');
        $numProfs->setSize('20%');
        //$conceito->setSize('30%');
        //$instanciaGestora->setSize('60%');
        //$marcoReferencial->setSize('50%');
        $municipio->setSize('50%');
        $zonaLocalizacao->setSize('30%');
        
        // outros
        $id->setEditable(false);
        
        // validations
        $nome->addValidation('Nome', new TRequiredValidator);
        //$instanciaGestora->addValidation('Instância Gestora', new TRequiredValidator);
        $municipio->addValidation('Municipio', new TRequiredValidator);

        //Layout::Formulario();
        
        $this->form->addFields( [$id] );
        $this->form->addFields( [new TLabel('Nome')], [$nome] );
        $this->form->addFields( [new TLabel('Número de Alunos')], [$numAlunos] );
        $this->form->addFields( [new TLabel('Número de Professores')], [$numProfs] );
        //$this->form->addFields( [new TLabel('Conceito do Programa')], [$conceito] );
        //$this->form->addFields( [new TLabel('Instância Gestora')], [$instanciaGestora] );
        //$this->form->addFields( [new TLabel('Marco Referêncial')], [$marcoReferencial] );
        $this->form->addFields( [new TLabel('Município')], [$municipio] );
        $this->form->addFields( [new TLabel('Zona de Localização')], [$zonaLocalizacao] );

         
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'FormEscolaList'));
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
            // open a transaction with database 'memore'
            TTransaction::open('jedieduca');
            
            $data = $this->form->getData();
            $this->form->setData($data);
            
            $object = new Colegio;
            $object->fromArray( (array) $data );

            //$message = 'Id: '           . $data->id . '<br>';
            //$message.= 'instancia : ' . $data->idinstanciagestora . '<br>';
 
            $object->store();
            
            $data = new stdClass;
            $data->id = $object->id;
            TForm::sendData('form_escola', $data);
            
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
                
                // open a transaction with database 'memore'
                TTransaction::open('jedieduca');
                
                // instantiates object System_user
                $object = new Colegio($key);
                              
                
                // fill the form with the active record data
                $this->form->setData($object);
                //TForm::sendData('form_escola', $object);
                
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