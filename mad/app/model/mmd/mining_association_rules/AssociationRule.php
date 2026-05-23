<?php

use Adianti\Database\TRecord;

class AssociationRule extends TRecord
{
    const TABLENAME = 'vw_apriori';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('escola');
        parent::addAttribute('turma');
        parent::addAttribute('login');
        parent::addAttribute('jogador');
        parent::addAttribute('dt_jogo');
        parent::addAttribute('idade');
        parent::addAttribute('auto_avaliacao');
        parent::addAttribute('avaliacao_jogo');
        parent::addAttribute('tutor');
        parent::addAttribute('categoria');
        parent::addAttribute('tema');
        parent::addAttribute('numero_partidas');
        parent::addAttribute('tempo_gasto');
        parent::addAttribute('percentual_acertos');
        parent::addAttribute('percentual_erros');
        parent::addAttribute('capacidade_critica');
    }
}