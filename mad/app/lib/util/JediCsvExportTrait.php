<?php

use Adianti\Core\AdiantiCoreTranslator;
use Adianti\Database\TTransaction;

/**
 * Exportação CSV padrão dos módulos do JEDi
 *
 * Substitui o exportToCSV do AdiantiStandardListExportTrait:
 * - todos os registros (com os filtros da tela), mesmo nos módulos cujo onReload não devolve os objetos
 * - formato do Excel em português: separador ';', BOM UTF-8 e decimais com vírgula
 */
trait JediCsvExportTrait
{
    /**
     * Export to CSV
     * @param $output Output file
     */
    protected function exportToCSV($output)
    {
        if ( !((!file_exists($output) && is_writable(dirname($output))) OR is_writable($output)) )
        {
            throw new Exception(AdiantiCoreTranslator::translate('Permission denied') . ': ' . $output);
        }

        $this->limit = 0; // todos os registros, não só a página atual

        // Vários módulos sobrescrevem o onReload sem devolver os registros: usa os itens do datagrid
        $objects = $this->onReload([]) ?: $this->datagrid->getItems();

        $handler = fopen($output, 'w');
        fwrite($handler, "\xEF\xBB\xBF"); // BOM: acentos corretos no Excel

        TTransaction::openFake($this->database);

        $columns = array_filter($this->datagrid->getColumns(), fn($column) => $column->isPrintable());

        fputcsv($handler, array_map(fn($column) => $column->getLabel(), $columns), ';', '"', '', "\n");

        foreach ((array) $objects as $object)
        {
            $row = [];
            foreach ($columns as $column)
            {
                $column_name = $column->getName();

                if (isset($object->$column_name))
                {
                    $row[] = is_scalar($object->$column_name) ? $this->csvValue($object->$column_name) : '';
                }
                else if (method_exists($object, 'render'))
                {
                    $column_name = (strpos($column_name, '{') === false) ? ('{' . $column_name . '}') : $column_name;
                    $row[] = $object->render($column_name);
                }
                else
                {
                    $row[] = '';
                }
            }

            fputcsv($handler, $row, ';', '"', '', "\n");
        }

        fclose($handler);
        TTransaction::close();
    }

    /**
     * Decimais com 2 casas e vírgula (74.205492957 -> 74,21): com ';' o Excel em português
     * leria "74.2" como texto ou data. Inteiros e textos saem como estão.
     */
    private function csvValue($value)
    {
        if (is_float($value) || (is_string($value) && preg_match('/^-?\d+\.\d+$/', $value)))
        {
            return number_format((float) $value, 2, ',', '');
        }

        return $value;
    }
}
