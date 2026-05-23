<?php

use Adianti\Database\TRecord;

class QuestionCategory extends TRecord
{
    const TABLENAME  = 'pergunta_categoria2';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('id_tema');
        parent::addAttribute('id_pergunta');
        parent::addAttribute('id_categoria');
    }

    // Métodos para facilitar o acesso (Lazy Load)
    public function get_question()
    { 
        return new Question($this->id_pergunta); 
    }

    public function get_category()
    {
        return new Category($this->id_categoria);
    }

    public function get_theme()
    { 
        return new Theme($this->id_tema);
    }
}