<?php
/**
 * Active Record for table Category
 * @author  Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 */
class Municipio extends TRecord
{
    const TABLENAME = 'municipio';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($codigo = NULL)
    {
        parent::__construct($codigo);
        parent::addAttribute('nome');
        parent::addAttribute('num_habitantes');
        parent::addAttribute('id_uf');
        parent::addAttribute('renda_media');
        parent::addAttribute('idh');
    }
}
?>
