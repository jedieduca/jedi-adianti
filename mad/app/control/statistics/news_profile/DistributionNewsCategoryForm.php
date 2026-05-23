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

class DistributionNewsCategoryForm extends TPage
{
    protected $form; // form

    public function __construct()
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');
        // creates the form
        $this->form = new BootstrapFormBuilder('formDistributionNewsCategory');
        $this->form->setFormTitle(_t('Distribution of News by Category'));

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
                $distributionNewsCategory = new DistributionNewsCategory($param['key']);
                $this->form->addFields(
                    [new TLabel('Id')],
                    [new TTextDisplay($distributionNewsCategory->id)]
                );
                $this->form->addFields(
                    [new TLabel(_t('Category'))],
                    [new TTextDisplay($distributionNewsCategory->categoria)],
                );
                $this->form->addFields(
                    [new TLabel('Fake (Qt.)')],
                    [new TTextDisplay($distributionNewsCategory->fake_qt)],
                    [new TLabel('Fake (%)')],
                    [new TTextDisplay($distributionNewsCategory->fake_perc)],
                );
                $this->form->addFields(
                    [new TLabel('Não Fake (Qt.)')],
                    [new TTextDisplay($distributionNewsCategory->nao_fake_qt)],
                    [new TLabel('Não Fake (%)')],
                    [new TTextDisplay($distributionNewsCategory->nao_fake_perc)],
                );
                // fill the form with the active record data
                $this->form->setData($distributionNewsCategory);
                
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