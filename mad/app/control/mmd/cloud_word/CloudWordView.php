<?php

use Adianti\Base\TStandardList;
use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;
use Adianti\Database\TRepository;
use Adianti\Database\TTransaction;
use Adianti\Registry\TSession;
use Adianti\Widget\Base\TElement;
use Adianti\Widget\Container\THBox;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TTable;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Datagrid\TDataGrid;
use Adianti\Widget\Datagrid\TDataGridColumn;
use Adianti\Widget\Datagrid\TPageNavigation;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Form\TButton;
use Adianti\Widget\Form\TForm;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Util\TImage;
use Adianti\Widget\Util\TXMLBreadCrumb;
use Adianti\Widget\Wrapper\TDBCombo;
use Adianti\Wrapper\BootstrapDatagridWrapper;

class CloudWordView extends TStandardList
{
    protected $panelImagem;
    protected $imageContainer;
    protected $datagrid;       // listing
    protected $pageNavigation; // Page Navigation
    protected $form; // Form de Busca 

    public function __construct()
    {
        parent::__construct();

        parent::setDatabase('jedi');                                     // defines the database

        // Criar o Painel Principal
        $panel_filtro = new TPanelGroup(_t('Filters'));

        // Criar o Form de Busca
        $this->form = new TForm('form_busca');
        $this->form->setData(TSession::getValue(__CLASS__ . '_filter_data'));

        $filter_category = new TDBCombo('filter_category', 'jedi', 'Category', 'descricao', 'descricao', 'descricao asc');
        $filter_answer   = new TDBCombo('filter_answer', 'jedi', 'Question', 'respcerta', 'respcerta', 'respcerta asc');

        $filter_category->setChangeAction(new TAction([$this, 'onChangeCategory']));

        $filter_category->setSize('100%');
        $filter_answer->setSize('100%');

        $btn_search = TButton::create('btn_search', [$this, 'onSearch'], 'Filtrar', 'fa:search blue');
        $btn_clear  = TButton::create('btn_clear',  [$this, 'onClear'],  'Limpar',  'fa:eraser red');
        $btn_export = TButton::create('btn_export', [$this, 'onExportCsv'], 'Exportar CSV', 'fa:file-csv green');

        // REGISTRO OBRIGATÓRIO DOS CAMPOS NO FORMULÁRIO
        $this->form->setFields([
            $filter_category,
            $filter_answer,
            $btn_search,
            $btn_clear,
            $btn_export
        ]);

        // Recarrega os dados salvos na sessão para o formulário não "limpar" ao recarregar
        $data = TSession::getValue(__CLASS__ . '_filter_data');
        if ($data) {
            $this->form->setData($data);
        }

        // Organizando os botões em uma caixa horizontal
        $button_box = new THBox;
        $button_box->add($btn_search);
        $button_box->add($btn_clear);
        $button_box->add($btn_export);

        // Criar o separador visual
        $separator = new TElement('hr');
        $separator->style = 'margin: 15px 0; border-top: 2px dotted #ccc; width: 100%';

        // Organizando em uma grade para ficar visualmente limpo
        $table = new TTable;
        $table->style = 'width: 100%; margin: 10px; border-collapse: separate; border-spacing: 5px; table-layout: fixed;';

        // --- Linha: Category ---
        $row1 = $table->addRow();
        $lbl1 = $row1->addCell(new TLabel(_t('Category')));
        $lbl1->style = 'text-align: right; vertical-align: middle; width: 200px;';
        $row1->addCell($filter_category);

        // --- Linha: Fact-checking ---
        $row2 = $table->addRow();
        $lbl2 = $row2->addCell(new TLabel(_t('News Classification')));
        $lbl2->style = 'text-align: right; vertical-align: middle; width: 200px';
        $row2->addCell($filter_answer);

        // --- Linha: Separador ---
        $row3 = $table->addRow();
        $separator = new TElement('hr');
        $separator->style = 'margin: 15px 0; border-top: 2px solid #ccc; width: 100%';
        $cell_sep = $row3->addCell($separator);
        $cell_sep->colspan = 2;

        // --- Linha: Botões ---
        $row4 = $table->addRow();
        #$row5->addCell(''); // Célula vazia para manter o alinhamento abaixo dos campos
        $btn_cell = $row4->addCell($button_box);
        $btn_cell->style = 'text-align: left;';
        $btn_cell->colspan = 2;

        $this->form->add($table);
        $panel_filtro->add($this->form);

        // Criar o DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';

        // Definir colunas
        $column_id       = new TDataGridColumn('id', 'Id', 'center');
        $column_category = new TDataGridColumn('category', _t('Category'), 'left');
        $column_news     = new TDataGridColumn('news', _t('News'), 'left');
        $column_answer   = new TDataGridColumn('answer', _t('News Classification'), 'center');

        // $column_suporte->setTransformer( function($value) {
        //     return number_format($value, 2, ',' );
        // });

        // $column_confianca->setTransformer( function($value) {
        //     return number_format($value, 2, ',' );
        // });

        // $column_lift->setTransformer( function($value) {
        //     return ($value >= 3.0) ? "<span class='label label-success' style='padding:4px'>{$value}</span>" : $value;
        // });

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_category);
        $this->datagrid->addColumn($column_news);
        $this->datagrid->addColumn($column_answer);

