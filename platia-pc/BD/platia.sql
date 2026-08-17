-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10/07/2026 às 23:37
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `platia`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `componentecurricular`
--

CREATE TABLE `componentecurricular` (
  `id` int(11) NOT NULL,
  `descricao` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `componentecurricular`
--

INSERT INTO `componentecurricular` (`id`, `descricao`) VALUES
(1, 'Matemática'),
(2, 'Língua Portuguesa (Literatura)'),
(4, 'História'),
(5, 'Língua Portuguesa (Gramática e Interpretação de Texto)'),
(6, 'Língua Portuguesa (Produção de Texto)'),
(7, 'Arte'),
(8, 'Educação Física'),
(9, 'Língua Inglesa '),
(10, 'Geografia'),
(11, 'Filosofia'),
(12, 'Sociologia'),
(13, 'Biologia'),
(14, 'Química'),
(15, 'Física');

-- --------------------------------------------------------

--
-- Estrutura para tabela `escola`
--

CREATE TABLE `escola` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `numalunos` int(11) NOT NULL,
  `numprofs` int(11) NOT NULL,
  `conceitoprograma` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `idinstanciagestora` int(11) NOT NULL,
  `ismacroreferencial` int(11) NOT NULL,
  `idmunicipio` int(11) NOT NULL,
  `zonalocalizacao` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `escola`
--

INSERT INTO `escola` (`id`, `nome`, `numalunos`, `numprofs`, `conceitoprograma`, `idinstanciagestora`, `ismacroreferencial`, `idmunicipio`, `zonalocalizacao`) VALUES
(35, 'Colégio Perdro II - SC I', 0, 0, 'livre', 412, 3, 1, 'Urbana'),
(36, 'Colégio Pedro II - Unidade SC II', 0, 0, 'Livre', 0, 3, 1, 'Urbana'),
(100, 'Memore', 0, 3, 'Livre', 100, 1, 1, 'Urbana');

-- --------------------------------------------------------

--
-- Estrutura para tabela `habilidade`
--

CREATE TABLE `habilidade` (
  `id` int(11) NOT NULL,
  `descricao` varchar(1000) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `codigo` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `id_componente_curricular` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `habilidade`
--

INSERT INTO `habilidade` (`id`, `descricao`, `codigo`, `id_componente_curricular`) VALUES
(1, 'Compreender e analisar processos de produção e circulação de discursos, nas diferentes linguagens, para fazer escolhas fundamentadas em função de interesses pessoais e coletivos.', 'EM13LGG101', 0),
(2, 'Analisar visões de mundo, conflitos de interesse, preconceitos e ideologias presentes nos discursos veiculados nas diferentes mídias, ampliando suas possibilidades de explicação, interpretação e intervenção crítica da/na realidade.', 'EM13LGG102', 0),
(3, 'Analisar o funcionamento das linguagens, para interpretar e produzir criticamente discursos em textos de diversas semioses (visuais, verbais, sonoras, gestuais).', 'EM13LGG103', 0),
(4, 'Utilizar as diferentes linguagens, levando em conta seus funcionamentos, para a compreensão e produção de textos e discursos em diversos campos de atuação social.', 'EM13LGG104', 0),
(5, 'Analisar e experimentar diversos processos de remidiação de produções multissemióticas, multimídia e transmídia, desenvolvendo diferentes modos de participação e intervenção social.', 'EM13LGG105', 0),
(6, 'Utilizar as diversas linguagens (artísticas, corporais e verbais) em diferentes contextos, valorizando-as como fenômeno social, cultural, histórico, variável, heterogêneo e sensível aos contextos de uso. ', 'EM13LGG201', 0),
(7, 'Analisar interesses, relações de poder e perspectivas de mundo nos discursos das diversas práticas de linguagem (artísticas, corporais e verbais), compreendendo criticamente o modo como circulam, constituem-se e (re)produzem significação e ideologias. ', 'EM13LGG202', 0),
(8, 'Analisar os diálogos e os processos de disputa por legitimidade nas práticas de linguagem e em suas produções (artísticas, corporais e verbais). ', 'EM13LGG203', 0),
(9, 'Dialogar e produzir entendimento mútuo, nas diversas linguagens (artísticas, corporais e verbais), com vistas ao interesse comum pautado em princípios e valores de equidade assentados na democracia e nos Direitos Humanos. ', 'EM13LGG204', 0),
(10, 'Participar de processos de produção individual e colaborativa em diferentes linguagens (artísticas, corporais e verbais), levando em conta suas formas e seus funcionamentos, para produzir sentidos em diferentes contextos. ', 'EM13LGG301', 0),
(11, 'Posicionar-se criticamente diante de diversas visões de mundo presentes nos discursos em diferentes linguagens, levando em conta seus contextos de produção e de circulação. ', 'EM13LGG302', 0),
(12, 'Debater questões polêmicas de relevância social, analisando diferentes argumentos e opiniões, para formular, negociar e sustentar posições, frente à análise de perspectivas distintas. ', 'EM13LGG303', 0),
(13, 'Formular propostas, intervir e tomar decisões que levem em conta o bem comum e os Direitos Humanos, a consciência socioambiental e o consumo responsável em âmbito local, regional e global. ', 'EM13LGG304', 0),
(14, 'Mapear e criar, por meio de práticas de linguagem, possibilidades de atuação social, política, artística e cultural para enfrentar desafios contemporâneos, discutindo princípios e objetivos dessa atuação de maneira crítica, criativa, solidária e ética. ', 'EM13LGG305', 0),
(15, 'Analisar criticamente textos de modo a compreender e caracterizar as línguas como fenômeno (geo)político, histórico, social, cultural, variável, heterogêneo e sensível aos contextos de uso. ', 'EM13LGG401', 0),
(16, 'Empregar, nas interações sociais, a variedade e o estilo de língua adequados à situação comunicativa, ao(s) interlocutor(es) e ao gênero do discurso, respeitando os usos das línguas por esse(s) interlocutor(es) e sem preconceito linguístico. ', 'EM13LGG402', 0),
(17, 'Fazer uso do inglês como língua de comunicação global, levando em conta a multiplicidade e variedade de usos, usuários e funções dessa língua no mundo contemporâneo. ', 'EM13LGG403', 0),
(18, 'Selecionar e utilizar movimentos corporais de forma consciente e intencional para interagir socialmente em práticas corporais, de modo a estabelecer relações construtivas, empáticas, éticas e de respeito às diferenças. ', 'EM13LGG501', 0),
(19, 'Analisar criticamente preconceitos, estereótipos e relações de poder presentes nas práticas corporais, adotando posicionamento contrário a qualquer manifestação de injustiça e desrespeito a direitos humanos e valores democráticos. ', 'EM13LGG502', 0),
(20, 'Vivenciar práticas corporais e significá-las em seu projeto de vida, como forma de autoconhecimento, autocuidado com o corpo e com a saúde, socialização e entretenimento. ', 'EM13LGG503', 0),
(21, 'Apropriar-se do patrimônio artístico de diferentes tempos e lugares, compreendendo a sua diversidade, bem como os processos de legitimação das manifestações artísticas na sociedade, desenvolvendo visão crítica e histórica. ', 'EM13LGG601', 0),
(22, 'Fruir e apreciar esteticamente diversas manifestações artísticas e culturais, das locais às mundiais, assim como delas participar, de modo a aguçar continuamente a sensibilidade, a imaginação e a criatividade. ', 'EM13LGG602', 0),
(23, 'Expressar-se e atuar em processos de criação autorais individuais e coletivos nas diferentes linguagens artísticas (artes visuais, audiovisual, dança, música e teatro) e nas intersecções entre elas, recorrendo a referências estéticas e culturais, conhecimentos de naturezas diversas (artísticos, históricos, sociais e políticos) e experiências individuais e coletivas. ', 'EM13LGG603', 0),
(24, 'Relacionar as práticas artísticas às diferentes dimensões da vida social, cultural, política e econômica e identificar o processo de construção histórica dessas práticas. ', 'EM13LGG604', 0),
(25, 'Explorar tecnologias digitais da informação e comunicação (TDIC), compreendendo seus princípios e funcionalidades, e utilizá-las de modo ético, criativo, responsável e adequado a práticas de linguagem em diferentes contextos. ', 'EM13LGG701', 0),
(26, 'Avaliar o impacto das tecnologias digitais da informação e comunicação (TDIC) na formação do sujeito e em suas práticas sociais, para fazer uso crítico dessa mídia em práticas de seleção, compreensão e produção de discursos em ambiente digital. ', 'EM13LGG702', 0),
(27, 'Utilizar diferentes linguagens, mídias e ferramentas digitais em processos de produção coletiva, colaborativa e projetos autorais em ambientes digitais. ', 'EM13LGG703', 0),
(28, 'Apropriar-se criticamente de processos de pesquisa e busca de informação, por meio de ferramentas e dos novos formatos de produção e distribuição do conhecimento na cultura de rede. ', 'EM13LGG704', 0),
(29, 'Interpretar criticamente situações econômicas, sociais e fatos relativos às Ciências da Natureza que envolvam a variação de grandezas, pela análise dos gráficos das funções representadas e das taxas de variação, com ou sem apoio de tecnologias digitais. ', 'EM13MAT101', 0),
(30, 'Analisar tabelas, gráficos e amostras de pesquisas estatísticas apresentadas em relatórios divulgados por diferentes meios de comunicação, identificando, quando for o caso, inadequações que possam induzir a erros de interpretação, como escalas e amostras não apropriadas. ', 'EM13MAT102', 0),
(31, 'Interpretar e compreender textos científicos ou divulgados pelas mídias, que empregam unidades de medida de diferentes grandezas e as conversões possíveis entre elas, adotadas ou não pelo Sistema Internacional (SI), como as de armazenamento e velocidade de transferência de dados, ligadas aos avanços tecnológicos. ', 'EM13MAT103', 0),
(32, 'Interpretar taxas e índices de natureza socioeconômica (índice de desenvolvimento humano, taxas de inflação, entre outros), investigando os processos de cálculo desses números, para analisar criticamente a realidade e produzir argumentos. ', 'EM13MAT104', 0),
(33, 'Utilizar as noções de transformações isométricas (translação, reflexão, rotação e composições destas) e transformações homotéticas para construir figuras e analisar elementos da natureza e diferentes produções humanas (fractais, construções civis, obras de arte, entre outras).', 'EM13MAT105', 0),
(34, 'Identificar situações da vida cotidiana nas quais seja necessário fazer escolhas levando-se em conta os riscos probabilísticos (usar este ou aquele método contraceptivo, optar por um tratamento médico em detrimento de outro etc.). ', 'EM13MAT106', 0),
(35, 'Propor ou participar de ações adequadas às demandas da região, preferencialmente para sua comunidade, envolvendo medições e cálculos de perímetro, de área, de volume, de capacidade ou de massa. ', 'EM13MAT201', 0),
(36, 'Planejar e executar pesquisa amostral sobre questões relevantes, usando dados coletados diretamente ou em diferentes fontes, e comunicar os resultados por meio de relatório contendo gráficos e interpretação das medidas de tendência central e das medidas de dispersão (amplitude e desvio padrão), utilizando ou não recursos tecnológicos. ', 'EM13MAT202', 0),
(37, 'Aplicar conceitos matemáticos no planejamento, na execução e na análise de ações envolvendo a utilização de aplicativos e a criação de planilhas (para o controle de orçamento familiar, simuladores de cálculos de juros simples e compostos, entre outros), para tomar decisões. ', 'EM13MAT203', 0),
(38, 'Resolver e elaborar problemas do cotidiano, da Matemática e de outras áreas do conhecimento, que envolvem equações lineares simultâneas, usando técnicas algébricas e gráficas, com ou sem apoio de tecnologias digitais.', 'EM13MAT301', 0),
(39, 'Construir modelos empregando as funções polinomiais de 1º ou 2º graus, para resolver problemas em contextos diversos, com ou sem apoio de tecnologias digitais. ', 'EM13MAT302', 0),
(40, 'Interpretar e comparar situações que envolvam juros simples com as que envolvem juros compostos, por meio de representações gráficas ou análise de planilhas, destacando o crescimento linear ou exponencial de cada caso. ', 'EM13MAT303', 0),
(41, 'Resolver e elaborar problemas com funções exponenciais nos quais seja necessário compreender e interpretar a variação das grandezas envolvidas, em contextos como o da Matemática Financeira, entre outros. ', 'EM13MAT304', 0),
(42, 'Resolver e elaborar problemas com funções logarítmicas nos quais seja necessário compreender e interpretar a variação das grandezas envolvidas, em contextos como os de abalos sísmicos, pH, radioatividade, Matemática Financeira, entre outros. ', 'EM13MAT305', 0),
(43, 'Resolver e elaborar problemas em contextos que envolvem fenômenos periódicos reais (ondas sonoras, fases da lua, movimentos cíclicos, entre outros) e comparar suas representações com as funções seno e cosseno, no plano cartesiano, com ou sem apoio de aplicativos de álgebra e geometria. ', 'EM13MAT306', 0),
(44, 'Empregar diferentes métodos para a obtenção da medida da área de uma superfície (reconfigurações, aproximação por cortes etc.) e deduzir expressões de cálculo para aplicá-las em situações reais (como o remanejamento e a distribuição de plantações, entre outros), com ou sem apoio de tecnologias digitais. ', 'EM13MAT307', 0),
(45, 'Aplicar as relações métricas, incluindo as leis do seno e do cosseno ou as noções de congruência e semelhança, para resolver e elaborar problemas que envolvem triângulos, em variados contextos. ', 'EM13MAT308', 0),
(46, 'Resolver e elaborar problemas que envolvem o cálculo de áreas totais e de volumes de prismas, pirâmides e corpos redondos em situações reais (como o cálculo do gasto de material para revestimento ou pinturas de objetos cujos formatos sejam composições dos sólidos estudados), com ou sem apoio de tecnologias digitais. ', 'EM13MAT309', 0),
(47, 'Resolver e elaborar problemas de contagem envolvendo agrupamentos ordenáveis ou não de elementos, por meio dos princípios multiplicativo e aditivo, recorrendo a estratégias diversas, como o diagrama de árvore. ', 'EM13MAT310', 0),
(48, 'Identificar e descrever o espaço amostral de eventos aleatórios, realizando contagem das possibilidades, para resolver e elaborar problemas que envolvem o cálculo da probabilidade. ', 'EM13MAT311', 0),
(49, 'Resolver e elaborar problemas que envolvem o cálculo de probabilidade de eventos em experimentos aleatórios sucessivos. ', 'EM13MAT312', 0),
(50, 'Utilizar, quando necessário, a notação científica para expressar uma medida, compreendendo as noções de algarismos significativos e algarismos duvidosos, e reconhecendo que toda medida é inevitavelmente acompanhada de erro. ', 'EM13MAT313', 0),
(51, 'Resolver e elaborar problemas que envolvem grandezas determinadas pela razão ou pelo produto de outras (velocidade, densidade demográfica, energia elétrica etc.). ', 'EM13MAT314', 0),
(52, 'Investigar e registrar, por meio de um fluxograma, quando possível, um algoritmo que resolve um problema. ', 'EM13MAT315', 0),
(53, 'Resolver e elaborar problemas, em diferentes contextos, que envolvem cálculo e interpretação das medidas de tendência central (média, moda, mediana) e das medidas de dispersão (amplitude, variância e desvio padrão). ', 'EM13MAT316', 0),
(54, 'Converter representações algébricas de funções polinomiais de 1º grau em representações geométricas no plano cartesiano, distinguindo os casos nos quais o comportamento é proporcional, recorrendo ou não a softwares ou aplicativos de álgebra e geometria dinâmica. ', 'EM13MAT401', 0),
(55, 'Converter representações algébricas de funções polinomiais de 2º grau em representações geométricas no plano cartesiano, distinguindo os casos nos quais uma variável for diretamente proporcional ao quadrado da outra, recorrendo ou não a softwares ou aplicativos de álgebra e geometria dinâmica, entre outros materiais. ', 'EM13MAT402', 0),
(56, 'Analisar e estabelecer relações, com ou sem apoio de tecnologias digitais, entre as representações de funções exponencial e logarítmica expressas em tabelas e em plano cartesiano, para identificar as características fundamentais (domínio, imagem, crescimento) de cada função. ', 'EM13MAT403', 0),
(57, 'Analisar funções definidas por uma ou mais sentenças (tabela do Imposto de Renda, contas de luz, água, gás etc.), em suas representações algébrica e gráfica, identificando domínios de validade, imagem, crescimento e decrescimento, e convertendo essas representações de uma para outra, com ou sem apoio de tecnologias digitais. ', 'EM13MAT404', 0),
(58, 'Utilizar conceitos iniciais de uma linguagem de programação na implementação de algoritmos escritos em linguagem corrente e/ou matemática. ', 'EM13MAT405', 0),
(59, 'Construir e interpretar tabelas e gráficos de frequências com base em dados obtidos em pesquisas por amostras estatísticas, incluindo ou não o uso de softwares que inter-relacionem estatística, geometria e álgebra. ', 'EM13MAT406', 0),
(60, 'Interpretar e comparar conjuntos de dados estatísticos por meio de diferentes diagramas e gráficos (histograma, de caixa (box-plot), de ramos e folhas, entre outros), reconhecendo os mais eficientes para sua análise. ', 'EM13MAT407', 0),
(61, 'Investigar relações entre números expressos em tabelas para representá-los no plano cartesiano, identificando padrões e criando conjecturas para generalizar e expressar algebricamente essa generalização, reconhecendo quando essa representação é de função polinomial de 1º grau. ', 'EM13MAT501', 0),
(62, 'Investigar relações entre números expressos em tabelas para representá-los no plano cartesiano, identificando padrões e criando conjecturas para generalizar e expressar algebricamente essa generalização, reconhecendo quando essa representação é de função polinomial de 2º grau do tipo y = (ax).(ax). ', 'EM13MAT502', 0),
(63, 'Investigar pontos de máximo ou de mínimo de funções quadráticas em contextos envolvendo superfícies, Matemática Financeira ou Cinemática, entre outros, com apoio de tecnologias digitais. ', 'EM13MAT503', 0),
(64, 'Investigar processos de obtenção da medida do volume de prismas, pirâmides, cilindros e cones, incluindo o princípio de Cavalieri, para a obtenção das fórmulas de cálculo da medida do volume dessas figuras.', 'EM13MAT504', 0),
(65, 'Resolver problemas sobre ladrilhamento do plano, com ou sem apoio de aplicativos de geometria dinâmica, para conjecturar a respeito dos tipos ou composição de polígonos que podem ser utilizados em ladrilhamento, generalizando padrões observados. ', 'EM13MAT505', 0),
(66, 'Representar graficamente a variação da área e do perímetro de um polígono regular quando os comprimentos de seus lados variam, analisando e classificando as funções envolvidas. ', 'EM13MAT506', 0),
(67, 'Identificar e associar progressões aritméticas (PA) a funções afins de domínios discretos, para análise de propriedades, dedução de algumas fórmulas e resolução de problemas. ', 'EM13MAT507', 0),
(68, 'Identificar e associar progressões geométricas (PG) a funções exponenciais de domínios discretos, para análise de propriedades, dedução de algumas fórmulas e resolução de problemas. ', 'EM13MAT508', 0),
(69, 'Investigar a deformação de ângulos e áreas provocada pelas diferentes projeções usadas em cartografia (como a cilíndrica e a cônica), com ou sem suporte de tecnologia digital. ', 'EM13MAT509', 0),
(70, 'Investigar conjuntos de dados relativos ao comportamento de duas variáveis numéricas, usando ou não tecnologias da informação, e, quando apropriado, levar em conta a variação e utilizar uma reta para descrever a relação observada. ', 'EM13MAT510', 0),
(71, 'Reconhecer a existência de diferentes tipos de espaços amostrais, discretos ou não, e de eventos, equiprováveis ou não, e investigar implicações no cálculo de probabilidades. ', 'EM13MAT511', 0),
(72, 'Analisar e representar, com ou sem o uso de dispositivos e de aplicativos digitais específicos, as transformações e conservações em sistemas que envolvam quantidade de matéria, de energia e de movimento para realizar previsões sobre seus comportamentos em situações cotidianas e em processos produtivos que priorizem o desenvolvimento sustentável, o uso consciente dos recursos naturais e a preservação da vida em todas as suas formas. ', 'EM13CNT101', 0),
(73, 'Realizar previsões, avaliar intervenções e/ou construir protótipos de sistemas térmicos que visem à sustentabilidade, considerando sua composição e os efeitos das variáveis termodinâmicas sobre seu funcionamento, considerando também o uso de tecnologias digitais que auxiliem no cálculo de estimativas e no apoio à construção dos protótipos. ', 'EM13CNT102', 0),
(74, 'Utilizar o conhecimento sobre as radiações e suas origens para avaliar as potencialidades e os riscos de sua aplicação em equipamentos de uso cotidiano, na saúde, no ambiente, na indústria, na agricultura e na geração de energia elétrica. ', 'EM13CNT103', 0),
(75, 'Avaliar os benefícios e os riscos à saúde e ao ambiente, considerando a composição, a toxicidade e a reatividade de diferentes materiais e produtos, como também o nível de exposição a eles, posicionando-se criticamente e propondo soluções individuais e/ou coletivas para seus usos e descartes responsáveis. ', 'EM13CNT104', 0),
(76, 'Analisar os ciclos biogeoquímicos e interpretar os efeitos de fenômenos naturais e da interferência humana sobre esses ciclos, para promover ações individuais e/ ou coletivas que minimizem consequências nocivas à vida. ', 'EM13CNT105', 0),
(77, 'Avaliar, com ou sem o uso de dispositivos e aplicativos digitais, tecnologias e possíveis soluções para as demandas que envolvem a geração, o transporte, a distribuição e o consumo de energia elétrica, considerando a disponibilidade de recursos, a eficiência energética, a relação custo/benefício, as características geográficas e ambientais, a produção de resíduos e os impactos socioambientais e culturais. ', 'EM13CNT106', 0),
(78, 'Realizar previsões qualitativas e quantitativas sobre o funcionamento de geradores, motores elétricos e seus componentes, bobinas, transformadores, pilhas, baterias e dispositivos eletrônicos, com base na análise dos processos de transformação e condução de energia envolvidos – com ou sem o uso de dispositivos e aplicativos digitais –, para propor ações que visem a sustentabilidade. ', 'EM13CNT107', 0),
(79, 'Analisar e discutir modelos, teorias e leis propostos em diferentes épocas e culturas para comparar distintas explicações sobre o surgimento e a evolução da Vida, da Terra e do Universo com as teorias científicas aceitas atualmente. ', 'EM13CNT201', 0),
(80, 'Analisar as diversas formas de manifestação da vida em seus diferentes níveis de organização, bem como as condições ambientais favoráveis e os fatores limitantes a elas, com ou sem o uso de dispositivos e aplicativos digitais (como softwares de simulação e de realidade virtual, entre outros). ', 'EM13CNT202', 0),
(81, 'Avaliar e prever efeitos de intervenções nos ecossistemas, e seus impactos nos seres vivos e no corpo humano, com base nos mecanismos de manutenção da vida, nos ciclos da matéria e nas transformações e transferências de energia, utilizando representações e simulações sobre tais fatores, com ou sem o uso de dispositivos e aplicativos digitais (como softwares de simulação e de realidade virtual, entre outros). ', 'EM13CNT203', 0),
(82, 'Elaborar explicações, previsões e cálculos a respeito dos movimentos de objetos na Terra, no Sistema Solar e no Universo com base na análise das interações gravitacionais, com ou sem o uso de dispositivos e aplicativos digitais (como softwares de simulação e de realidade virtual, entre outros). ', 'EM13CNT204', 0),
(83, 'Interpretar resultados e realizar previsões sobre atividades experimentais, fenômenos naturais e processos tecnológicos, com base nas noções de probabilidade e incerteza, reconhecendo os limites explicativos das ciências. ', 'EM13CNT205', 0),
(84, 'Discutir a importância da preservação e conservação da biodiversidade, considerando parâmetros qualitativos e quantitativos, e avaliar os efeitos da ação humana e das políticas ambientais para a garantia da sustentabilidade do planeta. ', 'EM13CNT206', 0),
(85, 'Identificar, analisar e discutir vulnerabilidades vinculadas às vivências e aos desafios contemporâneos aos quais as juventudes estão expostas, considerando os aspectos físico, psicoemocional e social, a fim de desenvolver e divulgar ações de prevenção e de promoção da saúde e do bem-estar.', 'EM13CNT207', 0),
(86, 'Aplicar os princípios da evolução biológica para analisar a história humana, considerando sua origem, diversificação, dispersão pelo planeta e diferentes formas de interação com a natureza, valorizando e respeitando a diversidade étnica e cultural humana. ', 'EM13CNT208', 0),
(87, 'Analisar a evolução estelar associando-a aos modelos de origem e distribuição dos elementos químicos no Universo, compreendendo suas relações com as condições necessárias ao surgimento de sistemas solares e planetários, suas estruturas e composições e as possibilidades de existência de vida, utilizando representações e simulações, com ou sem o uso de dispositivos e aplicativos digitais (como softwares de simulação e de realidade virtual, entre outros). ', 'EM13CNT209', 0),
(88, 'Construir questões, elaborar hipóteses, previsões e estimativas, empregar instrumentos de medição e representar e interpretar modelos explicativos, dados e/ou resultados experimentais para construir, avaliar e justificar conclusões no enfrentamento de situações-problema sob uma perspectiva científica. ', 'EM13CNT301', 0),
(89, 'Comunicar, para públicos variados, em diversos contextos, resultados de análises, pesquisas e/ou experimentos, elaborando e/ou interpretando textos, gráficos, tabelas, símbolos, códigos, sistemas de classificação e equações, por meio de diferentes linguagens, mídias, tecnologias digitais de informação e comunicação (TDIC), de modo a participar e/ou promover debates em torno de temas científicos e/ou tecnológicos de relevância sociocultural e ambiental. ', 'EM13CNT302', 0),
(90, 'Interpretar textos de divulgação científica que tratem de temáticas das Ciências da Natureza, disponíveis em diferentes mídias, considerando a apresentação dos dados, tanto na forma de textos como em equações, gráficos e/ou tabelas, a consistência dos argumentos e a coerência das conclusões, visando construir estratégias de seleção de fontes confiáveis de informações. ', 'EM13CNT303', 0),
(91, 'Analisar e debater situações controversas sobre a aplicação de conhecimentos da área de Ciências da Natureza (tais como tecnologias do DNA, tratamentos com células-tronco, neurotecnologias, produção de tecnologias de defesa, estratégias de controle de pragas, entre outros), com base em argumentos consistentes, legais, éticos e responsáveis, distinguindo diferentes pontos de vista. ', 'EM13CNT304', 0),
(92, 'Investigar e discutir o uso indevido de conhecimentos das Ciências da Natureza na justificativa de processos de discriminação, segregação e privação de direitos individuais e coletivos, em diferentes contextos sociais e históricos, para promover a equidade e o respeito à diversidade. ', 'EM13CNT305', 0),
(93, 'Avaliar os riscos envolvidos em atividades cotidianas, aplicando conhecimentos das Ciências da Natureza, para justificar o uso de equipamentos e recursos, bem como comportamentos de segurança, visando à integridade física, individual e coletiva, e socioambiental, podendo fazer uso de dispositivos e aplicativos digitais que viabilizem a estruturação de simulações de tais riscos. ', 'EM13CNT306', 0),
(94, 'Analisar as propriedades dos materiais para avaliar a adequação de seu uso em diferentes aplicações (industriais, cotidianas, arquitetônicas ou tecnológicas) e/ ou propor soluções seguras e sustentáveis considerando seu contexto local e cotidiano. ', 'EM13CNT307', 0),
(95, 'Investigar e analisar o funcionamento de equipamentos elétricos e/ou eletrônicos e sistemas de automação para compreender as tecnologias contemporâneas e avaliar seus impactos sociais, culturais e ambientais.', 'EM13CNT308', 0),
(96, 'Analisar questões socioambientais, políticas e econômicas relativas à dependência do mundo atual em relação aos recursos não renováveis e discutir a necessidade de introdução de alternativas e novas tecnologias energéticas e de materiais, comparando diferentes tipos de motores e processos de produção de novos materiais. ', 'EM13CNT309', 0),
(97, 'Investigar e analisar os efeitos de programas de infraestrutura e demais serviços básicos (saneamento, energia elétrica, transporte, telecomunicações, cobertura vacinal, atendimento primário à saúde e produção de alimentos, entre outros) e identificar necessidades locais e/ou regionais em relação a esses serviços, a fim de avaliar e/ou promover ações que contribuam para a melhoria na qualidade de vida e nas condições de saúde da população. ', 'EM13CNT310', 0),
(98, 'Identificar, analisar e comparar diferentes fontes e narrativas expressas em diversas linguagens, com vistas à compreensão de ideias filosóficas e de processos e eventos históricos, geográficos, políticos, econômicos, sociais, ambientais e culturais. ', 'EM13CHS101', 0),
(99, 'Identificar, analisar e discutir as circunstâncias históricas, geográficas, políticas, econômicas, sociais, ambientais e culturais de matrizes conceituais (etnocentrismo, racismo, evolução, modernidade, cooperativismo/desenvolvimento etc.), avaliando criticamente seu significado histórico e comparando-as a narrativas que contemplem outros agentes e discursos. ', 'EM13CHS102', 0),
(100, 'Elaborar hipóteses, selecionar evidências e compor argumentos relativos a processos políticos, econômicos, sociais, ambientais, culturais e epistemológicos, com base na sistematização de dados e informações de diversas naturezas (expressões artísticas, textos filosóficos e sociológicos, documentos históricos e geográficos, gráficos, mapas, tabelas, tradições orais, entre outros).', 'EM13CHS103', 0),
(101, 'Analisar objetos e vestígios da cultura material e imaterial de modo a identificar conhecimentos, valores, crenças e práticas que caracterizam a identidade e a diversidade cultural de diferentes sociedades inseridas no tempo e no espaço. ', 'EM13CHS104', 0),
(102, 'Identificar, contextualizar e criticar tipologias evolutivas (populações nômades e sedentárias, entre outras) e oposições dicotômicas (cidade/campo, cultura/ natureza, civilizados/bárbaros, razão/emoção, material/virtual etc.), explicitando suas ambiguidades. ', 'EM13CHS105', 0),
(103, 'Utilizar as linguagens cartográfica, gráfica e iconográfica, diferentes gêneros textuais e tecnologias digitais de informação e comunicação de forma crítica, significativa, reflexiva e ética nas diversas práticas sociais, incluindo as escolares, para se comunicar, acessar e difundir informações, produzir conhecimentos, resolver problemas e exercer protagonismo e autoria na vida pessoal e coletiva. ', 'EM13CHS106', 0),
(104, 'Analisar e caracterizar as dinâmicas das populações, das mercadorias e do capital nos diversos continentes, com destaque para a mobilidade e a fixação de pessoas, grupos humanos e povos, em função de eventos naturais, políticos, econômicos, sociais, religiosos e culturais, de modo a compreender e posicionar-se criticamente em relação a esses processos e às possíveis relações entre eles. ', 'EM13CHS201', 0),
(105, 'Analisar e avaliar os impactos das tecnologias na estruturação e nas dinâmicas de grupos, povos e sociedades contemporâneos (fluxos populacionais, financeiros, de mercadorias, de informações, de valores éticos e culturais etc.), bem como suas interferências nas decisões políticas, sociais, ambientais, econômicas e culturais. ', 'EM13CHS202', 0),
(106, 'Comparar os significados de território, fronteiras e vazio (espacial, temporal e cultural) em diferentes sociedades, contextualizando e relativizando visões dualistas (civilização/barbárie, nomadismo/sedentarismo, esclarecimento/obscurantismo, cidade/campo, entre outras). ', 'EM13CHS203', 0),
(107, 'Comparar e avaliar os processos de ocupação do espaço e a formação de territórios, territorialidades e fronteiras, identificando o papel de diferentes agentes (como grupos sociais e culturais, impérios, Estados Nacionais e organismos internacionais) e considerando os conflitos populacionais (internos e externos), a diversidade étnico-cultural e as características socioeconômicas, políticas e tecnológicas. ', 'EM13CHS204', 0),
(108, 'Analisar a produção de diferentes territorialidades em suas dimensões culturais, econômicas, ambientais, políticas e sociais, no Brasil e no mundo contemporâneo, com destaque para as culturas juvenis. ', 'EM13CHS205', 0),
(109, 'Analisar a ocupação humana e a produção do espaço em diferentes tempos, aplicando os princípios de localização, distribuição, ordem, extensão, conexão, arranjos, casualidade, entre outros que contribuem para o raciocínio geográfico. ', 'EM13CHS206', 0),
(110, 'Problematizar hábitos e práticas individuais e coletivos de produção, reaproveitamento e descarte de resíduos em metrópoles, áreas urbanas e rurais, e comunidades com diferentes características socioeconômicas, e elaborar e/ou selecionar propostas de ação que promovam a sustentabilidade socioambiental, o combate à poluição sistêmica e o consumo responsável. ', 'EM13CHS301', 0),
(111, 'Analisar e avaliar criticamente os impactos econômicos e socioambientais de cadeias produtivas ligadas à exploração de recursos naturais e às atividades agropecuárias em diferentes ambientes e escalas de análise, considerando o modo de vida das populações locais – entre elas as indígenas, quilombolas e demais comunidades tradicionais –, suas práticas agroextrativistas e o compromisso com a sustentabilidade. ', 'EM13CHS302', 0),
(112, 'Debater e avaliar o papel da indústria cultural e das culturas de massa no estímulo ao consumismo, seus impactos econômicos e socioambientais, com vistas à percepção crítica das necessidades criadas pelo consumo e à adoção de hábitos sustentáveis. ', 'EM13CHS303', 0),
(113, 'Analisar os impactos socioambientais decorrentes de práticas de instituições governamentais, de empresas e de indivíduos, discutindo as origens dessas práticas, selecionando, incorporando e promovendo aquelas que favoreçam a consciência e a ética socioambiental e o consumo responsável. ', 'EM13CHS304', 0),
(114, 'Analisar e discutir o papel e as competências legais dos organismos nacionais e internacionais de regulação, controle e fiscalização ambiental e dos acordos internacionais para a promoção e a garantia de práticas ambientais sustentáveis. ', 'EM13CHS305', 0),
(115, 'Contextualizar, comparar e avaliar os impactos de diferentes modelos socioeconômicos no uso dos recursos naturais e na promoção da sustentabilidade econômica e socioambiental do planeta (como a adoção dos sistemas da agrobiodiversidade e agroflorestal por diferentes comunidades, entre outros). ', 'EM13CHS306', 0),
(116, 'Identificar e analisar as relações entre sujeitos, grupos, classes sociais e sociedades com culturas distintas diante das transformações técnicas, tecnológicas e informacionais e das novas formas de trabalho ao longo do tempo, em diferentes espaços (urbanos e rurais) e contextos. ', 'EM13CHS401', 0),
(117, 'Analisar e comparar indicadores de emprego, trabalho e renda em diferentes espaços, escalas e tempos, associando-os a processos de estratificação e desigualdade socioeconômica. ', 'EM13CHS402', 0),
(118, 'Caracterizar e analisar os impactos das transformações tecnológicas nas relações sociais e de trabalho próprias da contemporaneidade, promovendo ações voltadas à superação das desigualdades sociais, da opressão e da violação dos Direitos Humanos. ', 'EM13CHS403', 0),
(119, 'Identificar e discutir os múltiplos aspectos do trabalho em diferentes circunstâncias e contextos históricos e/ou geográficos e seus efeitos sobre as gerações, em especial, os jovens, levando em consideração, na atualidade, as transformações técnicas, tecnológicas e informacionais. ', 'EM13CHS404', 0),
(120, 'Analisar os fundamentos da ética em diferentes culturas, tempos e espaços, identificando processos que contribuem para a formação de sujeitos éticos que valorizem a liberdade, a cooperação, a autonomia, o empreendedorismo, a convivência democrática e a solidariedade. ', 'EM13CHS501', 0),
(121, 'Analisar situações da vida cotidiana, estilos de vida, valores, condutas etc., desnaturalizando e problematizando formas de desigualdade, preconceito, intolerância e discriminação, e identificar ações que promovam os Direitos Humanos, a solidariedade e o respeito às diferenças e às liberdades individuais. ', 'EM13CHS502', 0),
(122, 'Identificar diversas formas de violência (física, simbólica, psicológica etc.), suas principais vítimas, suas causas sociais, psicológicas e afetivas, seus significados e usos políticos, sociais e culturais, discutindo e avaliando mecanismos para combatê-las, com base em argumentos éticos. ', 'EM13CHS503', 0),
(123, 'Analisar e avaliar os impasses ético-políticos decorrentes das transformações culturais, sociais, históricas, científicas e tecnológicas no mundo contemporâneo e seus desdobramentos nas atitudes e nos valores de indivíduos, grupos sociais, sociedades e culturas. ', 'EM13CHS504', 0),
(124, 'Identificar e analisar as demandas e os protagonismos políticos, sociais e culturais dos povos indígenas e das populações afrodescendentes (incluindo as quilombolas) no Brasil contemporâneo considerando a história das Américas e o contexto de exclusão e inclusão precária desses grupos na ordem social e econômica atual, promovendo ações para a redução das desigualdades étnico-raciais no país. ', 'EM13CHS601', 0),
(125, 'Identificar e caracterizar a presença do paternalismo, do autoritarismo e do populismo na política, na sociedade e nas culturas brasileira e latino-americana, em períodos ditatoriais e democráticos, relacionando-os com as formas de organização e de articulação das sociedades em defesa da autonomia, da liberdade, do diálogo e da promoção da democracia, da cidadania e dos direitos humanos na sociedade atual. ', 'EM13CHS602', 0),
(126, 'Analisar a formação de diferentes países, povos e nações e de suas experiências políticas e de exercício da cidadania, aplicando conceitos políticos básicos (Estado, poder, formas, sistemas e regimes de governo, soberania etc.). ', 'EM13CHS603', 0),
(127, 'Discutir o papel dos organismos internacionais no contexto mundial, com vistas à elaboração de uma visão crítica sobre seus limites e suas formas de atuação nos países, considerando os aspectos positivos e negativos dessa atuação para as populações locais. ', 'EM13CHS604', 0),
(128, 'Analisar os princípios da declaração dos Direitos Humanos, recorrendo às noções de justiça, igualdade e fraternidade, identificar os progressos e entraves à concretização desses direitos nas diversas sociedades contemporâneas e promover ações concretas diante da desigualdade e das violações desses direitos em diferentes espaços de vivência, respeitando a identidade de cada grupo e de cada indivíduo. ', 'EM13CHS605', 0),
(129, 'Analisar as características socioeconômicas da sociedade brasileira – com base na análise de documentos (dados, tabelas, mapas etc.) de diferentes fontes – e propor medidas para enfrentar os problemas identificados e construir uma sociedade mais próspera, justa e inclusiva, que valorize o protagonismo de seus cidadãos e promova o autoconhecimento, a autoestima, a autoconfiança e a empatia. ', 'EM13CHS606', 0),
(130, 'Relacionar o texto, tanto na produção como na leitura/ escuta, com suas condições de produção e seu contexto sócio-histórico de circulação (leitor/audiência previstos, objetivos, pontos de vista e perspectivas, papel social do autor, época, gênero do discurso etc.), de forma a ampliar as possibilidades de construção de sentidos e de análise crítica e produzir textos adequados a diferentes situações. ', 'EM13LP01', 0),
(131, 'Estabelecer relações entre as partes do texto, tanto na produção como na leitura/escuta, considerando a construção composicional e o estilo do gênero, usando/reconhecendo adequadamente elementos e recursos coesivos diversos que contribuam para a coerência, a continuidade do texto e sua progressão temática, e organizando informações, tendo em vista as condições de produção e as relações lógico-discursivas envolvidas (causa/efeito ou consequência; tese/argumentos; problema/solução; definição/exemplos etc.).', 'EM13LP02', 0),
(132, 'Analisar relações de intertextualidade e interdiscursividade que permitam a explicitação de relações dialógicas, a identificação de posicionamentos ou de perspectivas, a compreensão de paráfrases, paródias e estilizações, entre outras possibilidades. ', 'EM13LP03', 0),
(133, 'Estabelecer relações de interdiscursividade e intertextualidade para explicitar, sustentar e conferir consistência a posicionamentos e para construir e corroborar explicações e relatos, fazendo uso de citações e paráfrases devidamente marcadas. ', 'EM13LP04', 0),
(134, 'Analisar, em textos argumentativos, os posicionamentos assumidos, os movimentos argumentativos (sustentação, refutação/ contra-argumentação e negociação) e os argumentos utilizados para sustentá-los, para avaliar sua força e eficácia, e posicionar-se criticamente diante da questão discutida e/ou dos argumentos utilizados, recorrendo aos mecanismos linguísticos necessários. ', 'EM13LP05', 0),
(135, 'Analisar efeitos de sentido decorrentes de usos expressivos da linguagem, da escolha de determinadas palavras ou expressões e da ordenação, combinação e contraposição de palavras, dentre outros, para ampliar as possibilidades de construção de sentidos e de uso crítico da língua. ', 'EM13LP06', 0),
(136, 'Analisar, em textos de diferentes gêneros, marcas que expressam a posição do enunciador frente àquilo que é dito: uso de diferentes modalidades (epistêmica, deôntica e apreciativa) e de diferentes recursos gramaticais que operam como modalizadores (verbos modais, tempos e modos verbais, expressões modais, adjetivos, locuções ou orações adjetivas, advérbios, locuções ou orações adverbiais, entonação etc.), uso de estratégias de impessoalização (uso de terceira pessoa e de voz passiva etc.), com  vistas ao incremento da compreensão e da criticidade e ao manejo adequado desses elementos nos textos produzidos, considerando os contextos de produção.', 'EM13LP07', 0),
(137, 'Analisar elementos e aspectos da sintaxe do português, como a ordem dos constituintes da sentença (e os efeito que causam sua inversão), a estrutura dos sintagmas, as categorias sintáticas, os processos de coordenação e subordinação (e os efeitos de seus usos) e a sintaxe de concordância e de regência, de modo a potencializar os processos de compreensão e produção de textos e a possibilitar escolhas adequadas à situação comunicativa. ', 'EM13LP08', 0),
(138, 'Comparar o tratamento dado pela gramática tradicional e pelas gramáticas de uso contemporâneas em relação a diferentes tópicos gramaticais, de forma a perceber as diferenças de abordagem e o fenômeno da variação linguística e analisar motivações que levam ao predomínio do ensino da norma-padrão na escola. ', 'EM13LP09', 0),
(139, 'Analisar o fenômeno da variação linguística, em seus diferentes níveis (variações fonético-fonológica, lexical, sintática, semântica e estilístico-pragmática) e em suas diferentes dimensões (regional, histórica, social, situacional, ocupacional, etária etc.), de forma a ampliar a compreensão sobre a natureza viva e dinâmica da língua e sobre o fenômeno da constituição de variedades linguísticas de prestígio e estigmatizadas, e a fundamentar o respeito às variedades linguísticas e o combate a pre', 'EM13LP10', 0),
(140, 'Fazer curadoria de informação, tendo em vista diferentes propósitos e projetos discursivos. ', 'EM13LP11', 0),
(141, 'Selecionar informações, dados e argumentos em fontes confiáveis, impressas e digitais, e utilizá-los de forma referenciada, para que o texto a ser produzido tenha um nível de aprofundamento adequado (para além do senso comum) e contemple a sustentação das posições defendidas. ', 'EM13LP12', 0),
(142, 'Analisar, a partir de referências contextuais, estéticas e culturais, efeitos de sentido decorrentes de escolhas de elementos sonoros (volume, timbre, intensidade, pausas, ritmo, efeitos sonoros, sincronização etc.) e de suas relações com o verbal, levando-os em conta na produção de áudios, para ampliar as possibilidades de construção de sentidos e de apreciação. ', 'EM13LP13', 0),
(143, 'Analisar, a partir de referências contextuais, estéticas e culturais, efeitos de sentido decorrentes de escolhas e composição das imagens (enquadramento, ângulo/vetor, foco/profundidade de campo, iluminação, cor, linhas, formas etc.) e de sua sequenciação (disposição e transição, movimentos de câmera, remix, entre outros), das performances (movimentos do corpo, gestos, ocupação do espaço cênico), dos elementos sonoros (entonação, trilha sonora, sampleamento etc.) e das relações desses elementos com o verbal, levando em conta esses efeitos nas produções de imagens e vídeos, para ampliar as possibilidades de construção de  sentidos e de apreciação.', 'EM13LP14', 0),
(144, 'Planejar, produzir, revisar, editar, reescrever e avaliar textos escritos e multissemióticos, considerando sua adequação às condições de produção do texto, no que diz respeito ao lugar social a ser assumido e à imagem que se pretende passar a respeito de si mesmo, ao leitor pretendido, ao veículo e mídia em que o texto ou produção cultural vai circular, ao contexto imediato e sócio-histórico mais geral, ao gênero textual em questão e suas regularidades, à variedade linguística apropriada a esse ', 'EM13LP15', 0),
(145, 'Produzir e analisar textos orais, considerando sua adequação aos contextos de produção, à forma composicional e ao estilo do gênero em questão, à clareza, à progressão temática e à variedade linguística empregada, como também aos elementos relacionados à fala (modulação de voz, entonação, ritmo, altura e intensidade, respiração etc.) e à cinestesia (postura corporal, movimentos e gestualidade significativa, expressão facial, contato de olho com plateia etc.). ', 'EM13LP16', 0),
(146, 'Elaborar roteiros para a produção de vídeos variados (vlog, videoclipe, videominuto, documentário etc.), apresentações teatrais, narrativas multimídia e transmídia, podcasts, playlists comentadas etc., para ampliar as possibilidades de produção de sentidos e engajar-se em práticas autorais e coletivas. ', 'EM13LP17', 0),
(147, 'Utilizar softwares de edição de textos, fotos, vídeos e áudio, além de ferramentas e ambientes colaborativos para criar textos e produções multissemióticas com finalidades diversas, explorando os recursos e efeitos disponíveis e apropriando-se de práticas colaborativas de escrita, de construção coletiva do conhecimento e de desenvolvimento de projetos. ', 'EM13LP18', 0),
(148, 'Apresentar-se por meio de textos multimodais diversos (perfis variados, gifs biográficos, biodata, currículo web, videocurrículo etc.) e de ferramentas digitais (ferramenta de gif, wiki, site etc.), para falar de si mesmo de formas variadas, considerando diferentes situações e objetivos.', 'EM13LP19', 0),
(149, 'Compartilhar gostos, interesses, práticas culturais, temas/ problemas/questões que despertam maior interesse ou preocupação, respeitando e valorizando diferenças, como forma de identificar afinidades e interesses comuns, como também de organizar e/ou participar de grupos, clubes, oficinas e afins.', 'EM13LP20', 0),
(150, 'Produzir, de forma colaborativa, e socializar playlists comentadas de preferências culturais e de entretenimento, revistas culturais, fanzines, e-zines ou publicações afins que divulguem, comentem e avaliem músicas, games, séries, filmes, quadrinhos, livros, peças, exposições, espetáculos de dança etc., de forma a compartilhar gostos, identificar afinidades, fomentar comunidades etc.', 'EM13LP21', 0),
(151, 'Construir e/ou atualizar, de forma colaborativa, registros dinâmicos (mapas, wiki etc.) de profissões e ocupações de seu interesse (áreas de atuação, dados sobre formação, fazeres, produções, depoimentos de profissionais etc.) que possibilitem vislumbrar trajetórias pessoais e profissionais.', 'EM13LP22', 0),
(152, 'Analisar criticamente o histórico e o discurso político de candidatos, propagandas políticas, políticas públicas, programas e propostas de governo, de forma a participar do debate político e tomar decisões conscientes e fundamentadas.', 'EM13LP23', 0),
(153, 'Analisar formas não institucionalizadas de participação social, sobretudo as vinculadas a manifestações artísticas, produções culturais, intervenções urbanas e formas de expressão típica das culturas juvenis que pretendam expor uma problemática ou promover uma reflexão/ação, posicionando-se em relação a essas produções e manifestações.', 'EM13LP24', 0),
(154, 'Participar de reuniões na escola (conselho de escola e de classe, grêmio livre etc.), agremiações, coletivos ou movimentos, entre outros, em debates, assembleias, fóruns de discussão etc., exercitando a escuta atenta, respeitando seu turno e tempo de fala, posicionando-se de forma fundamentada, respeitosa e ética diante da apresentação de propostas e defesas de opiniões, usando estratégias linguísticas típicas de negociação e de apoio e/ou de consideração do discurso do outro (como solicitar esc', 'EM13LP25', 0),
(155, 'Relacionar textos e documentos legais e normativos de âmbito universal, nacional, local ou escolar que envolvam a definição de direitos e deveres – em especial, os voltados a adolescentes e jovens – aos seus contextos de produção, identificando ou inferindo possíveis motivações e finalidades, como forma de ampliar a compreensão desses direitos e deveres.', 'EM13LP26', 0),
(156, 'Engajar-se na busca de solução para problemas que envolvam a coletividade, denunciando o desrespeito a direitos, organizando e/ou participando de discussões, campanhas e debates, produzindo textos reivindicatórios, normativos, entre outras possibilidades, como forma de fomentar os princípios democráticos e uma atuação pautada pela ética da responsabilidade, pelo consumo consciente e pela consciência socioambiental.', 'EM13LP27', 0),
(157, 'Organizar situações de estudo e utilizar procedimentos e estratégias de leitura adequados aos objetivos e à natureza do conhecimento em questão.', 'EM13LP28', 0),
(158, 'Resumir e resenhar textos, por meio do uso de paráfrases, de marcas do discurso reportado e de citações, para uso em textos de divulgação de estudos e pesquisas.', 'EM13LP29', 0),
(159, 'Realizar pesquisas de diferentes tipos (bibliográfica, de campo, experimento científico, levantamento de dados etc.), usando fontes abertas e confiáveis, registrando o processo e comunicando os resultados, tendo em vista os objetivos pretendidos e demais elementos do contexto de produção, como forma de compreender como o conhecimento científico é produzido e apropriar-se dos procedimentos e dos gêneros textuais envolvidos na realização de pesquisas.', 'EM13LP30', 0),
(160, 'Compreender criticamente textos de divulgação científica orais, escritos e multissemióticos de diferentes áreas do conhecimento, identificando sua organização tópica e a hierarquização das informações, identificando e descartando fontes não confiáveis e problematizando enfoques tendenciosos ou superficiais.', 'EM13LP31', 0),
(161, 'Selecionar informações e dados necessários para uma dada pesquisa (sem excedê-los) em diferentes fontes (orais, impressas, digitais etc.) e comparar autonomamente esses conteúdos, levando em conta seus contextos de produção, referências e índices de confiabilidade, e percebendo coincidências, complementaridades, contradições, erros ou imprecisões conceituais e de dados, de forma a compreender e posicionar-se criticamente sobre esses conteúdos e estabelecer recortes precisos.', 'EM13LP32', 0),
(162, 'Selecionar, elaborar e utilizar instrumentos de coleta de dados e informações (questionários, enquetes, mapeamentos, opinários) e de tratamento e análise dos conteúdos obtidos, que atendam adequadamente a diferentes objetivos de pesquisa.', 'EM13LP33', 0);
INSERT INTO `habilidade` (`id`, `descricao`, `codigo`, `id_componente_curricular`) VALUES
(163, 'Produzir textos para a divulgação do conhecimento e de resultados de levantamentos e pesquisas – texto monográfico, ensaio, artigo de divulgação científica, verbete de enciclopédia (colaborativa ou não), infográfico (estático ou animado), relato de experimento, relatório, relatório multimidiático de campo, reportagem científica, podcast ou vlog científico, apresentações orais, seminários, comunicações em mesas redondas, mapas dinâmicos etc. –, considerando o contexto de produção e utilizando os ', 'EM13LP34', 0),
(164, 'Utilizar adequadamente ferramentas de apoio a apresentações orais, escolhendo e usando tipos e tamanhos de fontes que permitam boa visualização, topicalizando e/ou organizando o conteúdo em itens, inserindo de forma adequada imagens, gráficos, tabelas, formas e elementos gráficos, dimensionando a quantidade de texto e imagem por slide e usando, de forma harmônica, recursos (efeitos de transição, slides mestres, layouts personalizados, gravação de áudios em slides etc.).', 'EM13LP35', 0),
(165, 'Analisar os interesses que movem o campo jornalístico, os impactos das novas tecnologias digitais de informação e comunicação e da Web 2.0 no campo e as condições que fazem da informação uma mercadoria e da checagem de informação uma prática (e um serviço) essencial, adotando atitude analítica e crítica diante dos textos jornalísticos.', 'EM13LP36', 0),
(166, 'Conhecer e analisar diferentes projetos editorias – institucionais, privados, públicos, financiados, independentes etc. –, de forma a ampliar o repertório de escolhas possíveis de fontes de informação e opinião, reconhecendo o papel da mídia plural para a consolidação da democracia.', 'EM13LP37', 0),
(167, 'Analisar os diferentes graus de parcialidade/imparcialidade (no limite, a não neutralidade) em textos noticiosos, comparando relatos de diferentes fontes e analisando o recorte feito de fatos/dados e os efeitos de sentido provocados pelas escolhas realizadas pelo autor do texto, de forma a manter uma atitude crítica diante dos textos jornalísticos e tornar-se consciente das escolhas feitas como produtor.', 'EM13LP38', 0),
(168, 'Usar procedimentos de checagem de fatos noticiados e fotos publicadas (verificar/avaliar veículo, fonte, data e local da publicação, autoria, URL, formatação; comparar diferentes fontes; consultar ferramentas e sites checadores etc.), de forma a combater a proliferação de notícias falsas (fake news).', 'EM13LP39', 0),
(169, 'Analisar o fenômeno da pós-verdade – discutindo as condições e os mecanismos de disseminação de fake news e também exemplos, causas e consequências desse fenômeno e da prevalência de crenças e opiniões sobre fatos -, de forma a adotar atitude crítica em relação ao fenômeno e desenvolver uma postura flexível que permita rever crenças e opiniões quando fatos apurados as contradisserem.', 'EM13LP40', 0),
(170, 'Analisar os processos humanos e automáticos de curadoria que operam nas redes sociais e outros domínios da internet, comparando os feeds de diferentes páginas de redes sociais e discutindo os efeitos desses modelos de curadoria, de forma a ampliar as possibilidades de trato com o diferente e minimizar o efeito bolha e a manipulação de terceiros.', 'EM13LP41', 0),
(171, 'Acompanhar, analisar e discutir a cobertura da mídia diante de acontecimentos e questões de relevância social, local e global, comparando diferentes enfoques e perspectivas, por meio do uso de ferramentas de curadoria (como agregadores de conteúdo) e da consulta a serviços e fontes de checagem e curadoria de informação, de forma a aprofundar o entendimento sobre um determinado fato ou questão, identificar o enfoque preponderante da mídia e manter-se implicado, de forma crítica, com os fatos e as', 'EM13LP42', 0),
(172, 'Atuar de forma fundamentada, ética e crítica na produção e no compartilhamento de comentários, textos noticiosos e de opinião, memes, gifs, remixes variados etc. em redes sociais ou outros ambientes digitais.', 'EM13LP43', 0),
(173, 'Analisar formas contemporâneas de publicidade em contexto digital (advergame, anúncios em vídeos, social advertising, unboxing, narrativa mercadológica, entre outras), e peças de campanhas publicitárias e políticas (cartazes, folhetos, anúncios, propagandas em diferentes mídias, spots, jingles etc.), identificando valores e representações de situações, grupos e configurações sociais veiculadas, desconstruindo estereótipos, destacando estratégias de engajamento e viralização e explicando os mecan', 'EM13LP44', 0),
(174, 'Analisar, discutir, produzir e socializar, tendo em vista temas e acontecimentos de interesse local ou global, notícias, fotodenúncias, fotorreportagens, reportagens multimidiáticas, documentários, infográficos, podcasts noticiosos, artigos de opinião, críticas da mídia, vlogs de opinião, textos de apresentação e apreciação de produções culturais (resenhas, ensaios etc.) e outros gêneros próprios das formas de expressão das culturas juvenis (vlogs e podcasts culturais, gameplay etc.), em várias ', 'EM13LP45', 0),
(175, 'Compartilhar sentidos construídos na leitura/escuta de textos literários, percebendo diferenças e eventuais tensões entre as formas pessoais e as coletivas de apreensão desses textos, para exercitar o diálogo cultural e aguçar a perspectiva crítica.', 'EM13LP46', 0),
(176, 'Participar de eventos (saraus, competições orais, audições, mostras, festivais, feiras culturais e literárias, rodas e clubes de leitura, cooperativas culturais, jograis, repentes, slams etc.), inclusive para socializar obras da própria autoria (poemas, contos e suas variedades, roteiros e microrroteiros, videominutos, playlists comentadas de música etc.) e/ou interpretar obras de outros, inserindo-se nas diferentes práticas culturais de seu tempo.', 'EM13LP47', 0),
(177, 'Identificar assimilações, rupturas e permanências no processo de constituição da literatura brasileira e ao longo de sua trajetória, por meio da leitura e análise de obras fundamentais do cânone ocidental, em especial da literatura portuguesa, para perceber a historicidade de matrizes e procedimentos estéticos.', 'EM13LP48', 0),
(178, 'Perceber as peculiaridades estruturais e estilísticas de diferentes gêneros literários (a apreensão pessoal do cotidiano nas crônicas, a manifestação livre e subjetiva do eu lírico diante do mundo nos poemas, a múltipla perspectiva da vida humana e social dos romances, a dimensão política e social de textos da literatura marginal e da periferia etc.) para experimentar os diferentes ângulos de apreensão do indivíduo e do mundo pela literatura.', 'EM13LP49', 0),
(179, 'Analisar relações intertextuais e interdiscursivas entre obras de diferentes autores e gêneros literários de um mesmo momento histórico e de momentos históricos diversos, explorando os modos como a literatura e as artes em geral se constituem, dialogam e se retroalimentam.', 'EM13LP50', 0),
(180, 'Selecionar obras do repertório artístico-literário contemporâneo à disposição segundo suas predileções, de modo a constituir um acervo pessoal e dele se apropriar para se inserir e intervir com autonomia e criticidade no meio cultural.', 'EM13LP51', 0),
(181, 'Analisar obras significativas das literaturas brasileiras e de outros países e povos, em especial a portuguesa, a indígena, a africana e a latino-americana, com base em ferramentas da crítica literária (estrutura da composição, estilo, aspectos discursivos) ou outros critérios relacionados a diferentes matrizes culturais, considerando o contexto de produção (visões de mundo, diálogos com outros textos, inserções em movimentos estéticos e culturais etc.) e o modo como dialogam com o presente.', 'EM13LP52', 0),
(182, 'Produzir apresentações e comentários apreciativos e críticos sobre livros, filmes, discos, canções, espetáculos de teatro e dança, exposições etc. (resenhas, vlogs e podcasts literários e artísticos, playlists comentadas, fanzines, e-zines etc.).', 'EM13LP53', 0),
(183, 'Criar obras autorais, em diferentes gêneros e mídias – mediante seleção e apropriação de recursos textuais e expressivos do repertório artístico –, e/ou produções derivadas (paródias, estilizações, fanfics, fanclipes etc.), como forma de dialogar crítica e/ou subjetivamente com o texto literário.', 'EM13LP54', 0),
(184, 'Identificar as diferentes linguagens e seus recursos expressivos como elementos de caracterização dos sistemas de comunicação.', 'H1', 0),
(185, 'Recorrer aos conhecimentos sobre as linguagens dos sistemas de comunicação e informação para resolver problemas sociais.', 'H2', 0),
(186, 'Relacionar informações geradas nos sistemas de comunicação e informação, considerando a função social desses sistemas.', 'H3', 0),
(187, 'Reconhecer posições críticas aos usos sociais que são feitos das linguagens e dos sistemas de comunicação e informação.', 'H4', 0),
(188, 'Associar vocábulos e expressões de um texto em LEM ao seu tema.', 'H5', 0),
(189, 'Utilizar os conhecimentos da LEM e de seus mecanismos como meio de ampliar as possibilidades de acesso a informações, tecnologias e culturas.', 'H6', 0),
(190, 'Relacionar um texto em LEM, as estruturas linguísticas, sua função e seu uso social.', 'H7', 0),
(191, ' Reconhecer a importância da produção cultural em LEM como representação da diversidade cultural e linguística.', 'H8', 0),
(192, 'Reconhecer as manifestações corporais de movimento como originárias de necessidades cotidianas de um grupo social.', 'H9', 0),
(193, 'Reconhecer a necessidade de transformação de hábitos corporais em função das\r\nnecessidades cinestésicas.', 'H10', 0),
(194, 'Reconhecer a linguagem corporal como meio de interação social, considerando os limites de desempenho e as alternativas de adaptação para diferentes indivíduos.', 'H11', 0),
(195, 'Reconhecer diferentes funções da arte, do trabalho da produção dos artistas em seus meios culturais.', 'H12', 0),
(196, 'Analisar as diversas produções artísticas como meio de explicar diferentes culturas, padrões de beleza e preconceitos.', 'H13', 0),
(197, 'Reconhecer o valor da diversidade artística e das inter-relações de elementos que se apresentam nas manifestações de vários grupos sociais e étnicos.', 'H14', 0),
(198, 'Estabelecer relações entre o texto literário e o momento de sua produção, situando aspectos do contexto histórico, social e político.', 'H15', 0),
(199, 'Relacionar informações sobre concepções artísticas e procedimentos de construção do texto literário.', 'H16', 0),
(200, 'Reconhecer a presença de valores sociais e humanos atualizáveis e permanentes no patrimônio literário nacional.', 'H17', 0),
(201, 'Identificar os elementos que concorrem para a progressão temática e para a organização e estruturação de textos de diferentes gêneros e tipos.', 'H18', 0),
(202, 'Analisar a função da linguagem predominante nos textos em situações específicas de interlocução.', 'H19', 0),
(203, 'Reconhecer a importância do patrimônio linguístico para a preservação da memória e da identidade nacional.', 'H20', 0),
(204, 'Reconhecer em textos de diferentes gêneros, recursos verbais e não-verbais utilizados com a finalidade de criar e mudar comportamentos e hábitos.', 'H21', 0),
(205, 'Relacionar, em diferentes textos, opiniões, temas, assuntos e recursos linguísticos.', 'H22', 0),
(206, 'Inferir em um texto quais são os objetivos de seu produtor e quem é seu público alvo, pela análise dos procedimentos argumentativos utilizados.', 'H23', 0),
(207, 'Reconhecer no texto estratégias argumentativas empregadas para o convencimento do público, tais como a intimidação, sedução, comoção, chantagem, entre outras.', 'H24', 0),
(208, 'Identificar, em textos de diferentes gêneros, as marcas linguísticas que singularizam as variedades linguísticas sociais, regionais e de registro.', 'H25', 0),
(209, 'Relacionar as variedades linguísticas a situações específicas de uso social.', 'H26', 0),
(210, 'Reconhecer os usos da norma padrão da língua portuguesa nas diferentes situações de comunicação.', 'H27', 0),
(211, 'Reconhecer a função e o impacto social das diferentes tecnologias da comunicação e informação.', 'H28', 0),
(212, 'Identificar pela análise de suas linguagens, as tecnologias da comunicação e informação.', 'H29', 0),
(213, 'Relacionar as tecnologias de comunicação e informação ao desenvolvimento das sociedades e ao conhecimento que elas produzem.', 'H30', 0),
(214, ' Reconhecer, no contexto social, diferentes significados e representações dos números e operações - naturais, inteiros, racionais ou reais.', 'H1', 0),
(215, 'Identificar padrões numéricos ou princípios de contagem.', 'H2', 0),
(216, 'Resolver situação-problema envolvendo conhecimentos numéricos.', 'H3', 0),
(217, 'Avaliar a razoabilidade de um resultado numérico na construção de argumentos sobre afirmações quantitativas.', 'H4', 0),
(218, 'Avaliar propostas de intervenção na realidade utilizando conhecimentos numéricos.', 'H5', 0),
(219, 'Interpretar a localização e a movimentação de pessoas/objetos no espaço tridimensional e sua representação no espaço bidimensional.', 'H6', 0),
(220, 'Identificar características de figuras planas ou espaciais.', 'H7', 0),
(221, 'Resolver situação-problema que envolva conhecimentos geométricos de espaço e forma.', 'H8', 0),
(222, 'Utilizar conhecimentos geométricos de espaço e forma na seleção de argumentos propostos como solução de problemas do cotidiano.', 'H9', 0),
(223, 'Identificar relações entre grandezas e unidades de medida.', 'H10', 0),
(224, 'Utilizar a noção de escalas na leitura de representação de situação do cotidiano.', 'H11', 0),
(225, 'Resolver situação-problema que envolva medidas de grandezas.', 'H12', 0),
(226, 'Avaliar o resultado de uma medição na construção de um argumento consistente.', 'H13', 0),
(227, 'Avaliar proposta de intervenção na realidade utilizando conhecimentos geométricos relacionados a grandezas e medidas.', 'H14', 0),
(228, 'Identificar a relação de dependência entre grandezas.', 'H15', 0),
(229, 'Resolver situação-problema envolvendo a variação de grandezas, direta ou inversamente proporcionais.', 'H16', 0),
(230, 'Analisar informações envolvendo a variação de grandezas como recurso para a construção de argumentação.', 'H17', 0),
(231, 'Avaliar propostas de intervenção na realidade envolvendo variação de grandezas.', 'H18', 0),
(232, 'Identificar representações algébricas que expressem a relação entre grandezas.', 'H19', 0),
(233, 'Interpretar gráfico cartesiano que represente relações entre grandezas.', 'H20', 0),
(234, 'Resolver situação-problema cuja modelagem envolva conhecimentos algébricos.', 'H21', 0),
(235, 'Utilizar conhecimentos algébricos/geométricos como recurso para a construção de argumentação.', 'H22', 0),
(236, 'Avaliar propostas de intervenção na realidade utilizando conhecimentos algébricos.', 'H23', 0),
(237, 'Utilizar informações expressas em gráficos ou tabelas para fazer inferências.', 'H24', 0),
(238, 'Resolver problema com dados apresentados em tabelas ou gráficos.', 'H25', 0),
(239, 'Analisar informações expressas em gráficos ou tabelas como recurso para a construção de argumentos.', 'H26', 0),
(240, 'Calcular medidas de tendência central ou de dispersão de um conjunto de dados expressos em uma tabela de frequências de dados agrupados (não em classes) ou em gráficos.', 'H27', 0),
(241, 'Resolver situação-problema que envolva conhecimentos de estatística e probabilidade.', 'H28', 0),
(242, 'Utilizar conhecimentos de estatística e probabilidade como recurso para a construção de argumentação.', 'H29', 0),
(243, 'Avaliar propostas de intervenção na realidade utilizando conhecimentos de estatística e probabilidade.', 'H30', 0),
(244, 'Reconhecer características ou propriedades de fenômenos ondulatórios ou oscilatórios, relacionando-os a seus usos em diferentes contextos.', 'H1', 0),
(245, 'Associar a solução de problemas de comunicação, transporte, saúde ou outro, com o correspondente desenvolvimento científico e tecnológico.', 'H2', 0),
(246, 'Confrontar interpretações científicas com interpretações baseadas no senso comum, ao longo do tempo ou em diferentes culturas.', 'H3', 0),
(247, 'Avaliar propostas de intervenção no ambiente, considerando a qualidade da vida humana ou medidas de conservação, recuperação ou utilização sustentável da biodiversidade.', 'H4', 0),
(248, 'Dimensionar circuitos ou dispositivos elétricos de uso cotidiano.', 'H5', 0),
(249, 'Relacionar informações para compreender manuais de instalação ou utilização de aparelhos, ou sistemas tecnológicos de uso comum.', 'H6 ', 0),
(250, 'Selecionar testes de controle, parâmetros ou critérios para a comparação de materiais e produtos, tendo em vista a defesa do consumidor, a saúde do trabalhador ou a qualidade de vida.', 'H7', 0),
(251, 'Identificar etapas em processos de obtenção, transformação, utilização ou reciclagem de recursos naturais, energéticos ou matérias-primas, considerando processos biológicos, químicos ou físicos neles envolvidos.', 'H8', 0),
(252, 'Compreender a importância dos ciclos biogeoquímicos ou do fluxo energia para a vida, ou da ação de agentes ou fenômenos que podem causar alterações nesses processos.', 'H9', 0),
(253, 'Analisar perturbações ambientais, identificando fontes, transporte e(ou) destino dos poluentes ou prevendo efeitos em sistemas naturais, produtivos ou sociais.', 'H10 ', 0),
(254, 'Reconhecer benefícios, limitações e aspectos éticos da biotecnologia, considerando estruturas e processos biológicos envolvidos em produtos biotecnológicos.', 'H11', 0),
(255, ' Avaliar impactos em ambientes naturais decorrentes de atividades sociais ou econômicas, considerando interesses contraditórios.', 'H12', 0),
(256, ' Reconhecer mecanismos de transmissão da vida, prevendo ou explicando a manifestação de características dos seres vivos.', 'H13', 0),
(257, 'Identificar padrões em fenômenos e processos vitais dos organismos, como manutenção do equilíbrio interno, defesa, relações com o ambiente, sexualidade, entre outros.', 'H14', 0),
(258, ' Interpretar modelos e experimentos para explicar fenômenos ou processos biológicos em qualquer nível de organização dos sistemas biológicos.', 'H15', 0),
(259, 'Compreender o papel da evolução na produção de padrões, processos biológicos ou na organização taxonômica dos seres vivos.', 'H16', 0),
(260, 'Relacionar informações apresentadas em diferentes formas de linguagem e representação usadas nas ciências físicas, químicas ou biológicas, como texto discursivo, gráficos, tabelas, relações matemáticas ou linguagem simbólica.', 'H17', 0),
(261, ' Relacionar propriedades físicas, químicas ou biológicas de produtos, sistemas ou procedimentos tecnológicos às finalidades a que se destinam.', 'H18', 0),
(262, ' Avaliar métodos, processos ou procedimentos das ciências naturais que contribuam para diagnosticar ou solucionar problemas de ordem social, econômica ou ambiental.', 'H19', 0),
(263, 'Caracterizar causas ou efeitos dos movimentos de partículas, substâncias, objetos ou corpos celestes.', 'H20', 0),
(264, ' Utilizar leis físicas e (ou) químicas para interpretar processos naturais ou tecnológicos inseridos no contexto da termodinâmica e(ou) do eletromagnetismo.', 'H21', 0),
(265, 'Compreender fenômenos decorrentes da interação entre a radiação e a matéria em suas manifestações em processos naturais ou tecnológicos, ou em suas implicações biológicas, sociais, econômicas ou ambientais.', 'H22', 0),
(266, 'Avaliar possibilidades de geração, uso ou transformação de energia em ambientes específicos, considerando implicações éticas, ambientais, sociais e/ou econômicas.', 'H23', 0),
(267, 'Utilizar códigos e nomenclatura da química para caracterizar materiais, substâncias ou transformações químicas.', 'H24', 0),
(268, 'Caracterizar materiais ou substâncias, identificando etapas, rendimentos ou implicações biológicas, sociais, econômicas ou ambientais de sua obtenção ou produção.', 'H25', 0),
(269, 'Avaliar implicações sociais, ambientais e/ou econômicas na produção ou no consumo de recursos energéticos ou minerais, identificando transformações químicas ou de energia envolvidas nesses processos.', 'H26', 0),
(270, 'Avaliar propostas de intervenção no meio ambiente aplicando conhecimentos químicos, observando riscos ou benefícios.', 'H27', 0),
(271, 'Associar características adaptativas dos organismos com seu modo de vida ou com seus limites de distribuição em diferentes ambientes, em especial em ambientes brasileiros.', 'H28', 0),
(272, 'Interpretar experimentos ou técnicas que utilizam seres vivos, analisando implicações para o ambiente, a saúde, a produção de alimentos, matérias primas ou produtos industriais.', 'H29 ', 0),
(273, 'Avaliar propostas de alcance individual ou coletivo, identificando aquelas que visam à preservação e a implementação da saúde individual, coletiva ou do ambiente.', 'H30', 0),
(274, 'Interpretar historicamente e/ou geograficamente fontes documentais acerca de\r\naspectos da cultura.', 'H1', 0),
(275, 'Analisar a produção da memória pelas sociedades humanas.', 'H2 ', 0),
(276, 'Associar as manifestações culturais do presente aos seus processos históricos.', 'H3', 0),
(277, 'Comparar pontos de vista expressos em diferentes fontes sobre determinado aspecto da cultura.', 'H4', 0),
(278, 'Identificar as manifestações ou representações da diversidade do patrimônio cultural e artístico em diferentes sociedades.', 'H5', 0),
(279, 'Interpretar diferentes representações gráficas e cartográficas dos espaços geográficos.', 'H6', 0),
(280, 'Identificar os significados histórico-geográficos das relações de poder entre as nações.', 'H7', 0),
(281, 'Analisar a ação dos estados nacionais no que se refere à dinâmica dos fluxos populacionais e no enfrentamento de problemas de ordem econômico-social.', 'H8', 0),
(282, 'Comparar o significado histórico-geográfico das organizações políticas e socioeconômicas em escala local, regional ou mundial.', 'H9', 0),
(283, 'Reconhecer a dinâmica da organização dos movimentos sociais e a importância da participação da coletividade na transformação da realidade histórico-geográfica.', 'H10', 0),
(284, 'Identificar registros de práticas de grupos sociais no tempo e no espaço.', 'H11', 0),
(285, 'Analisar o papel da justiça como instituição na organização das sociedades.', 'H12', 0),
(286, 'Analisar a atuação dos movimentos sociais que contribuíram para mudanças ou rupturas em processos de disputa pelo poder.', 'H13', 0),
(287, 'Comparar diferentes pontos de vista, presentes em textos analíticos e interpretativos, sobre situação ou fatos de natureza histórico-geográfica acerca das instituições sociais, políticas e econômicas.', 'H14', 0),
(288, 'Avaliar criticamente conflitos culturais, sociais, políticos, econômicos ou ambientais ao longo da história.', 'H15', 0),
(289, 'Identificar registros sobre o papel das técnicas e tecnologias na organização do trabalho e/ou da vida social.', 'H16', 0),
(290, 'Analisar fatores que explicam o impacto das novas tecnologias no processo de territorialização da produção.', 'H17', 0),
(291, 'Analisar diferentes processos de produção ou circulação de riquezas e suas implicações sócio-espaciais.', 'H18', 0),
(292, 'Reconhecer as transformações técnicas e tecnológicas que determinam as várias formas de uso e apropriação dos espaços rural e urbano.', 'H19', 0),
(293, 'Selecionar argumentos favoráveis ou contrários às modificações impostas pelas novas tecnologias à vida social e ao mundo do trabalho.', 'H20', 0),
(294, 'Identificar o papel dos meios de comunicação na construção da vida social.', 'H21', 0),
(295, 'Analisar as lutas sociais e conquistas obtidas no que se refere às mudanças nas legislações ou nas políticas públicas.', 'H22', 0),
(296, 'Analisar a importância dos valores éticos na estruturação política das sociedades.', 'H23', 0),
(297, 'Relacionar cidadania e democracia na organização das sociedades.', 'H24', 0),
(298, 'Identificar estratégias que promovam formas de inclusão social.', 'H25', 0),
(299, 'Identificar em fontes diversas o processo de ocupação dos meios físicos e as relações da vida humana com a paisagem.', 'H26', 0),
(300, 'Analisar de maneira crítica as interações da sociedade com o meio físico, levando em consideração aspectos históricos e(ou) geográficos.', 'H27', 0),
(301, 'Relacionar o uso das tecnologias com os impactos sócio-ambientais em diferentes contextos histórico-geográficos.', 'H28', 0),
(302, 'Reconhecer a função dos recursos naturais na produção do espaço geográfico, relacionando-os com as mudanças provocadas pelas ações humanas.', 'H29', 0),
(303, 'Avaliar as relações entre preservação e degradação da vida no planeta nas diferentes escalas.', 'H30', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `habilidade_computacao`
--

CREATE TABLE `habilidade_computacao` (
  `id` int(11) NOT NULL,
  `codigo` char(10) NOT NULL,
  `descricao` text CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `eixo_computacao` tinyint(4) NOT NULL,
  `ano_escolar` tinyint(4) NOT NULL,
  `nivel_ensino` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `habilidade_computacao`
--

INSERT INTO `habilidade_computacao` (`id`, `codigo`, `descricao`, `eixo_computacao`, `ano_escolar`, `nivel_ensino`) VALUES
(1, 'EI03CO01', 'Reconhecer padrão de repetição em sequência de sons, movimentos, desenhos.', 2, 1, 1),
(2, 'EI03CO02', 'Expressar as etapas para a realização de uma tarefa de forma clara e\r\nordenada.\r\n', 2, 1, 1),
(3, 'EF01CO01', 'Organizar objetos físicos ou digitais considerando diferentes características para esta organização, explicitando semelhanças (padrões) e diferenças.', 2, 1, 1),
(4, 'EF01CO02', 'Conceituação de Algoritmos. Identificar e seguir sequências de passos aplicados no dia a dia para resolver problemas.\r\n', 2, 1, 1),
(5, 'EF01CO03', 'Conceituação de Algoritmos. Reorganizar e criar sequências de passos em meios físicos ou digitais, relacionando essas sequências à palavra ‘Algoritmos’', 2, 1, 1),
(6, 'EF01CO04', 'Codificação da informação.   Reconhecer o que é a informação, que ela pode ser armazenada, transmitida como\r\nmensagem por diversos meios e descrita em várias linguagens.', 3, 1, 1),
(7, 'EF01CO05', 'Codificação da informação. Representar informação usando diferentes codificações.\r\n', 3, 1, 1),
(8, 'EF01CO06', 'Uso de artefatos computacionais.  Reconhecer e explorar artefatos computacionais voltados a atender necessidades\r\npessoais ou coletivas', 1, 1, 1),
(9, 'EF01CO07', 'Segurança e responsabilidade no uso de tecnologia computacional. Conhecer as possibilidades de uso\r\nseguro das tecnologias computacionais para proteção dos dados pessoais e para garantir a própria segurança.\r\n', 1, 1, 1),
(10, 'EF02CO01', 'Modelagem de objetos. Criar e comparar modelos (representações) de objetos, identificando padrões e atributos essenciais.', 2, 2, 1),
(11, 'EF02CO02', 'Algoritmos com repetições simples.  Criar e simular algoritmos representados em linguagem oral, escrita ou\r\npictográfica, construídos como sequências com repetições simples (iterações definidas) com base em instruções preestabelecidas ou criadas, analisando como a precisão da instrução impacta na execução do algoritmo.\r\n', 2, 2, 1),
(12, 'EF02CO03', 'Instrução de máquina. Identificar que máquinas diferentes executam conjuntos próprios de instruções e que\r\npodem ser usadas para definir algoritmos.\r\n', 3, 2, 1),
(13, 'EF02CO04', 'Hardware e software. Diferenciar componentes físicos (hardware) e programas que fornecem as instruções (software) para o hardware.', 3, 2, 1),
(14, 'EF02CO05', 'Uso de artefatos computacionais.  Reconhecer as características e usos das tecnologias computacionais no cotidiano\r\ndentro e fora da escola.', 1, 2, 1),
(15, 'EF02CO06', 'Segurança e responsabilidade no uso de tecnologia computacional. Reconhecer os cuidados com a segurança no uso de dispositivos computacionais.', 1, 2, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `instanciagestora`
--

CREATE TABLE `instanciagestora` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `instancia_gestora_pai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `instanciagestora`
--

INSERT INTO `instanciagestora` (`id`, `nome`, `instancia_gestora_pai`) VALUES
(1, 'Ministério da Educação', 416),
(7, 'Secretaria de Educação do Estado do Rio de Janeiro', 1),
(100, 'IG Escola Protótipo', 416),
(416, 'Super Protótipo', NULL),
(417, 'Secretaria de Estado da Educação do Paraná - SEED/PR', 1),
(418, 'Núcleo Regional de Educação de Foz do Iguaçu', 417);

-- --------------------------------------------------------

--
-- Estrutura para tabela `plano_aula`
--

CREATE TABLE `plano_aula` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `nivel_ensino` tinyint(4) NOT NULL,
  `ano_escolar` tinyint(4) NOT NULL,
  `eixo_computacao` tinyint(4) NOT NULL,
  `duracao_aula` smallint(6) NOT NULL,
  `numero_aula` smallint(6) NOT NULL,
  `comentarios_adicionais` text NOT NULL,
  `prompt` text DEFAULT NULL,
  `conteudo_gerado` text DEFAULT NULL,
  `visibilidade` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `plano_aula`
--

INSERT INTO `plano_aula` (`id`, `titulo`, `nivel_ensino`, `ano_escolar`, `eixo_computacao`, `duracao_aula`, `numero_aula`, `comentarios_adicionais`, `prompt`, `conteudo_gerado`, `visibilidade`) VALUES
(1, 'Plano de Aula 1', 1, 1, 2, 45, 2, 'nenhum', NULL, '            <h2>Interpretação crítica de discursos midiáticos utilizando conceitos matemáticos e físicos</h2>\r\n            <p><b>Objetivos de aprendizagem:</b></p>\r\n            <ul><li>Identificar diferentes tipos de linguagens presentes em mídias diversas (visuais, verbais, sonoras, gestuais).</li><li>Reconhecer visões de mundo, conflitos de interesse, preconceitos e ideologias em discursos midiáticos.</li><li>Aplicar conceitos básicos de matemática para interpretar dados e informações presentes em mídias.</li><li>Utilizar noções elementares de física para compreender fenômenos representados em discursos midiáticos.</li><li>Estimular a reflexão crítica e a produção de discursos próprios fundamentados em análises multidisciplinares.</li></ul>\r\n            <p><b>Habilidades trabalhadas:</b></p>\r\n            <ul><li>Analisar visões de mundo, conflitos de interesse, preconceitos e ideologias presentes nos discursos veiculados nas diferentes mídias, ampliando suas possibilidades de explicação, interpretação e intervenção crítica da/na realidade.</li><li>Analisar o funcionamento das linguagens, para interpretar e produzir criticamente discursos em textos de diversas semioses (visuais, verbais, sonoras, gestuais).</li></ul>\r\n            <p><b>Conteúdos abordados:</b></p>\r\n            <ul><li>Linguagens e semioses (visual, verbal, sonora, gestual)</li><li>Visões de mundo e ideologias</li><li>Preconceitos e conflitos de interesse</li><li>Interpretação crítica de textos e mídias</li><li>Conceitos básicos de matemática (dados, gráficos, proporções)</li><li>Noções elementares de física (fenômenos naturais, energia, movimento)</li></ul>\r\n            <p><b>Metodologia:</b></p>\r\n            <ol><li><b>Introdução</b> (10 minutos)<br>Apresentação do tema central e problema orientador, contextualizando a importância da análise crítica de discursos midiáticos e a interdisciplinaridade entre matemática, física e linguagens.</li><li><b>Análise de exemplos reais</b> (15 minutos)<br>Exibição e discussão de exemplos de discursos midiáticos que contenham diferentes linguagens e elementos matemáticos e físicos, promovendo a identificação de visões de mundo, preconceitos e ideologias.</li><li><b>Atividade prática em grupo</b> (15 minutos)<br>Divisão da turma em grupos para analisar um discurso midiático específico, identificando as linguagens presentes, os conceitos matemáticos e físicos aplicados, e os elementos ideológicos e críticos.</li><li><b>Socialização e reflexão</b> (5 minutos)<br>Apresentação dos resultados dos grupos, debate coletivo e estímulo à produção de textos ou discursos críticos fundamentados nas análises realizadas.</li></ol>\r\n            <p><b>Recursos didáticos:</b></p>\r\n            <ul><li>Projetor multimídia ou TV para exibição de vídeos e imagens</li><li>Exemplos impressos ou digitais de discursos midiáticos (notícias, propagandas, reportagens)</li><li>Quadro branco e marcadores</li><li>Material para anotação (cadernos, canetas)</li></ul>\r\n            <p><b>Atividades práticas:</b></p>\r\n            <ul><li>Análise em grupos de discursos midiáticos para identificação das linguagens e elementos matemáticos e físicos.</li><li>Produção de textos ou apresentações críticas baseadas na análise interdisciplinar dos discursos.</li><li>Debates orientados para reflexão sobre preconceitos, ideologias e visões de mundo presentes nas mídias.</li></ul>\r\n            <p><b>Avaliação:</b> Avaliação contínua por meio da participação nas discussões, qualidade da análise realizada nas atividades em grupo e coerência na produção dos textos ou apresentações críticas, considerando a aplicação dos conceitos matemáticos, físicos e linguísticos.</p>\r\n            <p><b>Adaptações pedagógicas:</b></p>\r\n            <ul><li>Fornecer exemplos mais simples e visuais para alunos com dificuldade na identificação das linguagens.</li><li>Utilizar recursos audiovisuais e interativos para facilitar a compreensão dos conceitos matemáticos e físicos.</li><li>Oferecer apoio individualizado para alunos com limitações na expressão oral e escrita.</li><li>Dividir as atividades em etapas menores para melhor gestão do tempo e compreensão.</li></ul>\r\n            <p><b>Continuidade da aula:</b></p>\r\n            <ul><li>Explorar outras mídias e formatos discursivos para aprofundar a análise crítica.</li><li>Integrar projetos interdisciplinares envolvendo matemática, física e linguagens em temas sociais relevantes.</li><li>Estimular a produção contínua de conteúdos críticos pelos alunos, ampliando o repertório e a autonomia.</li><li>Promover oficinas de comunicação e argumentação para fortalecer a expressão oral e escrita.</li></ul>\r\n        ', 2),
(2, 'Plano de Aula 2', 1, 2, 2, 45, 4, 'nenhum', NULL, '            <h2>Uso do inglês como língua de comunicação global</h2>\r\n            <p><b>Objetivos de aprendizagem:</b></p>\r\n            <ul><li>Identificar diferentes situações em que o inglês é utilizado como língua global.</li><li>Reconhecer a variedade de usuários e contextos do inglês no mundo atual.</li><li>Praticar expressões básicas em inglês para comunicação simples e eficaz.</li><li>Valorizar a diversidade cultural associada ao uso do inglês.</li></ul>\r\n            <p><b>Habilidades trabalhadas:</b></p>\r\n            <ul><li>Fazer uso do inglês como língua de comunicação global, levando em conta a multiplicidade e variedade de usos, usuários e funções dessa língua no mundo contemporâneo.</li></ul>\r\n            <p><b>Conteúdos abordados:</b></p>\r\n            <ul><li>Inglês como língua global</li><li>Comunicação intercultural</li><li>Diversidade linguística</li><li>Funções da língua</li></ul>\r\n            <p><b>Metodologia:</b></p>\r\n            <ol><li><b>Introdução</b> (10 minutos)<br>Apresentar o tema central e o problema orientador, contextualizando o uso do inglês como língua global e sua importância na comunicação intercultural.</li><li><b>Atividade Interativa 1</b> (10 minutos)<br>Exibição de vídeos curtos que mostram diferentes situações de uso do inglês no mundo, seguida de discussão guiada para identificar usuários, contextos e funções da língua.</li><li><b>Atividade Interativa 2</b> (15 minutos)<br>Realização de jogos de papéis e diálogos simulados em inglês, focando em expressões básicas para comunicação simples e eficaz, promovendo a participação ativa dos alunos.</li><li><b>Conclusão</b> (10 minutos)<br>Reflexão coletiva sobre a diversidade cultural associada ao uso do inglês e a importância de valorizar essa diversidade na comunicação global.</li></ol>\r\n            <p><b>Recursos didáticos:</b></p>\r\n            <ul><li>Vídeos curtos sobre uso do inglês no mundo</li><li>Cartões com expressões básicas em inglês</li><li>Material para jogos de papéis (fichas, cenários)</li><li>Quadro branco e marcadores</li></ul>\r\n            <p><b>Atividades práticas:</b></p>\r\n            <ul><li>Assistir e discutir vídeos sobre o inglês como língua global.</li><li>Participar de diálogos simulados utilizando expressões básicas em inglês.</li><li>Realizar jogos de papéis para praticar comunicação em diferentes contextos.</li><li>Debater a diversidade cultural relacionada ao uso do inglês.</li></ul>\r\n            <p><b>Avaliação:</b> Observação contínua da participação dos alunos nas atividades orais e interativas, avaliação da capacidade de utilizar expressões básicas em inglês e da compreensão da diversidade cultural associada ao uso da língua.</p>\r\n            <p><b>Adaptações pedagógicas:</b></p>\r\n            <ul><li>Oferecer suporte adicional para alunos com dificuldade na pronúncia ou compreensão auditiva.</li><li>Utilizar recursos visuais e gestuais para facilitar a compreensão do vocabulário básico.</li><li>Adaptar o ritmo das atividades para alunos que apresentem resistência inicial ao uso do inglês.</li><li>Incluir exemplos de sotaques variados para ampliar a familiaridade com diferentes formas do inglês.</li></ul>\r\n            <p><b>Continuidade da aula:</b></p>\r\n            <ul><li>Incluir atividades semanais de conversação em inglês para ampliar o vocabulário e a fluência.</li><li>Explorar temas culturais de países de língua inglesa para aprofundar a compreensão intercultural.</li><li>Incentivar o uso do inglês em projetos interdisciplinares e em situações reais de comunicação.</li><li>Promover encontros com falantes nativos ou vídeos com diferentes sotaques para ampliar a exposição à diversidade do inglês.</li></ul>\r\n        ', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `plano_aula_comp_cur`
--

CREATE TABLE `plano_aula_comp_cur` (
  `id` int(11) NOT NULL,
  `id_plano` int(11) NOT NULL,
  `id_componente` int(11) NOT NULL,
  `assunto` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `plano_aula_comp_cur`
--

INSERT INTO `plano_aula_comp_cur` (`id`, `id_plano`, `id_componente`, `assunto`) VALUES
(4, 1, 1, NULL),
(5, 1, 15, NULL),
(6, 2, 9, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `plano_aula_habilidades`
--

CREATE TABLE `plano_aula_habilidades` (
  `id` int(11) NOT NULL,
  `id_plano` int(11) NOT NULL,
  `id_habilidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `plano_aula_habilidades`
--

INSERT INTO `plano_aula_habilidades` (`id`, `id_plano`, `id_habilidade`) VALUES
(4, 1, 2),
(5, 1, 3),
(6, 2, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `prompt`
--

CREATE TABLE `prompt` (
  `id` int(11) NOT NULL,
  `id_eixo` smallint(6) NOT NULL,
  `system_prompt1` text NOT NULL,
  `user_prompt1` text NOT NULL,
  `system_prompt2` text NOT NULL,
  `user_prompt2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `prompt`
--

INSERT INTO `prompt` (`id`, `id_eixo`, `system_prompt1`, `user_prompt1`, `system_prompt2`, `user_prompt2`) VALUES
(1, 2, 'Você é um especialista em educação básica e ensino de computação.\r\nSua tarefa é analisar os dados recebidos e gerar uma estrutura pedagógica objetiva.\r\nResponda exclusivamente em JSON válido.', 'Analise os parâmetros abaixo e gere uma estrutura pedagógica.\r\n\r\nEixo da Computação: [eixo_computacao]\r\nAno Escolar: [ano_escolar]\r\nDuração da Aula: [duracao_aula]\r\nNúmero de Aulas: [numero_aulas]\r\nConteúdo dos Componentes Curriculares: [conteudo_componentes]\r\nHabilidades Escolhidas: [habilidades]\r\nInformações adicionais: [informacoes_adicionais]', 'Você é um especialista em didática e planejamento de aula.\r\nCom base na estrutura pedagógica recebida, gere um plano de aula completo.', 'Utilize os dados abaixo para gerar um plano de aula completo.\r\n\r\nEixo da Computação: [eixo_computacao]\r\nAno Escolar: [ano_escolar]\r\nDuração da Aula: [duracao_aula]\r\nNúmero de Aulas: [numero_aulas]\r\nConteúdo dos Componentes Curriculares: [conteudo_componentes]\r\nHabilidades Escolhidas: [habilidades]\r\nInformações adicionais: [informacoes_adicionais]');

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `promptview`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `promptview` (
`id` int(11)
,`id_eixo` smallint(6)
,`user_prompt1` text
,`user_prompt2` text
,`system_prompt1` text
,`system_prompt2` text
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `prompt_interacao`
--

CREATE TABLE `prompt_interacao` (
  `id` int(11) NOT NULL,
  `id_prompt` int(11) NOT NULL,
  `sequencia` tinyint(4) NOT NULL,
  `system` text NOT NULL,
  `prompt` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_document`
--

CREATE TABLE `system_document` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `description` varchar(4096) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `submission_date` date DEFAULT NULL,
  `archive_date` date DEFAULT NULL,
  `filename` varchar(512) DEFAULT NULL,
  `in_trash` char(1) DEFAULT NULL,
  `system_folder_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_document_bookmark`
--

CREATE TABLE `system_document_bookmark` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) DEFAULT NULL,
  `system_document_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_document_category`
--

CREATE TABLE `system_document_category` (
  `id` int(11) NOT NULL,
  `name` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `system_document_category`
--

INSERT INTO `system_document_category` (`id`, `name`) VALUES
(1, 'Documentos');

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_document_group`
--

CREATE TABLE `system_document_group` (
  `id` int(11) NOT NULL,
  `document_id` int(11) DEFAULT NULL,
  `system_group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_document_user`
--

CREATE TABLE `system_document_user` (
  `id` int(11) NOT NULL,
  `document_id` int(11) DEFAULT NULL,
  `system_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_folder`
--

CREATE TABLE `system_folder` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `name` varchar(256) NOT NULL,
  `in_trash` char(1) DEFAULT NULL,
  `system_folder_parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_folder_bookmark`
--

CREATE TABLE `system_folder_bookmark` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) DEFAULT NULL,
  `system_folder_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_folder_group`
--

CREATE TABLE `system_folder_group` (
  `id` int(11) NOT NULL,
  `system_folder_id` int(11) DEFAULT NULL,
  `system_group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_folder_user`
--

CREATE TABLE `system_folder_user` (
  `id` int(11) NOT NULL,
  `system_folder_id` int(11) DEFAULT NULL,
  `system_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_group`
--

CREATE TABLE `system_group` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `system_group`
--

INSERT INTO `system_group` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Standard'),
(3, 'Memore'),
(4, 'Discente'),
(6, 'Docente'),
(7, 'Gestor');

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_group_program`
--

CREATE TABLE `system_group_program` (
  `id` int(11) NOT NULL,
  `system_group_id` int(11) DEFAULT NULL,
  `system_program_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `system_group_program`
--

INSERT INTO `system_group_program` (`id`, `system_group_id`, `system_program_id`) VALUES
(43, 1, 1),
(44, 1, 2),
(45, 1, 3),
(46, 1, 4),
(47, 1, 5),
(48, 1, 6),
(49, 1, 8),
(50, 1, 9),
(51, 1, 11),
(52, 1, 14),
(53, 1, 15),
(54, 1, 21),
(55, 1, 26),
(56, 1, 27),
(57, 1, 28),
(58, 1, 29),
(59, 1, 31),
(60, 1, 32),
(61, 1, 33),
(62, 1, 34),
(63, 1, 35),
(64, 1, 36),
(65, 1, 37),
(67, 1, 39),
(68, 1, 40),
(69, 1, 41),
(70, 1, 42),
(71, 1, 43),
(73, 1, 44),
(75, 1, 45),
(76, 2, 10),
(79, 2, 16),
(80, 2, 17),
(81, 2, 18),
(82, 2, 19),
(83, 2, 20),
(84, 2, 21),
(85, 2, 22),
(86, 2, 23),
(87, 2, 24),
(88, 2, 25),
(89, 2, 30),
(90, 2, 41),
(91, 2, 42),
(92, 2, 43),
(93, 2, 44),
(94, 2, 45),
(97, 1, 47),
(98, 2, 47),
(106, 1, 46),
(107, 2, 46),
(109, 1, 48),
(110, 2, 48),
(112, 1, 49),
(113, 2, 49),
(115, 1, 50),
(117, 1, 51),
(118, 3, 5),
(119, 3, 6),
(120, 3, 41),
(121, 3, 42),
(122, 3, 43),
(123, 3, 44),
(124, 3, 45),
(125, 3, 46),
(126, 3, 47),
(127, 3, 48),
(128, 3, 49),
(129, 3, 50),
(130, 3, 51),
(131, 1, 52),
(132, 3, 52),
(133, 1, 53),
(134, 3, 53),
(139, 1, 56),
(140, 3, 56),
(141, 1, 57),
(142, 3, 57),
(143, 1, 58),
(144, 3, 58),
(147, 1, 60),
(148, 3, 60),
(149, 1, 61),
(150, 3, 61),
(151, 1, 62),
(152, 3, 62),
(153, 1, 63),
(154, 3, 63),
(155, 1, 64),
(156, 3, 64),
(157, 1, 65),
(158, 3, 65),
(159, 1, 66),
(160, 3, 66),
(161, 1, 67),
(162, 3, 67),
(163, 1, 68),
(164, 3, 68),
(165, 1, 69),
(166, 3, 69),
(171, 1, 70),
(172, 3, 70),
(173, 1, 71),
(174, 3, 71),
(175, 1, 72),
(176, 3, 72),
(177, 1, 73),
(178, 3, 73),
(183, 1, 76),
(184, 3, 76),
(199, 1, 84),
(200, 3, 84),
(201, 1, 85),
(202, 3, 85),
(203, 1, 86),
(204, 3, 86),
(209, 1, 89),
(210, 3, 89),
(211, 1, 90),
(212, 3, 90),
(213, 1, 91),
(214, 3, 91),
(215, 1, 92),
(216, 3, 92),
(217, 1, 93),
(218, 3, 93),
(219, 1, 94),
(220, 3, 94),
(221, 1, 95),
(222, 3, 95),
(224, 1, 96),
(225, 3, 96),
(230, 1, 98),
(231, 3, 98),
(232, 1, 97),
(233, 3, 97),
(234, 1, 99),
(235, 3, 99),
(236, 1, 100),
(237, 3, 100),
(238, 1, 101),
(239, 3, 101),
(240, 1, 102),
(241, 3, 102),
(246, 1, 103),
(247, 3, 103),
(250, 1, 104),
(251, 3, 104),
(254, 1, 105),
(255, 3, 105),
(266, 1, 106),
(267, 3, 106),
(269, 1, 107),
(270, 3, 107),
(272, 1, 108),
(273, 3, 108),
(275, 1, 109),
(276, 3, 109),
(278, 1, 110),
(279, 3, 110),
(281, 1, 111),
(282, 3, 111),
(284, 1, 112),
(285, 3, 112),
(287, 1, 113),
(288, 3, 113),
(290, 1, 114),
(291, 3, 114),
(294, 1, 115),
(295, 3, 115),
(298, 1, 116),
(299, 3, 116),
(302, 1, 117),
(303, 3, 117),
(306, 1, 118),
(307, 3, 118),
(310, 1, 119),
(311, 3, 119),
(314, 1, 120),
(315, 3, 120),
(318, 1, 121),
(319, 3, 121),
(322, 1, 122),
(323, 3, 122),
(326, 1, 123),
(327, 3, 123),
(330, 1, 124),
(331, 3, 124),
(334, 1, 125),
(335, 3, 125),
(339, 1, 126),
(340, 3, 126),
(344, 1, 127),
(345, 3, 127),
(348, 1, 128),
(349, 3, 128),
(420, 1, 130),
(421, 3, 130),
(425, 1, 131),
(426, 3, 131),
(429, 1, 132),
(430, 3, 132),
(432, 7, 5),
(433, 7, 6),
(434, 7, 58),
(436, 7, 68),
(437, 7, 69),
(438, 7, 72),
(439, 7, 73),
(440, 7, 89),
(441, 7, 90),
(442, 7, 93),
(443, 7, 94),
(444, 7, 103),
(445, 7, 104),
(446, 7, 105),
(447, 7, 111),
(448, 7, 116),
(449, 7, 117),
(450, 7, 118),
(451, 7, 125),
(452, 7, 126),
(453, 7, 130),
(454, 7, 131),
(455, 7, 132),
(482, 4, 95),
(483, 4, 96),
(484, 4, 103),
(485, 4, 104),
(486, 4, 105),
(487, 4, 125),
(488, 4, 126),
(489, 4, 130),
(490, 1, 74),
(491, 3, 74),
(493, 7, 74),
(494, 1, 75),
(495, 3, 75),
(497, 7, 75),
(498, 1, 77),
(499, 3, 77),
(501, 7, 77),
(502, 1, 79),
(503, 3, 79),
(505, 7, 79),
(506, 1, 80),
(507, 3, 80),
(509, 7, 80),
(510, 1, 81),
(511, 3, 81),
(513, 7, 81),
(514, 1, 82),
(515, 3, 82),
(517, 7, 82),
(518, 1, 78),
(519, 3, 78),
(521, 7, 78),
(522, 1, 129),
(523, 3, 129),
(525, 7, 129),
(526, 1, 133),
(527, 3, 133),
(528, 4, 133),
(530, 7, 133),
(531, 1, 134),
(532, 3, 134),
(533, 4, 134),
(535, 7, 134),
(536, 1, 135),
(537, 3, 135),
(538, 2, 12),
(539, 3, 12),
(540, 4, 12),
(542, 7, 12),
(543, 2, 13),
(544, 3, 13),
(545, 4, 13),
(547, 7, 13),
(548, 1, 136),
(549, 3, 136),
(550, 4, 136),
(552, 7, 136),
(553, 1, 137),
(554, 3, 137),
(555, 4, 137),
(557, 7, 137),
(558, 1, 138),
(559, 3, 138),
(561, 7, 138),
(562, 1, 38),
(563, 3, 38),
(564, 1, 139),
(565, 3, 139),
(566, 1, 140),
(567, 3, 140),
(568, 1, 141),
(569, 3, 141),
(571, 1, 142),
(572, 3, 142),
(573, 4, 142),
(575, 7, 142),
(576, 1, 143),
(577, 3, 143),
(578, 6, 44),
(579, 6, 45),
(580, 6, 91),
(581, 6, 92),
(582, 6, 97),
(583, 6, 98),
(584, 6, 99),
(585, 6, 100),
(586, 6, 101),
(587, 6, 102),
(588, 6, 112),
(589, 6, 113),
(590, 1, 144),
(591, 3, 144),
(592, 6, 144),
(593, 1, 145),
(594, 3, 145),
(595, 6, 145),
(596, 1, 59),
(597, 3, 59),
(598, 7, 59),
(599, 1, 146),
(600, 7, 146),
(601, 1, 147),
(602, 7, 147),
(603, 1, 55),
(604, 3, 55),
(605, 7, 55),
(606, 1, 54),
(607, 3, 54),
(608, 7, 54),
(609, 1, 148),
(610, 3, 148),
(611, 6, 148),
(612, 1, 149),
(613, 3, 149),
(614, 6, 149),
(615, 7, 149),
(616, 1, 150),
(617, 3, 150),
(618, 6, 150),
(619, 7, 150),
(620, 1, 151),
(621, 3, 151),
(622, 6, 151),
(623, 7, 151),
(624, 1, 152),
(625, 6, 152),
(626, 7, 152),
(627, 1, 153),
(628, 6, 153),
(629, 7, 153),
(630, 1, 154),
(631, 7, 154),
(632, 1, 155),
(633, 7, 155);

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_preference`
--

CREATE TABLE `system_preference` (
  `id` text DEFAULT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `system_preference`
--

INSERT INTO `system_preference` (`id`, `value`) VALUES
('mail_from', 'admin@memore-net.com'),
('smtp_auth', '1'),
('smtp_host', 'email-ssl.com.br'),
('smtp_port', '465'),
('smtp_user', 'admin@memore-net.com'),
('smtp_pass', 'memoreSP2022@'),
('mail_support', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_program`
--

CREATE TABLE `system_program` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `controller` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `system_program`
--

INSERT INTO `system_program` (`id`, `name`, `controller`) VALUES
(1, 'System Group Form', 'SystemGroupForm'),
(2, 'System Group List', 'SystemGroupList'),
(3, 'System Program Form', 'SystemProgramForm'),
(4, 'System Program List', 'SystemProgramList'),
(5, 'System User Form', 'SystemUserForm'),
(6, 'System User List', 'SystemUserList'),
(7, 'Common Page', 'CommonPage'),
(8, 'System PHP Info', 'SystemPHPInfoView'),
(9, 'System ChangeLog View', 'SystemChangeLogView'),
(10, 'Welcome View', 'WelcomeView'),
(11, 'System Sql Log', 'SystemSqlLogList'),
(12, 'System Profile View', 'SystemProfileView'),
(13, 'System Profile Form', 'SystemProfileForm'),
(14, 'System SQL Panel', 'SystemSQLPanel'),
(15, 'System Access Log', 'SystemAccessLogList'),
(16, 'System Message Form', 'SystemMessageForm'),
(17, 'System Message List', 'SystemMessageList'),
(18, 'System Message Form View', 'SystemMessageFormView'),
(19, 'System Notification List', 'SystemNotificationList'),
(20, 'System Notification Form View', 'SystemNotificationFormView'),
(21, 'System Document Category List', 'SystemDocumentCategoryFormList'),
(22, 'System Document Form', 'SystemDocumentForm'),
(23, 'System Document Upload Form', 'SystemDocumentUploadForm'),
(24, 'System Document List', 'SystemDocumentList'),
(25, 'System Shared Document List', 'SystemSharedDocumentList'),
(26, 'System Unit Form', 'SystemUnitForm'),
(27, 'System Unit List', 'SystemUnitList'),
(28, 'System Access stats', 'SystemAccessLogStats'),
(29, 'System Preference form', 'SystemPreferenceForm'),
(30, 'System Support form', 'SystemSupportForm'),
(31, 'System PHP Error', 'SystemPHPErrorLogView'),
(32, 'System Database Browser', 'SystemDatabaseExplorer'),
(33, 'System Table List', 'SystemTableList'),
(34, 'System Data Browser', 'SystemDataBrowser'),
(35, 'System Menu Editor', 'SystemMenuEditor'),
(36, 'System Request Log', 'SystemRequestLogList'),
(37, 'System Request Log View', 'SystemRequestLogView'),
(38, 'System Administration Dashboard', 'SystemAdministrationDashboard'),
(39, 'System Log Dashboard', 'SystemLogDashboard'),
(40, 'System Session dump', 'SystemSessionDumpView'),
(41, 'Public View', 'PublicView'),
(42, 'FormAreaView.class', 'FormAreaView'),
(43, 'Form Tema View', 'FormTemaView'),
(44, 'Form User List', 'FormUserList'),
(45, 'Form User', 'FormUser'),
(46, 'Form Ano Letivo List', 'FormAnoLetivoList'),
(47, 'Form Ano Letivo', 'FormAnoLetivo'),
(48, 'Form Desc MECList', 'FormDescMECList'),
(49, 'Form Desc MEC', 'FormDescMEC'),
(50, 'Form Disciplina List', 'FormDisciplinaList'),
(51, 'Form Disciplina', 'FormDisciplina'),
(52, 'Form Produto', 'FormProduto'),
(53, 'Form Produto List', 'FormProdutoList'),
(54, 'Form Serie Escolar', 'FormSerieEscolar'),
(55, 'Form Serie Escolar List', 'FormSerieEscolarList'),
(56, 'Form Tipo Atividade', 'FormTipoAtividade'),
(57, 'Form Tipo Atividade List', 'FormTipoAtividadeList'),
(58, 'Form Turma', 'FormTurma'),
(59, 'Form Turma List', 'FormTurmaList'),
(60, 'Form Escola', 'FormEscola'),
(61, 'Form Escola List', 'FormEscolaList'),
(62, 'Form Instancia', 'FormInstancia'),
(63, 'Form Instancia List', 'FormInstanciaList'),
(64, 'Form Marco Referencial', 'FormMarcoReferencial'),
(65, 'Form Marco Referencial List', 'FormMarcoReferencialList'),
(66, 'Form Nivel Ensino', 'FormNivelEnsino'),
(67, 'Form Nivel Ensino List', 'FormNivelEnsinoList'),
(68, 'Form Oferta Turma', 'FormOfertaTurma'),
(69, 'Form Oferta Turma List', 'FormOfertaTurmaList'),
(70, 'Form Resultado IDEB', 'FormResultadoIDEB'),
(71, 'Form Resultado IDEBList', 'FormResultadoIDEBList'),
(72, 'Form Projeto Aprend', 'FormProjetoAprend'),
(73, 'Form Projeto Aprend List', 'FormProjetoAprendList'),
(74, 'Completar Projeto Aprend', 'CompletarProjetoAprend'),
(75, 'Form Projeto Disciplina', 'FormProjetoDisciplina'),
(76, 'Form Projeto Desc MEC', 'FormProjetoDescMEC'),
(77, 'Form Produto Projeto List', 'FormProdutoProjetoList'),
(78, 'Form Produto Projeto', 'FormProdutoProjeto'),
(79, 'Form Conteudo Projeto', 'FormConteudoProjeto'),
(80, 'Form Conteudo Projeto List', 'FormConteudoProjetoList'),
(81, 'Form Objetivo Especifico List', 'FormObjetivoEspecificoList'),
(82, 'Form Objetivo Especifico', 'FormObjetivoEspecifico'),
(84, 'Form Escola PAEstatistica', 'FormEscolaPAEstatistica'),
(85, 'Form Escola Turma Estatistica', 'FormEscolaTurmaEstatistica'),
(86, 'Form Disc PAEstatistica', 'FormDiscPAEstatistica'),
(89, 'Form Sessao Pratica', 'FormSessaoPratica'),
(90, 'Form Sessao Pratica List', 'FormSessaoPraticaList'),
(91, 'Form Pergunta List', 'FormPerguntaList'),
(92, 'Form Pergunta', 'FormPergunta'),
(93, 'Form Questionario List', 'FormQuestionarioList'),
(94, 'Form Questionario', 'FormQuestionario'),
(95, 'Form SPPergunta List', 'FormSPPerguntaList'),
(96, 'Form SPPergunta', 'FormSPPergunta'),
(97, 'Form Area', 'FormArea'),
(98, 'Form Area List', 'FormAreaList'),
(99, 'Form Tema', 'FormTema'),
(100, 'Form Tema List', 'FormTemaList'),
(101, 'Form Usuario Tema', 'FormUsuarioTema'),
(102, 'Form Usuario Tema List', 'FormUsuarioTemaList'),
(103, 'Form SPEstatistica', 'FormSPEstatistica'),
(104, 'Form SPRealizada', 'FormSPRealizada'),
(105, 'Form SPRealizada List', 'FormSPRealizadaList'),
(106, 'Form Ordena Sessao Pratica', 'FormOrdenaSessaoPratica'),
(107, 'Form Software', 'FormSoftware'),
(108, 'Form Software List', 'FormSoftwareList'),
(109, 'Form Tipo Recurso', 'FormTipoRecurso'),
(110, 'Form Tipo Recurso List', 'FormTipoRecursoList'),
(111, 'Form Usuario Estatistica', 'FormUsuarioEstatistica'),
(112, 'Form Palavra List', 'FormPalavraList'),
(113, 'Form Palavra', 'FormPalavra'),
(114, 'Form Cad Questao', 'FormCadQuestao'),
(115, 'Form Cad Questao List', 'FormCadQuestaoList'),
(116, 'Form Sessao Pratica2', 'FormSessaoPratica2'),
(117, 'Form QPergunta', 'FormQPergunta'),
(118, 'Form QPergunta List', 'FormQPerguntaList'),
(119, 'Form Assunto', 'FormAssunto'),
(120, 'Form Assunto List', 'FormAssuntoList'),
(121, 'Form Competencia', 'FormCompetencia'),
(122, 'Form Competencia List', 'FormCompetenciaList'),
(123, 'Form Habilidade', 'FormHabilidade'),
(124, 'Form Habilidade List', 'FormHabilidadeList'),
(125, 'Form SPAnalise Pontual', 'FormSPAnalisePontual'),
(126, 'Form SPRecalcula TRI', 'FormSPRecalculaTRI'),
(127, 'Form Comp Curricular', 'FormCompCurricular'),
(128, 'Form Comp Curricular List', 'FormCompCurricularList'),
(129, 'Form Projeto Comp Curricular', 'FormProjetoCompCurricular'),
(130, 'Form SPRealizada Detalhada', 'FormSPRealizadaDetalhada'),
(131, 'Form Tipo Exame List', 'FormTipoExameList'),
(132, 'Form Tipo Exame', 'FormTipoExame'),
(133, 'Form Desemp Long Res', 'FormDesempLongRes'),
(134, 'Form Desemp Long Det', 'FormDesempLongDet'),
(135, 'Form Carga ABC', 'FormCargaABC'),
(136, 'Form SPPergunta Card', 'FormSPPerguntaCard'),
(137, 'Form SPRealizada Card', 'FormSPRealizadaCard'),
(138, 'Form Cad Questao Conteudo', 'FormCadQuestaoConteudo'),
(139, 'Form Msg Parecer List', 'FormMsgParecerList'),
(140, 'Form Msg Parecer', 'FormMsgParecer'),
(141, 'Form Rel Desemp Turma List', 'FormRelDesempTurmaList'),
(142, 'Form Simulacao SISU', 'FormSimulacaoSISU'),
(143, 'Form Cad Questao Curadoria', 'FormCadQuestaoCuradoria'),
(144, 'FormAlunoList', 'FormAlunoList'),
(145, 'Form Aluno', 'FormAluno'),
(146, 'Form Prompt List', 'FormPromptList'),
(147, 'Form Prompt', 'FormPrompt'),
(148, 'Export Pergunta2Json', 'ExportPergunta2Json'),
(149, 'Form Categoria List', 'FormCategoriaList'),
(150, 'Form Categoria', 'FormCategoria'),
(151, 'Video Card View', 'VideoCardView'),
(152, 'Form Plano Aula List', 'FormPlanoAulaList'),
(153, 'Form Plano Aula', 'FormPlanoAula'),
(154, 'Form Habilidade Computacao List', 'FormHabilidadeComputacaoList'),
(155, 'Form Habilidade Computacao', 'FormHabilidadeComputacao');

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_unit`
--

CREATE TABLE `system_unit` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `connection_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `system_unit`
--

INSERT INTO `system_unit` (`id`, `name`, `connection_name`) VALUES
(1, 'UNIT A', 'unit_a'),
(2, 'UNIT B', 'unit_b');

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_user`
--

CREATE TABLE `system_user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `login` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `frontpage_id` int(11) DEFAULT NULL,
  `system_unit_id` int(11) DEFAULT NULL,
  `active` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `system_user`
--

INSERT INTO `system_user` (`id`, `name`, `login`, `password`, `email`, `frontpage_id`, `system_unit_id`, `active`) VALUES
(1, 'Administrator', 'admin', 'b68b257d506c2c249ee77d274a12b43c', 'admin@admin.net', 41, NULL, 'Y'),
(2, 'User', 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'user@user.net', 7, NULL, 'Y'),
(3, 'Claudio Azevedo Passos', 'Claudio', '8d0f760bd11049a6190d4492f7adf00d', 'cpassos.cp2@gmail.com', 41, 1, 'Y'),
(4, 'Ronaldo Goldshmidt', 'Ronaldo', '202cb962ac59075b964b07152d234b70', 'rgold@gmail.com', 41, NULL, 'Y'),
(16, 'Paulo Cesar Coelho', 'pccoelho.rio@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'pccoelho.rio@gmail.com', 41, NULL, 'Y'),
(17, 'ProfessorPrototipo1', 'ProfessorPrototipo1', 'bf1c677e27a5657c238dc4542d2aab6a', 'professor1@gmail.com', 41, NULL, 'Y'),
(18, 'GestorPrototipo1', 'GestorPrototipo1', '202cb962ac59075b964b07152d234b70', 'gestor1@gmail.com', 41, NULL, 'Y'),
(19, 'AlunoPrototipo1', 'AlunoPrototipo1', '202cb962ac59075b964b07152d234b70', 'aluno1@aluno.com', 41, NULL, 'Y'),
(27, 'ConvidadoPrototipo1', 'ConvidadoPrototipo1', '202cb962ac59075b964b07152d234b70', 'convidado@gmail.com', 41, NULL, 'Y'),
(28, 'Márcia ', '563015', '14ca5e2dbb49d7807210a5f1e5b7616c', 'marciacpq@yahoo.com.br', 41, NULL, 'Y'),
(29, 'Paulo', 'paulo', '89794b621a313bb59eed0d9f0f4e8205', 'paulocmoraes@gmail.com', 41, NULL, 'Y'),
(30, 'Isabel Fernandes de Souza', 'ifsouza', 'bf1c677e27a5657c238dc4542d2aab6a', 'ifsouza@yahoo.com.br', 41, NULL, 'Y'),
(31, 'RONALDO GOLDSCHMIDT', 'TesteRonaldo', '6c1a4a16fdd9db8ce360413add2ff581', 'ronaldo.rgold@gmail.com', 41, NULL, 'Y'),
(32, 'Aluno Protótipo 2', 'alunoprototipo2', 'bf1c677e27a5657c238dc4542d2aab6a', 'ifsouza@123.com.br', 41, NULL, 'Y'),
(33, 'Letícia Kruger Echterhoff', 'Letícia.kruger', '663bdfaea1c953e57f2e28dd8a01b0e6', 'leticiaechterhoff882@gmail.com', 41, NULL, 'Y'),
(34, 'Phellippe Aprigio', 'phellippe', 'fa3c21a331677986f7f1cae50d88d153', 'phellippe58@gmail.com', 41, NULL, 'Y'),
(35, 'Felipe Nunes Garlet', 'Felipe ', '7ce512941e1fc46f570baabbc04fa53d', 'felipe.garlet@escola.pr.gov.br', 41, NULL, 'Y'),
(36, 'Nicolas Gabriel Campos', 'nicogcampos', 'eb84f88697f304364d976d9fcea1f89c', 'nicolas.gabriel.campos@escola.pr.gov.br', 41, NULL, 'Y'),
(37, 'vinicius fenske', 'vinifenske', 'dec45cb77a72f4df76497b6e60a3288f', 'vinigus.guilherme.fenske@gmail.com', 41, NULL, 'Y'),
(38, 'Nathan Miguel Fernandes Pereira Dos Santos', 'Nathan Miguel', 'a4958c0e611e7d1bd5afad1c0588b4ca', 'nathan.sanstos23@escola.pr.gov.br', 41, NULL, 'Y'),
(39, 'Joao Gabriel Simi de Oliveira', 'joao_simi', 'e7c7d8e072aa6bffe57143d1267a7a7d', 'joao02simi@gmail.com', 41, NULL, 'Y'),
(40, 'Eric Henrique Brandão Eich', 'EricEich01', 'd9d822af6a1e661ddc7e8b118085534a', 'ericeich123@gmail.com', 41, NULL, 'Y'),
(41, 'gabriella paula cantele', 'gabriella.cantele', '9b627edf3402c688c641d7d4fbbf902e', 'gabicantele31@gmail.com', 41, NULL, 'Y'),
(42, 'laura felisetti', 'laura felisetti', '767892172a991e2d3b7805e4689a1ff3', 'laurafelisetti0403@gmail.com', 41, NULL, 'Y'),
(43, 'Letícia Niendicker Levandowski', 'Letícia', '1aa613383ae35206ccade92688e58e44', 'leticia.levandowski@escola.pr.gov.br', 41, NULL, 'Y'),
(44, 'Isabelle Bresolin Bilha', 'Isabelle Bilha', '1de4688b3ec8884bb576ff95647f2b5a', 'isabelle_bilha@hotmail.com', 41, NULL, 'Y'),
(45, 'Vitória Aparecida Jacomeli Cecchetto', 'Vitória', '495660c5f152ad2a4a955494de27bcac', 'vitoria.cecchetto@escola.pr.gov.br', 41, NULL, 'Y'),
(46, 'Maria Vitoria Guimarães Marcelino', 'Maria Vitoria Guimarães', '04ba980ee4ee4f4429e83056397c8cf0', 'maria.guimaraes.marcelino@escola.pr.gov.br', 41, NULL, 'Y'),
(47, 'Gabriela Bilha ', 'GabrielaBilhaNunes ', 'fbbe3d58ac25d7269c475164cd8682a3', 'gbilhanunes31@gmail.com', 41, NULL, 'Y'),
(48, 'Alisson Victor de Oliveira', 'Alissonv', 'ebd1b70912a67e8200b456ff13256395', 'alisson.victor.oliveira@escola.pr.gov.br', 41, NULL, 'Y'),
(49, 'Maria Vitória Ávila da Silva de Paula', 'MariaÁvila', '3f9927a60c7e2be9713aa14d1a90093d', 'mariavic10.depaula@gmail.com', 41, NULL, 'Y'),
(50, 'Maria Eduarda Vieira Nunez ', 'MariaEduardaVieira', '0722ff9debfc7b0b90c48b9645b5531d', 'maria.nunez@escola.pr.gov.br', 41, NULL, 'Y'),
(51, 'Pedro Scherer', 'Ppedrosjf', 'cada8b3b1e8ff6a67694f4bda31cf1eb', 'Ppedroschf@gmail.com', 41, NULL, 'Y'),
(52, 'Gabriel de Souza Oliveira ', 'Gabrielzinho', 'e2dec5418533607ab0f2704a952c2501', 'gabriel.souza.oliveira28@escola.pr.gov.br', 41, NULL, 'Y'),
(53, 'Suanne Kim', 'Suanne', '462ebbe5a56eb53596a4a03fe75596e7', 'suanne.kim@escola.pr.gov.br', 41, NULL, 'Y'),
(54, 'Thomas Edson Caimi', 'thomas', 'f55d23b9401948e847299838e37bb7ca', 'thomas.caimi@escola.pr.gov.br', 41, NULL, 'Y'),
(55, 'daniella knopka valencio', 'dani knopka', '1a7b9788e8aae9c91389a33bfa020a83', '@daniellaknopkavalencio.com.br', 41, NULL, 'Y'),
(56, 'Arthur Telles', 'Arthur.Telles.santos', '5650e0eb300d8d8c552c86792ea14839', 'Arhurviniciustellesdossantos@gmail.com', 41, NULL, 'Y'),
(57, 'Guilherme Barbosa Pires', 'barbosa_045', '04ba980ee4ee4f4429e83056397c8cf0', 'guilherme.barbosa.pires@escola.pr.gov.br', 41, NULL, 'Y'),
(58, 'Arthur Avelar', 'Arthur Avelar', '2da7d51b3d3418bc77857d1dc8e4bc8f', 'arthur10avelar@gmail.com', 41, NULL, 'Y'),
(59, 'Luiza Guedes Soares Silva', 'luiza.silva', 'e4c94c048505a49ed98e7e8b70b079e1', 'luizaguedesssilva@gmail.com', 41, NULL, 'Y'),
(60, 'João Victor de Castro Vargas', 'João_Vargas', 'e2aa199f8c563e040882238540c89a89', 'joaovictorcvargas@gmail.com', 41, NULL, 'Y'),
(61, 'Nicolas.Miranda.Batista', 'Niki_Mb', '52cb3554cb0dd0b4bc1849d3625f1e49', 'nickziinn.drkz@gmail.com', 41, NULL, 'Y'),
(62, 'Luan Alex Rodrigues', 'Luan.Cria', 'bd1a857fd7057cf00d2489e2b182b157', 'luanalex395@gmail.com', 41, NULL, 'Y'),
(63, 'ana beatriz dagostin ', 'ana.dagostin', '0722f639cb6177514f731fdfd61d9638', 'anabeatrizdagostin@gmail.com', 41, NULL, 'Y'),
(64, 'Thargo Gasparin Lopez', 'Thargo.lopez', '679ba1e89f10e0a1e75d30502d68e5e3', 'thargo100@gmail.com', 41, NULL, 'Y'),
(65, 'Julia Marconi Gama', 'Skugaa', '489b29e997ea98b2e9dde7e708694daa', '0beylun0@gmail.com', 41, NULL, 'Y'),
(66, 'Maria Julia Nunez Pimentel', 'maria julia nunez', '5476bbee920fc114f1853fbcdc2b8c58', 'maria.nunez.pimentel@escola.pr.gov.br', 41, NULL, 'Y'),
(67, 'Arthur Dantas Barros', 'arthur dantas', '9ab3f8fea1e08e7f45f5b984e821c735', 'arthur.dantas@escola.pr.gov.br', 41, NULL, 'Y'),
(68, 'Ilso Antonio Gehlen Neto', 'Eon!!!', 'd39960afa8fae0f9938389c89c5670a7', 'ilsoantoniogehlenneto@gmail.com', 41, NULL, 'Y'),
(69, 'Yasmin.Moura.Rodrigues', 'Yasmin ', 'a14ffe538d456fb0b63f6b5e1981d03d', 'Yasmin.moura.r10@gmail.com', 41, NULL, 'Y'),
(70, 'Rafaella Santacruz Assmann', 'rafaassmann', 'b1d01a3d5acd1aa12f3d1af521ad9bf6', 'rafasantacruz10@gmail.com', 41, NULL, 'Y'),
(71, 'Petra Silveira Machado', 'petramachado', 'a00725a0b5cdbd76084e01add107e977', 'petramachado23@gamil.com', 41, NULL, 'Y'),
(72, 'Felipe.Gabriel.da.Silva.Pacheco', 'gabriel_f01', '5998ddcb339e4b86980be2f5824ec738', 'felipegabrielpacheco79@gmail.com', 41, NULL, 'Y'),
(73, 'Nathan Ilario Ribeiro', 'Nathan.a.a.a', '4c1b604a0fc1dab6d5b463ddf67835c2', 'nathanribeiro191@gmail.com', 41, NULL, 'Y'),
(74, 'Ademir Gabriel Dure', 'Gabriel Dure', 'f352871a59432614b78659eac5228942', 'ademir.dure@escola.pr.gov.br', 41, NULL, 'Y'),
(75, 'Ana Larissa Pereira Krul', 'Ana Larissa', '6fbed7747576123cc5378b60309577ff', 'a.krul@escola.pr.gov.br', 41, NULL, 'Y'),
(76, 'Vitória Luiza Ferreira Borges', 'Vitória.Luiza', '95603f0fe68472d85963c0732e3c17de', 'boges.vitoria@escola.pr.gov.br', 41, NULL, 'Y'),
(77, 'Liz Werminghoff Carvalho', 'windyee', 'eb3194287e209f3697db4c50eae28078', 'lizwerminghoffcarvalho2006@gmail.com', 41, NULL, 'Y'),
(78, 'Ana Júlia Henriques Mafalda', 'AnaJ.Mafalda', '01bfbbcdb734c1653886d9d5fcecbc44', 'ana.mafalda@escola.pr.gov.br', 41, NULL, 'Y'),
(79, 'lukas gonzalez', 'lukasgym', '9d7fc27b6637a226decf45c6f4037212', 'lukaseorap@gmail.com', 41, NULL, 'Y'),
(80, 'angelica kreinki', 'angelica.kreinki', '3d632b6416269d4fcb39f0549dfd954e', 'angelica.kreinki@escola.pr.gov.br', 41, NULL, 'Y'),
(81, 'luiza da silva caetano', 'luiza caetano', '38b8c6b008a5a583bd132add3aa686af', 'caetano.luiza@escola.pr.gov.br', 41, NULL, 'Y'),
(82, 'Isaac Félix de Oliveira', 'Isaac Félix', '06d4e482d59e0b4b3724ab5d88654d44', 'isaacfelix362@gmail.com', 41, NULL, 'Y'),
(83, 'Leonardo Raphael Toriani', 'Leonardo Toriani', 'c796dbdc03e69f856be7a910aba67614', 'leonardo.toriani@escola.pr.gov.br', 41, NULL, 'Y'),
(84, 'Lettycia ', 'Lettycia.Monsores ', '19b4b9313c621c7bc01fb4ec7ad197d5', 'monsoreslettycia@gmail.com', 41, NULL, 'Y'),
(85, 'João Victor Martins de Paula', 'Its me Jojo', '7a2286deef2f18d81fad691719c2f0e8', 'mjoaovictor662@gmail.com', 41, NULL, 'Y'),
(86, 'Acxel Martins Garda', 'acxel garda', '07ba5c2113ade894e48a74891cf60d11', 'acxel.garda@escola.pr.gov.br', 41, NULL, 'Y'),
(87, 'gabrielly raquel borges', 'gabi.raquel', '9a3fa4c6a861bd7388785f740877076e', 'gabiraquelborges@gmail.com', 41, NULL, 'Y'),
(88, 'Maria Eduarda Vanin', 'maria.vanin', '511557815a976b622e42f665aa7cbf1a', 'mah.vanin2005@gmail.com', 41, NULL, 'Y'),
(89, 'Carlos Isaac Villalba Portillo', 'ayl4.17', '028a5e52a73d47a753727759a3699219', 'carlos.portillo@escola.pr.gov.br', 41, NULL, 'Y'),
(90, 'Kamilly R. Oliveira', 'Kmilly ', 'ded0191d49dd2af8de483a15ba3ba6fb', 'kamilly.reginade.oliveira@escola.pr.gov.br', 41, NULL, 'Y'),
(91, 'gabriel andrade ', 'gabrielandrade', '8952900d4cc1b53d7763dc61a39cb1e1', 'gabrielandrade0302@gmail.com', 41, NULL, 'Y'),
(92, 'Arthur Bernardo Lisboa Santana', 'arthur', 'b6770d3cec409292ca69ae71813d8327', 'arthur.lisboa.santana@escola.pr.gov.br', 41, NULL, 'Y'),
(93, 'elissara dos santos ferreira', 'elissara', '93071acecf14297a5f91db248a4f181a', 'elissaraxxt@gmail.com', 41, NULL, 'Y'),
(94, 'Guilherme Eduardo de Souza Olmedo', 'guiolmedo', 'c45f10a1fb859c2eee66bec8d32911da', 'guilhermeeduardo278@gmail.com', 41, NULL, 'Y'),
(95, 'João Vitor Souza Gonzalez', 'joaoGonzalez', 'fc9ae66833093e93f2a2a574707254b2', 'joaovitorpedro195@gmail.com', 41, NULL, 'Y'),
(96, 'Anna Carolimy Avelino Pinto', 'anna.caroliny.avelino', 'c73a387847102d54641c7ce8fd04d609', 'anna.avelino.pinto@escola.pr.gov.br', 41, NULL, 'Y'),
(97, 'Emily Caroline Berwig', 'Emily.berwig', '4b9a65ef437b6a3b4a35a75b3fb1fe58', 'Emily.berwig@escola.pr.gov.br', 41, NULL, 'Y'),
(98, 'Rafaela eckhardt', 'rafaela.eckhardt', '636b9e5f9e039d685d7724400015d1dd', 'rafaela.buffa@escola.pr.gov.br', 41, NULL, 'Y'),
(99, 'Carlos Eduardo Ziemann da Silva', 'BetaniaZiemann', '3777de0fe3db3740c6387151413d32c4', 'caduloso123@gmail.com', 41, NULL, 'Y'),
(100, 'gabrie gomes reis ', 'gabriel.gomes', '83621183d235d910e6fe645e81b1ff37', 'Gabriel-gomes05@hotmail.com ', 41, NULL, 'Y'),
(101, 'Emili Fortuoso Miranda ', 'Emili.Fortuoso', '8b391ea1a863dc1953dc199c5cb740f0', 'miranda.emili@escola.pr.gov.br', 41, NULL, 'Y'),
(102, 'Rafael Guglielmi Moro', 'Moro_197', '24ddc2fd3b672864ac1c40e2de5b0180', 'moro.rafael@escola.pr.gov.br', 41, NULL, 'Y'),
(103, 'Ana Sara Anício Reis', 'Nasara', '36a05d4c33f06463a0d438850a71ec3f', 'ana.anicio.reis@escola.pr.gov.br', 41, NULL, 'Y'),
(104, 'jheniffer', 'jheniffer.miere', '0a233c11d7fe1787b65db75877d796c3', 'jheniffermier@gmail.com', 41, NULL, 'Y'),
(105, 'Rayssa de Lima Vera', 'Rayssa.vera@escola.pr.gov.br', 'a9db6984f10ddb24dea73b11fb4875b8', 'rayssa.vera@escola.pr.gov.br', 41, NULL, 'Y'),
(106, 'matheus munslinger da silva ', 'matheus ', '9f554c045e5184800cacd648581c613b', 'matheus.munslinger.silva@escola.pr.gov.br', 41, NULL, 'Y'),
(107, 'Mateus Benitez Basso', 'MateusBasso17', '9b74761331971276cb35e52f83e54e1e', 'mateusbenitezbasso@gmail.com', 41, NULL, 'Y'),
(108, 'Teilor Aguiar Lopez ', 'Teilorniga', 'bd9824a04e34981080f27d52a92c2ff6', 'teilorlopez28@gmail.com', 41, NULL, 'Y'),
(109, 'Luan', 'LuanPM', 'f1ae705621a8025b2afbabee01831a42', 'lwlwka2909@gmail.com', 41, NULL, 'Y'),
(110, 'Paulo Victor M. Passos', 'pv', '202cb962ac59075b964b07152d234b70', 'pv@gmail.com', 41, NULL, 'Y'),
(111, 'Ana Carolina', 'anapassos', '202cb962ac59075b964b07152d234b70', 'anapassos@gmail.com', 41, NULL, 'Y'),
(112, 'Núcleo de Atendimento a Pessoas com Necessidade Específica', 'napne', '202cb962ac59075b964b07152d234b70', 'napne@gmail.com', 41, NULL, 'Y'),
(113, 'teste', 'teste', '202cb962ac59075b964b07152d234b70', 'teste@gmail.com', 41, NULL, 'Y'),
(114, 'Treice', 'treice', '202cb962ac59075b964b07152d234b70', 'treice.moreira@ime.eb.br', 41, NULL, 'Y'),
(115, 'Lilian Castanha', 'lilian', '202cb962ac59075b964b07152d234b70', 'contato@jedieduca.com.br', 41, NULL, 'Y'),
(116, 'Isabel Fernandes', 'Isabel', '202cb962ac59075b964b07152d234b70', 'ifernandes@gmail.com', 41, NULL, 'Y'),
(117, 'teste claudio', 'testeClaudio', '123', 'testeclaudio@gmail.com', 41, NULL, 'Y'),
(0, 'userTeste', 'userTeste', 'f4dd816ec522e8a0fe9a0ef7a5260865', 'userTeste@gmail.com', 41, NULL, 'Y');

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_user_group`
--

CREATE TABLE `system_user_group` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) DEFAULT NULL,
  `system_group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `system_user_group`
--

INSERT INTO `system_user_group` (`id`, `system_user_id`, `system_group_id`) VALUES
(27, 16, 3),
(64, 4, 3),
(75, 18, 7),
(76, 19, 4),
(77, 27, 4),
(80, 28, 4),
(81, 29, 4),
(84, 30, 4),
(85, 31, 4),
(86, 32, 4),
(87, 33, 4),
(89, 35, 4),
(90, 36, 4),
(91, 37, 4),
(92, 38, 4),
(93, 39, 4),
(94, 40, 4),
(95, 41, 4),
(96, 42, 4),
(97, 43, 4),
(98, 44, 4),
(99, 45, 4),
(100, 46, 4),
(101, 47, 4),
(102, 48, 4),
(103, 49, 4),
(104, 50, 4),
(105, 51, 4),
(106, 52, 4),
(107, 53, 4),
(108, 54, 4),
(109, 55, 4),
(110, 56, 4),
(111, 57, 4),
(112, 58, 4),
(113, 59, 4),
(114, 60, 4),
(115, 61, 4),
(116, 62, 4),
(117, 63, 4),
(118, 64, 4),
(119, 65, 4),
(120, 66, 4),
(121, 67, 4),
(122, 68, 4),
(123, 69, 4),
(124, 70, 4),
(125, 71, 4),
(126, 72, 4),
(127, 73, 4),
(128, 74, 4),
(129, 75, 4),
(130, 76, 4),
(131, 77, 4),
(132, 78, 4),
(133, 79, 4),
(134, 80, 4),
(135, 81, 4),
(136, 82, 4),
(137, 83, 4),
(138, 84, 4),
(139, 85, 4),
(140, 86, 4),
(141, 87, 4),
(142, 88, 4),
(143, 89, 4),
(144, 90, 4),
(145, 91, 4),
(146, 92, 4),
(147, 93, 4),
(148, 94, 4),
(149, 95, 4),
(150, 96, 4),
(151, 97, 4),
(152, 98, 4),
(153, 99, 4),
(154, 100, 4),
(156, 102, 4),
(157, 103, 4),
(158, 104, 4),
(159, 105, 4),
(160, 106, 4),
(161, 107, 4),
(162, 108, 4),
(163, 101, 4),
(164, 34, 4),
(166, 109, 4),
(167, 110, 4),
(168, 111, 4),
(170, 4, 6),
(172, 16, 6),
(173, 17, 6),
(174, 34, 6),
(175, 112, 6),
(176, 113, 6),
(177, 114, 6),
(184, 1, 1),
(185, 1, 2),
(186, 3, 3),
(187, 3, 6),
(188, 3, 7),
(192, 116, 3),
(193, 116, 6),
(194, 116, 7),
(195, 115, 1),
(196, 115, 3),
(197, 115, 4),
(198, 115, 6),
(199, 117, 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_user_program`
--

CREATE TABLE `system_user_program` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) DEFAULT NULL,
  `system_program_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_user_unit`
--

CREATE TABLE `system_user_unit` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) DEFAULT NULL,
  `system_unit_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `system_user_unit`
--

INSERT INTO `system_user_unit` (`id`, `system_user_id`, `system_unit_id`) VALUES
(1, 28, 1),
(2, 29, 1),
(3, 30, 1),
(4, 31, 1),
(5, 32, 1),
(6, 33, 1),
(8, 35, 1),
(9, 36, 1),
(10, 37, 1),
(11, 38, 1),
(12, 39, 1),
(13, 40, 1),
(14, 41, 1),
(15, 42, 1),
(16, 43, 1),
(17, 44, 1),
(18, 45, 1),
(19, 46, 1),
(20, 47, 1),
(21, 48, 1),
(22, 49, 1),
(23, 50, 1),
(24, 51, 1),
(25, 52, 1),
(26, 53, 1),
(27, 54, 1),
(28, 55, 1),
(29, 56, 1),
(30, 57, 1),
(31, 58, 1),
(32, 59, 1),
(33, 60, 1),
(34, 61, 1),
(35, 62, 1),
(36, 63, 1),
(37, 64, 1),
(38, 65, 1),
(39, 66, 1),
(40, 67, 1),
(41, 68, 1),
(42, 69, 1),
(43, 70, 1),
(44, 71, 1),
(45, 72, 1),
(46, 73, 1),
(47, 74, 1),
(48, 75, 1),
(49, 76, 1),
(50, 77, 1),
(51, 78, 1),
(52, 79, 1),
(53, 80, 1),
(54, 81, 1),
(55, 82, 1),
(56, 83, 1),
(57, 84, 1),
(58, 85, 1),
(59, 86, 1),
(60, 87, 1),
(61, 88, 1),
(62, 89, 1),
(63, 90, 1),
(64, 91, 1),
(65, 92, 1),
(66, 93, 1),
(67, 94, 1),
(68, 95, 1),
(69, 96, 1),
(70, 97, 1),
(71, 98, 1),
(72, 99, 1),
(73, 100, 1),
(75, 102, 1),
(76, 103, 1),
(77, 104, 1),
(78, 105, 1),
(79, 106, 1),
(80, 107, 1),
(81, 108, 1),
(82, 109, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarioinstanciagestora`
--

CREATE TABLE `usuarioinstanciagestora` (
  `id` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idinstanciagestora` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `usuarioinstanciagestora`
--

INSERT INTO `usuarioinstanciagestora` (`id`, `idusuario`, `idinstanciagestora`) VALUES
(64, 3, 100),
(47, 4, 100),
(22, 16, 100),
(58, 19, 100),
(59, 27, 100),
(62, 28, 100),
(63, 29, 100),
(65, 30, 100),
(66, 31, 100),
(67, 32, 100),
(68, 33, 100),
(145, 34, 100),
(70, 35, 100),
(71, 36, 100),
(72, 37, 100),
(73, 38, 100),
(74, 39, 100),
(75, 40, 100),
(76, 41, 100),
(77, 42, 100),
(78, 43, 100),
(79, 44, 100),
(80, 45, 100),
(81, 46, 100),
(82, 47, 100),
(83, 48, 100),
(84, 49, 100),
(86, 50, 100),
(85, 51, 100),
(87, 52, 100),
(88, 53, 100),
(89, 54, 100),
(90, 55, 100),
(91, 56, 100),
(92, 57, 100),
(93, 58, 100),
(94, 59, 100),
(95, 60, 100),
(96, 61, 100),
(97, 62, 100),
(98, 63, 100),
(99, 64, 100),
(100, 65, 100),
(101, 66, 100),
(103, 67, 100),
(102, 68, 100),
(104, 69, 100),
(105, 70, 100),
(107, 71, 100),
(106, 72, 100),
(108, 73, 100),
(109, 74, 100),
(110, 75, 100),
(111, 76, 100),
(112, 77, 100),
(113, 78, 100),
(114, 79, 100),
(115, 80, 100),
(116, 81, 100),
(117, 82, 100),
(118, 83, 100),
(119, 84, 100),
(120, 85, 100),
(121, 86, 100),
(122, 87, 100),
(123, 88, 100),
(124, 89, 100),
(125, 90, 100),
(126, 91, 100),
(127, 92, 100),
(128, 93, 100),
(129, 94, 100),
(130, 95, 100),
(131, 96, 100),
(132, 97, 100),
(133, 98, 100),
(134, 99, 100),
(135, 100, 100),
(144, 101, 100),
(137, 102, 100),
(138, 103, 100),
(139, 104, 100),
(140, 105, 100),
(141, 106, 100),
(142, 107, 100),
(143, 108, 100),
(146, 109, 100),
(147, 112, 1),
(148, 113, 1),
(149, 114, 1),
(150, 3, 100),
(151, 1, 100),
(152, 3, 100),
(153, 115, 100),
(154, 116, 100),
(155, 115, 100),
(156, 115, 100),
(157, 115, 100);

-- --------------------------------------------------------

--
-- Estrutura para view `promptview`
--
DROP TABLE IF EXISTS `promptview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `promptview`  AS SELECT `p`.`id` AS `id`, `p`.`id_eixo` AS `id_eixo`, `p`.`user_prompt1` AS `user_prompt1`, `p`.`user_prompt2` AS `user_prompt2`, `p`.`system_prompt1` AS `system_prompt1`, `p`.`system_prompt2` AS `system_prompt2` FROM `prompt` AS `p` ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `escola`
--
ALTER TABLE `escola`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instanciagestora_escola_fk` (`idinstanciagestora`),
  ADD KEY `municipio_escola_fk` (`idmunicipio`);

--
-- Índices de tabela `habilidade_computacao`
--
ALTER TABLE `habilidade_computacao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `instanciagestora`
--
ALTER TABLE `instanciagestora`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `plano_aula`
--
ALTER TABLE `plano_aula`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `plano_aula_comp_cur`
--
ALTER TABLE `plano_aula_comp_cur`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `plano_aula_habilidades`
--
ALTER TABLE `plano_aula_habilidades`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `prompt_interacao`
--
ALTER TABLE `prompt_interacao`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `habilidade_computacao`
--
ALTER TABLE `habilidade_computacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `plano_aula`
--
ALTER TABLE `plano_aula`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `plano_aula_comp_cur`
--
ALTER TABLE `plano_aula_comp_cur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `plano_aula_habilidades`
--
ALTER TABLE `plano_aula_habilidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `prompt_interacao`
--
ALTER TABLE `prompt_interacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
