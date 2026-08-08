<?php
declare(strict_types=1);

namespace Metricas\Repository;

use PDO;

final class PerguntaRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /** @return array<int, array<string, mixed>> */
    public function findAll(): array
    {
        return $this->pdo->query('SELECT * FROM pergunta')->fetchAll();
    }

    /** @return array<string, mixed> */
    public function atualizarMetricas(
        int $id,
        int $tempoLeituraAdulto,
        int $tempoLeituraInfantil,
        int $numeroPalavras,
        int $numeroCaracteres
    ): array {
        $stmt = $this->pdo->prepare(
            'UPDATE pergunta
                SET tempo_leitura_adulto   = :tempo_leitura_adulto,
                    tempo_leitura_infantil = :tempo_leitura_infantil,
                    numero_palavras        = :numero_palavras,
                    numero_caracteres      = :numero_caracteres
              WHERE id = :id'
        );

        $stmt->execute([
            'tempo_leitura_adulto'   => $tempoLeituraAdulto,
            'tempo_leitura_infantil' => $tempoLeituraInfantil,
            'numero_palavras'        => $numeroPalavras,
            'numero_caracteres'      => $numeroCaracteres,
            'id'                     => $id,
        ]);

        return $this->buscarPorId($id);
    }

    /** @return array<string, mixed> */
    public function buscarPorId(int $id): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, pergunta, tempo_leitura_adulto, tempo_leitura_infantil, numero_palavras, numero_caracteres
               FROM pergunta
              WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }
}
