<?php
/**
 * Active Record for table Aluno
 * @author  Claudio Azevedo Passos
 */
class Aluno extends TRecord
{
    const TABLENAME = 'aluno';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('cpf');
        parent::addAttribute('nome');
        parent::addAttribute('senha');
        parent::addAttribute('dtNasc');
        parent::addAttribute('dtRegistro');
        parent::addAttribute('email');
        parent::addAttribute('telefone');
        parent::addAttribute('situacao');
        parent::addAttribute('idEscola');
    }

    public function addAlunoTurma($turma)
    {
        $object = new AlunoTurma;
        $object->idTurma = $turma;
        $object->cpf = $this->cpf;
        //$object->ano = date('Y');
        $object->store();
    }

    public function getTurma2($cpf)
    {
        $ano = date('Y');
        $conn = TTransaction::get();
        // run query
        $sql="SELECT turma ";
        $sql.="FROM alunoturma at ";
        $sql.="WHERE at.cpf='{$cpf}' ";
        $sql.="and at.ano={$ano}";
        $result = $conn->query($sql);
        foreach ($result as $row) 
            return $row['turma'];
    }

    public function getTurma()
    {
        $ano = date('Y');
        $conn = TTransaction::get();
        // run query
        $sql="SELECT idTurma ";
        $sql.="FROM alunoturma at ";
        $sql.="WHERE at.cpf='{$this->cpf}' ";
        //$sql.="and at.ano={$ano}";
        $result = $conn->query($sql);
        foreach ($result as $row) 
            return $row['turma'];
    }

    public function getNome($cpf)
    {
        $ano = date('Y');
        $conn = TTransaction::get();
        // run query
        $sql="SELECT nome ";
        $sql.="FROM aluno a ";
        $sql.="WHERE a.cpf='{$cpf}' ";
        //echo '<pre>'; print_r($sql); echo '</pre>';
        $result = $conn->query($sql);
        foreach ($result as $row) 
            return $row['nome'];
    }

    public static function StatusAluno($situacao)
    {
        TTransaction::open('jedieduca');
        $repositorio = new TRepository('Aluno');
        $criteria = new TCriteria();
        $criteria->add(new TFilter("situacao", "=", $situacao));
        $repositorio->load($criteria);
		TTransaction::close();
        return $criteria;
    }
}
?>
