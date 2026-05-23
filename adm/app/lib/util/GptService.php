<?php

class GptService
{
    private $apiKey;
    private $apiUrl;
    private $model;

    public function __construct()
    {
        $config = AdiantiApplicationConfig::get();
        $this->apiKey = $config['openai']['apikey'];
        $this->model  = $config['openai']['model'];

        $this->apiUrl = "https://api.openai.com/v1/chat/completions";
    }

    function substituirColchetes($texto, $variaveis) 
    {
        return preg_replace_callback('/\[(.*?)\]/', function($matches) use ($variaveis) {
            $chave = $matches[1];
            return isset($variaveis[$chave]) ? $variaveis[$chave] : $matches[0];
        }, $texto);
    }

    /**
     * Gera resposta do GPT
     * 
     * @param string $noticia Texto da notícia avaliada
     * @param string $resposta Classificação sugerida (FAKE ou NÃO FAKE)
     * @param string $caracteristicas Lista completa de características
     * @return string
     */
    public function generateResponse($systemPrompt, $userPrompt, $noticia, $resposta, $caracteristicas)
    {
        $variaveis = [
            'noticia' => $noticia,
            'resposta' => $resposta,
            'caracteristicas' => $caracteristicas
        ];

        // Prompt do usuário, já no formato esperado
        $resUserPrompt = $this->substituirColchetes($userPrompt, $variaveis);
        //echo "<pre>"; print_r($resUserPrompt); echo "</pre>"; exit;

        $payload = [
            "model" => $this->model, // pode trocar por gpt-4o se quiser mais qualidade
            "messages" => [
                ["role" => "system", "content" => $systemPrompt],
                ["role" => "user", "content" => $resUserPrompt],
            ],
            "temperature" => 0.7,
            "max_tokens" => 500
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer {$this->apiKey}"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('Erro CURL: ' . curl_error($ch));
        }

        curl_close($ch);

        $response = json_decode($result, true);

        if (isset($response['error'])) {
            throw new Exception('Erro API OpenAI: ' . $response['error']['message']);
        }

        return $response['choices'][0]['message']['content'] ?? '';
    }

    /**
     * Gera resposta do GPT
     * 
     * @param string $noticia Texto da notícia avaliada
     * @param string $resposta Classificação sugerida (FAKE ou NÃO FAKE)
     * @param string $caracteristicas Lista completa de características
     * @return string
     */
    public function generateResponse_ok($noticia, $resposta, $caracteristicas, $passo)
    {
        $variaveis = [
            'noticia' => $noticia,
            'resposta' => $resposta,
            'caracteristicas' => $caracteristicas
        ];

        // Prompt de sistema (persona + tom de voz)
        $systemPrompt = "
            Persona: Você é uma professora simpática, descolada, irreverente e divertida, mas ainda assim é uma professora.
            Usa o português coloquial, mas sempre correto. 
            Seja concisa, objetiva, clara e fácil de entender para adolescentes.
            Seu papel é explicar de forma leve quais características tornam a notícia FAKE ou NÃO FAKE.
        ";

        // Prompt do usuário, já no formato esperado
        /*if ($passo==1) {
            $userPrompt = "
                Notícia avaliada: {$noticia}
                Classificação sugerida da notícia: {$resposta}
                Lista completa de características: {$caracteristicas}
                
                Tarefa:
                Crie uma lista (separada por ponto e vírgula) com 1 a 3 características da lista completa que sejam as mais relevantes
                para classificar a notícia como {$resposta}, explicando de forma simples como cada característica se aplica a essa notícia.
            ";
        } else if ($passo==2) {
            $userPrompt = "
                Notícia avaliada: {$noticia}
                Classificação sugerida da notícia: {$resposta}
                Lista completa de características: {$caracteristicas}
                
                Tarefa:
                Elabore a sua fala para um adolescente, explicando no contexto da notícia a razão de essas características selecionadas contribuirem para essa classificação.
                Use, somente, texto puro, curto e coloquial de uma conversa que será narrada.   
                Use apenas um resumo das partes mais relevantes dessas características escolhidas (para explicar a razão), de modo irreverente e descolado (mas correto), para um adolescente.
                Use apenas uma frase, sem gírias ou formatações!
            ";
        } else {
            throw new Exception('Passo inválido. Use 1 ou 2.');
        }*/



        // Prompt do usuário, já no formato esperado
        if ($passo==1) {
            $userPrompt = "
                Notícia avaliada: [noticia]
                Classificação sugerida da notícia: [resposta]
                Lista completa de características: [caracteristicas]
                
                Tarefa:
                Crie uma lista (separada por ponto e vírgula) com 1 a 3 características da lista completa que sejam as mais relevantes
                para classificar a notícia como [resposta], explicando de forma simples como cada característica se aplica a essa notícia.
            ";
        } else if ($passo==2) {
            $userPrompt = "
                Notícia avaliada: [noticia]
                Classificação sugerida da notícia: [resposta]
                Lista completa de características: [caracteristicas]
                
                Tarefa:
                Elabore a sua fala para um adolescente, explicando no contexto da notícia a razão de essas características selecionadas contribuirem para essa classificação.
                Use, somente, texto puro, curto e coloquial de uma conversa que será narrada.   
                Use apenas um resumo das partes mais relevantes dessas características escolhidas (para explicar a razão), de modo irreverente e descolado (mas correto), para um adolescente.
                Use apenas uma frase, sem gírias ou formatações!
            ";
        } else {
            throw new Exception('Passo inválido. Use 1 ou 2.');
        }


        $resUserPrompt = $this->substituirColchetes($userPrompt, $variaveis);
        echo "<pre>"; print_r($resUserPrompt); echo "</pre>"; exit;


        $payload = [
            "model" => $this->model, // pode trocar por gpt-4o se quiser mais qualidade
            "messages" => [
                ["role" => "system", "content" => $systemPrompt],
                ["role" => "user", "content" => $resUserPrompt],
            ],
            "temperature" => 0.7,
            "max_tokens" => 500
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer {$this->apiKey}"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('Erro CURL: ' . curl_error($ch));
        }

        curl_close($ch);

        $response = json_decode($result, true);

        if (isset($response['error'])) {
            throw new Exception('Erro API OpenAI: ' . $response['error']['message']);
        }

        return $response['choices'][0]['message']['content'] ?? '';
    }
}
