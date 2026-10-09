<?php

use Adianti\Database\TRecord;

class StatisticsAutoavaliacaoJogo extends TRecord
{
    const TABLENAME  = 'vw_estatistica_autoavaliacao_jogo';
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
        parent::addAttribute('ordem_auto');
        parent::addAttribute('autoavaliacao');
        parent::addAttribute('ordem_jogo');
        parent::addAttribute('avaliacao_jogo');
        parent::addAttribute('qtd');
        parent::addAttribute('total_grupo');
        parent::addAttribute('pct_no_grupo');
    }
}
