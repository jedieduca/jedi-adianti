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
            'is_secretaria'  => in_array(8, $user_group_ids),
            'is_professor'   => in_array(5, $user_group_ids)
        ];
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
            $usuario_escolas = SchoolsUser::where('id_usuario', '=', $profile['system_user_id'])->load();
            if ($usuario_escolas)
            {
                foreach ($usuario_escolas as $vinculo)
                {
                    $objEscola = Schools::find($vinculo->id_escola);
                    if ($objEscola && !empty($objEscola->nome))
                    {
                        $options_escolas[$objEscola->nome] = $objEscola->nome;
                    }
                }
            }

            asort($options_escolas);

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
                if ($profile['is_professor'])
                {
                    $vinculos_professor = ClassesTeacher::where('id_professor', '=', $profile['system_user_id'])->load();
                    $turma_ids = [];

                    if ($vinculos_professor)
                    {
                        foreach ($vinculos_professor as $v)
                        {
                            $turma_ids[] = $v->id_turma;
                        }
                    }

                    if (!empty($turma_ids))
                    {
                        $turmas = Classes::where('id', 'in', $turma_ids)
                                         ->orderBy('identificacao', 'asc')
                                         ->load();
                    }
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
    * Retorna o TCriteria de segurança filtrado pelo perfil e escolas do usuário
    */
    public static function getSecurityCriteria()
    {
        $profile = self::getUserProfile();
        $criteria = new TCriteria;

        // Se NÃO for administrador, aplica a restrição de segurança
        if (!$profile['is_admin'])
        {
            $user_escolas = TSession::getValue('userescolanames') ?? [];

            if (!empty($user_escolas))
            {
                $criteria->add(new TFilter('escola', 'in', array_values($user_escolas)));
            }
            else
            {
                // Força o retorno de zero registros caso não tenha vínculo com nenhuma escola
                $criteria->add(new TFilter('id', '=', -1));
            }
        }

        return $criteria;
    }
}