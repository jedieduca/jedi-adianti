<?php

use Adianti\Control\TPage;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Util\TImage;

class JediEducaRestDataView extends TPage
{
    private $container; // Definimos o container como atributo
    private $image;

    public function __construct()
    {
        parent::__construct();

        // Criamos a estrutura básica no construtor
        $this->container = new TVBox;
        $this->container->style = 'width: 100%';

        try {
    
            $apiData = (array) JediEducaRestService::getDataStatisticsCategory();

            if ($apiData) {
                // Componente de Imagem
                $this->image = new TImage($apiData['link_imagem']->grafico_categoria_turma);
                $this->image->style = 'width: 100%; height: auto; margin-bottom: 20px; border: 1px solid #ddd';                
    
                $panel = new TPanelGroup('Configurações da Aplicação (Carregadas via onShow)');
                $panel->add($this->image);
                $this->container->add($panel);
                // $table = new TTable;
                // $table->width = '100%';
                // $table->class = 'table table-striped'; // Classe CSS do Bootstrap
            }
    
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
        
        parent::add($this->container);
    }

}