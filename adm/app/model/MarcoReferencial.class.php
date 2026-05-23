<?php
/**
 * Active Record for table marcoreferencial
 * @author  Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 */
class MarcoReferencial extends TRecord
{
    const TABLENAME = 'marcoreferencial';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('titulo');
        parent::addAttribute('detalhamento');
    }
}
?>
