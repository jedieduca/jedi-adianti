<?php
/**
 * Active Record for table plano_aula_habilidades
 * @author  Claudio A Passos
 */
class PlanoAulaHabilidades extends TRecord
{
    const TABLENAME = 'plano_aula_habilidades';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('id_plano');
        parent::addAttribute('id_habilidade');
    }
}
?>
