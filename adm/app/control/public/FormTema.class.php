<?php
/**
 * FormTema
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormTema extends TPage
{
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // create the form
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('Cadastro de Temas');
        $this->form->generateAria(); // automatic aria-label
        
        // create the form fields
        $id          = new THidden('id');
        $nome        = new TEntry('nome');
        $descricao   = new TEntry('descricao');

        //$codArea        = new TCombo('codArea');
        $idArea      = new TDBCombo('idarea', 'jedieduca', 'Area', 'id', 'descricao');
        
        $radioVisib    = new TRadioGroup('visibilidade');
        
        $idArea->setSize('100%');
        $radioVisib->setLayout('horizontal');
        $items = ['P'=>'Pública', 'R'=>'Restrita'];
        //$publico->addItems(array(1=>'Sim', 0=>'Não'));
        $radioVisib->addItems($items);
        
        // add the fields inside the form
        $this->form->addFields( [new TLabel('Id')],          [$id]);
        $this->form->addFields( [new TLabel('Nome')],        [$nome] );
        $this->form->addFields( [new TLabel('Descrição')],   [$descricao] );
        $this->form->addFields( [new TLabel('Área')],    [$idArea] );
        $this->form->addFields( [new TLabel('Visibilidade')],  [$radioVisib]);

        
        $descricao->placeholder = 'Descrição do tema';
        $descricao->setTip('Tip for description');
        
              
        // define the form action 
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addActionLink( _t('Back'), new TAction(array('FormTemaList','onReload')), 'far:arrow-alt-circle-left blue');

      
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', 'FormTemaList'));
        $vbox->add($this->form);

        parent::add($vbox);
    }
    
    /**
     * Simulates an save button
     * Show the form content
     */
    public function onSave($param)
    {
        $data = $this->form->getData();
        //echo '<pre>'; print_r($data); echo '</pre>';
        if ((empty($data->nome)) || (empty($data->descricao)) || (empty($data->idarea)) || (empty($data->visibilidade)))
        {
            new TMessage('error', 'Campo obrigatório não preenchido!'); 
            $this->form->setData($data);
            return;
        }
        // put the data back to the form
        $this->form->setData($data);

        try 
        { 

            TTransaction::open('jedieduca');
            
            // create a new object
            $objTema = new Tema;
            $objTema->id = $data->id; 
            $objTema->nome = $data->nome;  
            $objTema->descricao = $data->descricao; 
            $objTema->idarea = $data->idarea;
            $objTema->visibilidade = $data->visibilidade;
            $objTema->idautor = TSession::getValue('userid');
 
            $objTema->store(); // store the object 
            
            
            // show the message
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));

            TTransaction::close(); // Closes the transaction 
        } 
        catch (Exception $e) 
        { 
            new TMessage('error', $e->getMessage()); 
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
                $object = new Tema($key);
                              
                
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
