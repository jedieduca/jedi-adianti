<?php
/**
 * Active Record for table Palavra
 * @author  Claudio Passos, Isabel Fernandes e Ronaldo Goldshmidt
 */
class Palavra extends TRecord
{
    const TABLENAME = 'palavra2';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('idtema');
        parent::addAttribute('palavra');
        parent::addAttribute('palavraingles');
        parent::addAttribute('texto');
        parent::addAttribute('caminhoimagem');
        parent::addAttribute('silabas');
        parent::addAttribute('caminhoaudio');
        parent::addAttribute('fase');
    }
}
?>
