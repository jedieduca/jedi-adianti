<?php
/**
 * VideoCardView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class VideoCardView extends TPage
{
    private $form;
    
    public function __construct()
    {
        parent::__construct();

        $idPergunta = TSession::getValue('id');
        //echo '<pre>'; print_r($idPergunta); echo '</pre>';
        
        $cards = new TCardView;
        $cards->setUseButton();
        $items = array();
        $items[] = (object) [ 'id' => 1, 'title' => 'Melhorias do Framework 4.0', 'source' => "http://localhost/adianti-template-7.6.0/cadJEDI/app/storage/audios/pergunta_{$idPergunta}.mp3"];

        
        foreach ($items as $key => $item)
        {
            $cards->addItem($item);
        }
        
        $cards->setTitleAttribute('title');
        
        $cards->setItemTemplate('<iframe width="100%" height="300px" src="{source}""></iframe>');
        
       
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        //$vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($cards);
        parent::add($vbox);
    }

    public function onViewVideo($param)
    {
        //echo '<pre>'; print_r($param); echo '</pre>';
    }
}