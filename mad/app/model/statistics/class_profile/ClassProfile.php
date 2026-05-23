<?php

use Adianti\Database\TRecord;

class ClassProfile extends TRecord
{
    const TABLENAME  = 'vw_perfil_turma';
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
        parent::addAttribute('total_alunos');
        parent::addAttribute('idade');
        parent::addAttribute('localizacao_geo');
    }
}