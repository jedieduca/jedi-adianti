<?php
/**
 * Active Record for table plano_aula_comp_cur
 * @author  Claudio A Passos
 */
class PlanoAulaCompCur extends TRecord
{
    const TABLENAME = 'plano_aula_comp_cur';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('id_plano');
        parent::addAttribute('id_componente');
        parent::addAttribute('assunto');
    }
}
?>
