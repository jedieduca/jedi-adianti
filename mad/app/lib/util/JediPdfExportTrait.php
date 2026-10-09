<?php

use Adianti\Core\AdiantiCoreTranslator;
use Adianti\Registry\TSession;
use Adianti\Widget\Form\TCombo;
use Adianti\Widget\Form\TField;
use Adianti\Widget\Form\TLabel;
use Adianti\Wrapper\BootstrapFormBuilder;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Template padrão dos PDFs exportados pelos módulos do JEDi
 *
 * Substitui o exportToPDF do AdiantiStandardListExportTrait:
 * - cabeçalho (todas as páginas): logo, título do módulo, data/hora da impressão,
 *   filtros aplicados e, nos módulos com pdfSecurityDescription(), a restrição de acesso do perfil
 * - conteúdo: grid completo (cabeçalho das colunas repetido a cada página) e os gráficos
 *   registrados pelo módulo em $this->pdfCharts durante o onReload
 * - rodapé: "Página X de Y" centralizado
 * A página sai em retrato; se o grid não couber na largura, é refeita em paisagem.
 */
trait JediPdfExportTrait
{
    // URLs dos gráficos impressos abaixo do grid (o módulo preenche no onReload)
    protected $pdfCharts = [];

    // Largura (pt) que o grid ocuparia sem quebrar linhas, medida no último render
    private $pdfGridWidth = 0;

    // Linhas extras do cabeçalho: ['Filtros' => texto, 'Acesso' => texto]
    private $pdfHeaderInfo = [];

    private static $pdfLogo       = 'app/images/Logo_JEDi_1.png';
    private static $pdfFont       = 'Helvetica';
    private static $pdfMargin     = ['top' => 95, 'right' => 28, 'bottom' => 45, 'left' => 28]; // pt
    private static $pdfInfoLineH  = 11;   // altura (pt) de cada linha de filtros/acesso
    private static $pdfInfoCharW  = 3.9;  // largura média (pt) de um caractere na fonte 7.5pt

    /**
     * Export to PDF
     * @param $output Output file
     */
    protected function exportToPDF($output)
    {
        if ( !((!file_exists($output) && is_writable(dirname($output))) OR is_writable($output)) )
        {
            throw new Exception(AdiantiCoreTranslator::translate('Permission denied') . ': ' . $output);
        }

        // Gráficos altos são abertos e fatiados em memória pelo GD
        ini_set('memory_limit', '512M');

        $this->limit     = 0;
        $this->pdfCharts = [];
        $this->datagrid->prepareForPrinting();
        $this->onReload([]);

        $grid     = clone $this->datagrid;
        $gridHtml = $grid->getContents();

        $this->pdfHeaderInfo = ['Filtros' => $this->pdfFilterDescription()];
        if (method_exists($this, 'pdfSecurityDescription'))
        {
            $this->pdfHeaderInfo['Acesso'] = $this->pdfSecurityDescription();
        }

        // Baixa cada gráfico uma única vez (null = indisponível)
        $charts = [];
        foreach (array_unique(array_filter($this->pdfCharts)) as $url)
        {
            $charts[] = $this->pdfImage($url);
        }

        // 1º render só com o grid, em retrato, para medir a largura que ele precisa
        $dompdf      = $this->pdfRender($gridHtml, [], 'portrait');
        $orientation = ($this->pdfGridWidth > $this->pdfContentSize('portrait')['w']) ? 'landscape' : 'portrait';

        if ($charts || $orientation === 'landscape')
        {
            $dompdf = $this->pdfRender($gridHtml, $charts, $orientation);
        }

        file_put_contents($output, $dompdf->output());
    }

