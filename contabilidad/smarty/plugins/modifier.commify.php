<?php
function smarty_modifier_commify($string, $decimals=-1, $dec_point='.', $thousands_sep=',')
{
    if ($decimals == -1)
    {
        if (preg_match('/\.\d+/', $string))
            return number_format($string) . preg_replace('/.*(\.\d+).*/', '$1', $string);
        else
            return number_format($string);
    }
    else
        return number_format($string, $decimals, $dec_point, $thousands_sep);
}
?>
