<?php
/**
 * Active Record for table Category
 * @author  Claudio A. Passos
 */
class Turma extends TRecord
{
    const TABLENAME = 'turma';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('idescola');
        parent::addAttribute('idserieescolar');
        parent::addAttribute('identificacao');
        parent::addAttribute('ano');
    }

    public static function TurmaAno($ano)
    {
        TTransaction::open('platia');
        $repositorio = new TRepository('Turma');
        $criteria = new TCriteria();
        $criteria->add(new TFilter("ano", "=", $ano));
        $repositorio->load($criteria);
		TTransaction::close();
        return $criteria;
    }
}
?>
