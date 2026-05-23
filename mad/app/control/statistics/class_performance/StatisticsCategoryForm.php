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

class StatisticsCategoryForm extends TPage
{
    protected $form; // form

    public function __construct()
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');
        // creates the form
        $this->form = new BootstrapFormBuilder('formStatisticsCategory');
        $this->form->setFormTitle(_t('Distribution of Results by News Categories'));

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
                $statisticsCategory = new StatisticsCategory($param['key']);
                $this->form->addFields(
                    [new TLabel('ID')],
                    [new TTextDisplay($statisticsCategory->id)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Class'))],
                    [new TTextDisplay($statisticsCategory->escola)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Class'))],
                    [new TTextDisplay($statisticsCategory->turma)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Category'))],
                    [new TTextDisplay($statisticsCategory->categoria)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Average number of correct answers'))],
                    [new TTextDisplay(number_format($statisticsCategory->media_acertos, 2, ','))],
                    [new TLabel(_t('Error average'))],
                    [new TTextDisplay(number_format($statisticsCategory->media_erros, 2, ','))],
                );

                // fill the form with the active record data
                $this->form->setData($statisticsCategory);

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
