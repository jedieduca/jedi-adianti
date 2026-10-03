<?php

use Adianti\Registry\TSession;
use Adianti\Widget\Form\TCombo;

class ClassesSchoolService
{
    /**
     * Retorna os dados de perfil do usuário logado
     */
    public static function getUserProfile()
    {
        $user_group_ids = TSession::getValue('usergroupids') ?? [];
        $system_user_id = TSession::getValue('userid');

        return [
            'system_user_id' => $system_user_id,
            'is_admin'       => in_array(1, $user_group_ids),
            'is_gestor'      => in_array(4, $user_group_ids),
            'is_professor'   => in_array(5, $user_group_ids),
            'is_secretaria'  => in_array(8, $user_group_ids),
        ];
    }

    /**
     * Indica se o usuário deve ficar restrito às turmas vinculadas (professor sem perfil de gestão)
     */
    public static function isRestritoTurmas($profile)
    {
        return $profile['is_professor'] && !$profile['is_gestor'] && !$profile['is_secretaria'];
    }

    /**
     * Retorna os nomes das escolas vinculadas ao usuário logado (ordem alfabética)
     */
    public static function getEscolasUsuario()
    {
        $profile = self::getUserProfile();
        $escolas = [];

        $usuario_escolas = SchoolsUser::where('id_usuario', '=', $profile['system_user_id'])->load();
        if ($usuario_escolas)
        {
            foreach ($usuario_escolas as $vinculo)
            {
                $objEscola = Schools::find($vinculo->id_escola);
                if ($objEscola && !empty($objEscola->nome))
                {
                    $escolas[$objEscola->nome] = $objEscola->nome;
                }
            }
        }

        asort($escolas);
        return $escolas;
    }

    /**
     * Configura e carrega as opções do campo Escola (TCombo)
     */
    public static function loadEscolas(TCombo $fieldEscola)
    {
        $profile = self::getUserProfile();
        $options_escolas = [];

        if ($profile['is_admin'])
        {
            $escolas = Schools::orderBy('nome', 'asc')->load();
            if ($escolas)
            {
                foreach ($escolas as $objEscola)
                {
                    if (!empty($objEscola->nome))
                    {
                        $options_escolas[$objEscola->nome] = $objEscola->nome;
                    }
                }
            }
        }
        else
        {
            $options_escolas = self::getEscolasUsuario();

            if (count($options_escolas) >= 1)
            {
                $fieldEscola->setValue(array_key_first($options_escolas));
            }

            $fieldEscola->setEditable(false);
        }

        $fieldEscola->addItems($options_escolas);
        return $options_escolas;
    }

    /**
     * Carrega a lista de opções de turmas baseada na escola informada e no perfil
     */
    public static function getOptionsTurmas($selected_escola = null)
    {
        $profile = self::getUserProfile();
        $options_turmas = [];
        $turmas = [];

        if (!empty($selected_escola))
        {
            $objEscola = Schools::where('nome', '=', $selected_escola)->first();

            if ($objEscola)
            {
                // Professor (sem perfil de gestão): somente as turmas vinculadas a ele
                if (self::isRestritoTurmas($profile))
                {
                    return self::getTurmasProfessor($objEscola->id);
                }
                else
                {
                    $turmas = Classes::where('id_escola', '=', $objEscola->id)
                                     ->orderBy('identificacao', 'asc')
                                     ->load();
                }
            }
        }
        else if ($profile['is_admin'])
        {
            $turmas = Classes::orderBy('identificacao', 'asc')->load();
        }

        if ($turmas)
        {
            foreach ($turmas as $objTurma)
            {
                $identificacao = $objTurma->identificacao ?? '';
                if (!empty($identificacao))
                {
                    $options_turmas[$identificacao] = $identificacao;
                }
            }
        }

        return $options_turmas;
    }

