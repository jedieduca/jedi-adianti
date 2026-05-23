<?php

use Adianti\Database\TRecord;

class TextualCharacteristicsNews extends TRecord
{
    const TABLENAME  = 'vw_caracteristicas_textuais_noticia';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('caracteristica_observada');
        parent::addAttribute('fake');
        parent::addAttribute('nao_fake');

    }
}