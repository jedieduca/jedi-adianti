<?php

use Adianti\Database\TRecord;

/**
 * Escola
 *
 * @version    1.0
 * @package    model
 * @author     Seu Nome
 */
class Schools extends TRecord
{
    // Nome exato da tabela no banco de dados
    const DATABASE  = 'jedi';
    const TABLENAME = 'escola';
    
    // Nome da chave primária da tabela
    const PRIMARYKEY = 'id';
    
    // Política de ID: 'max' busca o próximo ID via MAX(id)+1, 'serial' utiliza auto-incremento nativo do banco (ex: PostgreSQL/MySQL)
    const IDPOLICY = 'max'; // {max, serial}

    /**
     * Método construtor
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        
        // Mapeamento dos atributos correspondentes às colunas da tabela
        parent::addAttribute('nome');
    }
}