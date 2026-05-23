<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Base\TScript;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Util\TTextDisplay;
use Adianti\Wrapper\BootstrapFormBuilder;

class MatchSummaryForm extends TPage
{
    protected $form; // form

    public function __construct()
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');
        // creates the form
        $this->form = new BootstrapFormBuilder('formMatchSummary');
        $this->form->setFormTitle(_t('Match Summary'));

        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);

        // add the container to the page
        parent::add($container);
    }

    public function onView($param)
    {
        try
        {
            if (isset($param['key']))
            {
                // open a transaction with database 'permission'
                TTransaction::open('jedi');
                
                // instantiates object System_user
                $matchSummary = new MatchSummary($param['key']);
                $this->form->addFields(
                    [new TLabel('Id')],
                    [new TTextDisplay($matchSummary->id)]
                );
                $this->form->addFields(
                    [new TLabel(_t('School'))],
                    [new TTextDisplay($matchSummary->escola)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Class'))],
                    [new TTextDisplay($matchSummary->turma)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Observed Characteristics'))],
                    [new TTextDisplay($matchSummary->caracteristicas_observadas)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Average'))],
                    [new TTextDisplay($matchSummary->media)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Standard Deviation'))],
                    [new TTextDisplay($matchSummary->desvio)],
                );
                // fill the form with the active record data
                $this->form->setData($matchSummary);
                
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

    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}