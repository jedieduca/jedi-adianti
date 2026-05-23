<?php
/**
 * Active Record for table Category
 * @author  Pablo Dall'Oglio
 */
class Area extends TRecord
{
    const TABLENAME = 'area2';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('descricao');
    }
}
?>
