<?php
/**
 * Active Record for table escola
 * @author  Claudio A. Passos
 */
class Colegio extends TRecord
{
    const TABLENAME = 'escola';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('nome');
        parent::addAttribute('num_alunos');
        parent::addAttribute('num_profs');
        parent::addAttribute('conceito_programa');
        parent::addAttribute('id_instancia_gestora');
        //parent::addAttribute('is_marco_referencial');
        parent::addAttribute('id_municipio');
        parent::addAttribute('zona_localizacao');
    }
}
?>
