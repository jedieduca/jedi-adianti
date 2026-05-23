<?php
/**
 * Active Record for table SucessaoTurma
 * @author  Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 */
class OfertaTurma extends TRecord
{
    const TABLENAME = 'turmaoferta';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}

    private $turma;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('denominacao');
        parent::addAttribute('dtini');
        parent::addAttribute('dtfim');
        parent::addAttribute('turno');
        parent::addAttribute('idturma');
        parent::addAttribute('anoletivo');
    }


    public function addSucessaoTurma(int $sucedida,Turma $sucessora)
    {
        $object = new SucessaoTurma;
        $object->idofertaturmasucessora = $sucessora->id;
        $object->idofertasucedida = $sucedida; //$this->id;
        $object->store();
    }

    public function getOfertaTurma()
    {
        //new TMessage('info', $this->id);
        return parent::loadAggregate('Turma', 'SucessaoTurma', 'idofertasucedida', 'idofertaturmasucessora', $this->id);
        //return parent::loadAggregate('SucessaoTurma', 'Turma', 'id', $this->id);
    }

    /*public function getTurma($key)
    {
        $this->turma = new Turma($key);
        return $this->turma->identificacao;
    }*/

    public function getOfertaTurmaUsers()
    {
        $system_users = array();
        
        // load the related System_user objects
        $repository = new TRepository('OfertaTurmaAluno');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idofertaturma', '=', $this->id));
        $ofertaTurma_system_users = $repository->load($criteria);
        if ($ofertaTurma_system_users)
        {
            TTransaction::open('jedieduca');
            foreach ($ofertaTurma_system_users as $ofertaTurma_system_user)
            {
                $system_users[] = new SystemUser( $ofertaTurma_system_user->idaluno );
            }
            TTransaction::close();
        }
        return $system_users;
    }

    public function addSystemUser($param)
    {
//        if (OfertaTurmaAluno::where('idaluno','=',$systemuser->id)->where('idofertaturma','=',$this->id)->count() == 0)
        if (OfertaTurmaAluno::where('idaluno','=',$param)->where('idofertaturma','=',$this->id)->count() == 0)
        {
            $object = new OfertaTurmaAluno;
            $object->idaluno  = $param;
            //$object->idaluno  = $systemuser->id;
            $object->idofertaturma = $this->id;
            $object->store();
        }
    }

    public static function carregaTurmas($param, $formulario, $campo)
    {
        TTransaction::open('memore');

        $repos = new TRepository('OfertaTurma');
    	$criteria = new TCriteria;
    	$criteria->add(new TFilter('anoletivo', '=', $param['idanoletivo']));
		$criteria->setProperties(array('order'=>'anoletivo'));
    	$obj = $repos->load($criteria);
		$turmas = array();
		foreach($obj as $oid => $turma ):
			$turmas[$turma->id] = $turma->denominacao;
		endforeach;
        TCombo::reload($formulario, $campo, $turmas);

        TTransaction::close();
    }

    static public function getTurma($id)
    {
        return parent::where('id', '=', $id)->first(); 
    }

    public static function TurmaAno($ano)
    {
        TTransaction::open('jedieduca');
        $repositorio = new TRepository('OfertaTurma');
        $criteria = new TCriteria();
        $criteria->add(new TFilter("anoletivo", "=", $ano));
        $repositorio->load($criteria);
		TTransaction::close();
        return $criteria;
    }

}
?>
