<?php
/**
 * Active Record for table usuariotema
 * @author  Claudio A. Passos - Isabel Fernandes - Ronaldo Goldschmidt
 */
class UsuarioTema extends TRecord
{
    const TABLENAME = 'usuariotema2';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('userid');
        parent::addAttribute('idtema');
    }
    /**
     * Remove all user from UsuarioTema
     * @param $idDisc
     */
    public static function removeAllUsuarioTema($idUser)
    {
        $conn = TTransaction::get();
        // run query
        $sql="delete FROM usuariotema2 ";
        $sql.="WHERE userid={$idUser}";
        $conn->query($sql);
    }
}
?>
