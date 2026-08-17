<?php
/**
 * Active Record for table prompt
 * @author  Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt - Paulo Coelho
 */
class PromptView extends TRecord
{
    const TABLENAME = 'promptview';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('id_eixo');
        parent::addAttribute('system_prompt1');
        parent::addAttribute('system_prompt2');
        parent::addAttribute('user_prompt1');
        parent::addAttribute('user_prompt2');
    }

}
?>
