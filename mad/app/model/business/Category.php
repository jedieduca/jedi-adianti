<?php

use Adianti\Database\TRecord;

class Category extends TRecord
{
    const TABLENAME  = 'categoria';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('descricao');
    }
}