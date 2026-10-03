<?php

use Adianti\Registry\TSession;

/**
 * Aplica nas listagens (TStandardList) os filtros padrão do perfil do usuário
 * na primeira abertura da tela, como se o usuário tivesse pesquisado
 */
trait ClassesSchoolFilterTrait
{
    protected function applyDefaultProfileFilters()
    {
        // Respeita os filtros já pesquisados pelo usuário
        if (TSession::getValue(get_class($this) . '_filter_data'))
        {
            return;
        }

        $data = ClassesSchoolService::getDefaultFilterData(in_array('turma', $this->formFilters));

        if (empty($data))
        {
            return;
        }

        $count_filters = 0;

        // Grava os filtros na sessão no mesmo formato do onSearch do TStandardList
        foreach ($this->formFilters as $filterKey => $formFilter)
        {
            if (empty($data->{$formFilter}))
            {
                continue;
            }

            $operator = $this->operators[$filterKey] ?? 'like';
            $value    = $data->{$formFilter};

            if (stristr($operator, 'like'))
            {
                $value = '%' . str_replace(' ', '%', $value) . '%';
            }

            $filter = new TFilter($this->filterFields[$filterKey], $operator, $value);

            TSession::setValue($this->activeRecord . '_filter_' . $formFilter, $filter);
            TSession::setValue($this->activeRecord . '_filter_' . $filterKey, $filter);
            TSession::setValue($this->activeRecord . '_' . $formFilter, $data->{$formFilter});
            $count_filters++;
        }

        TSession::setValue($this->activeRecord . '_filter_data', $data);
        TSession::setValue(get_class($this) . '_filter_data', $data);
        TSession::setValue(get_class($this) . '_filter_counter', $count_filters);
    }
}
