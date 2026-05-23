<?php
/**
 * Active Record for table ofertaturmaaluno
 * @author  Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 */
class OfertaTurmaAluno extends TRecord
{
    const TABLENAME = 'ofertaturmaaluno';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('idofertaturma');
        parent::addAttribute('idaluno');
    }

    static public function getAlunos($id)
    {
        //return FrequenciaAulaMonitoria::where('idAula', '=', $idAula)->orderBy('nome', 'asc')->load(); 
        return OfertaTurmaAluno::where('idofertaturma', '=', $id)->load(); 
    }

    static public function getAluno($id)
    {
        return parent::where('idaluno', '=', $id)->first(); 
    }
}
?>
