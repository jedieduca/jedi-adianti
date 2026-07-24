<?php
/**
 * Active Record for table usuario_instancia_gestora
 * @author  Claudio A. Passos
 */
class UsuarioInstanciaGestora extends TRecord
{
    const TABLENAME = 'usuario_instancia_gestora';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('id_usuario');
        parent::addAttribute('id_instancia_gestora');
    }
}
?>
