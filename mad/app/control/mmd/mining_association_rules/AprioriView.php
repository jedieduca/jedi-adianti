<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
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
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TForm;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Form\TNumeric;
use Adianti\Widget\Util\TImage;
use Adianti\Widget\Util\TXMLBreadCrumb;
use Adianti\Wrapper\BootstrapDatagridWrapper;

class AprioriView extends TPage
{
    protected $panelImagem;
    protected $imageContainer;
    protected $datagrid;       // listing
    protected $pageNavigation; // Page Navigation
    protected $form; // Form de Busca
    protected $cellSummary; // <--- ADICIONE ESTA PROPRIEDADE 

    public function __construct()
    {
        parent::__construct();

        // 1. Criar o Painel Principal
        $panel_filtro = new TPanelGroup(_t('Mining Association Rules') . ' - ' . _t('Apriori'));

        // 2. Criar o Form de Busca
        $this->form = new TForm('form_busca');
        $this->form->setData(TSession::getValue(__CLASS__.'_filter_data'));

        $filter_antecedent = new TEntry('filter_antecedent');
        $filter_antecedent->placeholder = 'Contém no antecedente...';
        $filter_antecedent->setSize('100%');

        $filter_consequent = new TEntry('filter_consequent');
        $filter_consequent->placeholder = 'Contém no consequente...';
        $filter_consequent->setSize('100%');

        $filter_support = new TNumeric('filter_support', 2, ',', '.', true);
        $filter_support->placeholder = 'Suporte Mín.';
        $filter_support->setSize('30%');

        $filter_conf = new TNumeric('filter_conf', 2, ',', '.', true); // 4 decimais
        $filter_conf->placeholder = 'Confiança Mín.';
        $filter_conf->setSize('30%');

        $filter_lift = new TNumeric('filter_lift', 2, ',', '.', true); // 2 decimais
        $filter_lift->placeholder = 'Lift Mínimo';
        $filter_lift->setSize('30%');


        $btn_search = TButton::create('btn_search', [$this, 'onSearch'], 'Filtrar Regras', 'fa:search blue');
        $btn_clear  = TButton::create('btn_clear',  [$this, 'onClear'],  'Limpar',  'fa:eraser red');
        $btn_export = new TButton('btn_export');
        $btn_export->setAction(new TAction([__CLASS__, 'onExportCsv'], ['static' => '1']), 'Exportar CSV');
        $btn_export->setImage('fa:file-csv green');

        // REGISTRO OBRIGATÓRIO DOS CAMPOS NO FORMULÁRIO
        $this->form->setFields([
            $filter_antecedent,
            $filter_consequent,
            $filter_support,
            $filter_conf,
            $filter_lift,
            $btn_search,
            $btn_clear,
            $btn_export
        ]);

        // Recarrega os dados salvos na sessão para o formulário não "limpar" ao recarregar
        $data = TSession::getValue(__CLASS__.'_filter_data');
        if($data){
            $this->form->setData($data);
        }

        // Organizando os botões em uma caixa horizontal
        $button_box = new THBox;
        $button_box->add($btn_search);
        $button_box->add($btn_clear);
        $button_box->add($btn_export);

        // Organizando em uma grade para ficar visualmente limpo
        $table = new TTable;
        $table->style = 'width: 100%; margin: 10px; border-collapse: separate; border-spacing: 5px;'; 

        // =========================================================
        // Linha para exibir os filtros ativos (Container TVBox)
        // =========================================================
        $this->cellSummary = new TVBox;
        $this->cellSummary->style = 'width: 100%;';
        $this->cellSummary->add($this->buildFilterSummary());

        $row_summary = $table->addRow();
        $cell_summary = $row_summary->addCell($this->cellSummary);
        $cell_summary->colspan = 2;
        // =========================================================

        $addFilterRow = function ($label, $field) use ($table) {
            $row = $table->addRow();
            $lbl = $row->addCell(new TLabel($label));
            $lbl->style = 'text-align: right; width: 15%; vertical-align: middle;';
            $row->addCell($field);
        };

        $addSeparatorRow = function () use ($table) {
            $separator = new TElement('hr');
            $separator->style = 'margin: 15px 0; border-top: 2px solid #ccc; width: 100%';
            $cell = $table->addRow()->addCell($separator);
            $cell->colspan = 2;
        };

        $addSeparatorRow();

        $addFilterRow(_t('Antecedent') . ' :', $filter_antecedent);
        $addFilterRow(_t('Consequent') . ' :', $filter_consequent);

        $addSeparatorRow();
        
        $addFilterRow(_t('Support')    . ' >= :', $filter_support);
        $addFilterRow(_t('Trust')      . ' >= :', $filter_conf);
        $addFilterRow(_t('Lift')       . ' >= :', $filter_lift);

        $addSeparatorRow();

        // --- Linha: Botões ---
        $row5 = $table->addRow();
        #$row5->addCell(''); // Célula vazia para manter o alinhamento abaixo dos campos
        $btn_cell = $row5->addCell($button_box);
        $btn_cell->style = 'text-align: left;';
        $btn_cell->colspan = 2;

        $this->form->add($table);

        $panel_filtro->add($this->form);

        // 1. Criar o Painel Principal
        $panel_grid = new TPanelGroup();

        // 3. Criar o DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        // Definir colunas
        $this->datagrid->addColumn(new TDataGridColumn('antecedents', 'Antecedentes', 'left'));
        $this->datagrid->addColumn(new TDataGridColumn('consequents', 'Consequentes', 'left'));
        $column_suporte   = new TDataGridColumn('support', 'Suporte', 'right');
        $column_confianca = new TDataGridColumn('confidence', 'Confiança', 'right');
        $column_lift      = new TDataGridColumn('lift', 'Lift', 'right');
        
        // ADICIONE ISTO: Permite que o HTML seja interpretado
        $column_suporte->setTransformer( function($value) {
            return number_format($value, 2, ',' );
        });
        
        $column_confianca->setTransformer( function($value) {
            return number_format($value, 2, ',' );
        });
        
        $column_lift->setTransformer( function($value) {
            return ($value >= 3.0) ? "<span class='label label-success' style='padding:4px'>{$value}</span>" : $value;
        });
            
        $this->datagrid->addColumn($column_suporte);
        $this->datagrid->addColumn($column_confianca);
        $this->datagrid->addColumn($column_lift);

        // create the datagrid model
        $this->datagrid->createModel();
        
        // 4. Criar Paginação
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
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
        $container->add($panel_filtro);
        $container->add($panel_grid);
        $container->add($this->panelImagem);        
        parent::add($container);
    }