    /**
     * Monta o HTML do template e renderiza na orientação informada
     */
    private function pdfRender(string $gridHtml, array $charts, string $orientation): Dompdf
    {
        $m        = self::$pdfMargin;
        $m['top'] = $this->pdfTopMargin($orientation);
        $headerY  = $m['top'] - 15;   // cabeçalho começa a 15pt da borda da página
        $area     = $this->pdfContentSize($orientation);

        $infoHtml = '';
        foreach ($this->pdfHeaderInfo as $label => $text)
        {
            $infoHtml .= '<div class="pdf-info"><b>' . $label . ':</b> ' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</div>';
        }
        // Título: método pdfTitle() do módulo, se houver; senão, o título do formulário de busca
        $title = method_exists($this, 'pdfTitle') ? $this->pdfTitle()
               : (method_exists($this->form, 'getFormTitle') ? $this->form->getFormTitle() : '');
        $title = htmlspecialchars(strip_tags((string) $title), ENT_QUOTES, 'UTF-8');
        $date  = date('d/m/Y H:i');
        $logo  = $this->pdfImage(self::$pdfLogo);
        $logo  = $logo ? "<img src=\"{$logo['src']}\" style=\"height:42pt\">" : '';

        $chartsHtml = '';
        foreach ($charts as $chart)
        {
            if (!$chart)
            {
                $chartsHtml .= '<div class="pdf-chart pdf-chart-missing">Gráfico indisponível no momento.</div>';
                continue;
            }

            $chartsHtml .= $this->pdfChartHtml($chart, $area);
        }

        $font  = self::$pdfFont;
        $lineH = self::$pdfInfoLineH;
        $html  = <<<HTML
<html>
<head>
<meta charset="UTF-8">
<style>
    @page { margin: {$m['top']}pt {$m['right']}pt {$m['bottom']}pt {$m['left']}pt; }
    body  { font-family: '{$font}', sans-serif; font-size: 8.5pt; color: #222; }

    #pdf-header { position: fixed; top: -{$headerY}pt; left: 0; right: 0; border-bottom: 1.2pt solid #4a4a8a; padding-bottom: 4pt; }
    #pdf-header table { width: 100%; border-collapse: collapse; }
    #pdf-header td    { vertical-align: middle; padding: 0 0 6pt 0; height: 52pt; }
    #pdf-header .pdf-info { font-size: 7.5pt; line-height: {$lineH}pt; color: #333; }
    #pdf-header .pdf-logo  { width: 25%; text-align: left; }
    #pdf-header .pdf-title { width: 50%; text-align: center; font-size: 12.5pt; font-weight: bold; }
    #pdf-header .pdf-date  { width: 25%; text-align: right; font-size: 8pt; color: #555; }

    .pdf-grid table { width: 100%; border-collapse: collapse; }
    .pdf-grid thead { display: table-header-group; }
    .pdf-grid th    { background: #e8eaf3; border: 0.5pt solid #b5bad0; padding: 4pt 5pt; font-weight: bold; }
    .pdf-grid td    { border: 0.5pt solid #d3d6e2; padding: 3pt 5pt; }
    .pdf-grid tbody tr:nth-child(even) td { background: #f6f7fb; }
    .pdf-grid tr    { page-break-inside: avoid; }

    .pdf-chart         { text-align: center; margin-top: 14pt; page-break-inside: avoid; }
    .pdf-chart-missing { color: #888; font-style: italic; }
</style>
</head>
<body>
    <div id="pdf-header">
        <table><tr>
            <td class="pdf-logo">{$logo}</td>
            <td class="pdf-title">{$title}</td>
            <td class="pdf-date">Impresso em<br>{$date}</td>
        </tr></table>
        {$infoHtml}
    </div>
    <div class="pdf-grid">{$gridHtml}</div>
    {$chartsHtml}
</body>
</html>
HTML;

        $options = new Options();
        $options->setChroot(getcwd());
        $options->setDefaultFont($font);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', $orientation);

        // Mede o grid durante a renderização (o Dompdf descarta os frames ao fechar cada página)
        $this->pdfGridWidth = 0;
        $dompdf->setCallbacks([[
            'event' => 'begin_frame',
            'f'     => function ($frame) {
                try
                {
                    $node = $frame->get_node();
                    if (!$this->pdfGridWidth && $node instanceof DOMElement && $node->nodeName === 'table' && $this->pdfInsideGrid($node))
                    {
                        $minMax = $frame->get_reflower()->get_min_max_width();
                        $this->pdfGridWidth = (float) ($minMax['max'] ?? $minMax[1] ?? 0);
                    }
                }
                catch (Throwable $e)
                {
                    // Sem medida: mantém retrato
                }
            },
        ]]);

        $dompdf->render();

        // Rodapé: "Página X de Y" centralizado
        $dompdf->getCanvas()->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) use ($font) {
            $text = "Página {$pageNumber} de {$pageCount}";
            $face = $fontMetrics->getFont($font);
            $size = 8;
            $x    = ($canvas->get_width() - $fontMetrics->getTextWidth($text, $face, $size)) / 2;
            $canvas->text($x, $canvas->get_height() - 28, $text, $face, $size, [0.35, 0.35, 0.35]);
        });

        return $dompdf;
    }

    /**
     * Gráfico na largura total da página; se for mais alto que uma página, é fatiado
     * em várias, cortando nas faixas em branco entre os gráficos empilhados
     */
    private function pdfChartHtml(array $chart, array $area): string
    {
        $block   = '<div class="pdf-chart"><img src="%s" style="width:%.1fpt; height:%.1fpt"></div>';
        $maxH    = $area['h'] - 20;               // pt disponíveis por página (folga para a margem do bloco)
        $ptPerPx = $area['w'] / $chart['w'];      // escala para ocupar a largura útil

        // Só fatia se, para caber numa página, precisasse encolher mais de 30%
        $img = ($chart['h'] * $ptPerPx > $maxH * 1.3) ? @imagecreatefromstring($chart['bytes']) : false;

        // Cabe numa página, ainda que levemente reduzida (ou não foi possível abrir a imagem): imagem inteira
        if (!$img)
        {
            $scale = min($ptPerPx, $maxH / $chart['h']);
            return sprintf($block, $chart['src'], $chart['w'] * $scale, $chart['h'] * $scale);
        }

        if (!imageistruecolor($img))
        {
            imagepalettetotruecolor($img);
        }

        $sliceMax = (int) floor($maxH / $ptPerPx); // altura máxima da fatia, em px
        $html     = '';

        for ($y = 0; $y < $chart['h']; $y = $end)
        {
            $end = min($y + $sliceMax, $chart['h']);

            // Corta no maior espaço em branco da fatia (o espaço entre um gráfico e o próximo)
            if ($end < $chart['h'])
            {
                $end = $this->pdfBestCut($img, $chart['w'], $y + intdiv($sliceMax, 4), $end) ?? $end;
            }

            $sliceH = $end - $y;
            $slice  = imagecreatetruecolor($chart['w'], $sliceH);
            imagefill($slice, 0, 0, imagecolorallocate($slice, 255, 255, 255)); // fundo branco p/ PNG transparente
            imagecopy($slice, $img, 0, 0, 0, $y, $chart['w'], $sliceH);

            ob_start();
            imagejpeg($slice, null, 90);
            $src = 'data:image/jpeg;base64,' . base64_encode(ob_get_clean());
            imagedestroy($slice);

            $html .= sprintf($block, $src, $area['w'], $sliceH * $ptPerPx);
        }

        imagedestroy($img);
        return $html;
    }

    /**
     * Ponto de corte entre $from e $to: meio da faixa em branco mais alta (em empate, a mais
     * baixa, para aproveitar a página). Null se não houver faixa em branco.
     */
    private function pdfBestCut($img, int $width, int $from, int $to): ?int
    {
        $bands = [];
        $start = null;

        for ($row = $from; $row <= $to; $row++)
        {
            $blank = ($row < $to) && $this->pdfIsBlankRow($img, $width, $row);

            if ($blank && $start === null)
            {
                $start = $row;
            }
            elseif (!$blank && $start !== null)
            {
                $bands[] = [$start, $row - $start];
                $start   = null;
            }
        }

        $bands = array_filter($bands, fn($b) => $b[1] >= 3);
        if (!$bands)
        {
            return null;
        }

        $tallest = max(array_column($bands, 1));
        foreach (array_reverse($bands) as [$bandStart, $height])
        {
            if ($height >= 0.9 * $tallest)
            {
                return $bandStart + intdiv($height, 2);
            }
        }

        return null;
    }

    /**
     * Linha sem conteúdo: só branco, fundo claro ou linhas de grade (tons claros e sem cor)
     */
    private function pdfIsBlankRow($img, int $width, int $row): bool
    {
        for ($x = 0; $x < $width; $x += 3)
        {
            $c = imagecolorat($img, $x, $row);
            if ((($c >> 24) & 0x7F) > 100)
            {
                continue; // transparente
            }

            $r = ($c >> 16) & 0xFF;
            $g = ($c >> 8) & 0xFF;
            $b = $c & 0xFF;
            if (min($r, $g, $b) < 210 || max($r, $g, $b) - min($r, $g, $b) > 25)
            {
                return false;
            }
        }
        return true;
    }

    private function pdfInsideGrid(DOMNode $node): bool
    {
        for ($n = $node->parentNode; $n instanceof DOMElement; $n = $n->parentNode)
        {
            if ($n->getAttribute('class') === 'pdf-grid')
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Área útil (pt) da página A4 descontadas as margens
     */
    private function pdfContentSize(string $orientation): array
    {
        [$w, $h] = $this->pdfPaperSize($orientation);
        $m = self::$pdfMargin;

        return ['w' => $w - $m['left'] - $m['right'], 'h' => $h - $this->pdfTopMargin($orientation) - $m['bottom']];
    }

    private function pdfPaperSize(string $orientation): array
    {
        return $orientation === 'landscape' ? [841.89, 595.28] : [595.28, 841.89];
    }

    /**
     * Margem superior (pt): base + as linhas de filtros/acesso, estimando as quebras pela largura
     */
    private function pdfTopMargin(string $orientation): float
    {
        $m        = self::$pdfMargin;
        $width    = $this->pdfPaperSize($orientation)[0] - $m['left'] - $m['right'];
        $perLine  = max(1, (int) floor($width / self::$pdfInfoCharW));
        $lines    = 0;

        foreach ($this->pdfHeaderInfo as $label => $text)
        {
            $lines += (int) ceil(mb_strlen("{$label}: {$text}") / $perLine);
        }

        return $m['top'] + ($lines ? $lines * self::$pdfInfoLineH + 4 : 0);
    }

    /**
     * Filtros aplicados na tela (sessão), no formato "Rótulo: valor · Rótulo: valor"
     */
    private function pdfFilterDescription(): string
    {
        // Módulos com filtros fora do formulário (ex.: herdados de outra tela) informam [rótulo => valor]
        if (method_exists($this, 'pdfFilterValues'))
        {
            $parts = [];
            foreach ($this->pdfFilterValues() as $label => $value)
            {
                $parts[] = "{$label}: {$value}";
            }
            return $parts ? implode(' · ', $parts) : 'nenhum (todos os registros)';
        }

        $data   = (array) (TSession::getValue(get_class($this) . '_filter_data') ?? []);
        $labels = $this->pdfFormLabels();
        $parts  = [];

        foreach ($data as $name => $value)
        {
            if ($value === null || $value === '' || $value === [])
            {
                continue;
            }

            // Combos: exibe o texto da opção no lugar da chave
            $field = method_exists($this->form, 'getField') ? $this->form->getField($name) : null;
            $items = ($field instanceof TCombo) ? (array) $field->getItems() : [];
            $text  = implode(', ', array_map(fn($v) => (string) ($items[$v] ?? $v), (array) $value));

            $parts[] = ($labels[$name] ?? $name) . ': ' . $text;
        }

        return $parts ? implode(' · ', $parts) : 'nenhum (todos os registros)';
    }

    /**
     * Rótulos dos campos do formulário de busca: [nome do campo => texto do TLabel ao lado]
     * O BootstrapFormBuilder não expõe as linhas, então são lidas da propriedade interna
     */
    private function pdfFormLabels(): array
    {
        // Módulos com formulário simples (sem BootstrapFormBuilder) informam os rótulos diretamente
        if (method_exists($this, 'pdfFieldLabels'))
        {
            return $this->pdfFieldLabels();
        }

        if (!$this->form instanceof BootstrapFormBuilder)
        {
            return [];
        }

        try
        {
            $tabs = Closure::bind(fn() => $this->tabcontent, $this->form, BootstrapFormBuilder::class)();
        }
        catch (Throwable $e)
        {
            return [];
        }

        $labels = [];
        foreach ((array) $tabs as $rows)
        {
            foreach ((array) $rows as $row)
            {
                if (($row->type ?? '') !== 'fields')
                {
                    continue;
                }

                $label = null;
                foreach ((array) $row->content as $slot)
                {
                    foreach ((array) $slot as $item)
                    {
                        if ($item instanceof TLabel)
                        {
                            $label = trim(strip_tags((string) $item->getValue()));
                        }
                        elseif ($item instanceof TField && $label)
                        {
                            $labels[$item->getName()] = $label;
                            $label = null;
                        }
                    }
                }
            }
        }

        return $labels;
    }

    /**
     * Carrega uma imagem (arquivo local ou URL) como data URI, para embutir no PDF
     * @return array|null ['src', 'w', 'h'] em px; null se indisponível
     */
    private function pdfImage(string $source): ?array
    {
        if (preg_match('#^https?://#i', $source))
        {
            $ch = curl_init($source);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT        => 30,
            ]);
            $bytes = curl_exec($ch);
            $code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            unset($ch);

            if ($bytes === false || $code !== 200)
            {
                return null;
            }
        }
        else
        {
            $bytes = is_file($source) ? file_get_contents($source) : false;
        }

        $info = $bytes ? @getimagesizefromstring($bytes) : false;
        if (!$info)
        {
            return null;
        }

        return [
            'src'   => 'data:' . $info['mime'] . ';base64,' . base64_encode($bytes),
            'bytes' => $bytes,
            'w'     => $info[0],
            'h'     => $info[1],
        ];
    }
}
