<?php
/**
 * Active Record for table componentecurricular
 * @author  Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 */
class ComponenteCurricular extends TRecord
{
    const TABLENAME = 'componentecurricular';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('descricao');
        parent::addAttribute('nivel_ensino');
    }

    public function addCompCurricularArea(Area $area)
    {
        $object = new CompCurricularArea;
        $object->idarea = $area->id;
        $object->idcompcurricular = $this->id;
        $object->store();
    }
    public function getCompCurricularArea()
    {
        return parent::loadAggregate('Area', 'CompCurricularArea', 'idcompcurricular', 'idarea', $this->id);
    }
    public function get_descr()
	{
		return $this->descricao;
	}
    public static function getDescricaoById($id)
    {
        $componente = new self($id);
        return $componente->descricao;
    }

}
?>
