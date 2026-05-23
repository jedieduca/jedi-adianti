<?php
/**
 * Active Record for view ofertaturmaview criada na FormOfertaTurmaList
 * @author  Claudio A. Passos Isabel Fernandes e Ronaldo Goldschmidt
 */
class OfertaTurmaView extends TRecord
{
    const TABLENAME = 'ofertaturmaview';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial';

  
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('nome');
        parent::addAttribute('denominacao');
        parent::addAttribute('descricao');
    }

}
?>
