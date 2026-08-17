<?php

use Adianti\Base\TStandardForm;
use Adianti\Control\TPage;
use Adianti\Control\TAction;
use Adianti\Database\TTransaction;
use Adianti\Validator\TRequiredValidator;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\THidden;
use Adianti\Widget\Form\TRadioGroup;
use Adianti\Widget\Form\TText;
use Adianti\Widget\Form\TCombo;
use Adianti\Widget\Form\TCheckGroup;
use Adianti\Widget\Form\THtmlEditor;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Form\TSpinner;
use Adianti\Widget\Form\TDate;
use Adianti\Widget\Form\TButton;
use Adianti\Widget\Form\TForm;
use Adianti\Widget\Base\TElement;
use Adianti\Wrapper\BootstrapFormBuilder;
use Adianti\Wrapper\BootstrapFormBuilder as TQuickForm;

class FormPlanoAula extends TPage
{
    protected $form;
    public $plano_formatado;

    public function __construct($param = null)
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder('form_plano_aula');
        $this->form->setFormTitle('Cadastro de Plano de Aula');

        // Campos principais
        $id = new THidden('id');

        $titulo = new TEntry('titulo');
        $titulo->setSize('100%');
        $titulo->addValidation('Título', new TRequiredValidator);

        $nivel_ensino = new TRadioGroup('nivel_ensino');
        $nivel_ensino->addItems([
            0 => 'Educação Infantil',
            1 => 'Ensino Fundamental - Anos Iniciais'
        ]);
        $nivel_ensino->setUseButton();
        $nivel_ensino->setLayout('horizontal');
        //$nivel_ensino->setValue(0);
        $nivel_ensino->addValidation('Nível de ensino', new TRequiredValidator);
        $nivel_ensino->setChangeAction(new TAction([$this, 'onChangeFiltroComponentes']));

        $ano_escolar = new TSpinner('ano_escolar');
        $ano_escolar->setRange(1, 9, 1);
        $ano_escolar->setSize('30%');
        $ano_escolar->addValidation('Ano escolar', new TRequiredValidator);
        $ano_escolar->setExitAction(new TAction([$this, 'onChangeFiltroHabilidades']));

        $eixo_computacao = new TRadioGroup('eixo_computacao');
        $eixo_computacao->addItems([
            //1 => 'Cultura Digital',
            2 => 'Pensamento Computacional'
            //3 => 'Mundo Digital'
        ]);
        $eixo_computacao->setUseButton();
        $eixo_computacao->setLayout('horizontal');
        $eixo_computacao->addValidation('Eixo da computação', new TRequiredValidator);
        $eixo_computacao->setChangeAction(new TAction([$this, 'onChangeFiltroHabilidades']));

        $duracao_aula = new TEntry('duracao_aula');
        $duracao_aula->setSize('30%');

        $numero_aula = new TEntry('numero_aula');
        $numero_aula->setSize('30%');

        $visibilidade = new TRadioGroup('visibilidade');
        $visibilidade->addItems([
            '1' => 'Privado',
            '2' => 'Público'
        ]);
        $visibilidade->setUseButton();
        $visibilidade->setLayout('horizontal');

        //$prompt = new TText('prompt');
        //$prompt->setSize('100%', 100);

        $comentarios_adicionais = new TText('comentarios_adicionais');
        $comentarios_adicionais->setSize('100%', 100);

        $actGeraPlanoGPT = new TAction(array($this, 'onGerarPlanoComIA'));
        $btnGeraPlanoGPT = new TButton('btnGeraPlanoGPT');
        $btnGeraPlanoGPT->setImage('fa:bullhorn red');
        $btnGeraPlanoGPT->style = 'background:#285e8e;color:#ffffff; width: 45%';
        $btnGeraPlanoGPT->setAction($actGeraPlanoGPT,' Gerar Plano de Aula');

        $conteudo_gerado = new THtmlEditor('conteudo_gerado');
        $conteudo_gerado->setSize('100%', 400);
        $conteudo_gerado->setEditable(false);

        /*$this->plano_formatado = new THtmlEditor('plano_formatado');
        $this->plano_formatado->setSize('100%', 400);
        $this->plano_formatado->setEditable(false);*/

        // ============================================================
        // Seção: Práticas de Aprendizagem
        // ============================================================
        $gerar_praticas_aprend = new TRadioGroup('gerar_praticas_aprend');
        $gerar_praticas_aprend->addItems([1 => 'Sim', 0 => 'Não']);
        $gerar_praticas_aprend->setUseButton();
        $gerar_praticas_aprend->setLayout('horizontal');
        $gerar_praticas_aprend->setValue(0);
        $gerar_praticas_aprend->setChangeAction(new TAction([$this, 'onChangeVisibilidadePraticas']));

        $qtd_praticas = new TSpinner('qtd_praticas');
        $qtd_praticas->setRange(1, 100, 1);
        $qtd_praticas->setSize('7%');
        $qtd_praticas->setValue(0);

        $nivel_inicial = new TSpinner('nivel_inicial');
        $nivel_inicial->setRange(0, 100, 1);
        $nivel_inicial->setSize('40%');
        $nivel_inicial->setValue(0);

