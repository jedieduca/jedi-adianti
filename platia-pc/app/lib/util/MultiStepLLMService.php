<?php

use Adianti\Core\AdiantiApplicationConfig;

class MultiStepLLMService
{
    private string $apiKey;
    private string $apiUrl;
    private string $model;
    private int $timeout;
    private int $maxRetries;
    private int $retryDelayMs;
    private int $previousOutputMaxChars;

    public function __construct()
    {
        $config = AdiantiApplicationConfig::get();

        $this->apiKey = $config['openai']['apikey'] ?? '';
        $this->apiUrl = $config['openai']['api_url'] ?? 'https://api.openai.com/v1/chat/completions';
        $this->model  = $config['openai']['model'] ?? 'gpt-4.1-mini';

        $this->timeout                = (int) ($config['openai']['timeout'] ?? 120);
        $this->maxRetries             = (int) ($config['openai']['max_retries'] ?? 3);
        $this->retryDelayMs           = (int) ($config['openai']['retry_delay_ms'] ?? 1500);
        $this->previousOutputMaxChars = (int) ($config['openai']['previous_output_max_chars'] ?? 4000);

        if (empty($this->apiKey)) {
            throw new Exception('Chave da API não configurada em application.ini');
        }
    }

    public function gerarPlanoAulaEmPassos(string $userRequest): array
    {
        $steps = [
            [
                'name' => 'estrutura_pedagogica',
                'system' => 'Você é um especialista em didática e planejamento pedagógico. Analise a solicitação e devolva somente um JSON válido com a estrutura pedagógica do plano de aula.',
                'prompt' => <<<PROMPT
                Analise a solicitação abaixo e gere somente um JSON válido com a estrutura pedagógica.

                Solicitação:
                {{user_request}}

                A estrutura pedagógica deve conter, quando possível:
                - tema
                - publico_alvo
                - carga_horaria
                - objetivo_geral
                - objetivos_especificos
                - componentes_curriculares
                - habilidades
                - conteudos
                - metodologia
                - recursos
                - avaliacao

                Não escreva explicações fora do JSON.
                PROMPT,
                'examples' => 
                [
                    [
                        'input' => <<<EXEMPLO
                                    Analise a solicitação abaixo e gere somente um JSON válido com a estrutura pedagógica.

                                    Solicitação:
                                    Criar um plano de aula sobre energia solar para estudantes do ensino médio com foco em sustentabilidade.

                                    A estrutura pedagógica deve conter, quando possível:
                                    - tema
                                    - publico_alvo
                                    - carga_horaria
                                    - objetivo_geral
                                    - objetivos_especificos
                                    - componentes_curriculares
                                    - habilidades
                                    - conteudos
                                    - metodologia
                                    - recursos
                                    - avaliacao

                                    Não escreva explicações fora do JSON.
                                    EXEMPLO,
                                    'output' => <<<JSON
                                    {
                                    "tema": "Energia solar e sustentabilidade",
                                    "publico_alvo": "Ensino médio",
                                    "carga_horaria": "2 aulas",
                                    "objetivo_geral": "Compreender o conceito de energia solar e sua relação com a sustentabilidade.",
                                    "objetivos_especificos": [
                                        "Identificar formas de captação da energia solar",
                                        "Relacionar energia solar à preservação ambiental"
                                    ],
                                    "componentes_curriculares": [
                                        "Física",
                                        "Geografia"
                                    ],
                                    "habilidades": [
                                        "Analisar soluções energéticas sustentáveis",
                                        "Relacionar ciência e realidade social"
                                    ],
                                    "conteudos": [
                                        "Energia solar",
                                        "Fontes renováveis",
                                        "Sustentabilidade"
                                    ],
                                    "metodologia": [
                                        "Aula dialogada",
                                        "Exibição de exemplos práticos",
                                        "Discussão em grupo"
                                    ],
                                    "recursos": [
                                        "Quadro",
                                        "Projetor",
                                        "Imagens ou vídeos"
                                    ],
                                    "avaliacao": [
                                        "Participação",
                                        "Produção de síntese"
                                    ]
                                    }
                                    JSON
                    ]
                ],
                            'temperature' => 0.2,
                            'max_tokens' => 1200,
                            'expect_json' => true,
                            'json_required_keys' => [
                                'tema',
                                'publico_alvo',
                                'objetivo_geral',
                                'objetivos_especificos',
                                'conteudos',
                                'metodologia',
                                'avaliacao'
                            ],
                            'json_schema' => [
                                'tipo' => 'object',
                                'required' => [
                                    'tema',
                                    'publico_alvo',
                                    'objetivo_geral',
                                    'objetivos_especificos',
                                    'conteudos',
                                    'metodologia',
                                    'avaliacao'
                                ],
                                'properties' => [
                                    'tema' => ['type' => 'string'],
                                    'publico_alvo' => ['type' => 'string'],
                                    'carga_horaria' => ['type' => 'string'],
                                    'objetivo_geral' => ['type' => 'string'],
                                    'objetivos_especificos' => ['type' => 'array'],
                                    'componentes_curriculares' => ['type' => 'array'],
                                    'habilidades' => ['type' => 'array'],
                                    'conteudos' => ['type' => 'array'],
                                    'metodologia' => ['type' => 'array'],
                                    'recursos' => ['type' => 'array'],
                                    'avaliacao' => ['type' => 'array']
                                ]
                            ]
            ],
            [
                'name' => 'plano_aula',
                'system' => 'Você é um especialista em redação técnico-pedagógica. Receba uma estrutura pedagógica em JSON e devolva somente um JSON válido de plano de aula completo.',
                'prompt' => <<<PROMPT
                Com base na estrutura pedagógica abaixo, gere somente um JSON válido com o plano de aula completo.

                Estrutura pedagógica:
                {{previous_output}}

                O JSON do plano de aula deve conter, quando possível:
                - titulo
                - introducao
                - objetivo_geral
                - objetivos_especificos
                - desenvolvimento
                - metodologia
                - recursos
                - avaliacao
                - encerramento

                Não escreva explicações fora do JSON.
                PROMPT,
                    'examples' => 
                    [
                        [
                            'input' => <<<EXEMPLO
                            Com base na estrutura pedagógica abaixo, gere somente um JSON válido com o plano de aula completo.

                            Estrutura pedagógica:
                            {
                                "tema": "Energia solar e sustentabilidade",
                                "publico_alvo": "Ensino médio",
                                "carga_horaria": "2 aulas",
                                "objetivo_geral": "Compreender o conceito de energia solar e sua relação com a sustentabilidade.",
                                "objetivos_especificos": [
                                    "Identificar formas de captação da energia solar",
                                    "Relacionar energia solar à preservação ambiental"
                                ],
                                "conteudos": [
                                    "Energia solar",
                                    "Fontes renováveis",
                                    "Sustentabilidade"
                                ],
                                "metodologia": [
                                    "Aula dialogada",
                                    "Exibição de exemplos práticos",
                                    "Discussão em grupo"
                                ],
                                "recursos": [
                                    "Quadro",
                                    "Projetor"
                                ],
                                "avaliacao": [
                                    "Participação",
                                    "Produção de síntese"
                                ]
                            }

                            O JSON do plano de aula deve conter, quando possível:
                            - titulo
                            - introducao
                            - objetivo_geral
                            - objetivos_especificos
                            - desenvolvimento
                            - metodologia
                            - recursos
                            - avaliacao
                            - encerramento

                            Não escreva explicações fora do JSON.
                            EXEMPLO,
                                            'output' => <<<JSON
                                    {
                                    "titulo": "Plano de Aula: Energia Solar e Sustentabilidade",
                                    "introducao": "A aula introduz o conceito de energia solar como fonte renovável e sua importância para práticas sustentáveis no mundo contemporâneo.",
                                    "objetivo_geral": "Compreender o conceito de energia solar e sua relação com a sustentabilidade.",
                                    "objetivos_especificos": [
                                        "Identificar formas de captação da energia solar",
                                        "Relacionar energia solar à preservação ambiental"
                                    ],
                                    "desenvolvimento": [
                                        "Apresentação inicial sobre fontes de energia",
                                        "Discussão sobre vantagens da energia solar",
                                        "Análise de exemplos do cotidiano",
                                        "Atividade em grupo sobre soluções sustentáveis"
                                    ],
                                    "metodologia": [
                                        "Aula dialogada",
                                        "Discussão orientada",
                                        "Atividade colaborativa"
                                    ],
                                    "recursos": [
                                        "Quadro",
                                        "Projetor"
                                    ],
                                    "avaliacao": [
                                        "Participação nas discussões",
                                        "Entrega da atividade final"
                                    ],
                                    "encerramento": "Retomada dos principais conceitos e reflexão sobre o uso consciente de fontes renováveis."
                                    }
                                    JSON
                        ]
                ],
                'temperature' => 0.3,
                'max_tokens' => 1600,
                'expect_json' => true,
                'json_required_keys' => [
                                'titulo',
                                'objetivo_geral',
                                'objetivos_especificos',
                                'desenvolvimento',
                                'metodologia',
                                'avaliacao'
                            ],
                'json_schema' => [
                                'tipo' => 'object',
                                'required' => [
                                    'titulo',
                                    'objetivo_geral',
                                    'objetivos_especificos',
                                    'desenvolvimento',
                                    'metodologia',
                                    'avaliacao'
                                ],
                'properties' => [
                                    'titulo' => ['type' => 'string'],
                                    'introducao' => ['type' => 'string'],
                                    'objetivo_geral' => ['type' => 'string'],
                                    'objetivos_especificos' => ['type' => 'array'],
                                    'desenvolvimento' => ['type' => 'array'],
                                    'metodologia' => ['type' => 'array'],
                                    'recursos' => ['type' => 'array'],
                                    'avaliacao' => ['type' => 'array'],
                                    'encerramento' => ['type' => 'string']
                                ]
                            ]
            ]
        ];

        $resultado = $this->gerarEmPassos($userRequest, $steps);

        return [
            'estrutura_pedagogica' => $resultado['steps']['estrutura_pedagogica']['parsed_json'] ?? [],
            'plano_aula'           => $resultado['steps']['plano_aula']['parsed_json'] ?? [],
            'resultado_completo'   => $resultado
        ];
    }


