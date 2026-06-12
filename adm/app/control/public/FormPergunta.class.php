<?php

use Adianti\Registry\TSession;
use Adianti\Widget\Form\TRadioGroup;
use Adianti\Widget\Util\TCardView;
use Adianti\Widget\Wrapper\TQuickForm;

/**
 * FormPergunta
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @license    http://www.adianti.com.br/framework-license
 */
class FormPergunta extends TPage
{
    protected $form; // form
    protected $program_list;
    public $location = "app/images/noticias"; //localhost "/trilha/uploadImage"
    public $caminhoImagem;

    private $labelId;
    private $btnTocarAudio;

    //use Adianti\Base\AdiantiFileSaveTrait;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct($param)
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_pergunta');
        //$this->form->setFormTitle( 'Notícias' );

        $table1 = new TTable;
        $table1->border = '0';
        $table1->cellpadding = '2';
        
        // create the form fields
        $id  = new THidden('id');
        $this->labelId = new TLabel('');
        $this->labelId->style = 'font-weight: bold';

        //$criteria=Tema::TemaUsuario(TSession::getValue('userid'));
		//$idTema     = new TDBCombo('idtema','jedieduca','Tema','id','nome', 'nome', $criteria);
        
        $lblTema = new TLabel('Tema');
        $idTema  = new TEntry('idtema');
        $idTema->setEditable(false);
        $idTema->setSize('40%');

        $lblPergunta = new TLabel('Noticia');
        $pergunta = new TText('pergunta');
        $pergunta->setSize('70%',120);

        //$respCerta = new TCombo('respcerta');
        $respCerta = new TRadioGroup('respcerta');
        $respCertaId = $respCerta->getId();
        $respCerta->setLayout('horizontal');
        $respCerta->setUseButton('btn-sm');
        $items = ['NÃO FAKE'=>'SIM', 'FAKE'=>'NÃO'];
        $respCerta->addItems($items);
        //$resp2 = new TCombo('resp2');
        //$resp2->addItems($items);
        //$resp2->setSize('70%');
        //$resp3 = new TEntry('resp3');
        //$resp3->setSize('70%');
        //$resp4 = new TEntry('resp4');
        //$resp4->setSize('70%');

        /*TScript::create("setTimeout(function(){ 
                    $('#{$id} button').css({
                        'width': '120px',
                        'height': '50px',
                        'font-size': '18px',
                        'background': '#285e8e'
                    });
                    }, 100);
                    ");*/

        //$imagem = new TImageCropper('caminhoimagem');

        $caractSugerida  = new TText('caract_sugerida');
        $caractSugerida->setSize('100%',120);

        $caractProposta  = new TText('caract_proposta');
        $caractProposta->id='caract_proposta';
        $caractProposta->setSize('100%',120);

        $analiseGPT  = new TText('analise_gpt');
        $analiseGPT->id='analise_gpt';
        $analiseGPT->setSize('100%',120);

        $actAnaliseGPT = new TAction(array($this, 'onAnaliseGPT'));
        $btnAnaliseGPT = new TButton('btnAnaliseGPT');
        $btnAnaliseGPT->setImage('fa:bullhorn red');
        $btnAnaliseGPT->style = 'background:#285e8e;color:#ffffff; width: 25%';
        $btnAnaliseGPT->setAction($actAnaliseGPT,' GPT');
        //$fields[] = $btnCaractGPT;

        $analiseGemini  = new TText('analise_gemini');
        $analiseGemini->setSize('100%',120);

        $actAnaliseGemini = new TAction(array($this, 'onAnaliseGPT'));  //trocar para onCaractGemini
        //$actAssunto->setParameter('key',$key);
        $btnAnaliseGemini = new TButton('btnAnaliseGemini');
        $btnAnaliseGemini->setImage('fa:bullhorn red');
        $btnAnaliseGemini->style = 'background:#285e8e;color:#ffffff;  width: 25%';
        $btnAnaliseGemini->setAction($actAnaliseGemini,' Gemini');
        //$fields[] = $btnFalaGemini;

        $origemAnalise = new TRadioGroup('origem_analise');
        $origemAnaliseId = $origemAnalise->getId();
        $origemAnalise->setUseButton('btn-sm');
        $origemAnalise->setLayout('horizontal');
        $items = [1=>'GPT', 2=>'Gemini'];
        $origemAnalise->addItems($items);
        $origemAnalise->setChangeAction(new TAction([$this, 'onChangeOrigemAnalise']));

        $analiseProposta  = new TText('analise_proposta');
        $analiseProposta->id='analise_proposta';
        $analiseProposta->setSize('85%',120);

        $falaGPT  = new TText('fala_gpt');
        $falaGPT->setSize('100%',120);

        $actFalaGPT = new TAction(array($this, 'onFalaGPT'));
        //$actAssunto->setParameter('key',$key);
        $btnFalaGPT = new TButton('btnFalaGPT');
        $btnFalaGPT->setImage('fa:bullhorn red');
        $btnFalaGPT->style = 'background:#285e8e;color:#ffffff; width: 25%';
        $btnFalaGPT->setAction($actFalaGPT,' GPT');
        //$fields[] = $btnFalaGPT;

        $falaGemini  = new TText('fala_gemini');
        $falaGemini->setSize('100%',120);

