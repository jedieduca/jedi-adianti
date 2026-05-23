<?php
/**
 * Active Record for table pergunta2view
 * @author  Claudio Passos, Isabel Fernandes e Ronaldo Goldshmidt
 */
class PerguntaView extends TRecord
{
    const TABLENAME = 'pergunta2view';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('idtema');
        parent::addAttribute('pergunta');
        parent::addAttribute('analise_proposta');
        parent::addAttribute('fala_proposta');
    }
}
?>
