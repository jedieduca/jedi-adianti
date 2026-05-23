<?php

use Adianti\Database\TRecord;

class StatisticsCategory extends TRecord
{
    const TABLENAME = 'vw_estatistica_categoria_turma';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('escola');
        parent::addAttribute('turma');
        parent::addAttribute('categoria');
        parent::addAttribute('media_acertos');
        parent::addAttribute('media_erros');
    }
}