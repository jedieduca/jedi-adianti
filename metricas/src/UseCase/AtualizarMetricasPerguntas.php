<?php
declare(strict_types=1);

namespace Metricas\UseCase;

use Metricas\Repository\PerguntaRepository;
use Metricas\Service\JediApiClient;
use Metricas\View\MetricasPresenter;

final class AtualizarMetricasPerguntas
{
    public function __construct(
        private readonly PerguntaRepository $perguntas,
        private readonly JediApiClient $apiClient,
        private readonly MetricasPresenter $presenter,
    ) {
    }

    public function executar(): void
    {
        $token = $this->apiClient->autenticar();

        foreach ($this->perguntas->findAll() as $pergunta) {
            $metricas = $this->apiClient->calcularTempoLeitura(
                $token['code'],
                (string) $pergunta['pergunta'],
                isset($pergunta['caminho_imagem'])
            );

            $registroAtualizado = $this->perguntas->atualizarMetricas(
                (int) $pergunta['id'],
                (int) $metricas->publico_adulto,
                (int) $metricas->publico_infantil,
                (int) $metricas->numero_palavras,
                (int) $metricas->numero_caracteres,
            );

            $this->presenter->exibirRegistroAtualizado($registroAtualizado);
        }
    }
}
