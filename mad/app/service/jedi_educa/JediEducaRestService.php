<?php

use Adianti\Core\AdiantiApplicationConfig;

class JediEducaRestService
{
    private static $token;

    /**
     * Fluxo de dois passos para obter o Token
     */
    public static function getToken($config)
    {
        if (self::$token) return self::$token;

        // PASSO 1: Autenticação Inicial
        // Supondo que este serviço valide o client_id/secret e retorne um code ou temp_key
        $authData = [
            'username' => $config['client_id'],
            'password' => $config['client_pass'],
            'grant_type' => 'password',
        ];

        $endpoint = $config['api_auth_url'] . '/usuarios/login';
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($authData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $authResponse = json_decode(curl_exec($ch));
        $authHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        unset($ch);

        if ($authHttpCode !== 200) {
            throw new Exception("Passo 1 (Autenticação) falhou: " . ($authResponse->detail ?? 'Erro'));
        }

        // PASSO 2: Troca do Código pelo Access Token Final
        $tokenData = [
            'grant_type' => $authResponse->token_type,
            'code'       => $authResponse->access_token, // Dado vindo do Serviço 1
            'client_id'  => $authResponse->client_id,
        ];

        return $tokenData;
    }

    public static function getData($servico)
    {
        $ini   = AdiantiApplicationConfig::get();
        $data  = self::getToken($ini['general']);
        $token = $data['code'];
        $url   = $ini['general']['api_auth_url'] . $servico;

        $ch    = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
        $data = json_decode(curl_exec($ch));
        $authHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        unset($ch); 

        if ($authHttpCode !== 200) {
            throw new Exception("Erro durante o processamento do serviço: " . ($authResponse->detail ?? 'Erro'));
        }
            
        return $data;
    }
}