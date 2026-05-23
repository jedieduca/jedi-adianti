<?php

use Adianti\Database\TRecord;

class Theme extends TRecord
{
    const TABLENAME  = 'tema2';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('nome');
        parent::addAttribute('descricao');
        parent::addAttribute('id_area');
        parent::addAttribute('visibilidade');
        parent::addAttribute('idautor');
    }

    // Relacionamento N:1 (Muitos temas pertencem a uma área)
    public function get_area()
    {
        return new Area($this->id_area);
    }

    // Relacionamento 1:N (Um tema tem muitas perguntas)
    public function get_questions()
    {
        return Question::where('id_tema', '=', $this->id)->load();
    }
}