    /**
     * Retorna as identificações das turmas vinculadas ao professor logado
     * (filtra pela escola quando informada)
     */
    public static function getTurmasProfessor($id_escola = null)
    {
        $profile = self::getUserProfile();

        $turma_ids = [];
        $vinculos_professor = ClassesTeacher::where('id_professor', '=', $profile['system_user_id'])->load();

        if ($vinculos_professor)
        {
            foreach ($vinculos_professor as $v)
            {
                $turma_ids[] = $v->id_turma;
            }
        }

        if (empty($turma_ids))
        {
            return [];
        }

        $repository = Classes::where('id', 'in', $turma_ids);

        if (!empty($id_escola))
        {
            $repository->where('id_escola', '=', $id_escola);
        }

        $identificacoes = [];
        $turmas = $repository->orderBy('identificacao', 'asc')->load();

        if ($turmas)
        {
            foreach ($turmas as $objTurma)
            {
                if (!empty($objTurma->identificacao))
                {
                    $identificacoes[$objTurma->identificacao] = $objTurma->identificacao;
                }
            }
        }

        return $identificacoes;
    }

    /**
    * Retorna o TCriteria de segurança filtrado pelo perfil e escolas do usuário
    * @param $turmaField nome da coluna de turma na view (null quando a view não possui turma)
    */
    public static function getSecurityCriteria($turmaField = 'turma')
    {
        $profile = self::getUserProfile();
        $criteria = new TCriteria;

        // Administrador não sofre restrição de segurança
        if ($profile['is_admin'])
        {
            return $criteria;
        }

        $user_escolas = TSession::getValue('userescolanames') ?? [];

        if (empty($user_escolas))
        {
            // Força o retorno de zero registros caso não tenha vínculo com nenhuma escola
            $criteria->add(new TFilter('id', '=', -1));
            return $criteria;
        }

        $criteria->add(new TFilter('escola', 'in', array_values($user_escolas)));

        // Professor (sem perfil de gestão): restringe às turmas vinculadas a ele
        if ($turmaField && self::isRestritoTurmas($profile))
        {
            // Abre transação somente se o chamador ainda não tiver aberto uma
            $open_transaction = !TTransaction::get();

            if ($open_transaction)
            {
                TTransaction::open('jedi');
            }

            $turmas = self::getTurmasProfessor();

            if ($open_transaction)
            {
                TTransaction::close();
            }

            if (empty($turmas))
            {
                // Força o retorno de zero registros caso não tenha vínculo com nenhuma turma
                $criteria->add(new TFilter('id', '=', -1));
            }
            else
            {
                $criteria->add(new TFilter($turmaField, 'in', array_values($turmas)));
            }
        }

        return $criteria;
    }

    /**
     * Ajusta os parâmetros enviados à API conforme o perfil do usuário
     * @param $params   parâmetros montados pela tela
     * @param $useTurma false quando o endpoint não filtra por turma
     * @return array|null null quando o usuário não tem acesso a nenhum dado
     */
    public static function applySecurityParams(array $params, $useTurma = true)
    {
        $profile = self::getUserProfile();

        // Administrador não sofre restrição de segurança
        if ($profile['is_admin'])
        {
            return $params;
        }

        // Abre transação somente se o chamador ainda não tiver aberto uma
        $open_transaction = !TTransaction::get();

        if ($open_transaction)
        {
            TTransaction::open('jedi');
        }

        // Escola: somente as vinculadas ao usuário
        $escolas = self::getEscolasUsuario();

        if (empty($params['escola']) || !isset($escolas[$params['escola']]))
        {
            $params['escola'] = reset($escolas) ?: null;
        }

        // Turma: professor somente as vinculadas a ele na escola
        if ($useTurma && !empty($params['escola']) && self::isRestritoTurmas($profile))
        {
            $objEscola = Schools::where('nome', '=', $params['escola'])->first();
            $turmas    = $objEscola ? self::getTurmasProfessor($objEscola->id) : [];

            if (empty($params['turma']) || !isset($turmas[$params['turma']]))
            {
                $params['turma'] = reset($turmas) ?: null;
            }

            if (empty($params['turma']))
            {
                // Força o retorno sem dados caso não tenha vínculo com nenhuma turma
                $params['escola'] = null;
            }
        }

        if ($open_transaction)
        {
            TTransaction::close();
        }

        return empty($params['escola']) ? null : array_filter($params);
    }
}