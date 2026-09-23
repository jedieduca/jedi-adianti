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

class SchoolProfilesForm extends TPage
{
    protected $form; // form

    public function __construct()
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_SchoolProfiles');
        $this->form->setFormTitle(_t('School Profiles'));

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
                $schoolProfiles = new SchoolProfiles($param['key']);
                $this->form->addFields(
                    [new TLabel('ID')],
                    [new TTextDisplay($schoolProfiles->id)],
                );
                $this->form->addFields(
                    [new TLabel(_t('School'))],
                    [new TTextDisplay($schoolProfiles->escola)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Number of Students'))],
                    [new TTextDisplay($schoolProfiles->num_turmas)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Number of Teachers'))],
                    [new TTextDisplay($schoolProfiles->num_docentes)],
                );
                $this->form->addFields(
                    [new TLabel(_t('Number of Educational Managers'))],
                    [new TTextDisplay(number_format($schoolProfiles->num_gestores, 0, ',', '.'))],
                    [new TLabel(_t('Number of Educational Managers'))],
                    [new TTextDisplay(number_format($schoolProfiles->num_secretarios, 0, ',', '.'))],
                );

                // fill the form with the active record data
                $this->form->setData($schoolProfiles);

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
