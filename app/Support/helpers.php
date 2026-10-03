<?php

/**
 * Formatea una hora en formato 24h ("14:30:00" o "14:30") como 12 horas con AM/PM.
 * Devuelve un texto alternativo cuando la hora es nula o inválida.
 */
if (! function_exists('formato_hora')) {
    function formato_hora(?string $hora, string $vacio = 'No disponible'): string
    {
        if (! $hora) {
            return $vacio;
        }

        $partes = explode(':', substr($hora, 0, 5));
        if (count($partes) !== 2 || ! ctype_digit($partes[0]) || ! ctype_digit($partes[1])) {
            return $vacio;
        }

        [$h, $m] = array_map('intval', $partes);
        if ($h > 23 || $m > 59) {
            return $vacio;
        }

        $sufijo = $h < 12 ? 'AM' : 'PM';
        $h12 = $h % 12;
        if ($h12 === 0) {
            $h12 = 12;
        }

        return sprintf('%d:%02d %s', $h12, $m, $sufijo);
    }
}
