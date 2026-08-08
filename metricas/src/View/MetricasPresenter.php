<?php
declare(strict_types=1);

namespace Metricas\View;

final class MetricasPresenter
{
    /** @param array<string, mixed> $registro */
    public function exibirRegistroAtualizado(array $registro): void
    {
        echo '<pre>';
        echo "Pergunta #{$registro['id']} atualizada:\n";
        echo '  pergunta................: ' . htmlspecialchars((string) $registro['pergunta']) . "\n";
        echo "  tempo_leitura_adulto....: {$registro['tempo_leitura_adulto']}\n";
        echo "  tempo_leitura_infantil..: {$registro['tempo_leitura_infantil']}\n";
        echo "  numero_palavras.........: {$registro['numero_palavras']}\n";
        echo "  numero_caracteres.......: {$registro['numero_caracteres']}\n";
        echo '</pre>';
    }
}
