<?php

use Adianti\Database\TRecord;

class MatchSummary extends TRecord
{
    const TABLENAME  = 'vw_resumo_partidas';
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
        parent::addAttribute('caracteristicas_observadas');
        parent::addAttribute('media');
        parent::addAttribute('desvio');
        parent::addAttribute('ordem');
    }
}