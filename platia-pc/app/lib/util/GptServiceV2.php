<?php

class GptServiceV2
{
    private string $apiKey;
    private string $apiUrl;
    private string $model;

    public function __construct()
    {
        $config = AdiantiApplicationConfig::get();

        $this->apiKey = $config['openai']['apikey'] ?? '';
        $this->model  = $config['openai']['model'] ?? 'gpt-4o-mini';
        $this->apiUrl = 'https://api.openai.com/v1/chat/completions';

        if (empty($this->apiKey)) {
            throw new Exception('API Key da OpenAI não configurada.');
        }
    }

    public function substituirColchetes(string $texto, array $variaveis = []): string
    {
        return preg_replace_callback('/\[(.*?)\]/', function ($matches) use ($variaveis) {
            $chave = trim($matches[1]);
            return array_key_exists($chave, $variaveis) ? (string) $variaveis[$chave] : $matches[0];
        }, $texto);
    }

    private function post(array $payload): array
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey,
            ],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            CURLOPT_TIMEOUT => 120,
        ]);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $erro = curl_error($ch);
            curl_close($ch);
            throw new Exception('Erro CURL: ' . $erro);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response = json_decode($result, true);

        if ($httpCode >= 400) {
            $mensagem = $response['error']['message'] ?? 'Erro desconhecido na API';
            throw new Exception('Erro API OpenAI: ' . $mensagem);
        }

        if (!is_array($response)) {
            throw new Exception('Resposta inválida da API.');
        }

        return $response;
    }

    public function chat(
        string $systemPrompt,
        string $userPrompt,
        array $variaveis = [],
        float $temperature = 0.3,
        int $maxTokens = 1200
    ): string {
        $userPromptFinal = $this->substituirColchetes($userPrompt, $variaveis);

        $payload = [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPromptFinal],
            ],
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
        ];

        $response = $this->post($payload);

        return $response['choices'][0]['message']['content'] ?? '';
    }

    public function chatJson(
        string $systemPrompt,
        string $userPrompt,
        array $variaveis = [],
        float $temperature = 0.2,
        int $maxTokens = 1500
    ): array {
        $userPromptFinal = $this->substituirColchetes($userPrompt, $variaveis);

        $payload = [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPromptFinal],
            ],
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
        ];

        $response = $this->post($payload);

        $content = $response['choices'][0]['message']['content'] ?? '';
        $json = json_decode($content, true);

        if (!is_array($json)) {
            $jsonString = $this->extractJsonFromText($content);
            $json = json_decode($jsonString, true);
        }

        if (!is_array($json)) {
            $preview = mb_substr($content, 0, 1000);
            throw new Exception('A resposta não retornou um JSON válido. Conteúdo retornado: ' . $preview);
        }

        return $json;
    }

    private function extractJsonFromText(string $text): string
    {
        if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $text, $matches)) {
            return $matches[0];
        }

        return $text;
    }

    /*public function gerarPlanoAulaEmDoisPassos_(array $dados): array
    {
        $systemPrompt1 = <<<PROMPT
    Você é um especialista em educação básica e ensino de computação.
    Sua tarefa é analisar os dados recebidos e gerar uma estrutura pedagógica objetiva.
    Responda em texto organizado, com títulos claros e linguagem pedagógica.
    PROMPT;

        $userPrompt1 = <<<PROMPT
    Analise os parâmetros abaixo e gere uma estrutura pedagógica da aula.

    Eixo da Computação: [eixo_computacao]
    Ano Escolar: [ano_escolar]
    Duração da Aula: [duracao_aula]
    Número de Aulas: [numero_aulas]
    Conteúdo dos Componentes Curriculares: [conteudo_componentes]
    Habilidades Escolhidas: [habilidades]
    Informações adicionais: [informacoes_adicionais]

    Organize sua resposta com os seguintes tópicos:

    Tema central:
    Problema orientador:
    Objetivo geral:
    Objetivos específicos:
    Conceitos principais:
    Estratégia pedagógica:
    Possíveis dificuldades dos alunos:
    PROMPT;

        $estrutura = $this->chat(
            $systemPrompt1,
            $userPrompt1,
            $dados,
            0.2,
            700
        );

        $systemPrompt2 = <<<PROMPT
    Você é um especialista em didática e planejamento de aula.
    Com base na estrutura pedagógica recebida, gere um plano de aula completo.
    Responda em texto organizado, com seções bem definidas, linguagem clara e objetiva.
    PROMPT;

        $userPrompt2 = <<<PROMPT
    Utilize os dados abaixo para gerar um plano de aula completo.

    Eixo da Computação: [eixo_computacao]
    Ano Escolar: [ano_escolar]
    Duração da Aula: [duracao_aula]
    Número de Aulas: [numero_aulas]
    Conteúdo dos Componentes Curriculares: [conteudo_componentes]
    Habilidades Escolhidas: [habilidades]
    Informações adicionais: [informacoes_adicionais]

    Estrutura pedagógica:
    [estrutura_pedagogica]

    O plano de aula deve conter os seguintes tópicos:

    Título da aula
    Objetivos de aprendizagem
    Habilidades trabalhadas
    Conteúdos abordados
    Metodologia detalhada
    Distribuição do tempo por etapa
    Atividades práticas
    Recursos didáticos
    Estratégia de avaliação
    Adaptações pedagógicas
    Continuidade da aula
    PROMPT;

        $dadosPrompt2 = $dados;
        $dadosPrompt2['estrutura_pedagogica'] = $estrutura;

        $plano = $this->chat(
            $systemPrompt2,
            $userPrompt2,
            $dadosPrompt2,
            0.2,
            1400
        );

        return [
            'estrutura_pedagogica' => $estrutura,
            'plano_aula' => $plano,
        ];
    }*/

    
    public function gerarPlanoAulaEmDoisPassos(string $sp1, string $up1, string $sp2, string $up2, array $dados): array
    {
        $systemPrompt1 = $sp1;
        $userPrompt1 = $up1.<<<PROMPT
                                Retorne um JSON com esta estrutura:
                                {
                                "tema_central": "",
                                "problema_orientador": "",
                                "objetivo_geral": "",
                                "objetivos_especificos": [],
                                "conceitos_principais": [],
                                "estrategia_pedagogica": "",
                                "possiveis_dificuldades": []
                                }
                                PROMPT;
        $estrutura = $this->chatJson(
            $systemPrompt1,
            $userPrompt1,
            $dados,
            0.2,
            700
        );

        $systemPrompt2 = $sp2.<<<PROMPT
                                Responda exclusivamente em JSON válido.
                                PROMPT;
        $userPrompt2 = $up2.<<<PROMPT
                                Estrutura pedagógica:
                                [estrutura_json]

                                Retorne um JSON com esta estrutura:
                                {
                                "titulo": "",
                                "objetivos_aprendizagem": [],
                                "habilidades_trabalhadas": [],
                                "conteudos_abordados": [],
                                "metodologia": [
                                    {
                                    "etapa": "",
                                    "descricao": "",
                                    "tempo": ""
                                    }
                                ],
                                "recursos_didaticos": [],
                                "atividades_praticas": [],
                                "avaliacao": "",
                                "adaptacoes": [],
                                "continuidade": []
                                }
                                PROMPT;
        $dadosPrompt2 = $dados;
        $dadosPrompt2['estrutura_json'] = json_encode($estrutura, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $plano = $this->chatJson(
            $systemPrompt2,
            $userPrompt2,
            $dadosPrompt2,
            0.2,
            1400
        );

        return [
            'estrutura_pedagogica' => $estrutura,
            'plano_aula' => $plano,
        ];
    }

    
    public function gerarPraticaAula(string $sp, string $up, array $dados): array
    {
        $systemPrompt = $sp;
        $userPrompt = $up.<<<PROMPT
                            Retorne um JSON com esta estrutura:
                            {
                            "titulo_pratica": "",
                            "descricao_pratica": "",
                            "objetivo_pratica": "",
                            "materiais_necessarios": [],
                            "passos_pratica": [
                                {
                                "etapa": "",
                                "descricao": "",
                                "tempo_estimado": ""
                                }
                            ],
                            "avaliacao_pratica": ""
                            }
                            PROMPT;
        $exercicio = $this->chatJson(
            $systemPrompt,
            $userPrompt,
            $dados,
            0.2,
            700
        );

        return [
            'exercicio' => $exercicio,
        ];
    }


    public function prepararTextoCurto(?string $texto, int $limite = 1200): string
    {
        $texto = trim((string) $texto);
        $texto = strip_tags($texto);
        $texto = preg_replace('/\s+/', ' ', $texto);

        return mb_substr($texto, 0, $limite);
    }
}