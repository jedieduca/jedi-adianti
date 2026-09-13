<?php

use Adianti\Database\TRecord;

/**
 * TurmaProfessor Active Record
 * Tabela: turma_professor
 */
class ClassesTeacher extends TRecord
{
    const DATABASE  = 'jedi';
    const TABLENAME  = 'turma_professor';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial'; // Define que o ID é autoincremento (use 'max' se preferir pegar o próximo ID max)

    /**
     * Relacionamento N:1 (TurmaProfessor pertence a uma Turma/Classes)
     */
    private $turma;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $call_object_load = TRUE)
    {
        parent::__construct($id, $call_object_load);
        parent::addAttribute('id_turma');
        parent::addAttribute('id_professor');
    }

    /**
     * Método de conveniência para carregar o objeto da Turma associada
     */
    public function get_turma()
    {
        if (empty($this->turma))
        {
            $this->turma = new Classes($this->id_turma);
        }
        return $this->turma;
    }
}