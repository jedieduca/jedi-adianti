<?php
/**
 * Active Record for table Pergunta
 * @author  Claudio Passos, Isabel Fernandes e Ronaldo Goldshmidt
 */
class Pergunta extends TRecord
{
    const TABLENAME = 'pergunta';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
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
        parent::addAttribute('caract_proposta');
        parent::addAttribute('analise_proposta');
        parent::addAttribute('analise_gpt');
        parent::addAttribute('analise_gemini');
        parent::addAttribute('origem_analise');
        parent::addAttribute('fala_gpt');
        parent::addAttribute('fala_gemini');
        parent::addAttribute('fala_proposta');
        parent::addAttribute('origem_fala');
        parent::addAttribute('tempo_leitura_adulto');
        parent::addAttribute('tempo_leitura_infantil');
        parent::addAttribute('numero_palavras');
        parent::addAttribute('numero_caracteres');
        parent::addAttribute('publica');
    }
}
?>