        $nivel_intermediario = new TSpinner('nivel_intermediario');
        $nivel_intermediario->setRange(0, 100, 1);
        $nivel_intermediario->setSize('40%');
        $nivel_intermediario->setValue(0);

        $nivel_avancado = new TSpinner('nivel_avancado');
        $nivel_avancado->setRange(0, 100, 1);
        $nivel_avancado->setSize('40%');
        $nivel_avancado->setValue(0);

        $actGerarPratica = new TAction([$this, 'onValidarPraticas']);
        $btn_gerar_pratica = new TButton('btn_gerar_pratica');
        $btn_gerar_pratica->setImage('fa:cogs blue');
        $btn_gerar_pratica->style = 'background:#285e8e;color:#ffffff; width: 57%';
        $btn_gerar_pratica->setAction($actGerarPratica, 'Gerar Práticas de Aprendizagem');

        $data_inicio_praticas = new TDate('data_inicio_praticas');
        $data_inicio_praticas->setSize('40%');

        $data_fim_praticas = new TDate('data_fim_praticas');
        $data_fim_praticas->setSize('40%');

        $neurodivergencia = new TRadioGroup('neurodivergencia');
        $neurodivergencia->id='neurodivergencia';
        $neurodivergencia->addItems([0 => 'Nenhuma', 1 => 'TDAH', 2 => 'TEA']);
        $neurodivergencia->setValue(0);
        $neurodivergencia->setLayout('horizontal');
        TScript::create("$('label').css('line-height', '1.1');");
        TScript::create("$('label').css('margin-right', '12px');");

        $conteudo_gerado_pratica = new THtmlEditor('conteudo_gerado_pratica');
        $conteudo_gerado_pratica->setSize('100%', 400);
        $conteudo_gerado_pratica->setEditable(false);
             

        /**
         * Relações 1:N
         * Como precisa selecionar múltiplos itens,
         * o correto é usar TDBCheckGroup, e não TDBRadioGroup.
         */

        $criteriaComponentes = new TCriteria;
        $criteriaComponentes->add(new TFilter('nivel_ensino', '=', 0));
        $id_componentes = new TDBCheckGroup('id_componentes', 'platia', 'ComponenteCurricular', 'id', 'descricao', 'descricao', $criteriaComponentes);
        //$id_componentes = new TDBCheckGroup('id_componentes', 'platia', 'ComponenteCurricular', 'id', 'descricao', 'descricao');
        $id_componentes->setLayout('vertical');
        $id_componentes->setSize('100%');
        $id_componentes->addValidation('Componentes curriculares', new TRequiredValidator);
        $wrapComponentes = new TElement('div');
        $wrapComponentes->style = '
            height: 190px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 4px;
            column-count: 2;
            -webkit-column-count: 2;
            -moz-column-count: 2;
        ';
        $wrapComponentes->add($id_componentes);

        $id_habilidades = new TDBCheckGroup('id_habilidades', 'platia', 'HabilidadeComputacao', 'id', 'descricao');
        $id_habilidades->setId('id_habilidades');
        $id_habilidades->setLayout('vertical');
        $id_habilidades->setSize('100%');
        $id_habilidades->addValidation('Habilidades', new TRequiredValidator);
        $scrollHab = new TElement('div');
        $scrollHab->style = 'height:150px; overflow-y:auto; border:1px solid #ddd; padding:8px;';
        $scrollHab->add($id_habilidades);

        // Montagem do formulário
        $this->form->addFields([$id]);

        $this->form->addFields([new TLabel('Título')], [$titulo]);

        $row = $this->form->addFields(
            [new TLabel('Nível de ensino')],
            [$nivel_ensino]
        );
        $row->layout = ['col-sm-2 control-label', 'col-sm-10'];

        $row = $this->form->addFields(
            [new TLabel('Ano escolar')],
            [$ano_escolar],
            [new TLabel('Duração da aula')],
            [$duracao_aula],
            [new TLabel('Número da aula')],
            [$numero_aula]
        );
        $row->layout = ['col-sm-2 control-label', 'col-sm-2',
                         'col-sm-2 control-label', 'col-sm-2',
                         'col-sm-2 control-label', 'col-sm-2'];

        $row = $this->form->addFields(
            [new TLabel('Eixo da computação')],
            [$eixo_computacao]
        );
        $row->layout = ['col-sm-2 control-label', 'col-sm-10'];

        //$this->form->addFields([new TLabel('Componentes curriculares')], [$id_componentes]);
        $this->form->addField($id_componentes);
        $this->form->addFields(
            [new TLabel('Componentes curriculares')],
            [$wrapComponentes]
        );
        //$this->form->addFields([new TLabel('Habilidades')], [$id_habilidades]);
        $this->form->addField($id_habilidades);
        $this->form->addFields(
            [new TLabel('Habilidades')],
            [$scrollHab]
        );

        $this->form->addFields([new TLabel('Comentários adicionais')], [$comentarios_adicionais]);
        //$this->form->addFields([new TLabel('Prompt')], [$prompt]);

        $row = $this->form->addFields( [$btnGeraPlanoGPT] );
        $row->layout = ['col-md-4 control-label' ];
        $this->form->addFields([new TLabel('Conteúdo gerado para o Plano de Aula')], [$conteudo_gerado]);

