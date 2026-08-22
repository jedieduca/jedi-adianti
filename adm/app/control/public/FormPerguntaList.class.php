<?php
/**
 * FormPerguntaList
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @copyright  Copyright (c) 2021
 * @license    http://www.memore-net.com.br/framework-license
 */
class FormPerguntaList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;
    protected $totalCuradoria;
    protected $totalCuradoriaNaoPublicada;
    protected $totalSemCuradoria;
    protected $totalSemCuradoriaPublicada;

    private function normalizeMultiValue($value)
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (is_array($value)) {
            $items = $value;
        }
        elseif ($value instanceof Traversable) {
            $items = iterator_to_array($value);
        }
        else {
            $items = preg_split('/[\s,;]+/', trim((string) $value));
        }

        $normalized = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                $item = $item['value'] ?? $item['id'] ?? current($item);
            }

            $item = trim((string) $item);
            if ($item !== '' && $item !== 'null' && $item !== '[]') {
                $normalized[] = $item;
            }
        }

        return array_values(array_unique($normalized));
    }
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('jedieduca');            // defines the database
        parent::setActiveRecord('Pergunta');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('pergunta', 'like', 'pergunta'); // filterField, operator, formField
        parent::addFilterField('publica', '=', 'publica'); // filterField, operator, formField
    
        // creates the form
        $this->form = new BootstrapFormBuilder('form_pergunta_list');
        $this->form->setFormTitle('Cadastro de Notícias');
        

        // create the form fields
        $id         = new TEntry('id');
		$pergunta   = new TEntry('pergunta');
        $publica    = new TRadioGroup('publica');
        $publica->addItems(['1' => 'Sim', '0' => 'Não']);
        $publica->setLayout('horizontal');
        $curadoria  = new TRadioGroup('curadoria');
        $curadoria->addItems(['1' => 'Sim', '0' => 'Não']);
        $curadoria->setLayout('horizontal');
        $this->totalCuradoria = new TLabel('0');
        $this->totalCuradoriaNaoPublicada = new TLabel('0');
        $this->totalSemCuradoria = new TLabel('0');
        $this->totalSemCuradoriaPublicada = new TLabel('0');

        //$idTema     = new TDBCombo('idtema','jedieduca','Tema','id','nome');
        $categoria  = new TDBMultiSearch('idCategorias', 'jedieduca', 'Categoria', 'id', 'descricao', 'descricao');
       
        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel('Notícia')], [$pergunta] );
        $this->form->addFields( [new TLabel('Categoria')], [$categoria] );
        $this->form->addFields( [new TLabel('Disponibilizada para o Jogo')], [$publica] );
        $this->form->addFields( [new TLabel('Curadoria')], [$curadoria] );
        $totalLabels = [
            new TLabel('Total com curadoria : '),
            new TLabel('Com curadoria e não publicada : '),
            new TLabel('Total sem curadoria : '),
            new TLabel('Sem curadoria e publicada : ')
        ];
        foreach ($totalLabels as $totalLabel) {
            $totalLabel->style = 'color: black';
        }
        $this->totalCuradoria->style = 'color: black; font-weight: bold';
        $this->totalCuradoriaNaoPublicada->style = 'color: black; font-weight: bold';
        $this->totalSemCuradoria->style = 'color: black; font-weight: bold';
        $this->totalSemCuradoriaPublicada->style = 'color: black; font-weight: bold';
        $totalsTable = new TTable;
        $totalsTable->style = 'width: 100%; margin-left: 0';
        $row = $totalsTable->addRow();
        $row->addCell($totalLabels[0])->add($this->totalCuradoria);
        $row->addCell($totalLabels[1])->add($this->totalCuradoriaNaoPublicada);
        $row->addCell($totalLabels[2])->add($this->totalSemCuradoria);
        $row->addCell($totalLabels[3])->add($this->totalSemCuradoriaPublicada);
        $this->form->addFields([$totalsTable]);

        
        $id->setSize('5%');
        $pergunta->setSize('70%');
        //$publica->setValue('1');
        //$curadoria->setValue('1');
        $categoria->setSize('100%',60);

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('formPerguntaList_filter_data') );
        $publica->setValue(null);
        $curadoria->setValue(null);
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction(array('FormPergunta', 'onEdit')), 'fa:plus green');
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        //$column_id->setVisibility(false);
        $column_curadoria = new TDataGridColumn('analise_proposta', 'Curadoria', 'center');
        //$column_tema = new TDataGridColumn('idtema', 'Tema', 'left');
        $column_pergunta = new TDataGridColumn('pergunta', 'Notícia', 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_curadoria);
        $column_curadoria->setTransformer(function($value, $object, $row) {

            $valor = trim((string) $value);
            $valor2 = trim((string) $object->fala_proposta);

            //echo '<pre>'; print_r($object->analise_proposta); echo '</pre>';
            //echo '<pre>'; print_r($object->fala_proposta); echo '</pre>';

            $isTrue = (($valor !== '') && ($valor2 !== ''));

            $class = $isTrue ? 'success' : 'danger';
            $label = $isTrue ? 'Sim' : 'Não';

            $div = new TElement('span');
            $div->class = "label label-{$class}";
            $div->style = "text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);

            return $div;
        });
        //$this->datagrid->addColumn($column_tema);
        $this->datagrid->addColumn($column_pergunta);

       
        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        /*$order_tema = new TAction(array($this, 'onReload'));
        $order_tema->setParameter('order', 'idtema');
        $column_tema->setAction($order_tema);*/
 
        /*$order_pergunta = new TAction(array($this, 'onReload'));
        $order_pergunta->setParameter('order', 'pergunta');
        $column_pergunta->setAction($order_pergunta);*/

        // create EDIT action
        $action_edit = new TDataGridAction(array('FormPergunta', 'onEdit'));
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('far:edit blue');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('far:trash-alt red');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);
        
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $panel = new TPanelGroup;
        $panel->add($this->datagrid)->style = 'overflow-x:auto';
        $panel->addFooter($this->pageNavigation);
        
        // header actions
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table fa-fw blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf fa-fw red' );
        $panel->addHeaderWidget( $dropdown );
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }

    /**
     * Register the filter in the session
     */
    public function onSearch($param = NULL)
    {
        // get the search form data
        $data = $this->form->getData();
        //echo '<pre>'; print_r($param); echo '</pre>'; return

        if (isset($data->idCategorias)) {
            $data->idCategorias = $this->normalizeMultiValue($data->idCategorias);
        }

        $publica = isset($data->publica) && $data->publica !== ''
            ? (int) $data->publica
            : null;
        $data->publica = $publica;
        
        // clear session filters
        TSession::setValue('formPerguntaList_filter_id',   NULL);
        TSession::setValue('formPerguntaList_filter_noticia',   NULL);
        TSession::setValue('formPerguntaList_filter_idCategorias',   NULL);
        TSession::setValue('formPerguntaList_filter_curadoria',   NULL);

        if (isset($data->id) AND ($data->id)) {
            $filter = new TFilter('id', '=', $data->id); // create the filter
            TSession::setValue('formPerguntaList_filter_id',   $filter); // stores the filter in the session
        }

        if (isset($data->pergunta) AND ($data->pergunta)) {
            $filter = new TFilter('pergunta', 'like', "%{$data->pergunta}%"); // create the filter
            TSession::setValue('formPerguntaList_filter_noticia',   $filter); // stores the filter in the session
        }

        TSession::setValue('formPerguntaList_filter_publica', NULL);
        if ($publica !== null) {
            TSession::setValue('formPerguntaList_filter_publica', new TFilter('publica', '=', $publica));
        }

        if (!empty($data->idCategorias)) {
            $filter = new TFilter('idCategorias', 'IN', $data->idCategorias);
            TSession::setValue('formPerguntaList_filter_idCategorias', $filter);
        }
      
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('formPerguntaList_filter_data', $data);
        
        $param=array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }

    function onReload($param = NULL)
    {
        parent::onReload();

        $limit = 10;

        $data = $this->form->getData();

        try
        {
            TTransaction::open('jedieduca');
            $conn = TTransaction::get();

            // (re)cria view base (sem categorias, pois vamos filtrar por subquery/lista)
            $conn->query('DROP VIEW IF EXISTS perguntaview');

            $sql  = 'CREATE VIEW perguntaview AS ';
            $sql .= 'SELECT p.id, p.id_tema, p.pergunta, p.publica, p.analise_proposta, p.fala_proposta ';
            $sql .= 'FROM pergunta p ';
            $sql .= 'LEFT JOIN tema t ON p.id_tema = t.id ';
            $sql .= 'WHERE p.id_tema = 17 '; // tema default Fake News

            if ((strlen(array_search(1, TSession::getValue('usergroupids'))) == 0) &&
                (strlen(array_search(3, TSession::getValue('usergroupids'))) == 0))
            {
                $sql .= 'AND t.id_autor = ' . (int) TSession::getValue('userid');
            }

            $statsSql = 'SELECT '
                      . 'SUM(CASE WHEN p.analise_proposta IS NOT NULL AND p.fala_proposta IS NOT NULL THEN 1 ELSE 0 END) AS total_curadoria, '
                      . 'SUM(CASE WHEN p.analise_proposta IS NOT NULL AND p.fala_proposta IS NOT NULL AND p.publica = 0 THEN 1 ELSE 0 END) AS total_curadoria_nao_publicada, '
                      . 'SUM(CASE WHEN p.analise_proposta IS NULL OR p.fala_proposta IS NULL THEN 1 ELSE 0 END) AS total_sem_curadoria, '
                      . 'SUM(CASE WHEN (p.analise_proposta IS NULL OR p.fala_proposta IS NULL) AND p.publica = 1 THEN 1 ELSE 0 END) AS total_sem_curadoria_publicada '
                      . 'FROM pergunta p '
                      . 'LEFT JOIN tema t ON p.id_tema = t.id '
                      . 'WHERE p.id_tema = 17';

            if ((strlen(array_search(1, TSession::getValue('usergroupids'))) == 0) &&
                (strlen(array_search(3, TSession::getValue('usergroupids'))) == 0))
            {
                $statsSql .= ' AND t.id_autor = ' . (int) TSession::getValue('userid');
            }

            $stats = $conn->query($statsSql)->fetch(PDO::FETCH_OBJ);
            $this->totalCuradoria->setValue((int) $stats->total_curadoria);
            $this->totalCuradoriaNaoPublicada->setValue((int) $stats->total_curadoria_nao_publicada);
            $this->totalSemCuradoria->setValue((int) $stats->total_sem_curadoria);
            $this->totalSemCuradoriaPublicada->setValue((int) $stats->total_sem_curadoria_publicada);

            if ($data->curadoria==1)
            {
                $sql .= ' AND p.analise_proposta is not null AND p.fala_proposta is not null ';
            }
            else if ($data->curadoria==0)
            {
                $sql .= ' AND (p.analise_proposta is null OR p.fala_proposta is null) ';
            }

            $conn->query($sql);
            // echo '<pre>'; print_r($sql); echo '</pre>';

            $repository = new TRepository('PerguntaView');
            $criteria   = new TCriteria;

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);

            // filtros já existentes
            if (TSession::getValue('formPerguntaList_filter_id')) {
                $criteria->add(TSession::getValue('formPerguntaList_filter_id'));
            }

            if (TSession::getValue('formPerguntaList_filter_noticia')) {
                $criteria->add(TSession::getValue('formPerguntaList_filter_noticia'));
            }

            if (TSession::getValue('formPerguntaList_filter_publica')) {
                $criteria->add(TSession::getValue('formPerguntaList_filter_publica'));
            }

            /*$data = TSession::getValue('formPerguntaList_filter_data');
            if (isset($data->curadoria) && $data->curadoria !== '') {
                if ((string) $data->curadoria === '1') {
                    $criteria->add(new TFilter('analise_proposta', '!=', ''));
                    $criteria->add(new TFilter('fala_proposta', '!=', ''));
                } else {
                    $curadoriaCriteria = new TCriteria;
                    $curadoriaCriteria->add(new TFilter('analise_proposta', '=', ''), TExpression::OR_OPERATOR);
                    $curadoriaCriteria->add(new TFilter('fala_proposta', '=', ''), TExpression::OR_OPERATOR);
                    $criteria->add($curadoriaCriteria);
                }
            }*/

            // ==========================================================
            // ✅ FILTRO idCategorias (via tabela pergunta_categoria)
            // ==========================================================
            if (!empty($data->idCategorias))
            {
                $cats = $this->normalizeMultiValue($data->idCategorias);

                if ($cats)
                {
                    $placeholders = implode(',', array_fill(0, count($cats), '?'));

                    $sqlCats  = "SELECT DISTINCT id_pergunta
                                FROM pergunta_categoria
                                WHERE id_tema = 17
                                AND id_categoria IN ($placeholders)";

                    $stmt = $conn->prepare($sqlCats);
                    $stmt->execute($cats);

                    $idsPerg = $stmt->fetchAll(PDO::FETCH_COLUMN);

                    if (!empty($idsPerg))
                    {
                        $criteria->add(new TFilter('id', 'IN', $idsPerg));
                    }
                    else
                    {
                        $criteria->add(new TFilter('id', '=', 0));
                    }
                }
            }

            // carrega
            $objects = $repository->load($criteria);

            $this->datagrid->clear();
            if ($objects)
            {
                foreach ($objects as $object)
                {
                    $this->datagrid->addItem($object);
                }
            }

            // paginação
            $criteria->resetProperties();
            $count = $repository->count($criteria);

            $this->pageNavigation->setCount($count);
            $this->pageNavigation->setProperties($param);
            $this->pageNavigation->setLimit($limit);

            //TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    function onDelete($param)   
    {
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead
        
        new TQuestion(_t('Do you really want to delete ?'), $action);
    }
    
    function Delete($param)
    {
        try
        {
            $key=$param['key'];
            TTransaction::open('jedieduca');

            //Remove as respostaopcoes
            //$object = new RespostaOpcoes($key);
            //$object->delete($key);

            //select * FROM questao q, spquestao spq WHERE q.id = spq.idquestao and q.id=8
            $conn = TTransaction::get();
            // run query

            $sql="select * FROM log_perguntas lp ";
            $sql.="WHERE lp.id_pergunta=$key";
            $result=$conn->query($sql);

            if ($result->rowCount()>0)
            {
                $this->onReload();
                new TMessage('info', "Pergunta não pode ser removida por já ter sido utilizada em jogo!");
            }
            else
            {
                $conn = TTransaction::get();
                // run query
                $sql='delete FROM pergunta ';
                $sql.='WHERE id='.$key;
                $conn->query($sql);

                $sql='delete FROM pergunta_categoria ';
                $sql.='WHERE id_pergunta='.$key;
                $conn->query($sql);

                TTransaction::close();
                
                parent::onReload();
                new TMessage('info', _t("Record deleted"));
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }
    
}
