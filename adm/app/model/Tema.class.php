<?php
/**
 * Active Record for table Category
 * @author  Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 */
class Tema extends TRecord
{
    const TABLENAME = 'tema2';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('nome');
        parent::addAttribute('descricao');
        parent::addAttribute('idarea');
        parent::addAttribute('visibilidade');
        parent::addAttribute('idautor');
    }

    public static function TemaUsuario($userId)
    {
        TTransaction::open('jedieduca');
        $repositorio = new TRepository('Tema');
        $criteria = new TCriteria();
        $criteria->add(new TFilter("idautor", "=", $userId));
        $repositorio->load($criteria);
		//$idTema     = new TDBCombo('idtema','jedieduca','Tema','id','nome', 'nome', $criteria);
		TTransaction::close();
        return $criteria;
    }
}
?>
