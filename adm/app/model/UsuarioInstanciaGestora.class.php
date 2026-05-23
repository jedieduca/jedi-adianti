<?php
/**
 * Active Record for table Usuarioinstanciagestora
 * @author  Claudio A. Passos
 */
class UsuarioInstanciaGestora extends TRecord
{
    const TABLENAME = 'usuarioinstanciagestora';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('idusuario');
        parent::addAttribute('idinstanciagestora');
    }
}
?>
