<?php
/**
 * Active Record for table prompt
 * @author  Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 */
class Prompt extends TRecord
{
    const TABLENAME = 'prompt';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('id_tema');
        parent::addAttribute('caracteristicas');
        parent::addAttribute('system_prompt');
        parent::addAttribute('user_prompt1');
        parent::addAttribute('user_prompt2');
    }

    static public function getPrompt($idTema)
    {
        /*$conn = TTransaction::get();
        // run query
        $sql='select login FROM usuario2 ';
        $sql.='WHERE login="'.$login.'"';

        echo '<pre>'; print_r($sql);
        $result = $conn->query($sql);
        return $result->rowCount();*/


        return self::where('id_tema', '=', $idTema)->first();
    }

}
?>
