<?php
/**
 * Active Record for table partidas_perguntas
 * @author  Claudio Passos, Isabel Fernandes e Ronaldo Goldshmidt
 */
class PartidasPerguntas extends TRecord
{
    const TABLENAME = 'partidas_perguntas';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('dt_jogo');
        parent::addAttribute('id_usuario');
        parent::addAttribute('login');
        parent::addAttribute('jogador');
        parent::addAttribute('id_tema');
        parent::addAttribute('tutor');
        parent::addAttribute('nome');
        parent::addAttribute('idade');
        parent::addAttribute('pontuacao');
        parent::addAttribute('qtd_acertos');
        parent::addAttribute('qtd_erros');
        parent::addAttribute('tempo_gasto');
        parent::addAttribute('auto_avaliacao');
        parent::addAttribute('avaliacao_jogo');
        parent::addAttribute('finalizado');
    }
}
?>
