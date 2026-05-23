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

class AssociationRulesForm extends TPage
{
    protected $form; // form

    public function __construct()
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');
        // creates the form
        $this->form = new BootstrapFormBuilder('formAssociationRules');
        $this->form->setFormTitle(_t('Association Rules'));

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
                $associationRule = new AssociationRule($param['key']);
                $this->form->addFields([new TLabel(_t('User'))], [new TTextDisplay($associationRule->login)]);
                $this->form->addFields(
                    [new TLabel('ID')],
                    [new TTextDisplay($associationRule->id)],
                    [new TLabel(_t('Data Origin'))],
                    [new TTextDisplay($associationRule->origem)],
                );
                $this->form->addFields(
                    [new TLabel(_t('School'))],
                    [new TTextDisplay($associationRule->escola)],
                    [new TLabel(_t('Class'))],
                    [new TTextDisplay($associationRule->turma)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Player'))],
                    [new TTextDisplay($associationRule->jogador)],
                    [new TLabel(_t('Age'))],
                    [new TTextDisplay($associationRule->idade)],
                );
                $this->form->addFields([new TLabel(_t('Game Date'))], [new TTextDisplay($associationRule->dt_jogo)]);
                $this->form->addFields(
                    [new TLabel(_t('Self-assessment'))],
                    [new TTextDisplay($associationRule->auto_avaliacao)],
                    [new TLabel(_t('Game Review'))],
                    [new TTextDisplay($associationRule->avaliacao_jogo)],
                );
                $this->form->addFields([new TLabel(_t('Use of Tutoring'))], [new TTextDisplay($associationRule->tutor)]);
                $this->form->addFields(
                    [new TLabel(_t('Category'))],
                    [new TTextDisplay($associationRule->categoria)],
                    [new TLabel(_t('Theme'))],
                    [new TTextDisplay($associationRule->tema)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Number of Matches'))],
                    [new TTextDisplay($associationRule->numero_partidas)],
                    [new TLabel(_t('School'))],
                    [new TTextDisplay($associationRule->tempo_gasto)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Correct Answers (%)'))],
                    [new TTextDisplay($associationRule->percentual_acertos)],
                    [new TLabel(_t('Wrong Answers (%)'))],
                    [new TTextDisplay($associationRule->percentual_erros)],
                );
                $this->form->addFields([new TLabel(_t('Critical Capacity'))], [new TTextDisplay($associationRule->capacidade_critica)]);

                // fill the form with the active record data
                $this->form->setData($associationRule);
                
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