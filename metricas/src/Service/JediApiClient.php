<?php
declare(strict_types=1);

namespace Metricas\Service;

use RuntimeException;
use stdClass;

final class JediApiClient
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $usuario,
        private readonly string $senha,
    ) {
    }

    /** @return array{grant_type: string, code: string, client_id: string} */
    public function autenticar(): array
    {
        $endpoint = "{$this->baseUrl}/v1/usuarios/login";

        [$httpCode, $raw, $curlError] = $this->post(
            $endpoint,
            http_build_query([
                'username'   => $this->usuario,
                'password'   => $this->senha,
                'grant_type' => 'password',
            ]),
            ['Content-Type: application/x-www-form-urlencoded']
        );

        $resposta = json_decode($raw);

        if ($httpCode !== 200) {
            $motivo = $resposta->detail ?? ($curlError ?: "HTTP $httpCode / resposta: $raw");
            throw new RuntimeException("Passo 1 (Autenticação) falhou: $motivo");
        }

        return [
            'grant_type' => $resposta->token_type,
            'code'       => $resposta->access_token,
            'client_id'  => $resposta->client_id,
        ];
    }

    public function calcularTempoLeitura(string $token, string $texto, bool $possuiImg = false): stdClass
    {
        $endpoint = "{$this->baseUrl}/v1/metricas/tempo_leitura";

        $payload = json_encode($possuiImg
            ? ['texto' => $texto, 'possui_img' => true]
            : ['texto' => $texto]);

        [$httpCode, $raw, $curlError] = $this->post(
            $endpoint,
            $payload,
            ["Authorization: Bearer $token", 'Content-Type: application/json']
        );

        $resposta = json_decode($raw);

        if ($httpCode !== 200) {
            $motivo = $resposta->detail ?? ($curlError ?: "HTTP $httpCode / resposta: $raw");
            throw new RuntimeException("Erro durante o processamento do serviço: $motivo");
        }

        return $resposta;
    }

    /** @return array{0: int, 1: string|false, 2: string} */
    private function post(string $url, string $body, array $headers): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);

        $raw       = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        return [$httpCode, $raw, $curlError];
    }
}
