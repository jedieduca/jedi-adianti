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
        TTransaction::open('jedieduca');
        $repositorio = new TRepository('Turma');
        $criteria = new TCriteria();
        $criteria->add(new TFilter("ano", "=", $ano));
        $repositorio->load($criteria);
		TTransaction::close();
        return $criteria;
    }

    public function addSystemUser($param)
    {
        if (TurmaAluno::where('idAluno','=',$param)->where('idTurma','=',$this->id)->count() == 0)
        {
            $object = new TurmaAluno;
            $object->idAluno  = $param;
            $object->idTurma = $this->id;
            $object->store();
        }
    }

    public function getTurmaUsers()
    {
        $system_users = array();
        
        // load the related System_user objects
        $repository = new TRepository('TurmaAluno');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idTurma', '=', $this->id));
        $turma_system_users = $repository->load($criteria);
        if ($turma_system_users)
        {
            TTransaction::open('jedieduca');
            foreach ($turma_system_users as $turma_system_user)
            {
                $system_users[] = new SystemUser( $turma_system_user->idAluno );
            }
            TTransaction::close();
        }
        return $system_users;
    }
}
?>
