<?php
/**
 * Active Record for table serieescolar
 * @author  Claudio A Passos
 */
class SerieEscolar extends TRecord
{
    const TABLENAME = 'serieescolar';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('descricao');
        parent::addAttribute('idnivelensino');
    }
}
?>