        $actFalaGemini = new TAction(array($this, 'onFalaGPT'));
        //$actAssunto->setParameter('key',$key);
        $btnFalaGemini = new TButton('btnFalaGemini');
        $btnFalaGemini->setImage('fa:bullhorn red');
        $btnFalaGemini->style = 'background:#285e8e;color:#ffffff;  width: 25%';
        $btnFalaGemini->setAction($actFalaGemini,' Gemini');
        //$fields[] = $btnFalaGemini;

        $origemFala = new TRadioGroup('origem_fala');
        $origemFalaId = $origemFala->getId();
        $origemFala->setUseButton('btn-sm');
        $origemFala->setLayout('horizontal');
        $items = [1=>'GPT', 2=>'Gemini'];
        $origemFala->addItems($items);
        $origemFala->setChangeAction(new TAction([$this, 'onChangeOrigemFala']));

        $falaProposta  = new TText('fala_proposta');
        $falaProposta->id='fala_proposta';
        $falaProposta->setSize('85%',120);

        // BOTÕES DE ÁUDIO (TTS)
        $actGerarAudio = new TAction([$this, 'onGerarAudioMP3']);
        $btnGerarAudio = new TButton('btnGerarAudio');
        $btnGerarAudio->setImage('fa:microphone blue');
        $btnGerarAudio->style = 'background:#285e8e;color:#ffffff; width: 25%';
        $btnGerarAudio->setAction($actGerarAudio, ' Gerar MP3');

        $actTocarAudio = new TAction([$this, 'onTocarAudio']);
        //$actTocarAudio = new TAction(['VideoCardView', 'onViewVideo']);
        $this->btnTocarAudio = new TButton('btnTocarAudio');
        $this->btnTocarAudio->setImage('fa:play green');
        $this->btnTocarAudio->style = 'background:#285e8e;color:#ffffff; width: 25%';
        // Abre direto o MP3 em nova aba
        /*$url = "http://localhost/adianti-template-7.6.0/cadJEDI/app/storage/audios/pergunta_{$param['id']}.mp3";
        $this->btnTocarAudio->setProperty('onclick', "
            window.open('{$url}', '_blank');
            return false;
        ");*/
        $this->btnTocarAudio->setAction($actTocarAudio, ' Tocar Áudio');
        // FIM BOTÕES DE ÁUDIO (TTS)

        $imagePath = new TFile('caminhoimagem');
        $imagePath->setSize('100%');
        $imagePath->setAllowedExtensions( ['gif', 'png', 'jpg', 'jpeg'] );
        // enable progress bar, preview
        $imagePath->enableFileHandling();
        $imagePath->enablePopover();

        $img = new TImage('');
        $img->id = 'id_imagem';
        $img->width = '300px';
        //$caminhoImagem = new TEntry('caminhoimagem');   
        //$categoria     = new TDBCombo('categoria','jedieduca','Categoria','nome','nome');
        $categoria  = new TDBMultiSearch('idCategorias', 'jedieduca', 'Categoria', 'id', 'nome', 'nome');
        $categoria->addValidation('Categoria', new TRequiredValidator);
        $categoria->setSize('70%',60);

        $publica = new TRadioGroup('publica');
        $publica->setLayout('horizontal');
        $publica->setUseButton('btn-sm');
        $items = [1=>'SIM', 0=>'NÃO'];
        $publica->addItems($items);
        $publica->addValidation('Publica', new TRequiredValidator);

        $row=$table1->addRow();
        $row->addCell($lblTema);
        $row->addCell($idTema);
        $row=$table1->addRow();
        $row->addCell($lblPergunta);
        $row->addCell($pergunta);
        $row=$table1->addRow();


        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addActionLink( _t('Back'), new TAction(array('FormPerguntaList','onReload')), 'far:arrow-alt-circle-left blue');
        
        parent::register_css('nomecss','.tfile_del_icon{ display:none; }');  //desabilita botão de remover do tfile

        $btnCancelar = new TButton('removeFile');
		$action1 = new TAction(array($this,'removeFile'));
		$btnCancelar->setAction($action1, 'Remover arquivo');
        $btnCancelar->setImage('far:trash-alt red');

               
        $this->form->addFields( [$id] );
        $row = $this->form->addFields( [new TLabel('Número da notícia')], [$this->labelId] );
        $row->layout = ['col-sm-2 control-label', 'col-sm-1'];

        //$row = $this->form->addFields( [new TLabel('Tema')], [$idTema], [new TLabel('Categoria')], [$categoria] );        
        //$row->layout = ['col-sm-2 control-label', 'col-sm-4', 'col-sm-1 control-label', 'col-sm-4' ];
        $this->form->addFields( [new TLabel('Categoria')], [$categoria] );
        $this->form->addFields( [new TLabel('Notícia')], [$pergunta] );
        $this->form->addFields( [new TLabel('Fato')], [$respCerta] );
        $this->form->addFields( [new TLabel('Imagem')], [$imagePath], [$btnCancelar]);
        $this->form->addFields( [new TLabel('')], [$img] );
        $tit1=new TLabel('Características da Notícia');
        $tit1->style='color: #285e8e; font-size: 16px; font-weight: bold';
        $this->form->addFields( [new TFormSeparator('')] );
        $row = $this->form->addFields( [new TFormSeparator($tit1)] );
        //$row->layout = ['col-md-2 control-label'];