        // ============================================================
        // Campos para Práticas de Aprendizagem
        // ============================================================
        $row = $this->form->addFields(
            [new TLabel('Gerar práticas de aprendizagem?')],
            [$gerar_praticas_aprend]
        );
        $row->layout = ['col-sm-2 control-label', 'col-sm-10'];

        $row = $this->form->addFields(
            [new TLabel('Quantidade de práticas')],
            [$qtd_praticas]
        );
        $row->layout = ['col-sm-2 control-label', 'col-sm-10'];

        $row = $this->form->addFields(
            [new TLabel('Quantidade - Nível Inicial')],
            [$nivel_inicial],
            [new TLabel('Nível Intermediário')],
            [$nivel_intermediario],
            [new TLabel('Nível Avançado')],
            [$nivel_avancado]
        );
        $row->layout = ['col-sm-2 control-label', 'col-sm-2',
                        'col-sm-2 control-label', 'col-sm-2',
                        'col-sm-2 control-label', 'col-sm-2'];


        $row = $this->form->addFields(
            [new TLabel('Data início disponibilização')],
            [$data_inicio_praticas],
            [new TLabel('Data fim disponibilização')],
            [$data_fim_praticas]
        );
        $row->layout = ['col-sm-2 control-label', 'col-sm-3',
                        'col-sm-2 control-label', 'col-sm-3'];

        $row = $this->form->addFields(
            [new TLabel('Neurodivergência')],
            [$neurodivergencia]
        );
        $row->layout = ['col-sm-2 control-label', 'col-sm-10'];

        $row = $this->form->addFields( [$btn_gerar_pratica] );
        $row->layout = ['col-md-5 control-label' ];

        $this->form->addFields([new TLabel('Conteúdo gerado para a prática de aprendizagem')], [$conteudo_gerado_pratica]);

        $row = $this->form->addFields(
            [new TLabel('Visibilidade')],
            [$visibilidade]
        );
        $row->layout = ['col-sm-2 control-label', 'col-sm-10'];

