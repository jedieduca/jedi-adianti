<?php
/**
 * Active Record for table UsuarioEscola
 * @author  Claudio A. Passos
 */
class UsuarioEscola extends TRecord
{
    const TABLENAME = 'usuarioescola';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('idusuario');
        parent::addAttribute('idescola');
    }
}
?>