    /**
     * Método de conveniência usado no onGerar.
     *
     * @param string $userRequest
     * @param array $steps
     * @return array
     * @throws Exception
     */
    public function gerarEmPassos(string $userRequest, array $steps): array
    {
        return $this->runMultiStepPrompt($steps, [
            'user_request' => $userRequest
        ]);
    }

    /**
     * Executa múltiplos prompts em N passos.
     *
     * Estrutura esperada de cada passo:
     * [
     *   'name' => 'nome_etapa',
     *   'system' => 'instrução de sistema',
     *   'prompt' => 'prompt com {{user_request}} ou {{previous_output}}',
     *   'examples' => [
     *       ['input' => '...', 'output' => '...']
     *   ],
     *   'temperature' => 0.2,
     *   'max_tokens' => 1200,
     *   'expect_json' => true|false,
     *   'json_required_keys' => ['campo1', 'campo2'],
     *   'json_schema' => [...]
     * ]
     *
     * @param array $steps
     * @param array $variables
     * @return array
     * @throws Exception
     */
    public function runMultiStepPrompt(array $steps, array $variables = []): array
    {
        $stepOutputs = [];
        $history = [];
        $lastOutput = '';

        foreach ($steps as $index => $step) {
            $stepNumber = $index + 1;
            $stepName   = $step['name'] ?? "step_{$stepNumber}";
            $system     = $step['system'] ?? 'Você é um assistente útil, preciso e estruturado.';
            $prompt     = $step['prompt'] ?? '';
            $examples   = $step['examples'] ?? [];

            $temperature = isset($step['temperature']) ? (float) $step['temperature'] : 0.2;
            $maxTokens   = isset($step['max_tokens']) ? (int) $step['max_tokens'] : 1200;

            $expectJson       = !empty($step['expect_json']);
            $jsonSchema       = $step['json_schema'] ?? null;
            $jsonRequiredKeys = $step['json_required_keys'] ?? [];

            $limitedPreviousOutput = $this->limitText($lastOutput, $this->previousOutputMaxChars);

            $localVars = array_merge($variables, [
                'step_name'       => $stepName,
                'step_number'     => $stepNumber,
                'previous_output' => $limitedPreviousOutput
            ]);

            $system = $this->replaceVariables($system, $localVars);
            $prompt = $this->replaceVariables($prompt, $localVars);

            if ($expectJson) {
                $system .= $this->buildJsonInstruction($jsonSchema, $jsonRequiredKeys);
            }

            $messages = [];
            $messages[] = [
                'role'    => 'system',
                'content' => $system
            ];

            foreach ($examples as $example) {
                if (!isset($example['input']) || !isset($example['output'])) {
                    continue;
                }

                $messages[] = [
                    'role'    => 'user',
                    'content' => $this->replaceVariables($example['input'], $localVars)
                ];

                $messages[] = [
                    'role'    => 'assistant',
                    'content' => $this->replaceVariables($example['output'], $localVars)
                ];
            }

            $messages[] = [
                'role'    => 'user',
                'content' => $prompt
            ];

            $response = $this->callLLMWithRetry($messages, $temperature, $maxTokens);
            $content  = $response['content'] ?? '';

            $parsedJson = null;

            if ($expectJson) {
                $parsedJson = $this->validateJsonResponse($content, $jsonRequiredKeys);

                if (!empty($jsonSchema)) {
                    $this->validateJsonAgainstSchema($parsedJson, $jsonSchema);
                }
            }

            $stepOutputs[$stepName] = [
                'step'        => $stepNumber,
                'name'        => $stepName,
                'system'      => $system,
                'prompt'      => $prompt,
                'response'    => $content,
                'parsed_json' => $parsedJson,
                'usage'       => $response['usage'] ?? []
            ];

            $history[] = [
                'step'     => $stepNumber,
                'name'     => $stepName,
                'response' => $content
            ];

            $lastOutput = $content;
        }

        return [
            'success'      => true,
            'model'        => $this->model,
            'steps'        => $stepOutputs,
            'history'      => $history,
            'final_output' => $lastOutput
        ];
    }

