<?php
/**
 * FormPrompt
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormPrompt extends TPage
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
        $this->form->setFormTitle('Cadastro de Prompts');  //Parei aqui
        $this->form->generateAria(); // automatic aria-label
        
        // create the form fields
        $id          = new THidden('id');

        $idTema      = new TDBCombo('id_tema', 'jedieduca', 'Tema', 'id', 'nome');
        $idTema->setSize('30%');

        $caracteristicas = new TText('caracteristicas');
        $caracteristicas->placeholder = 'Digite aqui as características da notícia';
        $caracteristicas->setSize('100%', 120);

        $systemPrompt = new TText('system_prompt');
        $systemPrompt->placeholder = 'Digite aqui o System Prompt';
        //$systemPrompt->setTip('Tip for description');
        $systemPrompt->setSize('100%', 160);

        $userPrompt1   = new TText('user_prompt1');
        $userPrompt1->placeholder = 'Digite aqui o User Prompt';
        $userPrompt1->setSize('100%', 200);

        $userPrompt2   = new TText('user_prompt2');
        $userPrompt2->placeholder = 'Digite aqui o User Prompt';
        $userPrompt2->setSize('100%', 200);


        
        /*$radioVisib    = new TRadioGroup('visibilidade');
        
        $idArea->setSize('100%');
        $radioVisib->setLayout('horizontal');
        $items = ['P'=>'Pública', 'R'=>'Restrita'];
        //$publico->addItems(array(1=>'Sim', 0=>'Não'));
        $radioVisib->addItems($items);*/
        
        // add the fields inside the form
        $this->form->addFields( [$id]);
        $this->form->addFields( [new TLabel('Tema')],        [$idTema] );
        $this->form->addFields( [new TLabel('Características')], [$caracteristicas] );
        $this->form->addFields( [new TLabel('System Prompt')],   [$systemPrompt] );
        $this->form->addFields( [new TLabel('User Prompt (Passo 1)')],    [$userPrompt1] );
        $this->form->addFields( [new TLabel('User Prompt (Passo 2)')],    [$userPrompt2] );
        //$this->form->addFields( [new TLabel('Visibilidade')],  [$radioVisib]);
        
              
        // define the form action 
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addActionLink( _t('Back'), new TAction(array('FormPromptList','onReload')), 'far:arrow-alt-circle-left blue');

      
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', 'FormPromptList'));
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
        if ((empty($data->id_tema)) || (empty($data->system_prompt)) || (empty($data->user_prompt1)))
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
            $objPrompt = new Prompt;
            $objPrompt->id = $data->id; 
            $objPrompt->id_tema = $data->id_tema; 
            $objPrompt->caracteristicas = $data->caracteristicas; 
            $objPrompt->system_prompt = $data->system_prompt; 
            $objPrompt->user_prompt1 = $data->user_prompt1; 
            $objPrompt->user_prompt2 = $data->user_prompt2; 
            $objPrompt->store(); // store the object
            
            
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
                $object = new Prompt($key);
                              
                
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
