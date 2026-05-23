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

class StatisticsAvaliationForm extends TPage
{
    protected $form; // form

    public function __construct()
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');
        // creates the form
        $this->form = new BootstrapFormBuilder('formStatisticsAvaliation');
        $this->form->setFormTitle(_t('Distribution of Results by Self-Assessment Levels'));

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
                // open a transaction with database 'permission'
                TTransaction::open('jedi');

                // instantiates object System_user
                $statisticsAvaliation = new StatisticsAvaliation($param['key']);
                $this->form->addFields(
                    [new TLabel('Id')],
                    [new TTextDisplay($statisticsAvaliation->id)]
                );
                $this->form->addFields(
                    [new TLabel(_t('School'))],
                    [new TTextDisplay($statisticsAvaliation->escola)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Class'))],
                    [new TTextDisplay($statisticsAvaliation->turma)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Assessment'))],
                    [new TTextDisplay($statisticsAvaliation->avaliacao)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Self-assessment'))],
                    [new TTextDisplay(number_format($statisticsAvaliation->autoavaliacao, 2, ','))],
                );
                $this->form->addFields(
                    [new TLabel(_t('Game review'))],
                    [new TTextDisplay(number_format($statisticsAvaliation->avaliacao_jogo, 2, ','))],
                );
                // fill the form with the active record data
                $this->form->setData($statisticsAvaliation);

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
