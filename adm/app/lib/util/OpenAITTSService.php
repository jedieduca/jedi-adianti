<?php
class OpenAITTSService
{
    public static function synthesizeToBinary(string $text, string $voice = 'alloy', string $format = 'mp3'): string
    {
        //$apiKey = getenv('OPENAI_API_KEY');
        $config = AdiantiApplicationConfig::get();
        $apiKey = $config['openai']['apikey'];
        //echo '<pre>'; print_r($apiKey); echo '</pre>';

        if (!$apiKey) {
            throw new Exception('OPENAI_API_KEY não configurada no ambiente.');
        }

        $url = 'https://api.openai.com/v1/audio/speech';

        $payload = [
            'model'  => 'gpt-4o-mini-tts',
            'voice'  => $voice,
            'format' => $format,
            'input'  => $text,
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer {$apiKey}",
                "Content-Type: application/json",
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $binary = curl_exec($ch);
        if ($binary === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new Exception('Erro no cURL: ' . $err);
        }

        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http !== 200) {
            // Quando dá erro, geralmente vem JSON; aqui devolvemos bruto para debug
            throw new Exception("Erro na API (HTTP {$http}): " . $binary);
        }

        return $binary;
    }

    public static function mimeFromFormat(string $format): string
    {
        $format = strtolower($format);
        return match ($format) {
            'mp3'  => 'audio/mpeg',
            'wav'  => 'audio/wav',
            'aac'  => 'audio/aac',
            'flac' => 'audio/flac',
            'opus' => 'audio/opus',
            default => 'application/octet-stream',
        };
    }
}
