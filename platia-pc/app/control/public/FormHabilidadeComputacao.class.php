<?php

use Adianti\Widget\Form\TSpinner;

/**
 * FormHabilidadeComputacao
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @copyright  Copyright (c) 2021 Memore. (http://www.memore-net.com)
 * @license    http://www.memore-net.com/license
 */
class FormHabilidadeComputacao extends TPage
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
        $this->form = new BootstrapFormBuilder('form_habilidade_computacao');
        $this->form->setFormTitle( 'Habilidade de Computação' );
        
        // create the form fields
        $id            = new THidden('id');
        $descricao     = new TText('descricao');
        $descricao->setSize('70%', 100);
        $descricao->addValidation('Descricao', new TRequiredValidator);
        $codigo        = new TEntry('codigo');
        $codigo->setSize('10%');
        $codigo->addValidation('Código', new TRequiredValidator);

        $nivel_ensino = new TRadioGroup('nivel_ensino');
        $nivel_ensino->addItems([
            1 => 'Educação Infantil',
            2 => 'Ensino Fundamental - Anos Iniciais'
        ]);
        $nivel_ensino->setUseButton();
        $nivel_ensino->setLayout('horizontal');
        $nivel_ensino->addValidation('Nível de ensino', new TRequiredValidator);

        $ano_escolar = new TSpinner('ano_escolar');
        $ano_escolar->setRange(1, 9, 1);
        $ano_escolar->setSize('5%');
        $ano_escolar->addValidation('Ano escolar', new TRequiredValidator);

        $eixo_computacao = new TRadioGroup('eixo_computacao');
        $eixo_computacao->addItems([
            //1 => 'Cultura Digital',
            2 => 'Pensamento Computacional'
            //3 => 'Mundo Digital'
        ]);
        $eixo_computacao->setUseButton();
        $eixo_computacao->setLayout('horizontal');
        $eixo_computacao->addValidation('Eixo da computação', new TRequiredValidator);

        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addActionLink( _t('Back'), new TAction(array('FormHabilidadeComputacaoList','onReload')), 'far:arrow-alt-circle-left blue');      
        
        $this->form->addFields( [$id] );
        $this->form->addFields( [new TLabel('Código')], [$codigo] );
        $this->form->addFields( [new TLabel('Descrição')], [$descricao] );
        $this->form->addFields( [new TLabel('Nível de ensino')], [$nivel_ensino] );
        $this->form->addFields( [new TLabel('Ano escolar')], [$ano_escolar] );
        $this->form->addFields( [new TLabel('Eixo da computação')], [$eixo_computacao] );
              
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'FormHabilidadeComputacaoList'));
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
            // open a transaction with database 'permission'
            TTransaction::open('platia');
            
            $data = $this->form->getData();
            $this->form->setData($data);
            
            $object = new HabilidadeComputacao;
            $object->fromArray( (array) $data );
            $object->store();
            
            $data = new stdClass;
            $data->id = $object->id;
            TForm::sendData('form_habilidade_computacao', $data);
            
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
                
                // open a transaction with database 'permission'
                TTransaction::open('platia');
                
                // instantiates object System_user
                $object = new HabilidadeComputacao($key);
                              
                
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