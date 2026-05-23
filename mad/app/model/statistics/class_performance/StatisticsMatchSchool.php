<?php

use Adianti\Database\TRecord;

class StatisticsMatchSchool extends TRecord
{
    const TABLENAME = 'vw_estatistica_partida_turma';
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
        parent::addAttribute('PI');
        parent::addAttribute('PF');
    }
}