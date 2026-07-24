<?php
/**
 * Active Record for table instancia_gestora
 * @author  Claudio A. Passos
 */
class InstanciaGestora extends TRecord
{
    const TABLENAME = 'instancia_gestora';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}

    private $pai;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('nome');
        parent::addAttribute('id_instancia_gestora_pai');
    }

    public function get_pai()
	{
		if (empty($this->pai))
			$this->pai = new InstanciaGestora($this->id_instancia_gestora_pai);
		return $this->pai->nome;
	}



}
?>
