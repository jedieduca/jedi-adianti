<?php

use Adianti\Database\TRecord;

/**
 * Turma
 *
 * @version    1.0
 * @package    model
 * @subpackage jedi
 */
class Classes extends TRecord
{
    // Define a conexão da base de dados (arquivo app/config/jedi.ini)
    const DATABASE = 'jedi';
    
    // Nome exato da tabela no banco de dados
    const TABLENAME = 'turma';
    
    // Nome da chave primária
    const PRIMARYKEY = 'id';
    
    // Política de geração do ID (max ou serial)
    const IDPOLICY = 'max'; // {max, serial}

    /**
     * Construtor da classe
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        
        // Mapeamento dos atributos correspondentes às colunas da tabela
        parent::addAttribute('id_escola');
        parent::addAttribute('identificacao');
        parent::addAttribute('ano');
    }

    /**
     * Relacionamento N:1 com a Model Escola (Opcional, mas recomendado)
     * Retorna a instância da Escola à qual esta turma pertence
     */
    public function get_escola()
    {
        return new School($this->id_escola);
    }
}