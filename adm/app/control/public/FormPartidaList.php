<?php
/**
 * FormPartidaList
 *
 * @version    1.0
 * @package    control
 * @subpackage public
 */
class FormPartidaList extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;

    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder('form_partida_list');
        $this->form->setFormTitle('Partidas');

        $dt_jogo = new TDate('dt_jogo');
        $usuario = new TEntry('usuario');
        $pontuacao = new TEntry('pontuacao');
        $finalizado = new TCombo('finalizado');

        $finalizado->addItems(array('1' => 'Sim', '0' => 'Não'));

        $dt_jogo->setMask('dd/mm/yyyy');
        $dt_jogo->setDatabaseMask('yyyy-mm-dd');

        $dt_jogo->setSize('120');
        $usuario->setSize('70%');
        $pontuacao->setSize('120');
        $finalizado->setSize('120');

        $this->form->addFields([new TLabel('Data')], [$dt_jogo]);
        $this->form->addFields([new TLabel('Usuário')], [$usuario]);
        $this->form->addFields([new TLabel('Pontuação')], [$pontuacao]);
        $this->form->addFields([new TLabel('Finalizado')], [$finalizado]);

        $this->form->setData(TSession::getValue('formPartidaList_filter_data'));

        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('Clear'), new TAction(array($this, 'onClear')), 'fa:eraser red');

        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_dt = new TDataGridColumn('dt_jogo', 'Data Partida', 'left');
        $column_dt->setTransformer(function ($value) {
            $date = new DateTime($value);
            return $date->format('d/m/Y');
        });
        $column_usuario = new TDataGridColumn('usuario', 'Usuário', 'left');
        $column_jogador = new TDataGridColumn('jogador', 'Jogador', 'left');
        $column_idade = new TDataGridColumn('idade', 'Idade', 'center');
        $column_id_pergunta = new TDataGridColumn('id_pergunta', 'Id Pergunta', 'center');
        $column_pontuacao = new TDataGridColumn('pontuacao', 'Pontuação', 'center');
        $column_qtd_acertos = new TDataGridColumn('qtd_acertos', 'Qtd Acertos', 'center');
        $column_qtd_erros = new TDataGridColumn('qtd_erros', 'Qtd Erros', 'center');
        $column_tempo_gasto = new TDataGridColumn('tempo_gasto', 'Tempo Gasto', 'center');
        $column_tutor = new TDataGridColumn('tutor', 'Tutor', 'center');
        $column_tutor->setTransformer(function ($value) {
            return ((int) $value === 1) ? 'Sim' : 'Não';
        });
        $column_finalizado = new TDataGridColumn('finalizado', 'Finalizado', 'center');
        $column_finalizado->setTransformer( function($value, $object, $row) {
            $class = ($value==0) ? 'danger' : 'success';
            $label = ($value==0) ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });

        $this->datagrid->addColumn($column_dt);
        $this->datagrid->addColumn($column_usuario);
        $this->datagrid->addColumn($column_jogador);
        $this->datagrid->addColumn($column_idade);
        $this->datagrid->addColumn($column_id_pergunta);
        $this->datagrid->addColumn($column_pontuacao);
        $this->datagrid->addColumn($column_qtd_acertos);
        $this->datagrid->addColumn($column_qtd_erros);
        $this->datagrid->addColumn($column_tempo_gasto);
        $this->datagrid->addColumn($column_tutor);
        $this->datagrid->addColumn($column_finalizado);

        $this->setColumnOrder($column_dt, 'dt_jogo');
        $this->setColumnOrder($column_usuario, 'usuario');
        $this->setColumnOrder($column_jogador, 'jogador');
        $this->setColumnOrder($column_idade, 'idade');
        $this->setColumnOrder($column_id_pergunta, 'id_pergunta');
        $this->setColumnOrder($column_pontuacao, 'pontuacao');
        $this->setColumnOrder($column_qtd_acertos, 'qtd_acertos');
        $this->setColumnOrder($column_qtd_erros, 'qtd_erros');
        $this->setColumnOrder($column_tempo_gasto, 'tempo_gasto');
        $this->setColumnOrder($column_tutor, 'tutor');
        $this->setColumnOrder($column_finalizado, 'finalizado');

        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('far:trash-alt red');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);

        $this->datagrid->createModel();

        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup('Partidas');
        $panel->add($this->datagrid)->style = 'overflow-x:auto';
        $panel->addFooter($this->pageNavigation);

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);
    }

    /**
     * Configure column ordering.
     */
    private function setColumnOrder(TDataGridColumn $column, $field)
    {
        $action = new TAction(array($this, 'onReload'));
        $action->setParameter('order', $field);
        $column->setAction($action);
    }

    /**
     * Register filters in the session.
     */
    public function onSearch($param = NULL)
    {
        $data = $this->form->getData();

        TSession::setValue('formPartidaList_filter_dt_jogo', NULL);
        TSession::setValue('formPartidaList_filter_usuario', NULL);
        TSession::setValue('formPartidaList_filter_pontuacao', NULL);
        TSession::setValue('formPartidaList_filter_finalizado', NULL);

        if (isset($data->dt_jogo) AND $data->dt_jogo)
        {
            TSession::setValue('formPartidaList_filter_dt_jogo', $data->dt_jogo);
        }

        if (isset($data->usuario) AND $data->usuario)
        {
            TSession::setValue('formPartidaList_filter_usuario', $data->usuario);
        }

        if (isset($data->pontuacao) AND $data->pontuacao !== '')
        {
            TSession::setValue('formPartidaList_filter_pontuacao', $data->pontuacao);
        }

        if (isset($data->finalizado) AND $data->finalizado !== '')
        {
            TSession::setValue('formPartidaList_filter_finalizado', $data->finalizado);
        }

        $this->form->setData($data);
        TSession::setValue('formPartidaList_filter_data', $data);

        $param = array();
        $param['offset'] = 0;
        $param['first_page'] = 1;

        $this->onReload($param);
    }

    /**
     * Clear filters.
     */
    public function onClear($param = NULL)
    {
        TSession::setValue('formPartidaList_filter_dt_jogo', NULL);
        TSession::setValue('formPartidaList_filter_usuario', NULL);
        TSession::setValue('formPartidaList_filter_pontuacao', NULL);
        TSession::setValue('formPartidaList_filter_finalizado', NULL);
        TSession::setValue('formPartidaList_filter_data', NULL);

        $this->form->clear();
        $this->onReload(array('offset' => 0, 'first_page' => 1));
    }

    /**
     * Normalize date values coming from TDate/session.
     */
    private function normalizeDate($date)
    {
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date))
        {
            $parts = explode('/', $date);
            return "{$parts[2]}-{$parts[1]}-{$parts[0]}";
        }

        return $date;
    }

    /**
     * Load records.
     */
    public function onReload($param = NULL)
    {
        try
        {
            TTransaction::open('jedieduca');
            $conn = TTransaction::get();

            $limit = 10;
            $offset = isset($param['offset']) ? (int) $param['offset'] : 0;
            $allowedOrder = array(
                'dt_jogo' => 'pp.dt_jogo',
                'usuario' => 'usuario',
                'jogador' => 'pp.jogador',
                'idade' => 'pp.idade',
                'id_pergunta' => 'id_pergunta',
                'pontuacao' => 'pp.pontuacao',
                'qtd_acertos' => 'pp.qtd_acertos',
                'qtd_erros' => 'pp.qtd_erros',
                'tempo_gasto' => 'pp.tempo_gasto',
                'tutor' => 'pp.tutor',
                'finalizado' => 'pp.finalizado',
                'id' => 'pp.id'
            );

            $order = isset($param['order'], $allowedOrder[$param['order']]) ? $allowedOrder[$param['order']] : 'pp.id';
            $direction = (isset($param['direction']) && strtolower($param['direction']) === 'desc') ? 'desc' : 'asc';
            $where = array();
            $binds = array();

            if ($dt_jogo = TSession::getValue('formPartidaList_filter_dt_jogo'))
            {
                $where[] = 'pp.dt_jogo = :dt_jogo';
                $binds[':dt_jogo'] = $this->normalizeDate($dt_jogo);
            }

            if ($usuario = TSession::getValue('formPartidaList_filter_usuario'))
            {
                $where[] = 'su.name LIKE :usuario';
                $binds[':usuario'] = "%{$usuario}%";
            }

            if (($pontuacao = TSession::getValue('formPartidaList_filter_pontuacao')) !== NULL)
            {
                $where[] = 'pp.pontuacao = :pontuacao';
                $binds[':pontuacao'] = $pontuacao;
            }

            if (($finalizado = TSession::getValue('formPartidaList_filter_finalizado')) !== NULL)
            {
                $where[] = 'pp.finalizado = :finalizado';
                $binds[':finalizado'] = $finalizado;
            }

            $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            $sql = "SELECT pp.id,
                           pp.dt_jogo,
                           COALESCE(su.name, pp.login) AS usuario,
                           pp.jogador,
                           pp.idade,
                           GROUP_CONCAT(DISTINCT lp.id_pergunta ORDER BY lp.id_pergunta SEPARATOR ', ') AS id_pergunta,
                           pp.pontuacao,
                           pp.qtd_acertos,
                           pp.qtd_erros,
                           pp.tempo_gasto,
                           pp.tutor,
                           pp.finalizado
                    FROM partidas_perguntas pp
                    LEFT JOIN system_user su ON su.id = pp.id_usuario
                    LEFT JOIN log_perguntas lp ON lp.id_partida = pp.id
                    {$whereSql}
                    GROUP BY pp.id, pp.dt_jogo, su.name, pp.login, pp.jogador, pp.idade, pp.pontuacao,
                             pp.qtd_acertos, pp.qtd_erros, pp.tempo_gasto, pp.tutor, pp.finalizado
                    ORDER BY {$order} {$direction}
                    LIMIT {$limit} OFFSET {$offset}";

            $stmt = $conn->prepare($sql);
            foreach ($binds as $name => $value)
            {
                $stmt->bindValue($name, $value);
            }
            $stmt->execute();
            $objects = $stmt->fetchAll(PDO::FETCH_OBJ);

            $this->datagrid->clear();
            if ($objects)
            {
                foreach ($objects as $object)
                {
                    $this->datagrid->addItem($object);
                }
            }

            $countSql = "SELECT COUNT(DISTINCT pp.id)
                         FROM partidas_perguntas pp
                         LEFT JOIN system_user su ON su.id = pp.id_usuario
                         {$whereSql}";
            $countStmt = $conn->prepare($countSql);
            foreach ($binds as $name => $value)
            {
                $countStmt->bindValue($name, $value);
            }
            $countStmt->execute();
            $count = (int) $countStmt->fetchColumn();

            $this->pageNavigation->setCount($count);
            $this->pageNavigation->setProperties($param);
            $this->pageNavigation->setLimit($limit);

            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e)
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }

    /**
     * Ask before deletion.
     */
    public function onDelete($param)
    {
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param);

        new TQuestion(_t('Do you really want to delete ?'), $action);
    }

    /**
     * Delete partida and related log records.
     */
    public function Delete($param)
    {
        try
        {
            $key = (int) $param['key'];

            TTransaction::open('jedieduca');

            LogPerguntas::where('id_partida', '=', $key)->delete();
            PartidasPerguntas::where('id', '=', $key)->delete();

            TTransaction::close();

            $this->onReload($param);
            new TMessage('info', _t('Record deleted'));
        }
        catch (Exception $e)
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }

    /**
     * Shows the page.
     */
    public function show()
    {
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'], array('onReload', 'onSearch')))))
        {
            if (func_num_args() > 0)
            {
                $this->onReload(func_get_arg(0));
            }
            else
            {
                $this->onReload();
            }
        }

        parent::show();
    }
}
?>
