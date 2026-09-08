<?php
use Adianti\Database\TRecord;

class SchoolsUser extends TRecord
{
    const DATABASE  = 'jedi';
    const TABLENAME  = 'usuario_escola';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'max'; // {max, serial}

    public function __construct($id = NULL)
    {
        parent::__construct($id);
        
        // Mapeamento dos campos conforme sua tabela
        parent::addAttribute('id_usuario');
        parent::addAttribute('id_escola');
    }
}