    /**
     * Faz a chamada à API com retry automático.
     */
    private function callLLMWithRetry(array $messages, float $temperature, int $maxTokens): array
    {
        $attempt = 0;
        $lastException = null;

        while ($attempt < $this->maxRetries) {
            $attempt++;

            try {
                return $this->callLLM($messages, $temperature, $maxTokens);
            } catch (Exception $e) {
                $lastException = $e;

                if (!$this->isTransientError($e)) {
                    throw $e;
                }

                if ($attempt < $this->maxRetries) {
                    usleep($this->retryDelayMs * 1000);
                }
            }
        }

        throw new Exception(
            'Falha após múltiplas tentativas: ' .
            ($lastException ? $lastException->getMessage() : 'erro desconhecido')
        );
    }

    /**
     * Faz a chamada HTTP para a API de chat completions.
     */
    private function callLLM(array $messages, float $temperature = 0.2, int $maxTokens = 1200): array
    {
        $payload = [
            'model'       => $this->model,
            'messages'    => $messages,
            'temperature' => $temperature,
            'max_tokens'  => $maxTokens
        ];

        $ch = curl_init($this->apiUrl);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT        => $this->timeout
        ]);

        $result = curl_exec($ch);

        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception('Erro cURL: ' . $error);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded = json_decode($result, true);

        if ($httpCode < 200 || $httpCode >= 300) {
            $message = is_array($decoded)
                ? json_encode($decoded, JSON_UNESCAPED_UNICODE)
                : $result;

            throw new Exception("Erro HTTP {$httpCode}: {$message}");
        }

        $content = $decoded['choices'][0]['message']['content'] ?? null;

        if ($content === null) {
            throw new Exception('Resposta da API sem conteúdo em choices[0].message.content');
        }

        return [
            'content'      => $content,
            'usage'        => $decoded['usage'] ?? [],
            'raw_response' => $decoded
        ];
    }

    /**
     * Detecta falhas transitórias.
     */
    private function isTransientError(Exception $e): bool
    {
        $msg = mb_strtolower($e->getMessage());

        $patterns = [
            'timeout',
            'temporar',
            'temporarily',
            'try again',
            'rate limit',
            '429',
            '500',
            '502',
            '503',
            '504',
            'server error',
            'connection reset',
            'operation timed out',
            'upstream'
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($msg, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Limita o tamanho do previous_output.
     */
    private function limitText(string $text, int $maxChars): string
    {
        if (mb_strlen($text) <= $maxChars) {
            return $text;
        }

        return mb_substr($text, 0, $maxChars) . "\n\n[conteúdo truncado]";
    }

    /**
     * Instrui o modelo a devolver JSON válido.
     */
    private function buildJsonInstruction(?array $jsonSchema, array $jsonRequiredKeys = []): string
    {
        $instruction = "\n\nResponda obrigatoriamente em JSON válido, sem markdown, sem comentários e sem qualquer texto fora do JSON.";

        if (!empty($jsonRequiredKeys)) {
            $instruction .= "\nAs seguintes chaves são obrigatórias: " . implode(', ', $jsonRequiredKeys) . '.';
        }

        if (!empty($jsonSchema)) {
            $instruction .= "\nUse esta estrutura de referência:\n" .
                json_encode($jsonSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        return $instruction;
    }

    /**
     * Valida se a resposta é JSON válido e contém chaves obrigatórias.
     */
    private function validateJsonResponse(string $content, array $requiredKeys = []): array
    {
        $decoded = json_decode(trim($content), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON inválido retornado pelo modelo: ' . json_last_error_msg());
        }

        if (!is_array($decoded)) {
            throw new Exception('A resposta JSON é válida, mas não representa uma estrutura de objeto/array.');
        }

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $decoded)) {
                throw new Exception("Chave obrigatória ausente no JSON: {$key}");
            }
        }

        return $decoded;
    }

    /**
     * Validação simples de schema.
     *
     * Exemplo:
     * [
     *   'tipo' => 'object',
     *   'required' => ['titulo'],
     *   'properties' => [
     *       'titulo' => ['type' => 'string'],
     *       'itens'  => ['type' => 'array']
     *   ]
     * ]
     */
    private function validateJsonAgainstSchema(array $json, array $schema): void
    {
        if (($schema['tipo'] ?? 'object') !== 'object') {
            throw new Exception('A validação atual suporta apenas schema do tipo object.');
        }

        $required   = $schema['required'] ?? [];
        $properties = $schema['properties'] ?? [];

        foreach ($required as $field) {
            if (!array_key_exists($field, $json)) {
                throw new Exception("Campo obrigatório ausente conforme schema: {$field}");
            }
        }

        foreach ($properties as $field => $rules) {
            if (!array_key_exists($field, $json)) {
                continue;
            }

            $expectedType = $rules['type'] ?? null;
            if (!$expectedType) {
                continue;
            }

            $value = $json[$field];

            $valid = match ($expectedType) {
                'string'  => is_string($value),
                'integer' => is_int($value),
                'number'  => is_int($value) || is_float($value),
                'boolean' => is_bool($value),
                'array'   => is_array($value),
                'object'  => is_array($value),
                default   => true
            };

            if (!$valid) {
                throw new Exception(
                    "Campo '{$field}' fora do schema. Esperado: {$expectedType}. Recebido: " . gettype($value)
                );
            }
        }
    }

    /**
     * Substitui placeholders como {{user_request}} e {{previous_output}}.
     */
    private function replaceVariables(string $text, array $variables): string
    {
        foreach ($variables as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            }

            $text = str_replace('{{' . $key . '}}', (string) $value, $text);
        }

        return $text;
    }
}