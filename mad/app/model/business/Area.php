<?php

use Adianti\Database\TRecord;

class Area extends TRecord
{
    const TABLENAME  = 'area2';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial'; // autoincrement

    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('descricao');
    }

    // Relacionamento 1:N (Uma área tem muitos temas)
    public function get_themes()
    {
        return Theme::where('id_area', '=', $this->id)->load();
    }
}