        // create the datagrid model
        $this->datagrid->createModel();

        // 4. Criar Paginação
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));

        // Criar o Painel Principal
        $panel_grid = new TPanelGroup();
        $panel_grid->add($this->datagrid)->style = 'overflow-x:auto';
        $panel_grid->addFooter($this->pageNavigation);

        //  Container para as Imagens (Gráficos)
        $this->panelImagem = new TPanelGroup();
        $this->panelImagem->style = 'text-align: center; width: 100%';
        $this->imageContainer = new TVBox;
        $this->imageContainer->style = 'width: 100%; margin-bottom: 20px;';
        $this->panelImagem->add($this->imageContainer);

        // Montar o layout
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($panel_filtro);
        $container->add($panel_grid);
        $container->add($this->panelImagem);
        parent::add($container);
    }

    public static function onChangeCategory($param)
    {
        try {
            $category_name = $param['filter_category'] ?? null; // Obtém o valor selecionado

            TTransaction::open('jedi');

            $repo = new TRepository('Question');
            $criteria = new TCriteria;
            $criteria->setProperty('order', 'respcerta asc');

            if (!empty($category_name)) {

                $criteria->add(new TFilter('id', 'IN', "(SELECT pc.id_pergunta 
                                                         FROM pergunta_categoria2 pc inner join categoria c on pc.id_categoria = c.id 
                                                         WHERE c.descricao = '{$category_name}')"));

                // Obtém um array indexado para o combo [valor => exibição]
                $options = $repo->getIndexedArray('respcerta', 'respcerta', $criteria);
            } else {
                // Se a categoria for limpa, podemos carregar todas ou deixar vazio           
                $options = $repo->getIndexedArray('respcerta', 'respcerta', $criteria);
            }

            TTransaction::close();

            // RECARREGA o combo de classificação ('filter_answer') dentro do formulário ('form_busca')
            TDBCombo::reload('form_busca', 'filter_answer', $options, true);
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }


    public function onReload($param = NULL)
    {
        try {

            $filterData = TSession::getValue(__CLASS__ . '_filter_data');
            $query = [];

            // Monta os parâmetros da query com os nomes que o Python espera
            if (!empty($filterData->filter_category)) $query['categoria'] = $filterData->filter_category;
            if (!empty($filterData->filter_answer))   $query['respcerta'] = $filterData->filter_answer;

            // Converte o array para uma string de query (ex: ?area=X&tema=Y)
            $queryString = !empty($query) ? '?' . http_build_query($query) : '';

            // Chama a API passando os filtros na URL
            $apiData = (array) JediEducaRestService::getData('/nuvem_palavras' . $queryString);

            if ($apiData) {

                // Limpar dados atuais
                $this->datagrid->clear();
                $this->imageContainer->clearChildren();

                // --- LÓGICA DE FILTRO ---
                $dados = (array) $apiData['dados'];
                if (!empty($filterData)) {
                    $dados = array_filter($dados, function ($row) use ($filterData) {
                        $match = true;

                        // Filtro por Categoria
                        if (!empty($filterData->filter_category)) {
                            $term     = strtolower($filterData->filter_category);
                            $category = strtolower(implode(', ', (array) $row->categoria));
                            if (!str_contains($category, $term)) {
                                $match = false;
                            }
                        }

                        // Filtro por Categoria
                        if (!empty($filterData->filter_answer)) {
                            $term   = strtolower($filterData->filter_answer);
                            $answer = strtolower(implode(', ', (array) $row->respcerta));
                            if (!str_contains($answer, $term)) {
                                $match = false;
                            }
                        }

                        return $match;
                    });
                }

                // Componente de Imagem/*
                $this->image = new TImage($apiData['link_grafico']->link);
                $this->image->style = 'width: 100%; height: auto; margin-bottom: 20px; border: 1px solid #ddd';
                $this->imageContainer->add($this->image);

                $limit = 10;
                $offset = isset($param['offset']) ? (int) $param['offset'] : 0;
                $total_registros = $apiData['total_registros']; // Total vindo do Python
                # $total_registros = count($dados);

                // Corta o array para a página atual
                $rows = array_slice($dados, $offset, $limit);

                foreach ($rows as $row) {
                    // Converter arrays de antecedentes/consequentes para string (ex: "item1, item2")
                    $item = new stdClass;
                    $item->id       = $row->id;
                    $item->category = $row->categoria;
                    $item->news     = $row->pergunta;
                    $item->answer   = $row->respcerta;

                    $this->datagrid->addItem($item);
                }

                // Configurar o navegador de páginas
                $this->pageNavigation->setCount($total_registros);
                $this->pageNavigation->setProperties($param);
                $this->pageNavigation->setLimit($limit);
            }
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onShow($param)
    {
        try {
            // Se você precisa carregar dados ao exibir a página, 
            // chame o onReload() em vez de tentar mostrar a página manualmente.
            $this->onReload($param);
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
    /*
    public function onSearch($param)
    {
        $data = $this->form->getData();
        TSession::setValue(__CLASS__.'_filter_data', $data);
        // Força o formulário a exibir exatamente o que acabou de ser filtrado
        $this->form->setData($data);
        $this->onReload($param);
    }
*/
    public function onClear($param)
    {
        // Limpa a persistência na sessão
        TSession::setValue(__CLASS__ . '_filter_data', NULL);

        // Limpa o formulário na tela
        $this->form->clear();

        // Recarrega os dados sem filtros
        $this->onReload();
    }

    public function onExportCsv($param)
    {
        try {
            $apiData = (array) JediEducaRestService::getData('/regras');
            $regras  = (array) $apiData['regras'];
            $filterData = TSession::getValue(__CLASS__ . '_filter_data');

            // Aplicar a mesma lógica de filtro usada no onReload
            if (!empty($filterData)) {
                $regras = array_filter($regras, function ($row) use ($filterData) {
                    $match = true;
                    if (!empty($filterData->filter_text)) {
                        $term = strtolower($filterData->filter_text);
                        $ant = strtolower(implode(', ', (array) $row->antecedents));
                        $con = strtolower(implode(', ', (array) $row->consequents));
                        if (!str_contains($ant, $term) && !str_contains($con, $term)) $match = false;
                    }
                    if (!empty($filterData->filter_lift) && $row->lift < (float)$filterData->filter_lift) $match = false;
                    if (!empty($filterData->filter_conf) && $row->confidence < (float)$filterData->filter_conf) $match = false;
                    return $match;
                });
            }

            if ($regras) {
                $csv = "Antecedentes;Consequentes;Suporte;Confianca;Lift\n";
                foreach ($regras as $row) {
                    $ant = implode(', ', (array) $row->antecedents);
                    $con = implode(', ', (array) $row->consequents);
                    $csv .= "{$ant};{$con};{$row->support};{$row->confidence};{$row->lift}\n";
                }

                $file = 'tmp/regras_apriori_' . uniqid() . '.csv';
                file_put_contents($file, $csv);
                TPage::openFile($file);
            }
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
}
