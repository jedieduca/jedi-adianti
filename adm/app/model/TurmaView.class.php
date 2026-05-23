<?php
/**
 * Active Record for viw turmaview criada na FormTurmaList
 * @author  Claudio A. Passos Isabel Fernandes e Ronaldo Goldschmidt
 */
class TurmaView extends TRecord
{
    const TABLENAME = 'turmaview';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial';

  
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('nome');
        parent::addAttribute('descricao');
        parent::addAttribute('identificacao');
    }

}
?>