        // Ações
        $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'far:save green');
        $this->form->addAction('Limpar', new TAction([$this, 'onClear']), 'fa:eraser red');
        $this->form->addActionLink( _t('Back'), new TAction(array('FormPlanoAulaList','onReload')), 'far:arrow-alt-circle-left blue');

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);

        // Ocultar campos de práticas por padrão (quando gerar_praticas_aprend = 0)
        $ocultarPraticas = [
            'qtd_praticas',
            'nivel_inicial',
            'nivel_intermediario',
            'nivel_avancado',
            'btn_gerar_pratica',
            'data_inicio_praticas',
            'data_fim_praticas',
            'neurodivergencia',
            'conteudo_gerado_pratica'
        ];
        
        foreach ($ocultarPraticas as $campo) {
            TQuickForm::hideField('form_plano_aula', $campo);
        }
        //TCheckGroup::disableField('form_plano_aula', 'neurodivergencia');

        parent::add($container);
    }

    public function onSave($param)
    {
        try {
            TTransaction::open('platia');

            $this->form->validate();

            $data = $this->form->getData();
            //echo '<pre>'; print_r($data); echo '</pre>';

            // Salva plano de aula
            $plano = new PlanoAula;
            $plano->id                      = $data->id;
            $plano->titulo                  = $data->titulo;
            $plano->nivel_ensino            = $data->nivel_ensino;
            $plano->ano_escolar             = $data->ano_escolar;
            $plano->eixo_computacao         = $data->eixo_computacao;
            $plano->duracao_aula            = $data->duracao_aula;
            $plano->numero_aula             = $data->numero_aula;
            $plano->comentarios_adicionais  = $data->comentarios_adicionais;
            //$plano->prompt                = $data->prompt;
            $plano->conteudo_gerado         = $data->conteudo_gerado;
            $plano->conteudo_gerado_pratica = $data->conteudo_gerado_pratica ?? '';
            $plano->visibilidade            = $data->visibilidade;
            $plano->gerar_praticas_aprend   = $data->gerar_praticas_aprend ?? 0;
            $plano->qtd_praticas            = $data->qtd_praticas ?? 0;
            $plano->nivel_inicial           = $data->nivel_inicial ?? 0;
            $plano->nivel_intermediario     = $data->nivel_intermediario ?? 0;
            $plano->nivel_avancado          = $data->nivel_avancado ?? 0;
            $plano->data_inicio_praticas    = $data->data_inicio_praticas ?? null;
            $plano->data_fim_praticas       = $data->data_fim_praticas ?? null;
            
            // Converter array de neurodivergencia para string separada por pipe
            if (is_array($data->neurodivergencia)) {
                $plano->neurodivergencia = implode('|', $data->neurodivergencia);
            } else {
                $plano->neurodivergencia = $data->neurodivergencia ?? '';
            }

            $plano->store();

            // -------------------------------------------------------------
            // Remove relacionamentos antigos
            // -------------------------------------------------------------
            $relacoesComp = PlanoAulaCompCur::where('id_plano', '=', $plano->id)->load();
            if ($relacoesComp) {
                foreach ($relacoesComp as $rel) {
                    $rel->delete();
                }
            }

            $relacoesHab = PlanoAulaHabilidades::where('id_plano', '=', $plano->id)->load();
            if ($relacoesHab) {
                foreach ($relacoesHab as $rel) {
                    $rel->delete();
                }
            }
            // Salva componentes curriculares
            if (!empty($data->id_componentes))
            {
                foreach ($data->id_componentes as $id_componente)
                {
                    $rel = new PlanoAulaCompCur;
                    $rel->id_plano = $plano->id;
                    $rel->id_componente = $id_componente;
                    $rel->assunto = null;
                    $rel->store();
                }
            }

            // Salva habilidades
            if (!empty($data->id_habilidades))
            {
                foreach ($data->id_habilidades as $id_habilidade)
                {
                    $rel = new PlanoAulaHabilidades;
                    $rel->id_plano = $plano->id;
                    $rel->id_habilidade = $id_habilidade;
                    $rel->store();
                }
            }

            $data->id = $plano->id;
            $this->form->setData($data);

            if ((int) ($data->gerar_praticas_aprend ?? 0) === 1) {
                self::onChangeVisibilidadePraticas(['gerar_praticas_aprend' => $data->gerar_praticas_aprend]);
            }

            TForm::sendData('form_plano_aula', $data);

            TTransaction::close();

            new TMessage('info', 'Plano de aula salvo com sucesso.');
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    public function onEdit($param)
    {
        try {
            if (isset($param['key']))
            {
                TTransaction::open('platia');

                $plano = new PlanoAula($param['key']);

                $data = new stdClass;
                $data->id                      = $plano->id;
                $data->titulo                  = $plano->titulo;
                $data->nivel_ensino            = $plano->nivel_ensino;
                $data->ano_escolar             = $plano->ano_escolar;
                $data->eixo_computacao         = $plano->eixo_computacao;
                $data->duracao_aula            = $plano->duracao_aula;
                $data->numero_aula             = $plano->numero_aula;
                $data->comentarios_adicionais  = $plano->comentarios_adicionais;
                $data->prompt                  = $plano->prompt;
                $data->conteudo_gerado         = $plano->conteudo_gerado;
                $data->visibilidade            = $plano->visibilidade;
                $data->gerar_praticas_aprend   = $plano->gerar_praticas_aprend ?? 0;
                $data->qtd_praticas            = $plano->qtd_praticas ?? 0;
                $data->nivel_inicial           = $plano->nivel_inicial ?? 0;
                $data->nivel_intermediario     = $plano->nivel_intermediario ?? 0;
                $data->nivel_avancado          = $plano->nivel_avancado ?? 0;
                $data->data_inicio_praticas    = $plano->data_inicio_praticas ?? null;
                $data->data_fim_praticas       = $plano->data_fim_praticas ?? null;
                
                // Converter string de neurodivergencia para array para TCheckGroup
                if (!empty($plano->neurodivergencia)) {
                    $data->neurodivergencia = explode('|', $plano->neurodivergencia);
                } else {
                    $data->neurodivergencia = [];
                }
                $data->conteudo_gerado_pratica = $plano->conteudo_gerado_pratica ?? '';

                // Componentes
                $componentes = PlanoAulaCompCur::where('id_plano', '=', $plano->id)->load();
                $data->id_componentes = [];
                if ($componentes)
                {
                    foreach ($componentes as $comp)
                    {
                        $data->id_componentes[] = $comp->id_componente;
                    }
                }

                // Habilidades              
                $habilidades = PlanoAulaHabilidades::where('id_plano', '=', $plano->id)->load();
                $data->id_habilidades = [];
                if ($habilidades)
                {
                    foreach ($habilidades as $hab)
                    {
                        $data->id_habilidades[] = (string) $hab->id_habilidade;
                    }
                }
                //$param['id_habilidades']= $data->id_habilidades;
                //FormPlanoAula::onChangeFiltroHabilidades($param);
  
                TTransaction::close();
                 // 1. seta os dados básicos no formulário
                $this->form->setData($data);

                // 2. recarrega as opções filtradas de habilidades
                $filtro = [];
                $filtro['eixo_computacao'] = $data->eixo_computacao;
                $filtro['ano_escolar']     = $data->ano_escolar;

                self::onChangeFiltroHabilidades($filtro);

                // 2.5. recarrega as opções filtradas de componentes conforme o nível de ensino
                self::onChangeFiltroComponentes(['nivel_ensino' => $data->nivel_ensino]);

                // 3. Atualiza visibilidade dos campos de práticas
                if ((int) ($data->gerar_praticas_aprend ?? 0) === 1) {
                    $praticasParam = [
                        'gerar_praticas_aprend' => $data->gerar_praticas_aprend
                    ];
                    self::onChangeVisibilidadePraticas($praticasParam);
                }

                // 4. reenvia os dados para marcar componentes e habilidades
                TForm::sendData('form_plano_aula', $data);
            }
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    public function onClear($param)
    {
        $this->form->clear(true);
    }

    /**
     * Verifica se todos os campos informados estão preenchidos
     * @param array $campos  vetor com nomes dos campos do formulário
     * @return int
     */
    private function camposPreenchidos($campos)
    {
        $data = $this->form->getData();
        //echo '<pre>'; print_r($data); echo '</pre>';

        foreach ($campos as $campo)
        {
            if (!isset($data->$campo))
            {
                return 0;
            }

            $valor = $data->$campo;

            // verifica arrays (checkbox, checkgroup etc)
            if (is_array($valor))
            {
                if (count($valor) == 0)
                {
                    return 0;
                }
            }
            else
            {
                if (trim($valor) == '')
                {
                    return 0;
                }
            }
        }

        return 1;
    }

    public function onGerarPlanoComIA($param = null)
    {
        //echo '<pre>'; print_r($param); echo '</pre>'; return;
        try {

            $vet = array('titulo', 'nivel_ensino', 'ano_escolar', 'eixo_computacao', 'duracao_aula', 'numero_aula'); //, 'comentarios_adicionais');
            if (!$this->camposPreenchidos($vet)) {
                $data = $this->form->getData();
                $this->form->setData($data);
                TForm::sendData('form_plano_aula', $data);
                new TMessage('error', 'Por favor, preencha todos os campos obrigatórios.');
                return;
            }

            TTransaction::open('platia');

            $data = $this->form->getData();
            $this->form->setData($data);

            $idsHabilidades = $this->normalizarSelecao($data->id_habilidades ?? null);
            $idsComponentes = $this->normalizarSelecao($data->id_componentes ?? null);
         
            $nomesHabilidades = [];
            $nomesComponentes = [];

            foreach ($idsHabilidades as $id) {
                $habilidade = new HabilidadeComputacao($id);
                if ($habilidade) {
                    $nomesHabilidades[] = $habilidade->descricao;
                }
            }

            foreach ($idsComponentes as $id) {
                $componente = new ComponenteCurricular($id);
                if ($componente) {
                    $nomesComponentes[] = $componente->descricao;
                }
            }
            //echo '<pre>'; print_r($nomesComponentes); echo '</pre>'; 
            //echo '<pre>'; print_r($nomesHabilidades); echo '</pre>';
            

            $service = new GptServiceV2();

            $dados = [
                'eixo_computacao'        => $service->prepararTextoCurto($data->eixo_computacao ?? '', 200),
                'ano_escolar'            => $service->prepararTextoCurto($data->ano_escolar ?? '', 100),
                'duracao_aula'           => $service->prepararTextoCurto($data->duracao_aula ?? '', 50),
                'numero_aulas'           => $service->prepararTextoCurto($data->numero_aulas ?? '', 50),
                'conteudo_componentes'   => $service->prepararTextoCurto(implode('; ', $nomesComponentes), 1500),
                'habilidades'            => $service->prepararTextoCurto(implode('; ', $nomesHabilidades), 1500),
                'informacoes_adicionais' => $service->prepararTextoCurto($data->informacoes_adicionais ?? '', 1000),
            ];

            $objPrompt = Prompt::getPrompt($data->eixo_computacao);
            $resultado = $service->gerarPlanoAulaEmDoisPassos($objPrompt->system_prompt1, $objPrompt->user_prompt1, $objPrompt->system_prompt2, $objPrompt->user_prompt2, $dados);

            $estrutura = $resultado['estrutura_pedagogica'] ?? [];
            $plano     = $resultado['plano_aula'] ?? [];

            $data->titulo_gerado       = $plano['titulo'] ?? '';
            $data->estrutura_formatada = $this->formatarEstruturaPedagogicaHtml($estrutura);
            $data->conteudo_gerado     = $this->formatarPlanoAulaHtml($plano);
            $data->estrutura_json      = json_encode($estrutura, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $data->plano_json          = json_encode($plano, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $this->form->setData($data);
            TForm::sendData('form_plano_aula', $data);

            TTransaction::close();

            new TMessage('info', 'Plano de aula gerado com sucesso.');
        }
        catch (Exception $e) {
            if (TTransaction::get()) {
                TTransaction::rollback();
            }
            new TMessage('error', $e->getMessage());
        }
    }

    /*public function onGerarPlanoComIA($param)   //Mult step LLM
    {
        try {
            $data = $this->form->getData();

            $pedidoUsuario = $this->montarPromptUsuario($data);

            $service = new MultiStepLLMService();
            $resultado = $service->gerarPlanoAulaEmPassos($pedidoUsuario);

            $estrutura = $resultado['estrutura_pedagogica'] ?? [];
            $plano     = $resultado['plano_aula'] ?? [];

            $data->titulo_gerado       = $plano['titulo'] ?? '';
            $data->estrutura_formatada = $this->formatarEstruturaPedagogicaHtml($estrutura);
            $data->conteudo_gerado     = $this->formatarPlanoAulaHtml($plano);
            $data->estrutura_json      = json_encode($estrutura, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $data->plano_json          = json_encode($plano, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $this->form->setData($data);

            new TMessage('info', 'Plano de aula gerado com sucesso.');
        }
        catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }*/

    private function montarPromptUsuario($data): string
    {
        $partes = [];

        if (!empty($data->tema)) {
            $partes[] = "Tema: {$data->tema}";
        }

        if (!empty($data->publico_alvo)) {
            $partes[] = "Público-alvo: {$data->publico_alvo}";
        }

        if (!empty($data->carga_horaria)) {
            $partes[] = "Carga horária: {$data->carga_horaria}";
        }

        if (!empty($data->objetivo)) {
            $partes[] = "Objetivo pretendido: {$data->objetivo}";
        }

        if (!empty($data->conteudo_base)) {
            $partes[] = "Conteúdo base: {$data->conteudo_base}";
        }

        if (!empty($data->componentes_curriculares)) {
            $partes[] = "Componentes curriculares: {$data->componentes_curriculares}";
        }

        if (!empty($data->habilidades)) {
            $partes[] = "Habilidades a trabalhar: {$data->habilidades}";
        }

        $partes[] = "Gerar estrutura pedagógica e plano de aula completos, coerentes e prontos para uso docente.";

        return implode("\n", $partes);
    }

    private function formatarEstruturaPedagogicaHtml(array $estrutura): string
    {
        $objetivos = $this->arrayToHtmlList($estrutura['objetivos_especificos'] ?? []);
        $conceitos = $this->arrayToHtmlList($estrutura['conceitos_principais'] ?? []);
        $dificuldades = $this->arrayToHtmlList($estrutura['possiveis_dificuldades'] ?? []);

        return "
            <h3>Estrutura Pedagógica</h3>
            <p><b>Tema central:</b> " . htmlspecialchars($estrutura['tema_central'] ?? '') . "</p>
            <p><b>Problema orientador:</b> " . htmlspecialchars($estrutura['problema_orientador'] ?? '') . "</p>
            <p><b>Objetivo geral:</b> " . htmlspecialchars($estrutura['objetivo_geral'] ?? '') . "</p>
            <p><b>Objetivos específicos:</b></p>
            {$objetivos}
            <p><b>Conceitos principais:</b></p>
            {$conceitos}
            <p><b>Estratégia pedagógica:</b> " . htmlspecialchars($estrutura['estrategia_pedagogica'] ?? '') . "</p>
            <p><b>Possíveis dificuldades:</b></p>
            {$dificuldades}
        ";
    }

    private function formatarPlanoAulaHtml(array $plano): string
    {
        $objetivos = $this->arrayToHtmlList($plano['objetivos_aprendizagem'] ?? []);
        $habilidades = $this->arrayToHtmlList($plano['habilidades_trabalhadas'] ?? []);
        $conteudos = $this->arrayToHtmlList($plano['conteudos_abordados'] ?? []);
        $recursos = $this->arrayToHtmlList($plano['recursos_didaticos'] ?? []);
        $atividades = $this->arrayToHtmlList($plano['atividades_praticas'] ?? []);
        $adaptacoes = $this->arrayToHtmlList($plano['adaptacoes'] ?? []);
        $continuidade = $this->arrayToHtmlList($plano['continuidade'] ?? []);
        $metodologia = $this->metodologiaToHtml($plano['metodologia'] ?? []);

        return "
            <h2>" . htmlspecialchars($plano['titulo'] ?? 'Plano de Aula') . "</h2>
            <p><b>Objetivos de aprendizagem:</b></p>
            {$objetivos}
            <p><b>Habilidades trabalhadas:</b></p>
            {$habilidades}
            <p><b>Conteúdos abordados:</b></p>
            {$conteudos}
            <p><b>Metodologia:</b></p>
            {$metodologia}
            <p><b>Recursos didáticos:</b></p>
            {$recursos}
            <p><b>Atividades práticas:</b></p>
            {$atividades}
            <p><b>Avaliação:</b> " . nl2br(htmlspecialchars($plano['avaliacao'] ?? '')) . "</p>
            <p><b>Adaptações pedagógicas:</b></p>
            {$adaptacoes}
            <p><b>Continuidade da aula:</b></p>
            {$continuidade}
        ";
    }

    private function formatarPraticaAulaHtml(array $plano): string
    {
        echo '<pre>'; print_r($plano); echo '</pre>';
        $objetivos = $this->arrayToHtmlList($plano['objetivos_aprendizagem'] ?? []);
        $habilidades = $this->arrayToHtmlList($plano['habilidades_trabalhadas'] ?? []);
        $conteudos = $this->arrayToHtmlList($plano['conteudos_abordados'] ?? []);
        $recursos = $this->arrayToHtmlList($plano['recursos_didaticos'] ?? []);
        $atividades = $this->arrayToHtmlList($plano['atividades_praticas'] ?? []);
        $adaptacoes = $this->arrayToHtmlList($plano['adaptacoes'] ?? []);
        $continuidade = $this->arrayToHtmlList($plano['continuidade'] ?? []);
        $metodologia = $this->metodologiaToHtml($plano['metodologia'] ?? []);

        return "
            <h2>" . htmlspecialchars($plano['titulo'] ?? 'Plano de Aula') . "</h2>
            <p><b>Objetivos de aprendizagem:</b></p>
            {$objetivos}
            <p><b>Habilidades trabalhadas:</b></p>
            {$habilidades}
            <p><b>Conteúdos abordados:</b></p>
            {$conteudos}
            <p><b>Metodologia:</b></p>
            {$metodologia}
            <p><b>Recursos didáticos:</b></p>
            {$recursos}
            <p><b>Atividades práticas:</b></p>
            {$atividades}
            <p><b>Avaliação:</b> " . nl2br(htmlspecialchars($plano['avaliacao'] ?? '')) . "</p>
            <p><b>Adaptações pedagógicas:</b></p>
            {$adaptacoes}
            <p><b>Continuidade da aula:</b></p>
            {$continuidade}
        ";
    }

    private function arrayToHtmlList(array $itens): string
    {
        if (empty($itens)) {
            return '<ul><li>Não informado</li></ul>';
        }

        $html = '<ul>';
        foreach ($itens as $item) {
            $html .= '<li>' . htmlspecialchars((string) $item) . '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    private function metodologiaToHtml(array $metodologia): string
    {
        if (empty($metodologia)) {
            return '<ul><li>Não informado</li></ul>';
        }

        $html = '<ol>';
        foreach ($metodologia as $etapa) {
            $nome = htmlspecialchars($etapa['etapa'] ?? '');
            $descricao = htmlspecialchars($etapa['descricao'] ?? '');
            $tempo = htmlspecialchars($etapa['tempo'] ?? '');

            $html .= "<li><b>{$nome}</b> ({$tempo})<br>{$descricao}</li>";
        }
        $html .= '</ol>';

        return $html;
    }

    private function normalizarSelecao($valor): array
    {
        if (empty($valor)) {
            return [];
        }

        if (is_array($valor)) {
            return array_filter($valor);
        }

        if (is_string($valor)) {
            $itens = explode(',', $valor);
            return array_filter(array_map('trim', $itens));
        }

        return [];
    }

    private function buscarDescricoesPorIds(string $database, string $model, array $ids, string $campoDescricao = 'nome'): array
    {
        if (empty($ids)) {
            return [];
        }

        $descricoes = [];

        TTransaction::open($database);

        foreach ($ids as $id) {
            $obj = new $model($id);
            if ($obj && isset($obj->$campoDescricao)) {
                $descricoes[] = $obj->$campoDescricao;
            }
        }

        TTransaction::close();

        return $descricoes;
    }

    public static function onChangeFiltroHabilidades($param)
    {
        //echo '<pre>'; print_r($param); echo '</pre>';
        try
        {
            TTransaction::open('platia');

            $criteria = new TCriteria;

            if (!empty($param['eixo_computacao']))
            {
                $criteria->add(new TFilter('eixo_computacao', '=', $param['eixo_computacao']));
            }

            if (!empty($param['ano_escolar']))
            {
                $criteria->add(new TFilter('ano_escolar', '=', $param['ano_escolar']));
            }

            $criteria->setProperty('order', 'descricao');

            /*$repositorio = new TRepository('HabilidadeComputacao');
            $objetos = $repositorio->load($criteria);

            $items = [];

            if ($objetos)
            {
                foreach ($objetos as $obj)
                {
                    $items[$obj->id] = $obj->descricao;
                }
            }*/
            TDBCheckGroup::reloadFromModel('form_plano_aula', 'id_habilidades', 'platia', 'HabilidadeComputacao', 'id', 'descricao', 'descricao', $criteria);
            //TScript::create("$('#id_habilidades').val('{$param['id_habilidades']}'); ");

            TTransaction::close();

            //TCheckGroup::reload('form_plano_aula', 'id_habilidades', $items);
        }
        catch (Exception $e)
        {
            if (TTransaction::get())
            {
                TTransaction::rollback();
            }

            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Controla a visibilidade dos campos de práticas de aprendizagem
     */
    public static function onChangeFiltroComponentes($param)
    {
        //echo '<pre>'; print_r($param); echo '</pre>';
        try
        {
            TTransaction::open('platia');

            $criteria = new TCriteria;

            // Accept explicit value 0 as valid (empty() would treat '0' as empty)
            if (is_array($param) && array_key_exists('nivel_ensino', $param))
            {
                $criteria->add(new TFilter('nivel_ensino', '=', $param['nivel_ensino']));
            }
            else
            {
                // default to nivel_ensino = 0 when no param provided
                $criteria->add(new TFilter('nivel_ensino', '=', 0));
            }

            $criteria->setProperty('order', 'descricao');
            TDBCheckGroup::reloadFromModel('form_plano_aula', 'id_componentes', 'platia', 'ComponenteCurricular', 'id', 'descricao', 'descricao', $criteria);
            TTransaction::close();
        }
        catch (Exception $e)
        {
            if (TTransaction::get())
            {
                TTransaction::rollback();
            }

            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Controla a visibilidade dos campos de práticas de aprendizagem
     */
    public static function onChangeVisibilidadePraticas($param)
    {
        try {
            $data = (object) $param;
            $visivel = (int) ($data->gerar_praticas_aprend ?? 0) === 1;

            // Controlar visibilidade dos campos dependentes
            if ($visivel) {
                TQuickForm::showField('form_plano_aula', 'qtd_praticas');
                TQuickForm::showField('form_plano_aula', 'nivel_inicial');
                TQuickForm::showField('form_plano_aula', 'nivel_intermediario');
                TQuickForm::showField('form_plano_aula', 'nivel_avancado');
                TQuickForm::showField('form_plano_aula', 'btn_gerar_pratica');
                TQuickForm::showField('form_plano_aula', 'data_inicio_praticas');
                TQuickForm::showField('form_plano_aula', 'data_fim_praticas');
                TQuickForm::showField('form_plano_aula', 'neurodivergencia');
                TQuickForm::showField('form_plano_aula', 'conteudo_gerado_pratica');
                //TCheckGroup::enableField('form_plano_aula', 'neurodivergencia');

            } else {
                TQuickForm::hideField('form_plano_aula', 'qtd_praticas');
                TQuickForm::hideField('form_plano_aula', 'nivel_inicial');
                TQuickForm::hideField('form_plano_aula', 'nivel_intermediario');
                TQuickForm::hideField('form_plano_aula', 'nivel_avancado');
                TQuickForm::hideField('form_plano_aula', 'btn_gerar_pratica');
                TQuickForm::hideField('form_plano_aula', 'data_inicio_praticas');
                TQuickForm::hideField('form_plano_aula', 'data_fim_praticas');
                TQuickForm::hideField('form_plano_aula', 'neurodivergencia');
                TQuickForm::hideField('form_plano_aula', 'conteudo_gerado_pratica');
                //TCheckGroup::disableField('form_plano_aula', 'neurodivergencia');
            }
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Valida os níveis de prática de aprendizagem
     * A soma dos níveis não pode ser diferente do total de práticas
     */
    public function onValidarPraticas($param)
    {
        try 
        {
            $vet = array('conteudo_gerado', 'qtd_praticas', 'nivel_inicial', 'nivel_intermediario', 'nivel_avancado', 'data_inicio_praticas', 'data_fim_praticas', 'neurodivergencia'); 
            if (!$this->camposPreenchidos($vet)) {
                $data = $this->form->getData();
                $this->form->setData($data);
                TForm::sendData('form_plano_aula', $data);
                new TMessage('error', 'Por favor, preencha todos os campos obrigatórios.');
                return;
            }

            $data = $this->form->getData();

            $qtd_praticas = (int) $data->qtd_praticas ?? 0;
            $nivel_inicial = (int) $data->nivel_inicial ?? 0;
            $nivel_intermediario = (int) $data->nivel_intermediario ?? 0;
            $nivel_avancado = (int) $data->nivel_avancado ?? 0;

            $soma_niveis = $nivel_inicial + $nivel_intermediario + $nivel_avancado;

            if ($soma_niveis !== $qtd_praticas) {
                throw new Exception("A soma dos níveis ({$soma_niveis}) deve ser igual ao total de práticas ({$qtd_praticas}).");
            }

            $service = new GptServiceV2();

            $mapaNeurodivergencia = [
                1 => 'Nenhuma neurodivergência',
                2 => 'Transtorno do Déficit de Atenção com Hiperatividade',
                3 => 'Transtorno do Espectro Autista',
            ];

            $neurodivergenciaTexto = '';
            if (isset($data->neurodivergencia)) {
                if (is_array($data->neurodivergencia)) {
                    $neuroValores = array_map('intval', $data->neurodivergencia);
                    $neurodivergenciaTexto = implode(', ', array_map(fn($valor) => $mapaNeurodivergencia[$valor] ?? (string) $valor, $neuroValores));
                } else {
                    $valor = (int) $data->neurodivergencia;
                    $neurodivergenciaTexto = $mapaNeurodivergencia[$valor] ?? (string) $data->neurodivergencia;
                }
            }

            $dados = [
                'conteudo_gerado'       => $service->prepararTextoCurto($data->conteudo_gerado ?? '', 1500),
                'qtd_praticas'           => $service->prepararTextoCurto($data->qtd_praticas ?? '', 50),
                'nivel_inicial'          => $service->prepararTextoCurto($data->nivel_inicial ?? '', 100),
                'nivel_intermediario'    => $service->prepararTextoCurto($data->nivel_intermediario ?? '', 100),
                'nivel_avancado'         => $service->prepararTextoCurto($data->nivel_avancado ?? '', 100),
                'data_inicio_praticas'   => $service->prepararTextoCurto($data->data_inicio_praticas ?? '', 50),
                'data_fim_praticas'      => $service->prepararTextoCurto($data->data_fim_praticas ?? '', 50),
                'neurodivergencia'       => $service->prepararTextoCurto($neurodivergenciaTexto, 500),
            ];

            //$objPrompt = Prompt::getPrompt($data->eixo_computacao);
            $sp = "Você é um especialista em educação e aprendizagem, com experiência em criar práticas de aprendizagem para diferentes níveis de habilidade. Considerando o plano de aula {$dados['conteudo_gerado']}, sua tarefa é gerar práticas de aprendizagem detalhadas para alunos com {$dados['neurodivergencia']}, considerando os seguintes parâmetros:";
            $up = "Quantidade de práticas: {$dados['qtd_praticas']}\nQuantidade de práticas por nível:\nNível Inicial: {$dados['nivel_inicial']}\nNível Intermediário: {$dados['nivel_intermediario']}\nNível Avançado: {$dados['nivel_avancado']}\n\nPor favor, gere práticas de aprendizagem detalhadas e coerentes, considerando os níveis de habilidade especificados e fornecendo instruções claras para cada prática.";
            $resultado = $service->gerarPraticaAula($sp, $up, $dados);

            $pratica     = $resultado['exercicio'] ?? [];

            $data->titulo_gerado       = $pratica['titulo'] ?? '';
            $data->conteudo_gerado_pratica = $this->formatarPraticaAulaHtml($pratica);
            $data->pratica_json          = json_encode($pratica, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $this->form->setData($data);
            TForm::sendData('form_plano_aula', $data);

            TTransaction::close();

            new TMessage('info', 'Prática de aula gerado com sucesso.');
            

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
}
