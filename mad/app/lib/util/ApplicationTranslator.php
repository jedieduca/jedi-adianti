<?php
use Adianti\Core\AdiantiCoreTranslator;

/**
 * ApplicationTranslator
 *
 * @version    8.2
 * @package    util
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class ApplicationTranslator
{
    private static $instance; // singleton instance
    private $lang;            // target language
    private $messages;
    private $sourceMessages;
    
    /**
     * Class Constructor
     */
    private function __construct()
    {
        $this->messages = [];
        $this->messages['en'] = [];
        $this->messages['pt'] = [];
        $this->messages['es'] = [];
        
        $this->messages['en'][] = 'University';
        $this->messages['pt'][] = 'Universidade';
        $this->messages['es'][] = 'Universidad';
        
        $this->messages['en'][] = 'City';
        $this->messages['pt'][] = 'Cidade';
        $this->messages['es'][] = 'Ciudad';

        $this->messages['en'][] = 'Data Mining';
        $this->messages['pt'][] = 'Minerar Dados';
        $this->messages['es'][] = 'Minería de datos';

        $this->messages['en'][] = 'Mining Association Rules';
        $this->messages['pt'][] = 'Minerar Regras de Associação';
        $this->messages['es'][] = 'Reglamento de la Asociación Minera';

        $this->messages['en'][] = 'Data Origin';
        $this->messages['pt'][] = 'Origem dos Dados';
        $this->messages['es'][] = 'Origen de los Datos';

        $this->messages['en'][] = 'School';
        $this->messages['pt'][] = 'Escola';
        $this->messages['es'][] = 'Escuela';

        $this->messages['en'][] = 'Class';
        $this->messages['pt'][] = 'Turma';
        $this->messages['es'][] = 'Clase';

        $this->messages['en'][] = 'User';
        $this->messages['pt'][] = 'Usuário';
        $this->messages['es'][] = 'Usuario';

        $this->messages['en'][] = 'Player';
        $this->messages['pt'][] = 'Jogador';
        $this->messages['es'][] = 'Jugador';
        
        $this->messages['en'][] = 'Game Date';
        $this->messages['pt'][] = 'Data do Jogo';
        $this->messages['es'][] = 'Fecha del Juego';

        $this->messages['en'][] = 'Use of Tutoring';
        $this->messages['pt'][] = 'Uso de Tutoria';
        $this->messages['es'][] = 'Uso de la Tutoría';

        $this->messages['en'][] = 'Category';
        $this->messages['pt'][] = 'Categoria';
        $this->messages['es'][] = 'Categoría';

        $this->messages['en'][] = 'Theme';
        $this->messages['pt'][] = 'Tema';
        $this->messages['es'][] = 'Tema';

        $this->messages['en'][] = 'Number of Matches';
        $this->messages['pt'][] = 'Número de Partidas';
        $this->messages['es'][] = 'Número de Partidos';

        $this->messages['en'][] = 'Time Spent';
        $this->messages['pt'][] = 'Tempo Gasto';
        $this->messages['es'][] = 'Tiempo Dedicado';

        $this->messages['en'][] = 'Correct Answers (%)';
        $this->messages['pt'][] = 'Respostas Certas (%)';
        $this->messages['es'][] = 'Respuestas Correctas (%)';

        $this->messages['en'][] = 'Wrong Answers (%)';
        $this->messages['pt'][] = 'Respostas Erradas (%)';
        $this->messages['es'][] = 'Respuestas Incorrectas (%)';

        $this->messages['en'][] = 'Age';
        $this->messages['pt'][] = 'Idade';
        $this->messages['es'][] = 'Edad';
        
        $this->messages['en'][] = 'Critical Capacity';
        $this->messages['pt'][] = 'Capacidade Crítica';
        $this->messages['es'][] = 'Capacidad Crítica';

        $this->messages['en'][] = 'Apriori';
        $this->messages['pt'][] = 'Apriori';
        $this->messages['es'][] = 'Apriori';

        $this->messages['en'][] = 'Text search';
        $this->messages['pt'][] = 'Busca textual';
        $this->messages['es'][] = 'Búsqueda de texto';

        $this->messages['en'][] = 'Trust';
        $this->messages['pt'][] = 'Confiança';
        $this->messages['es'][] = 'Confianza';
        
        $this->messages['en'][] = 'Algorithms';
        $this->messages['pt'][] = 'Algoritmos';
        $this->messages['es'][] = 'Algoritmos';

        $this->messages['en'][] = 'Statistics';
        $this->messages['pt'][] = 'Estatísticas';
        $this->messages['es'][] = 'Estadística';

        $this->messages['en'][] = 'News Profile';
        $this->messages['pt'][] = 'Perfil das Notícias';
        $this->messages['es'][] = 'Perfil de Noticias';        

        $this->messages['en'][] = 'Distribution of News by Category';
        $this->messages['pt'][] = 'Distribuição das Notícias por Categoria';
        $this->messages['es'][] = 'Distribución de noticias por categoría';        

        $this->messages['en'][] = 'Textual Characteristics of News';
        $this->messages['pt'][] = 'Características Textuais das Notícias';
        $this->messages['es'][] = 'Características textuales de las noticias';        

        $this->messages['en'][] = 'Observed Characteristics';
        $this->messages['pt'][] = 'Características Observadas';
        $this->messages['es'][] = 'Características Observadas';        

        $this->messages['en'][] = 'Class Profiles';
        $this->messages['pt'][] = 'Perfil das Turmas';
        $this->messages['es'][] = 'Perfiles de clases';        

        $this->messages['en'][] = 'Distribution of Results by News Categories';
        $this->messages['pt'][] = 'Distribuição dos Resultados pelas Categorias de Notícias';
        $this->messages['es'][] = 'Distribución de resultados por categorías de noticias';

        $this->messages['en'][] = 'Assessment';
        $this->messages['pt'][] = 'Avaliação';
        $this->messages['es'][] = 'Evaluación';

        $this->messages['en'][] = 'Self-assessment';
        $this->messages['pt'][] = 'Autoavaliação';
        $this->messages['es'][] = 'Autoevaluación';

        $this->messages['en'][] = 'Game review';
        $this->messages['pt'][] = 'Avaliação do jogo';
        $this->messages['es'][] = 'Reseña del juego';

        $this->messages['en'][] = 'Distribution of Results by Self-Assessment Levels';
        $this->messages['pt'][] = 'Distribuição dos Resultados pelos Níveis de Autoavaliação';
        $this->messages['es'][] = 'Distribución de resultados por niveles de autoevaluación';

        $this->messages['en'][] = 'Average number of correct answers';
        $this->messages['pt'][] = 'Média de acertos';
        $this->messages['es'][] = 'Número promedio de respuestas correctas';
        
        $this->messages['en'][] = 'Error average';
        $this->messages['pt'][] = 'Média de erros';
        $this->messages['es'][] = 'Promedio de errores';

        $this->messages['en'][] = 'Student performances in the first and last matches';
        $this->messages['pt'][] = 'Desempenhos obtidos pelos alunos nas primeiras e últimas partidas';
        $this->messages['es'][] = 'Actuaciones de los estudiantes en el primer y último partido';

        $this->messages['en'][] = 'The first match';
        $this->messages['pt'][] = 'Partida inicial';
        $this->messages['es'][] = 'La primera partida ';

        $this->messages['en'][] = 'The final match';
        $this->messages['pt'][] = 'Partida final';
        $this->messages['es'][] = 'La última partida';

        $this->messages['en'][] = 'Student Performance';
        $this->messages['pt'][] = 'Desempenho dos Discentes';
        $this->messages['es'][] = 'Rendimiento estudiantil';

        $this->messages['en'][] = 'Match Summary';
        $this->messages['pt'][] = 'Resumo das Partidas';
        $this->messages['es'][] = 'Resumen del partido';

        $this->messages['en'][] = 'Class performance';
        $this->messages['pt'][] = 'Desempenho das Turmas';
        $this->messages['es'][] = 'El rendimiento de la clase';        

        $this->messages['en'][] = 'Age (Mean ± Standard Deviation)';
        $this->messages['pt'][] = 'Idade (Média ± Desvio Padrão)';
        $this->messages['es'][] = 'Edad (media ± desviación estándar)';        

        $this->messages['en'][] = 'Geographic Location';
        $this->messages['pt'][] = 'Localização Geográfica';
        $this->messages['es'][] = 'Ubicación geográfica';        

        $this->messages['en'][] = 'Number of Students';
        $this->messages['pt'][] = 'Nº de Alunos';
        $this->messages['es'][] = 'Número de estudiantes';        

        $this->messages['en'][] = 'Average';
        $this->messages['pt'][] = 'Média';
        $this->messages['es'][] = 'Promedio';        

        $this->messages['en'][] = 'Standard Deviation';
        $this->messages['pt'][] = 'Desvio Padrão';
        $this->messages['es'][] = 'Desviación estándar';        

        $this->messages['en'][] = 'Word cloud';
        $this->messages['pt'][] = 'Nuvem de palavras';
        $this->messages['es'][] = 'Nube de palabras';        

        $this->messages['en'][] = 'Filters';
        $this->messages['pt'][] = 'Filtros';
        $this->messages['es'][] = 'Filtros';

        $this->messages['en'][] = 'Area';
        $this->messages['pt'][] = 'Área';
        $this->messages['es'][] = 'Área';

        $this->messages['en'][] = 'News Classification';
        $this->messages['pt'][] = 'Classificação da Notícia';
        $this->messages['es'][] = 'Clasificación de noticias';

        $this->messages['en'][] = 'News';
        $this->messages['pt'][] = 'Notícia';
        $this->messages['es'][] = 'Noticias';

        //<entry-point>
        
        foreach ($this->messages as $lang => $messages)
        {
            $this->sourceMessages[$lang] = array_flip( $this->messages[ $lang ] );
        }
    }
    
    /**
     * Returns the singleton instance
     * @return  Instance of self
     */
    public static function getInstance()
    {
        // if there's no instance
        if (empty(self::$instance))
        {
            // creates a new object
            self::$instance = new self;
        }
        // returns the created instance
        return self::$instance;
    }
    
    /**
     * Define the target language
     * @param $lang Target language index
     */
    public static function setLanguage($lang, $global = true)
    {
        $instance = self::getInstance();
        
        if (substr( (string) $lang,0,4) == 'auto')
        {
            $parts = explode(',', $lang);
            $lang = $parts[1];
            
            if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
            {
                $autolang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
                if (in_array($autolang, array_keys($instance->messages)))
                {
                    $lang = $autolang;
                }
            }
        }
        
        if (in_array($lang, array_keys($instance->messages)))
        {
            $instance->lang = $lang;
        }
        
        if ($global)
        {
            AdiantiCoreTranslator::setLanguage( $lang );
            AdiantiTemplateTranslator::setLanguage( $lang );
        }
    }
    
    /**
     * Returns the target language
     * @return Target language index
     */
    public static function getLanguage()
    {
        $instance = self::getInstance();
        return $instance->lang;
    }
    
    /**
     * Translate a word to the target language
     * @param $word     Word to be translated
     * @return          Translated word
     */
    public static function translate($word, $source_language, $param1 = NULL, $param2 = NULL, $param3 = NULL, $param4 = NULL)
    {
        // get the self unique instance
        $instance = self::getInstance();
        // search by the numeric index of the word
        
        if (isset($instance->sourceMessages[$source_language][$word]) and !is_null($instance->sourceMessages[$source_language][$word]))
        {
            $key = $instance->sourceMessages[$source_language][$word];
            
            // get the target language
            $language = self::getLanguage();
            
            // returns the translated word
            $message = $instance->messages[$language][$key];
            
            if (isset($param1))
            {
                $message = str_replace('^1', $param1, $message);
            }
            if (isset($param2))
            {
                $message = str_replace('^2', $param2, $message);
            }
            if (isset($param3))
            {
                $message = str_replace('^3', $param3, $message);
            }
            if (isset($param4))
            {
                $message = str_replace('^4', $param4, $message);
            }
            return $message;
        }
        else
        {
            $word_template = AdiantiTemplateTranslator::translate($word, $source_language, $param1, $param2, $param3, $param4);
            
            if ($word_template)
            {
                return $word_template;
            }
            
            return 'Message not found: '. $word;
        }
    }
    
    /**
     * Translate a template file
     */
    public static function translateTemplate($template)
    {
        // search by translated words
        if(preg_match_all( '!_t\{(.*?)\}!i', $template, $match ) > 0)
        {
            foreach($match[1] as $word)
            {
                $translated = _t($word);
                $template = str_replace('_t{'.$word.'}', $translated, $template);
            }
        }
        
        if(preg_match_all( '!_tf\{(.*?), (.*?)\}!i', $template, $matches ) > 0)
        {
            foreach($matches[0] as $key => $match)
            {
                $raw        = $matches[0][$key];
                $word       = $matches[1][$key];
                $from       = $matches[2][$key];
                $translated = _tf($word, $from);
                $template = str_replace($raw, $translated, $template);
            }
        }
        return $template;
    }
}

/**
 * Facade to translate words from english
 * @param $word  Word to be translated
 * @param $param1 optional ^1
 * @param $param2 optional ^2
 * @param $param3 optional ^3
 * @return Translated word
 */
function _t($msg, $param1 = null, $param2 = null, $param3 = null)
{
    return ApplicationTranslator::translate($msg, 'en', $param1, $param2, $param3);
}

/**
 * Facade to translate words from specified language
 * @param $word  Word to be translated
 * @param $source_language  Source language
 * @param $param1 optional ^1
 * @param $param2 optional ^2
 * @param $param3 optional ^3
 * @return Translated word
 */
function _tf($msg, $source_language = 'en', $param1 = null, $param2 = null, $param3 = null)
{
    return ApplicationTranslator::translate($msg, $source_language, $param1, $param2, $param3);
}
