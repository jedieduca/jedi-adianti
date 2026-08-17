<?php
/**
 * Active Record for table PlanoAula
 * @author  Claudio Azevedo Passos
 */
class PlanoAula extends TRecord
{
    const TABLENAME = 'plano_aula';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('titulo');
        parent::addAttribute('nivel_ensino');
        parent::addAttribute('ano_escolar');
        parent::addAttribute('eixo_computacao');
        parent::addAttribute('duracao_aula');
        parent::addAttribute('numero_aula');
        parent::addAttribute('comentarios_adicionais');
        parent::addAttribute('prompt');
        parent::addAttribute('conteudo_gerado');
        parent::addAttribute('visibilidade');
        parent::addAttribute('gerar_praticas_aprend');
        parent::addAttribute('qtd_praticas');
        parent::addAttribute('nivel_inicial');
        parent::addAttribute('nivel_intermediario');
        parent::addAttribute('nivel_avancado');
        parent::addAttribute('data_inicio_praticas');
        parent::addAttribute('data_fim_praticas');
        parent::addAttribute('neurodivergencia');
        parent::addAttribute('conteudo_gerado_pratica');
    }


    public static function getEixo($eixo)
    {
        TTransaction::open('platia');
        $repositorio = new TRepository('PlanoAula');
        $criteria = new TCriteria();
        $criteria->add(new TFilter("eixo_computacao", "=", $eixo));
        $repositorio->load($criteria);
		TTransaction::close();
        return $criteria;
    }
}
?>
