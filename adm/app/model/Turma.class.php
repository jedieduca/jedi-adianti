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
        parent::addAttribute('id_escola');
        parent::addAttribute('id_serie_escolar');
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
        if (TurmaAluno::where('id_aluno','=',$param)->where('id_turma','=',$this->id)->count() == 0)
        {
            $object = new TurmaAluno;
            $object->id_aluno  = $param;
            $object->id_turma = $this->id;
            $object->store();
        }
    }

    public function getTurmaUsers()
    {
        $system_users = array();
        
        // load the related System_user objects
        $repository = new TRepository('TurmaAluno');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id_turma', '=', $this->id));
        $turma_system_users = $repository->load($criteria);
        if ($turma_system_users)
        {
            TTransaction::open('jedieduca');
            foreach ($turma_system_users as $turma_system_user)
            {
                $system_users[] = new SystemUser( $turma_system_user->id_aluno );
            }
            TTransaction::close();
        }
        return $system_users;
    }
}
?>
