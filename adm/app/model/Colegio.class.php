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
        parent::addAttribute('numalunos');
        parent::addAttribute('numprofs');
        parent::addAttribute('conceitoprograma');
        parent::addAttribute('idinstanciagestora');
        //parent::addAttribute('ismarcoreferencial');
        parent::addAttribute('idmunicipio');
        parent::addAttribute('zonalocalizacao');
    }
}
?>
