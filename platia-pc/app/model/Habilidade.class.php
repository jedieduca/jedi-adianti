<?php
/**
 * Active Record for table habilidade
 * @author  Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 */
class Habilidade extends TRecord
{
    const TABLENAME = 'habilidade';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('codigo');
        parent::addAttribute('descricao');
        parent::addAttribute('id_componente_curricular');
        //parent::addAttribute('ano_escolar');
        //parent::addAttribute('nivel_ensino');
    }

    /*public function addHabilidadeCompetencia(Competencia $competencia)
    {
        $object = new HabilidadeCompetencia;
        $object->idcompetencia = $competencia->id;
        $object->idhabilidade = $this->id;
        $object->store();
    }

    public function getHabilidadeCompetencia()
    {
        return parent::loadAggregate('Competencia', 'HabilidadeCompetencia', 'idhabilidade', 'idcompetencia', $this->id);
    }

    public function get_HabilidadeCompetencia()
    {
        $conn = TTransaction::get();
             
        $sql='SELECT c.descricao';
        $sql.=' FROM competencia c, habilidadecompetencia hc';
        $sql.=' where hc.idcompetencia=c.id';
        $sql.=' and hc.idhabilidade='.$this->id;  
        //echo '<pre>'; print_r($sql ); echo '</pre>';
        $result = $conn->query($sql);

        $st='';
        foreach ($result as $row)  //for ($i=0; $i<$result->rowCount();$ind++)
            $st.=$row['descricao'].';';
        
        return substr($st, 0, -1);
    }*/

    public function get_descr()
	{
		return $this->descricao;
	}


    /*public function getAreaMatriz()
    {
        $conn = TTransaction::get();

        $sql='select a.id idArea, a.idmatriz idMatriz from habilidade h ';
        $sql.='left join habilidadecompetencia hc on h.id=hc.idhabilidade ';
        $sql.='left join competencia c on hc.idcompetencia=c.id ';
        $sql.='left join area a on c.idarea=a.id ';
        $sql.='where h.id='.$this->id;
        $result = $conn->query($sql);
        $dataJSON=$result->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($dataJSON);
    }*/

    public static function getDescricaoById($id)
	{
		$habilidade = new self($id);
		return $habilidade->descricao;
	}
}
?>
