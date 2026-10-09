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

class StatisticsAutoavaliacaoJogoForm extends TPage
{
    protected $form; // form

    public function __construct()
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');
        // creates the form
        $this->form = new BootstrapFormBuilder('formStatisticsAutoavaliacaoJogo');
        $this->form->setFormTitle(_t('Performance Distribution by Self-Assessment x JEDi Assessment'));

        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);

        // add the container to the page
        parent::add($container);
    }

    public function onView($param)
    {
        try {
            if (isset($param['key'])) {
                // open a transaction with database 'jedi'
                TTransaction::open('jedi');

                $statistics = new StatisticsAutoavaliacaoJogo($param['key']);

                // Grupo vazio vem NULL da view: exibe "–"
                $pct = is_null($statistics->pct_no_grupo) ? '–' : number_format($statistics->pct_no_grupo, 1, ',', '.') . '%';

                $this->form->addFields(
                    [new TLabel('Id')],
                    [new TTextDisplay($statistics->id)]
                );
                $this->form->addFields(
                    [new TLabel(_t('School'))],
                    [new TTextDisplay($statistics->escola)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Class'))],
                    [new TTextDisplay($statistics->turma)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Self-assessment'))],
                    [new TTextDisplay($statistics->autoavaliacao)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Game assessment'))],
                    [new TTextDisplay($statistics->avaliacao_jogo)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Quantity'))],
                    [new TTextDisplay($statistics->qtd)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Group total'))],
                    [new TTextDisplay($statistics->total_grupo)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Percentage in group'))],
                    [new TTextDisplay($pct)],
                );

                // fill the form with the active record data
                $this->form->setData($statistics);

                // close the transaction
                TTransaction::close();
            } else {
                $this->form->clear();
            }
        } catch (Exception $e) // in case of exception
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
