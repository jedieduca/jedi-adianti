<?php
/**
 * Active Record for table log_perguntas
 * @author  Claudio Passos, Isabel Fernandes e Ronaldo Goldshmidt
 */
class LogPerguntas extends TRecord
{
    const TABLENAME = 'log_perguntas';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('id_partida');
        parent::addAttribute('dt_jogo');
        parent::addAttribute('id_usuario');
        parent::addAttribute('jogador');
        parent::addAttribute('idade');
        parent::addAttribute('id_tema');
        parent::addAttribute('num_jogada');
        parent::addAttribute('id_pergunta');
        parent::addAttribute('resp_certa');
        parent::addAttribute('resp_dada');
        parent::addAttribute('tempo_gasto');
        parent::addAttribute('realizada_tutor');
        parent::addAttribute('posicao');
    }
}
?>
