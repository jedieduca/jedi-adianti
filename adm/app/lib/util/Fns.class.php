<?php
/**
 * Fns.class [ HELPER ]
 * Classe para incluir funções!
 */
class Fns
{

    public static function formatDate($dt, $tipo)
    {
        $date = new DateTime($dt);
        if ($tipo==0)
            return $date->format('Y-m-d');
        else if ($tipo==1)
            return $date->format('d-m-Y');
    }

    public static function convertSegToMin($tempo, $abreviado=false)
    {
        //echo '<pre>'; print_r('tempo '.$tempo); echo '</pre>';
        $t=number_format(floatval($tempo),2);
        $n = explode('.', $t);
        $t=str_replace(",","",$t);
        if (intval($t)<60)
            if ($n[1]=='00')
                return intval($t).' segundo(s)';
            else
            {
                if ($abreviado)
                    return intval($t).'s e '.$n[1].' ms';
                else
                    return intval($t).' segundo(s) e '.$n[1].' milésimo(s)';
            }
        else
        {    
            $min=intval($t)/60;
            $seg=intval($t)%60;
        }
        
        if ($n[1]=='00')
        {
            if ($abreviado)
                return intval($min).' min e '.$seg.'s';
            else
                return intval($min).' minuto(s) e '.$seg.' segundo(s)';
        }
        else
        {
            if ($abreviado)
                return intval($min).' min e '.$seg.'s e '.$n[1].' ms';
            else
                return intval($min).' minuto(s) e '.$seg.' segundo(s) e '.$n[1].' milésimo(s)';
        }
    }

    public static function left_replace($text, $find, $replace)
    {
        return implode($replace, explode($find, $text, 2));
    }

    public static function fixEncoding ($value) 
    {
                if ($value === NULL || $value === '') {
                    return $value;
                }

                if (function_exists('mb_check_encoding') && mb_check_encoding($value, 'UTF-8')) {
                    if (strpos($value, 'Ã') !== FALSE || strpos($value, 'Â') !== FALSE) {
                        $sourceEncoding = preg_match('/[\x{0080}-\x{009F}]/u', $value) ? 'ISO-8859-1' : 'Windows-1252';
                        $converted = mb_convert_encoding($value, $sourceEncoding, 'UTF-8');
                        if (mb_check_encoding($converted, 'UTF-8')) {
                            return $converted;
                        }
                    }

                    return $value;
                }

                if (!function_exists('mb_check_encoding') && preg_match('//u', $value)) {
                    if (strpos($value, 'Ã') !== FALSE || strpos($value, 'Â') !== FALSE) {
                        $sourceEncoding = preg_match('/[\x{0080}-\x{009F}]/u', $value) ? 'ISO-8859-1' : 'Windows-1252';
                        $converted = iconv('UTF-8', $sourceEncoding.'//IGNORE', $value);
                        if (preg_match('//u', $converted)) {
                            return $converted;
                        }
                    }

                    return $value;
                }

                if (function_exists('mb_convert_encoding')) {
                    return mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
                }

                return iconv('Windows-1252', 'UTF-8//IGNORE', $value);
    }
}
?>