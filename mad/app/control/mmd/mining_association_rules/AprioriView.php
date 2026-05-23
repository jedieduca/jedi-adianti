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
 

    public function __construct()
    {
        parent::__construct();

        // 1. Criar o Painel Principal
        $panel_filtro = new TPanelGroup(_t('Mining Association Rules') . ' - ' . _t('Apriori'));

        // 2. Criar o Form de Busca
        $this->form = new TForm('form_busca');
        $this->form->setData(TSession::getValue(__CLASS__.'_filter_data'));
        $filter_field = new TEntry('filter_text');
        $filter_field->placeholder = 'Filtrar por antecedente ou consequente...';
        $filter_field->setSize('100%');

        $filter_lift = new TNumeric('filter_lift', 2, ',', '.'); // 2 decimais
        $filter_lift->placeholder = 'Lift Mínimo';
        $filter_lift->setSize('100%');

        $filter_conf = new TNumeric('filter_conf', 4, ',', '.'); // 4 decimais
        $filter_conf->placeholder = 'Confiança Mín.';
        $filter_conf->setSize('100%');

        $btn_search = TButton::create('btn_search', [$this, 'onSearch'], 'Filtrar Regras', 'fa:search blue');
        $btn_clear  = TButton::create('btn_clear',  [$this, 'onClear'],  'Limpar',  'fa:eraser red');
        $btn_export = TButton::create('btn_export', [$this, 'onExportCsv'], 'Exportar CSV', 'fa:file-csv green');

        // REGISTRO OBRIGATÓRIO DOS CAMPOS NO FORMULÁRIO
        $this->form->setFields([
            $filter_field, 
            $filter_lift, 
            $filter_conf, 
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

        // Criar o separador visual
        $separator = new TElement('hr');
        $separator->style = 'margin: 15px 0; border-top: 2px dotted #ccc; width: 100%';
        
        // Organizando em uma grade para ficar visualmente limpo
        $table = new TTable;
        $table->style = 'width: 100%; margin: 10px; border-collapse: separate; border-spacing: 5px;';

        // --- Linha: Text Search ---
        $row1 = $table->addRow();
        $lbl1 = $row1->addCell(new TLabel(_t('Text search') . ' :'));
        $lbl1->style = 'text-align: right; width: 15%; vertical-align: middle;'; // Alinha o Label à direita
        $row1->addCell($filter_field); // O campo permanece à esquerda por padrão

        // --- Linha: Lift ---
        $row2 = $table->addRow();
        $lbl2 = $row2->addCell(new TLabel('Lift >= :'));
        $lbl2->style = 'text-align: right; vertical-align: middle;';
        $row2->addCell($filter_lift);

        // --- Linha: Trust ---
        $row3 = $table->addRow();
        $lbl3 = $row3->addCell(new TLabel(_t('Trust') . ' >= :'));
        $lbl3->style = 'text-align: right; vertical-align: middle;';
        $row3->addCell($filter_conf);

        // --- Linha: Separador ---
        $row4 = $table->addRow();
        $separator = new TElement('hr');
        $separator->style = 'margin: 15px 0; border-top: 2px solid #ccc; width: 100%';
        $cell_sep = $row4->addCell($separator);
        $cell_sep->colspan = 2;

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
    
    public function onReload($param = NULL)
    {
        // Chamada ao serviço para confecção do gráfico
        try {
    
            $apiData = (array) JediEducaRestService::getData('/regras');
    
            if ($apiData) {
                
                // Limpar dados atuais
                $this->datagrid->clear();
                $this->imageContainer->clearChildren();

                // --- LÓGICA DE FILTRO ---
                $regras = (array) $apiData['regras'];
                $filterData = TSession::getValue(__CLASS__.'_filter_data');

                if (!empty($filterData)) {
                    $regras = array_filter($regras, function($row) use ($filterData) {
                        $match = true;

                        // 1. Filtro de Texto (Antecedentes ou Consequentes)
                        if (!empty($filterData->filter_text)) {
                            $term = strtolower($filterData->filter_text);
                            $ant = strtolower(implode(', ', (array) $row->antecedents));
                            $con = strtolower(implode(', ', (array) $row->consequents));
                            if (!str_contains($ant, $term) && !str_contains($con, $term)) {
                                $match = false;
                            }
                        }

                        // 2. Filtro de Lift Mínimo
                        if (!empty($filterData->filter_lift) && $row->lift < (float)$filterData->filter_lift) {
                            $match = false;
                        }

                        // 3. Filtro de Confiança Mínima
                        if (!empty($filterData->filter_conf) && $row->confidence < (float)$filterData->filter_conf) {
                            $match = false;
                        }

                        return $match;
                    });
                }

                // Componente de Imagem
                $this->image = new TImage($apiData['links_imagens']->grafico_lift);
                $this->image->style = 'width: 100%; height: auto; margin-bottom: 20px; border: 1px solid #ddd';                
                $this->imageContainer->add($this->image);
    
                // Componente de Imagem
                $this->image = new TImage($apiData['links_imagens']->grafico_dispersao);
                $this->image->style = 'width: 100%; height: auto; margin-bottom: 20px; border: 1px solid #ddd';                
                $this->imageContainer->add($this->image);

                $limit = 10;
                $offset = isset($param['offset']) ? (int) $param['offset'] : 0;
                // $total_registros = $apiData['total_regras']; // Total vindo do Python
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
        // Limpa a persistência na sessão
        TSession::setValue(__CLASS__.'_filter_data', NULL);
        
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
            $filterData = TSession::getValue(__CLASS__.'_filter_data');

            // Aplicar a mesma lógica de filtro usada no onReload
            if (!empty($filterData)) {
                $regras = array_filter($regras, function($row) use ($filterData) {
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