        $row = $this->form->addFields( [new TLabel('Sugeridas')], [$caractSugerida], [new TLabel('Propostas')], [$caractProposta] );
        $row->layout = ['col-sm-2 control-label', 'col-sm-4', 'col-sm-0 control-label', 'col-sm-4' ];

        $tit1=new TLabel('Análise Crítica da IA');
        $tit1->style='color: #285e8e; font-size: 16px; font-weight: bold';
        $this->form->addFields( [new TFormSeparator('')] );
        $row = $this->form->addFields( [new TFormSeparator($tit1)] );
        $row->layout = ['col-sm-2 control-label'];
        $row = $this->form->addFields( [new TLabel('GPT')], [$analiseGPT], [new TLabel('Gemini')], [$analiseGemini] );
        $row->layout = ['col-sm-2 control-label', 'col-sm-4', 'col-sm-0 control-label', 'col-sm-4' ];
        $row = $this->form->addFields( [$btnAnaliseGPT], [$btnAnaliseGemini] );
        $row->layout = ['col-md-5 control-label', 'col-md-5 control-label' ];
        $this->form->addFields( [new TLabel('LLM de referência')], [$origemAnalise] );
        $this->form->addFields( [new TLabel('Análise proposta')], [$analiseProposta] );

        $tit2=new TLabel('Fala da Tia Bel');
        $tit2->style='color: #285e8e; font-size: 16px; font-weight: bold';
        $this->form->addFields( [new TFormSeparator('')] );
        $row = $this->form->addFields( [new TFormSeparator($tit2)] );
        $row->layout = ['col-sm-2 control-label'];
        $row = $this->form->addFields( [new TLabel('GPT')], [$falaGPT], [new TLabel('Gemini')], [$falaGemini] );
        $row->layout = ['col-sm-2 control-label', 'col-sm-4', 'col-sm-0 control-label', 'col-sm-4' ];
        $row = $this->form->addFields( [$btnFalaGPT], [$btnFalaGemini] );
        $row->layout = ['col-md-5 control-label', 'col-md-5 control-label' ];
        $this->form->addFields( [new TLabel('LLM de referência')], [$origemFala] );
        $this->form->addFields( [new TLabel('Fala proposta')], [$falaProposta] );
        $row = $this->form->addFields( [$btnGerarAudio], [$this->btnTocarAudio] );
        $row->layout = ['col-md-5 control-label', 'col-md-5 control-label' ];
        $row = $this->form->addFields( [$this->cards] );
        $row->layout = ['col-md-5 control-label' ];
        //$row->layout = ['col-sm-5 control-label', 'col-sm-5 control-label' ];
        //$this->form->addFields( [new TLabel('Resposta 2')], [$resp2] );
        //$this->form->addFields( [new TLabel('Resposta 3')], [$resp3] );
        //$this->form->addFields( [new TLabel('Resposta 4')], [$resp4] );
        $this->form->addFields( [new TFormSeparator('')] ); 
        $this->form->addFields( [new TLabel('Publica')], [$publica] );

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'FormPerguntaList'));
        $container->add($this->form);
        //$container->add($table1);

        // add the container to the page
        parent::add($container);
    }

    public function onAnaliseGPT()
    {
        $data = $this->form->getData();

        if (empty($data->pergunta)) {
            new TMessage('error', 'Informe a notícia para gerar a fala do GPT');
            return;
        }

        try {
            TTransaction::open('jedieduca');

            $gpt = new GptService();

            // parâmetros fictícios, você pode puxar do banco/formulário
            $noticia        = $data->pergunta;
            $resposta       = $data->respcerta; 
            /*$caracteristicas = ' 1) Título Sensacionalista ou Alarmista
                    Uso de letras maiúsculas em excesso, muitos pontos de exclamação ou expressões de choque ("VOCÊ NÃO VAI ACREDITAR!!!").
                    Frases que apelam à emoção e não à informação, como "escândalo", "bomba", "verdade escondida".

                    2) Ausência de Fontes Confiáveis
                    Não cita jornais, revistas, instituições ou especialistas renomados.
                    Se cita fontes, muitas vezes são vagas (“pesquisadores dizem”, “um especialista afirmou”).

                    3) Erros de Ortografia e Gramática
                    Notícia mal escrita, com erros recorrentes de português ou de digitação, o que não é comum em veículos jornalísticos profissionais.

                    4) Data ou Contexto Incompatível
                    Reutilização de fatos antigos como se fossem atuais.
                    Incoerência temporal (ex.: citar algo que supostamente aconteceu em 2025, mas vinculando a dados de 2023).

                    5) Linguagem Extremamente Emotiva ou Parcial
                    Uso de adjetivos carregados de opinião (“o criminoso covarde”, “a brilhante decisão”).
                    Textos legítimos costumam ser mais objetivos.

                    6) Promessas Exageradas ou Conspiração
                    “A cura milagrosa do câncer foi descoberta e escondida da população.”
                    Teorias da conspiração, segredos “revelados” ou informações que “a grande mídia não quer que você saiba”.

                    7) Links ou Citações Suspeitas
                    Links que não levam a sites de veículos conhecidos.
                    Sites com nomes parecidos com veículos famosos, mas com pequenas alterações no domínio (ex.: globo-noticias.net em vez de g1.globo.com).

                    8) Apelo à Urgência e Compartilhamento
                    Frases como “compartilhe antes que apaguem”, “passe adiante”, “não deixe essa informação morrer”.
                    Pressiona o leitor a agir rapidamente, sem tempo de checar.

                    9) Fatos Sem Corroboração em Múltiplas Fontes
                    A notícia só aparece em um site obscuro ou em redes sociais.
                    Notícias verdadeiras costumam ser reportadas por mais de um veículo de confiança.';*/

            $prompt = Prompt::getPrompt( $data->idtema );
            if (empty($prompt->id)) {
                throw new Exception('Prompt não encontrado para o tema selecionado. Verifique.');
                return;
            }

            //$response = $gpt->generateResponse($noticia, $resposta, $caracteristicas, 1); // 1 para selecionar características, 2 para elaborar a fala
            $response = $gpt->generateResponse($prompt->system_prompt, $prompt->user_prompt1, $noticia, $resposta, $prompt->caracteristicas); // 1 para selecionar características, 2 para elaborar a fala

            $data->analise_gpt = $response;
            $this->form->setData($data);

            TTransaction::close();
        }
        catch (Exception $e) {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        } 
    }

    public function onFalaGPT()
    {
        $data = $this->form->getData();

        if (empty($data->pergunta)) {
            new TMessage('error', 'Informe a notícia para gerar a fala do GPT');
            return;
        }

        try {
            TTransaction::open('jedieduca');

            $gpt = new GptService();

            // parâmetros fictícios, você pode puxar do banco/formulário
            $noticia        = $data->pergunta;
            $resposta       = $data->respcerta; 
            //$caracteristicas = $data->caract_gpt;

            $prompt = Prompt::getPrompt( $data->idtema );
            if (empty($prompt->id)) {
                throw new Exception('Prompt não encontrado para o tema selecionado. Verifique.');
                return;
            }

            //$response = $gpt->generateResponse($noticia, $resposta, $caracteristicas, 2); // Passo 2 -> 1 para selecionar características, 2 para elaborar a fala
            $response = $gpt->generateResponse($prompt->system_prompt, $prompt->user_prompt2, $noticia, $resposta, $prompt->caracteristicas); 

            $data->fala_gpt = $response;
            $this->form->setData($data);

            TTransaction::close();
        }
        catch (Exception $e) {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        } 
    }


    public function removeFile($param)
    {
        //echo '<pre>'; print_r($param); echo '</pre>';
        try
        {
            TTransaction::open('jedieduca');
            $object = $this->form->getData();
            // define the delete action
            //$action = new TAction(unlink("app/output/".$object->file));
            //$action->setParameters($param); // pass the key parameter ahead

            //$obj = json_decode(urldecode($object->caminhoimagem));
            //unlink($obj->fileName);
            unlink(TSession::getValue('caminhoImagem'));
            //echo '<pre>'; print_r($obj->fileName); echo '</pre>';
    
            $object->caminhoimagem='';
            TForm::sendData('form_pergunta', $object);

            //atualiza registro de pergunta
            $this->AtualizaPerguntaImagem(17, $param['id']);  //17=tema default Fake News
            // shows a dialog to the user
            new TMessage('info',"Arquivo excluído");
            //new TQuestion('Confirma a EXCLUSÃO ?', $action);
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        } 
    }

    /**
     * Save file
     * @param $object      Active Record
     * @param $data        Form data
     * @param $input_name  Input field name
     * @param $target_path Target file path
     */
    public function saveFile($object, $data, $input_name, $target_path, $target_filename)
    {
        $dados_file = json_decode(urldecode($data->$input_name));
        
        if (isset($dados_file->fileName))
        {
            //echo '<pre>'; print_r('$dados_file->fileName '.$dados_file->fileName); echo '</pre>';
            $pk = $object->getPrimaryKey();
            
            //$target_path.= '/' . $object->$pk;
            $target_path = str_replace('//', '/', $target_path);
            
            $source_file = $dados_file->fileName;
//            $target_file = strpos($dados_file->fileName, $target_path) === FALSE ? $target_path . '/' . $dados_file->fileName : $dados_file->fileName;
            $target_file = strpos($dados_file->fileName, $target_path) === FALSE ? $target_path . '/' . $target_filename : $target_filename;
            $target_file = str_replace('tmp/', '', $target_file);
            //echo '<pre>'; print_r('$target_file '.$target_file); echo '</pre>';
            $class = get_class($object);
            $obj_store = new $class;
            $obj_store->$pk = $object->$pk;
            $obj_store->$input_name = $target_filename;
            
            $delFile = null;
            
            if (!empty($dados_file->delFile))
            {
                $obj_store->$input_name = '';
                $dados_file->fileName = '';
                
                if (is_file(urldecode($dados_file->delFile)))
                {
                    $delFile = urldecode($dados_file->delFile);
                    
                    if (file_exists($delFile))
                    {
                        unlink($delFile);
                    }
                }
            }
    
            if (!empty($dados_file->newFile))
            {
                if (file_exists($source_file))
                {
                    //echo '<pre>'; print_r('$target_path '.$target_path); echo '</pre>';
                    if (!file_exists($target_path))
                    {
                        if (!mkdir($target_path, 0777, true))
                        {
                            throw new Exception(AdiantiCoreTranslator::translate('Permission denied') . ': '. $target_path);
                        }
                    }
                    
                    // if the user uploaded a source file
                    if (file_exists($target_path))
                    {
                        // move to the target directory
                        if (! rename($source_file, $target_file))
                        {
                            throw new Exception(AdiantiCoreTranslator::translate('Error while copying file to ^1', $target_file));
                        }
                        
                        $obj_store->$input_name = $target_filename;
                    }
                }
            }
            elseif ($dados_file->fileName != $delFile)
            {
                $obj_store->$input_name = $dados_file->fileName;
            }
            //echo '<pre>'; print_r('$obj_store->$input_name '.$obj_store->$input_name); echo '</pre>';
            $obj_store->store();
            
            if ($obj_store->$input_name)
            {
                $dados_file->fileName = $obj_store->$input_name;
                $data->$input_name = urlencode(json_encode($dados_file));
            }
            else
            {
                $data->$input_name = '';
            }
            
            return $obj_store;
        }
    }

    public function ExistePerguntaCategoria($tema, $perg)
    {
        $conn = TTransaction::get();
        // run query
        $sql='select categoria FROM perguntacategoria2 ';
        $sql.='WHERE tema='.$tema;
        $sql.=' AND codPerg='.$perg;
        //echo '<pre>'; print_r($sql);
        $result = $conn->query($sql);
        return $result->rowCount();
    }

    public function AtualizaPerguntaImagem($tema, $perg)
    {
        $conn = TTransaction::get();
        // run query
        $sql="update pergunta2 ";
        $sql.="SET caminhoimagem='sem_imagem.png' ";
        $sql.='WHERE idtema='.$tema;
        $sql.=' AND id='.$perg;
        //echo '<pre>'; print_r($sql);
        $result = $conn->query($sql);
    }

    public function AtualizaPerguntaCategoria($tema, $perg, $categoria)
    {
        $conn = TTransaction::get();
        // run query
        $sql="update perguntacategoria2 ";
        $sql.="SET categoria='{$categoria}' ";
        $sql.='WHERE tema='.$tema;
        $sql.=' AND codPerg='.$perg;
        //echo '<pre>'; print_r($sql);
        $result = $conn->query($sql);
    }

    public function InserePerguntaCategoria($tema, $perg, $categoria)
    {
        $conn = TTransaction::get();
        // run query
        $sql="insert into perguntacategoria2 (tema, codPerg, categoria) ";
        $sql.="values ( {$tema}, {$perg}, '{$categoria}')";
        //echo '<pre>'; print_r($sql);
        $result = $conn->query($sql);
    }


    /**
     * Save user data
     */
    public function onSave($param)
    {
        if (empty($param['caminhoimagem']))
        {
            if (empty(TSession::getValue('caminhoImagem')))
                $param['caminhoimagem']='sem_imagem.png';
            else
                $param['caminhoimagem']=TSession::getValue('caminhoImagem');
        }
        //echo '<pre>'; print_r('1 -> '.$param['caminhoimagem']);
        try
        {
            $this->form->validate();    
            // open a transaction with database 'jedieduca'
            TTransaction::open('jedieduca');
            
            $data = $this->form->getData();
            $this->form->setData($data);
            //echo '<pre>'; print_r($data->caract_proposta); echo '</pre>';
            
            $object = new Pergunta;  //tabela pergunta2
            $object->fromArray( (array) $data );
            if (!empty(TSession::getValue('id'))) 
                $object->id=TSession::getValue('id');
            $object->idtema = 17; //tema default Fake News
            $object->resp2 = $data->respcerta=='FAKE' ? 'NÃO FAKE' : 'FAKE';
            
            //echo '<pre>'; print_r($object);

            //echo '<pre>'; print_r('$object->caminhoimagem '.empty($object->caminhoimagem));

            
            if ((strpos($param['caminhoimagem'],"sem_imagem.png")>0) || ($param['caminhoimagem']=='sem_imagem.png'))
                $object->caminhoimagem='sem_imagem.png';//TSession::getValue('caminhoImagem');
            //if (empty($object->caminhoimagem))
                //$object->caminhoimagem='sem_imagem.png';
            else
            {
                if (strpos($param['caminhoimagem'],"newFile")>0)
                    $targetExt=substr(urldecode($param['caminhoimagem']),-5,3);
                else
                    $targetExt=substr(urldecode($param['caminhoimagem']),-3);
                //echo '<pre>'; print_r($targetExt);
                //echo '<pre>'; print_r('$targetExt '.$targetExt); echo '</pre>';
                $targetFile="T".$object->idtema."_perg_".$object->id.'.'.$targetExt;
                $object->caminhoimagem=$targetFile;   //$param['caminhoimagem'];
                //echo '<pre>'; print_r($object->caminhoimagem);
            }
                
            $object->caract_proposta = $data->caract_proposta;
            $object->analise_proposta = $data->analise_proposta;
            $object->analise_gpt = $data->analise_gpt;
            $object->analise_gemini = $data->analise_gemini;
            $object->origem_analise = $data->origem_analise;
            $object->fala_gpt = $data->fala_gpt;
            $object->fala_gemini = $data->fala_gemini;
            $object->fala_proposta = $data->fala_proposta;
            $object->origem_fala = $data->origem_fala;
            $object->publica = $data->publica;
            $object->store();

            TSession::setValue('id',$object->id);
            $this->labelId->setValue($object->id);

            //echo '<pre>'; print_r($object); echo '</pre>';

            //echo '<pre>'; print_r($object);

            /*if ($this->ExistePerguntaCategoria($object->idtema, $object->id ))
            {
                $this->AtualizaPerguntaCategoria($object->idtema, $object->id, $data->categoria);
            }
            else
                $this->InserePerguntaCategoria($object->idtema, $object->id, $data->categoria );*/

            $objPC = new PerguntaCategoria();
            $objPC->fromArray( (array) $data );
            //Remove pergunta categoria antiga
            $objPC->removePerguntaCategoria($object->idtema, $object->id); 
            //gravar no repositorio PerguntaCategoria
            $conn = TTransaction::get();
            foreach ($data->idCategorias as $idCategoria)
            {
                if (empty(trim($idCategoria))) continue;
                // run query
                $sql="INSERT INTO perguntacategoria2 ";
                $sql.="(tema, codPerg, categoria) ";
                $sql.="Values ({$object->idtema}, {$object->id}, {$idCategoria})";
                $conn->query($sql);
                //echo '<pre>'; print_r($sql); echo '</pre>';
            }
            //echo '<pre>'; print_r($object->caminhoimagem);
            // copy file to target folder
            //$this->saveFile($object, $data, 'caminhoimagem', 'app/images/jogos');

            //echo '<pre>'; print_r($param['caminhoimagem']);
            $posicao=strpos($param['caminhoimagem'],'tmp');
            if (!$posicao===false)
            {
                //echo '<pre>'; print_r('$param["caminhoimagem"] '.$param['caminhoimagem']); echo '</pre>';
               $this->saveFile($object, $data, 'caminhoimagem', $this->location, $targetFile );
            }

            $data = new stdClass;
            $data->id = $object->id;
            $data->caminhoimagem = $param['caminhoimagem'];
            TForm::sendData('form_pergunta', $data);
            
            TScript::create("$('#id_imagem').attr('src','{$data->caminhoimagem}')");
            
            // close the transaction
            TTransaction::close();
            
            // shows the success message
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit($param)
    {
        //echo '<pre>'; print_r($param); echo '</pre>'; 
        try
        {
            // open a transaction with database 'jedieduca'
            TTransaction::open('jedieduca');
            // get the parameter $key            
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                TSession::setValue('id',$key);
                $this->labelId->setValue($key);
                               
                // instantiates object System_user
                $object = new Pergunta($key);
                //echo '<pre>'; print_r($object);

                $data = $this->form->getData();
                              
                // fill the form with the active record data
                //$object->caminhoimagem=$_SERVER['SERVER_NAME'].$this->location.'/'.$object->caminhoimagem;
                $object->caminhoimagem=$this->location.'/'.$object->caminhoimagem;
                TSession::setValue('caminhoImagem',$object->caminhoimagem);
                //echo '<pre>'; print_r($object->caminhoimagem); echo '</pre>'; 

                //Pega Categoria
                /*$conn = TTransaction::get();
                // run query
                $sql='select categoria FROM perguntacategoria2 ';
                $sql.='WHERE tema='.$object->idtema;
                $sql.=' AND codPerg='.$object->id;
                //echo '<pre>'; print_r($sql);
                $result = $conn->query($sql);
                $resulte = $result->fetchAll(PDO::FETCH_ASSOC);*/

                $vetCateg  = array();
                if( $perg_db = PerguntaCategoria::getCategoria($object->idtema, $param['key']) )
                {
                    foreach( $perg_db as $perg_cat )
                    {
                        $vetCateg[] = $perg_cat->categoria;
                    }
                }
                $data->idCategorias = $vetCateg;

                $data->id=$key;
                $data->idtema=$object->idtema;
                $data->pergunta=$object->pergunta;
                /*if ($result->rowCount()==0)
                    $data->categoria='';
                else
                    $data->categoria=$resulte[0]['categoria'];*/
                $data->respcerta=$object->respcerta;
                //$data->resp2=$object->resp2;
                //$data->resp3=$object->resp3;
                //$data->resp4=$object->resp4;

                $data->caract_proposta=$object->caract_proposta;
                $data->analise_proposta=$object->analise_proposta;
                $data->caract_gpt=$object->analise_gpt;
                $data->caract_gemini=$object->analise_gemini;
                $data->origem_analise=$object->origem_analise;
                $data->fala_gpt=$object->fala_gpt;
                $data->fala_gemini=$object->fala_gemini;
                $data->origem_fala=$object->origem_fala;
                $data->fala_proposta=$object->fala_proposta;
                $data->caminhoimagem=$object->caminhoimagem;
                $data->publica=$object->publica;

                $data->analise_gpt=$object->analise_gpt;
                $data->analise_gemini=$object->analise_gemini;

                $objPrompt = Prompt::getPrompt( $object->idtema );
                $data->caract_sugerida=$objPrompt->caracteristicas;


                //echo '<pre>'; print_r($data);
                TForm::sendData('form_pergunta', $data);
                $this->form->setData($object);
                TScript::create("$('#id_imagem').attr('src','{$data->caminhoimagem}')");
                $this->updateBotaoTocarAudioByPergunta($key);
            }
            else
            {
                TSession::setValue('caminhoImagem','');
                $this->form->clear();
                $this->labelId->setValue('');
                $obj = new stdClass;
                $obj->idtema = '17'; //tema default Fake News
                TSession::setValue('id','');
                $objPrompt = Prompt::getPrompt( 17 ); //tema default Fake News
                $obj->caract_sugerida=$objPrompt->caracteristicas;
                $this->form->setData($obj);
            }
            
            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public static function onChangeOrigemAnalise($param)
    {
        //echo '<pre>'; print_r($param); echo '</pre>';  exit;
        try {
            if (isset($param['_field_value'])) {
                if ($param['_field_value'] == 1) {
                    // GPT selecionado → copia de analise_gpt
                    TScript::create("$('#analise_proposta').val('{$param['analise_gpt']}'); ");
                } elseif ($param['_field_value'] == 2) {
                    // Gemini selecionado → copia de analise_gemini
                    TScript::create("$('#analise_proposta').val('{$param['analise_gemini']}'); ");
                }
            }

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    public static function onChangeOrigemFala($param)
    {
        //echo '<pre>'; print_r($param); echo '</pre>';  exit;
        try {
            if (isset($param['_field_value'])) {
                if ($param['_field_value'] == 1) {
                    // GPT selecionado → copia de analise_gpt
                    TScript::create("$('#fala_proposta').val('{$param['fala_gpt']}'); ");
                } elseif ($param['_field_value'] == 2) {
                    // Gemini selecionado → copia de analise_gemini
                    TScript::create("$('#fala_proposta').val('{$param['fala_gemini']}'); ");
                }
            }

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onGerarAudioMP3($param)
    {
        //echo '<pre>'; print_r($param); echo '</pre>';  return;
        $data = $this->form->getData();

        try {
            $idSessao = TSession::getValue('id');
            $data->id = !empty($idSessao) ? $idSessao : ($data->id ?? null);

            // precisa ter ID (pergunta salva)
            if (empty($data->id)) {
                throw new Exception('Salve a pergunta antes de gerar o MP3 (é necessário ter o ID).');
            }

            if (empty($data->fala_proposta)) {
                throw new Exception('Preencha o campo "Fala proposta" antes de gerar o áudio.');
            }

            $idPergunta = (int) $data->id;

            // 1) gera o binário do mp3
            $binary = OpenAITTSService::synthesizeToBinary(
                (string) $data->fala_proposta,
                'nova',
                'mp3'
            );

            // 2) diretório
            $baseDir = 'app/storage/audios';
            if (!is_dir($baseDir)) {
                if (!mkdir($baseDir, 0775, true) && !is_dir($baseDir)) {
                    throw new Exception('Não foi possível criar o diretório de áudio.');
                }
            }

            // 3) nome fixo por pergunta (overwrite)
            $filename = "pergunta_{$idPergunta}.mp3";
            $path = $baseDir . '/' . $filename;

            if (file_put_contents($path, $binary) === false) {
                throw new Exception('Falha ao gravar o arquivo de áudio no disco.');
            }

            // 4) (opcional) mostrar link + player imediatamente
            $streamUrl = "index.php?class=AudioStreamView&method=onStreamByPergunta&id_pergunta={$idPergunta}";

            $link = new THyperLink('Ouvir/abrir áudio', $streamUrl, 'blue', 14, '', '_blank');

            $audioTag = new TElement('audio');
            $audioTag->controls = 'controls';
            $audioTag->autoplay = 'autoplay';
            $audioTag->style = 'width: 100%; margin-top: 10px;';
            $audioTag->src = $streamUrl;

            $this->form->addContent([new TFormSeparator('Áudio')]);
            $this->form->addContent([$link]);
            $this->form->addContent([$audioTag]);

            new TMessage('info', "MP3 gerado/sobrescrito com sucesso: {$filename}");
            $this->form->setData($data);

        } catch (Exception $e) {
            $this->form->setData($data);
            TForm::sendData('form_pergunta', $data);
            new TMessage('error', $e->getMessage());
        }
    }

    public function onTocarAudio_($param)
    {
        try {
            $data = $this->form->getData();
            $data->id = TSession::getValue('id');

            if (empty($data->id)) {
                throw new Exception('Salve a pergunta antes de tocar o áudio.');
            }

            // URL recomendada: use o endpoint de stream (se você tiver)
            // $source = "index.php?class=AudioStreamView&method=onStreamByPergunta&id_pergunta={$data->id}";

            // Se você realmente quiser apontar direto pro arquivo (não recomendado em produção):
            $source = "app/storage/audios/pergunta_{$data->id}.mp3";

            $cards = new TCardView;
            $cards->setUseButton();

            $item = (object) [
                'id'     => 1,
                'title'  => "Áudio da Pergunta #{$data->id}",
                'source' => $source
            ];

            $cards->addItem($item);

            $cards->setTitleAttribute('title');

            // Player melhor que iframe: HTML5 audio
            $cards->setItemTemplate('
                <audio controls autoplay style="width: 100%;">
                    <source src="{source}" type="audio/mpeg">
                    Seu navegador não suporta áudio HTML5.
                </audio>
            ');

            $vbox = new TVBox;
            $vbox->style = 'width: 100%';
            $vbox->add($cards);

            // Abre em janela/modal centralizada
            $win = new TWindow('Reprodução do Áudio', 700, 220, $vbox);
            $win->setModal(true);
            $win->show();

            // Força centralização (garantia)
            TScript::create("
                setTimeout(function() {
                    if (typeof __adianti_center_window === 'function') {
                        __adianti_center_window('{$win->getId()}');
                    } else {
                        // fallback: tenta centralizar via jQuery, se existir
                        var w = $('#{$win->getId()}');
                        if (w.length) {
                            w.css({position:'fixed'})
                            .css('left', (($(window).width() - w.outerWidth())/2) + 'px')
                            .css('top',  (($(window).height() - w.outerHeight())/2) + 'px');
                        }
                    }
                }, 100);
            ");

            $this->form->setData($data);

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }



    public function onTocarAudio_executanoform($param)
    {
        try {
            $data = $this->form->getData();
            $data->id = TSession::getValue('id');

            if (empty($data->id)) {
                throw new Exception('Salve a pergunta antes de tocar o áudio.');
            }

            $cards = new TCardView;
            $cards->setUseButton();
            $items = [];
            $items[] = (object) [ 'id' => 1, 'title' => 'Melhorias do Framework 4.0', 'source' => "http://localhost/adianti-template-7.6.0/cadJEDI/app/storage/audios/pergunta_{$data->id}.mp3"];

            foreach ($items as $key => $item)
            {
                $cards->addItem($item);
            }

            $cards->setTitleAttribute('title');   
            
            $vbox = new TVBox;
            $vbox->style = 'width: 100%';
            $vbox->add($cards);

            // Abre em janela/modal centralizada
            /*$win = new TWindow('Reprodução do Áudio', 700, 220, $vbox);
            $win->setModal(true);
            $win->show();*/



            $cards->setItemTemplate('<iframe width="100%" height="300px" src="{source}""></iframe>');
            // wrap the page content using vertical box
            $vbox = new TVBox;
            $vbox->style = 'width: 100%';
            $vbox->add($cards);
            parent::add($vbox);
            $this->form->setData($data);

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onTocarAudio($param)
    {
        try {
            $data = $this->form->getData();
            $data->id = TSession::getValue('id');

            if (empty($data->id)) {
                throw new Exception('Salve a pergunta antes de tocar o áudio.');
            }

            $source = "app/storage/audios/pergunta_{$data->id}.mp3";

            $cards = new TCardView;
            $cards->setUseButton();

            $item = (object) [
                'id'     => 1,
                'title'  => "Áudio da Pergunta #{$data->id}",
                'source' => $source
            ];

            $cards->addItem($item);

            $cards->setTitleAttribute('title');

            // Player melhor que iframe: HTML5 audio
            $cards->setItemTemplate('
                <audio controls autoplay style="width: 100%;">
                    <source src="{source}" type="audio/mpeg">
                    Seu navegador não suporta áudio HTML5.
                </audio>
            ');

            $vbox = new TVBox;
            $vbox->style = 'width: 100%';
            $vbox->add($cards);
        

            // Abre em janela/modal centralizada
            $win = TWindow::create('Reprodução do Áudio', 0.3, 0.4);
            $win->setPosition(550,1200);
            $win->add($cards);
            //$win->add("<br><button type='button' id='btnVoltar' class='btn btn-primary' value='voltar'>Voltar</button>");
            $win->add("
                <div style='display:flex; justify-content:center; margin-top:25px;'>
                    <button 
                        type='button' 
                        id='btnVoltar' 
                        class='btn btn-primary'
                        style='padding: 9px 48px;
                            font-size: 20px;
                            min-width: 180px;
                            height: 45px;
                            border-radius: 10px;'
                    >
                        Voltar
                    </button>
                </div>
            ");

            $win->setModal(true);

            $win->show();
                    TScript::create("$('.ui-dialog-titlebar-close').hide();");

            $id = $win->get(0)->getId();
            TScript::create("
                $('#btnVoltar').on('click', function(){
                    $('label').css('margin-bottom', '17px');
                    $('#{$id}').remove();           
                });
            ");
            $this->form->setData($data);
            
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    private function updateBotaoTocarAudioByPergunta($idPergunta)
    {
        $baseDir = 'app/storage/audios';
        $file = $baseDir . "/pergunta_{$idPergunta}.mp3";

        if (is_file($file)) {
            // EXISTE → botão normal (verde/azul)
            $this->btnTocarAudio->style = 'background:#285e8e;color:#ffffff; width: 25%';
            $this->btnTocarAudio->setImage('fa:play green');
            $this->btnTocarAudio->setLabel(' Tocar Áudio');
            TButton::enableField('form_ficha_aluno', 'btnTocarAudio');
        } else {
            // NÃO EXISTE → botão vermelho
            $this->btnTocarAudio->style = 'background:#dc3545;color:#ffffff; width: 25%';
            $this->btnTocarAudio->setImage('fa:times red');
            $this->btnTocarAudio->setLabel(' Sem Áudio');
            $this->btnTocarAudio->enable = false; // opcional
            TButton::disableField('form_ficha_aluno', 'btnTocarAudio');
        }
    }




}