    private static function applyLocalFilters(array $regras, $filterData): array
    {
        if (empty($filterData)) {
            return $regras;
        }

        $ant_term = mb_strtolower(trim($filterData->filter_antecedent ?? ''));
        $con_term = mb_strtolower(trim($filterData->filter_consequent ?? ''));
        $min_sup  = $filterData->filter_support ?? null;
        $min_conf = $filterData->filter_conf    ?? null;
        $min_lift = $filterData->filter_lift    ?? null;

        return array_filter($regras, function ($row) use ($ant_term, $con_term, $min_sup, $min_conf, $min_lift) {
            if (!empty($min_sup)  && $row->support    < (float) $min_sup)  return false;
            if (!empty($min_conf) && $row->confidence < (float) $min_conf) return false;
            if (!empty($min_lift) && $row->lift       < (float) $min_lift) return false;

            if ($ant_term !== '') {
                $ant = mb_strtolower(implode(', ', (array) $row->antecedents));
                if (!str_contains($ant, $ant_term)) return false;
            }
            if ($con_term !== '') {
                $con = mb_strtolower(implode(', ', (array) $row->consequents));
                if (!str_contains($con, $con_term)) return false;
            }
            return true;
        });
    }

    
    public function onReload($param = NULL)
    {
        // Chamada ao serviço para confecção do gráfico
        try {
            // ============================================================================
            // 1. RESOLUÇÃO DOS FILTROS
            // ============================================================================
            if (array_key_exists('filtros', $param ?? []) && !empty($param['filtros'])) {
                // Caso venha via parâmetro explícito
                $filterObject = (object) $param['filtros'];
                TSession::setValue(__CLASS__.'_filter_object', $filterObject);
            } else if (!isset($param['offset'])) {
                // Ao abrir a tela diretamente pelo menu/botão (sem paginação):
                // Lê SEMPRE os filtros mais recentes salvos na sessão da AssociationRulesView
                $sessao_filtros = (array) TSession::getValue('AssociationRulesView_filter_data');
                
                // Limpa valores nulos ou vazios
                $filtros_ativos = array_filter($sessao_filtros, function($valor) {
                    return !is_null($valor) && trim((string) $valor) !== '';
                });

                $filterObject = !empty($filtros_ativos) ? (object) $filtros_ativos : null;
                TSession::setValue(__CLASS__.'_filter_object', $filterObject);
            } else {
                // Em navegações de paginação do DataGrid -> Mantém o filtro já ativo na sessão do AprioriView
                $filterObject = TSession::getValue(__CLASS__.'_filter_object');
            }

            // ============================================================================
            // 2. ATUALIZAÇÃO VISUAL (Badges HTML)
            // ============================================================================
            if ($this->cellSummary) {
                $this->cellSummary->clearChildren();
                $this->cellSummary->add($this->buildFilterSummary($filterObject));
            }

            // ============================================================================
            // 3. PREPARAÇÃO DA REQUISIÇÃO (Monta a query string para a API Python)
            // ============================================================================
            $cleanFilters = [];
            
            if (!empty($filterObject)) {
                $cleanFilters = array_filter((array) $filterObject, function($value) {
                    return !is_null($value) && trim((string) $value) !== '';
                });
            }

            $queryParams = http_build_query($cleanFilters);
            $endpoint = '/regras' . ($queryParams ? '?' . $queryParams : '');

            // ============================================================================
            // 4. CHAMADA À API E RENDERIZAÇÃO
            // ============================================================================
            $apiData = (array) JediEducaRestService::getData($endpoint);            
    
            if ($apiData) {
                
                // Limpar dados atuais
                $this->datagrid->clear();
                $this->imageContainer->clearChildren();

                // --- LÓGICA DE FILTRO ---
                $regras = (array) $apiData['regras'];
                $regras = self::applyLocalFilters($regras, TSession::getValue(__CLASS__.'_filter_data'));

                // Componente de Imagem
                $this->image = new TImage($apiData['links_imagens']->grafico_lift);
                $this->image->style = 'width: 100%; max-width: 1200px; height: auto; display: block; margin: 0 auto 20px auto; border: 1px solid #ddd;';
                $this->imageContainer->add($this->image);
    
                // Componente de Imagem
                $this->image = new TImage($apiData['links_imagens']->grafico_dispersao);
                $this->image->style = 'width: 100%; max-width: 1200px; height: auto; display: block; margin: 0 auto 20px auto; border: 1px solid #ddd;';
                $this->imageContainer->add($this->image);

                $limit = 10;
                $offset = isset($param['offset']) ? (int) $param['offset'] : 0;
                $total_registros = count($regras);

                // Corta o array para a página atual
                $rows = array_slice($regras, $offset, $limit);
                
                foreach ($rows as $row) {
                    // Converter arrays de antecedentes/consequentes para string (ex: "item1, item2")
                    $item = new stdClass;
                    $item->antecedents = implode(', ', (array) $row->antecedents);
                    $item->consequents = implode(', ', (array) $row->consequents);
                    $item->support     = number_format($row->support, 4);
                    $item->confidence  = number_format($row->confidence, 4);
                    $item->lift        = number_format($row->lift, 2);               

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

    public function onSearch($param)
    {
        $data = $this->form->getData();
        TSession::setValue(__CLASS__.'_filter_data', $data);
        // Força o formulário a exibir exatamente o que acabou de ser filtrado
        $this->form->setData($data);
        $this->onReload($param);
    }

    public function onClear($param)
    {

        // 1. Limpa APENAS os filtros locais do formulário Apriori
        TSession::setValue(__CLASS__.'_filter_data', NULL);
        
        // 2. Limpa os campos do formulário na tela (texto, lift, confiança)
        $this->form->clear();
        
        // 3. Recarrega a página mantendo os filtros globais
        $this->onReload($param);
    }

    public static function onExportCsv($param)
    {
        try {
            // Recupera os parâmetros guardados na sessão
            $escola     = TSession::getValue(__CLASS__.'_escola');
            $turma      = TSession::getValue(__CLASS__.'_turma');
            $capacidade = TSession::getValue(__CLASS__.'_capacidade');
            $nome       = TSession::getValue(__CLASS__.'_nome');

            $queryParams = http_build_query(array_filter([
                'escola'     => $escola,
                'turma'      => $turma,
                'capacidade' => $capacidade,
                'nome'       => $nome
            ], fn($value) => !is_null($value) && $value !== ''));

            $endpoint = '/regras' . ($queryParams ? '?' . $queryParams : '');

            $apiData = (array) JediEducaRestService::getData($endpoint);
            $regras  = (array) ($apiData['regras'] ?? []);
            $regras  = self::applyLocalFilters($regras, TSession::getValue(__CLASS__.'_filter_data'));

            if ($regras) {
                $file = 'tmp/regras_apriori_' . uniqid() . '.csv';
                $fp   = fopen($file, 'w');
                if (!$fp) {
                    throw new Exception("Não foi possível criar o arquivo {$file}. Verifique a permissão de escrita na pasta tmp/.");
                }
                
                // BOM UTF-8: faz o Excel reconhecer a acentuação
                fwrite($fp, "\xEF\xBB\xBF");

                fputcsv($fp, ['Antecedentes', 'Consequentes', 'Suporte', 'Confiança', 'Lift'], ';');
                foreach ($regras as $row) {
                    fputcsv($fp, [
                        implode(', ', (array) $row->antecedents),
                        implode(', ', (array) $row->consequents),
                        number_format($row->support,    4, ',', ''),
                        number_format($row->confidence, 4, ',', ''),
                        number_format($row->lift,       2, ',', ''),
                    ], ';');
                }
                fclose($fp);
                TPage::openFile($file);
            }
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Monta um container HTML (Badges) com os filtros ativos
     */
    private function buildFilterSummary($filterParam = null)
    {
        // 1. Tenta pegar dos parâmetros de entrada; se nulo, pega da sessão
        if (!empty($filterParam)) {
            $filterObject = is_object($filterParam) ? $filterParam : (object) $filterParam;
        } else {
            $filterObject = TSession::getValue(__CLASS__.'_filter_object');
        }

        // 2. Mapeamento de rótulos amigáveis
        $labelsMap = [
            'escola'     => 'Escola',
            'turma'      => 'Turma',
            'capacidade' => 'Capacidade Crítica',
            'nome'       => 'Jogador',
            'id'         => 'ID'
        ];

        $tags = [];

        // 3. Desmembra cada item do objeto/array de filtro
        if (!empty($filterObject)) {
            $filterVars = is_object($filterObject) ? get_object_vars($filterObject) : (array) $filterObject;

            foreach ($filterVars as $key => $value) {
                // Remove espaços em branco das extremidades
                $cleanValue = is_string($value) ? trim($value) : $value;

                // Exibe apenas chaves que realmente contenham algum valor
                if (!is_null($cleanValue) && $cleanValue !== '') {
                    $labelName = $labelsMap[$key] ?? ucfirst($key);
                    $tags[] = "<b>{$labelName}:</b> {$cleanValue}";
                }
            }
        }

        // 4. Constrói o container visual com as tags
        $html = new TElement('div');
        $html->id = 'filter_summary_container';
        $html->style = 'margin: 10px 0 5px 0; text-align: left;';

        if (!empty($tags)) {
            $label = new TElement('span');
            $label->style = 'margin-right: 8px; font-weight: bold; color: #555;';
            $label->add('<i class="fa fa-filter"></i> Filtros Ativos: ');
            $html->add($label);

            foreach ($tags as $tag) {
                $badge = new TElement('span');
                $badge->class = 'label label-info';
                $badge->style = 'margin-right: 5px; font-size: 11px; padding: 5px 8px; display: inline-block;';
                $badge->add($tag);
                $html->add($badge);
            }
        } else {
            $badge = new TElement('span');
            $badge->class = 'label label-default';
            $badge->style = 'font-size: 11px; padding: 5px 8px;';
            $badge->add('Nenhum filtro aplicado (Exibindo todos os registros)');
            $html->add($badge);
        }

        return $html;
    }     
}