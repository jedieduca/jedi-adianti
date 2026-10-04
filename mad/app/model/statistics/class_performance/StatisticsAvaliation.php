<?php

use Adianti\Database\TRecord;

class StatisticsAvaliation extends TRecord
{
    const TABLENAME  = 'vw_estatistica_avaliacoes';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('escola');
        parent::addAttribute('turma');
        parent::addAttribute('tipo_avaliacao');
        parent::addAttribute('nota');
        parent::addAttribute('qtd');
    }
}