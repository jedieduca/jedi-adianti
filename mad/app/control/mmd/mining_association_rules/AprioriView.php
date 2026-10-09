<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Control\TWindow;
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
    use JediPdfExportTrait;
    use JediCsvExportTrait;

    // Usados pelos traits de exportação (a tela é um TPage, sem a estrutura do TStandardList)
    protected $limit    = 10;
    protected $database = 'jedi';

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
        // Mesmas ações de exportação dos demais módulos (template de PDF e CSV padrão)
        $btn_export = new TButton('btn_export');
        $btn_export->setAction(new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static' => '1']), 'Exportar CSV');
        $btn_export->setImage('fa:file-csv green');

        $btn_pdf = new TButton('btn_pdf');
        $btn_pdf->setAction(new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static' => '1']), 'Exportar PDF');
        $btn_pdf->setImage('far:file-pdf red');

        // REGISTRO OBRIGATÓRIO DOS CAMPOS NO FORMULÁRIO
        $this->form->setFields([
            $filter_antecedent,
            $filter_consequent,
            $filter_support,
            $filter_conf,
            $filter_lift,
            $btn_search,
            $btn_clear,
            $btn_export,
            $btn_pdf
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
        $button_box->add($btn_pdf);

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

    /**
     * Converte os filtros do formulário Apriori nos parâmetros da API (/regras)
     */
    private static function getRuleFilters($filterData): array
    {
        if (empty($filterData)) {
            return [];
        }

        return array_filter([
            'antecedente'   => trim($filterData->filter_antecedent ?? ''),
            'consequente'   => trim($filterData->filter_consequent ?? ''),
            'suporte_min'   => $filterData->filter_support ?? null,
            'confianca_min' => $filterData->filter_conf    ?? null,
            'lift_min'      => $filterData->filter_lift    ?? null,
        ], fn($value) => !is_null($value) && $value !== '');
    }

    /**
     * Monta o endpoint /regras com os filtros globais (AssociationRulesView) e os da tela Apriori
     */
    private static function buildEndpoint($filterObject): string
    {
        $params = [];
        if (!empty($filterObject)) {
            $params = array_filter((array) $filterObject, fn($value) => !is_null($value) && trim((string) $value) !== '');
        }
        $params = array_merge($params, self::getRuleFilters(TSession::getValue(__CLASS__.'_filter_data')));

        return '/regras' . ($params ? '?' . http_build_query($params) : '');
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
            } else if (!isset($param['offset']) && $this->limit !== 0) {
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
                // Paginação do DataGrid ou exportação (limit = 0) -> Mantém o filtro já ativo na sessão do AprioriView
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
            $endpoint = self::buildEndpoint($filterObject);

            // ============================================================================
            // 4. CHAMADA À API E RENDERIZAÇÃO
            // ============================================================================
            $apiData = (array) JediEducaRestService::getData($endpoint);            
    
            if ($apiData) {
                
                // Limpar dados atuais
                $this->datagrid->clear();
                $this->imageContainer->clearChildren();

                // API respondeu sem dados ou sem regras: exibe o aviso no lugar dos gráficos
                if ($aviso = JediEducaRestService::getAviso($apiData)) {
                    $this->imageContainer->add($aviso);
                    return;
                }

                // Regras já filtradas pela API (mesmo conjunto usado nos gráficos)
                $regras = (array) $apiData['regras'];

                // Componente de Imagem
                $this->image = new TImage($apiData['links_imagens']->grafico_lift);
                $this->image->style = 'width: 100%; max-width: 1200px; height: auto; display: block; margin: 0 auto 20px auto; border: 1px solid #ddd;';
                $this->imageContainer->add($this->image);
    
                // Componente de Imagem
                $this->image = new TImage($apiData['links_imagens']->grafico_dispersao);
                $this->image->style = 'width: 100%; max-width: 1200px; height: auto; display: block; margin: 0 auto 20px auto; border: 1px solid #ddd;';
                $this->imageContainer->add($this->image);

                // Gráficos impressos no PDF
                $this->pdfCharts[] = $apiData['links_imagens']->grafico_lift;
                $this->pdfCharts[] = $apiData['links_imagens']->grafico_dispersao;

                // Exportação (CSV/PDF) usa limit = 0: todas as regras, não só a página
                $limit = ($this->limit === 0) ? max(count($regras), 1) : 10;
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

    /**
     * Exportação CSV (a tela é um TPage: não herda as ações de exportação do TStandardList)
     */
    public function onExportCSV($param)
    {
        try
        {
            $output = 'app/output/' . uniqid() . '.csv';
            $this->exportToCSV($output);
            TPage::openFile($output);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Exportação PDF, aberta numa janela de visualização (mesmo comportamento das demais telas)
     */
    public function onExportPDF($param)
    {
        try
        {
            $output = 'app/output/' . uniqid() . '.pdf';
            $this->exportToPDF($output);

            $window = TWindow::create('Export', 0.8, 0.8);
            $object = new TElement('object');
            $object->{'data'}  = 'download.php?file=' . $output;
            $object->{'type'}  = 'application/pdf';
            $object->{'style'} = 'width: 100%; height:calc(100% - 10px)';
            $window->add($object);
            $window->show();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Cabeçalho do PDF: título e filtros (globais da tela de Regras de Associação + os desta tela)
     */
    protected function pdfTitle()
    {
        return _t('Mining Association Rules') . ' - ' . _t('Apriori');
    }

    protected function pdfFilterValues()
    {
        $valores = [];

        $globais = ['escola' => 'Escola', 'turma' => 'Turma', 'capacidade' => 'Capacidade Crítica', 'nome' => 'Jogador', 'id' => 'ID'];
        foreach ((array) TSession::getValue(__CLASS__.'_filter_object') as $campo => $valor) {
            if (!is_null($valor) && trim((string) $valor) !== '') {
                $valores[$globais[$campo] ?? ucfirst($campo)] = $valor;
            }
        }

        // ">=" e não "≥": a fonte do PDF não tem o símbolo
        $locais = [
            'filter_antecedent' => _t('Antecedent'),
            'filter_consequent' => _t('Consequent'),
            'filter_support'    => _t('Support') . ' >=',
            'filter_conf'       => _t('Trust') . ' >=',
            'filter_lift'       => _t('Lift') . ' >=',
        ];
        $filterData = TSession::getValue(__CLASS__.'_filter_data');
        foreach ($locais as $campo => $rotulo) {
            $valor = $filterData->$campo ?? null;
            if (!is_null($valor) && trim((string) $valor) !== '') {
                $valores[$rotulo] = $valor;
            }
        }

        return $valores;
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