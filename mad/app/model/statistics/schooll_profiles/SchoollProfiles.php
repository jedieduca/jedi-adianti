<?php

use Adianti\Database\TRecord;

class SchoollProfiles extends TRecord
{
    const TABLENAME  = 'vw_perfil_escolas';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('escola');
        parent::addAttribute('num_turmas');
        parent::addAttribute('num_discentes');
        parent::addAttribute('num_docentes');
        parent::addAttribute('num_gestores');
        parent::addAttribute('num_secretarios');
        parent::addAttribute('total');

    }
}