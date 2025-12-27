<?php

if (! function_exists('displayInvoiceRow')) {
    /**
     * Affiche une ligne de facture seulement si la valeur est différente de "N/A"
     *
     * @param string $label
     * @param string $value
     * @param int $colspan
     * @return string
     */
    function displayInvoiceRow(string $label, $value, int $colspan = 1)
    {
        if ($value === 'N/A' || empty($value)) {
            return '';
        }

        $colspanAttr = $colspan > 1 ? ' colspan="'.$colspan.'"' : '';

        return '<tr><td'.$colspanAttr.'><strong>'.$label.' :</strong> '.$value.'</td></tr>';
    }
}
