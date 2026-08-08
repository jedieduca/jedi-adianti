<?php

use Adianti\Database\TRecord;

class Question extends TRecord
{
    const TABLENAME  = 'pergunta';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('id_tema');
        parent::addAttribute('pergunta');
        parent::addAttribute('resp_certa');
        parent::addAttribute('resp_2');
        parent::addAttribute('resp_3');
        parent::addAttribute('resp_4');
        parent::addAttribute('caminho_imagem');
        parent::addAttribute('tempo_leitura_adulto');
        parent::addAttribute('tempo_leitura_infantil');
        parent::addAttribute('numero_palavras');
        parent::addAttribute('numero_caracteres');
    }

    // Relacionamento N:1 (Muitas perguntas pertencem a um tema)
    public function get_theme()
    {
        return new Theme($this->id_tema);
    }

    // Dentro da classe Pergunta em app/models/Pergunta.php
    public function get_question_category()   
    {
        // Busca o primeiro vínculo na tabela associativa
        $vinculo = QuestionCategory::where('id_pergunta', '=', $this->id)->first();
        if ($vinculo) {
            return $vinculo->categoria->descricao;
        }
        return '';
    }
}