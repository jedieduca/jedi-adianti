<?php

use Adianti\Database\TRecord;

class NumMatches extends TRecord
{
    const TABLENAME  = 'vw_numero_partidas';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('usuario');
        parent::addAttribute('grupo');
        parent::addAttribute('escola');
        parent::addAttribute('turma');
        parent::addAttribute('aluno');
        parent::addAttribute('dt_jogo');
        parent::addAttribute('numero_partidas');

    }
}