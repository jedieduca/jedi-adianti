<?php
declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

use Metricas\Config\Config;
use Metricas\Database\ConnectionFactory;
use Metricas\Repository\PerguntaRepository;
use Metricas\Service\JediApiClient;
use Metricas\UseCase\AtualizarMetricasPerguntas;
use Metricas\View\MetricasPresenter;

$config = new Config();

try {
    $pdo = ConnectionFactory::create($config);

    $useCase = new AtualizarMetricasPerguntas(
        new PerguntaRepository($pdo),
        new JediApiClient($config->apiBaseUrl, $config->apiUsuario, $config->apiSenha),
        new MetricasPresenter(),
    );

    $useCase->executar();
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}
