<?php
/**
 * Active Record for table PalavraView
 * @author  Claudio Passos, Isabel Fernandes e Ronaldo Goldshmidt
 */
class PalavraView extends TRecord
{
    const TABLENAME = 'palavra2view';
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
        parent::addAttribute('silabas');
    }
}
?>
