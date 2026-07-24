<?php
/**
 * Active Record for table UsuarioEscola
 * @author  Claudio A. Passos
 */
class UsuarioEscola extends TRecord
{
    const TABLENAME = 'usuario_escola';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('id_usuario');
        parent::addAttribute('id_escola');
    }
}
?>
