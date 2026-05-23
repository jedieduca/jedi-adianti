<?php
/**
 * Active Record for table Pergunta
 * @author  Claudio Passos, Isabel Fernandes e Ronaldo Goldshmidt
 */
class Pergunta extends TRecord
{
    const TABLENAME = 'pergunta2';
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
        parent::addAttribute('respcerta');
        parent::addAttribute('resp2');
        parent::addAttribute('resp3');
        parent::addAttribute('resp4');
        parent::addAttribute('caminhoimagem');
        parent::addAttribute('caract_proposta');
        parent::addAttribute('analise_proposta');
        parent::addAttribute('analise_gpt');
        parent::addAttribute('analise_gemini');
        parent::addAttribute('origem_analise');
        parent::addAttribute('fala_gpt');
        parent::addAttribute('fala_gemini');
        parent::addAttribute('fala_proposta');
        parent::addAttribute('origem_fala');
        parent::addAttribute('publica');
    }
}
?>
