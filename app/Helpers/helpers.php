<?php

use Illuminate\Support\Facades\DB;

if (! function_exists('displayInvoiceRow')) {
    /**
     * @param string $label
     * @param string $value
     * @param int    $colspan
     * @param string $separator
     * @param string $bold  label|value|both|none
     */
    function displayInvoiceRow(
        string $label,
        $value,
        string $separator = ':',
        string $bold = 'label',
        int $colspan = 1
    ) {
        if ($value === 'N/A' || empty($value)) {
            return '';
        }

        $colspanAttr = $colspan > 1 ? ' colspan="'.$colspan.'"' : '';

        // Préparation du label
        $labelHtml = in_array($bold, ['label', 'both'])
            ? '<strong>'.$label.'</strong>'
            : $label;

        // Préparation de la valeur
        $valueHtml = in_array($bold, ['value', 'both'])
            ? '<strong>'.$value.'</strong>'
            : $value;

        return '<tr><td'.$colspanAttr.'>'
            .$labelHtml.' '.$separator.' '.$valueHtml
            .'</td></tr>';
    }
}





if (!function_exists('nextInvoiceNumber')) {
    /**
     * Génère le prochain numéro de facture pour un préfixe donné.
     *
     * @param string $prefix Le préfixe de la série (ex: AF, RW)
     * @param bool $isPreview Si true, ne fait pas l'incrément, juste lecture
     * @return int Le prochain numéro de facture
     */
    function nextInvoiceNumber(string $prefix, bool $isPreview = true): int
    {
        if ($isPreview) {
            // Lecture seule, sans transaction
            $sequence = DB::table('invoice_sequences')
                //->where('prefix', $prefix)
                ->first();

            if (!$sequence) {
                return 1; // Première facture
            }

            return $sequence->last_number + 1;
        }

        // Incrément réel, avec transaction pour éviter les conflits
        return DB::transaction(function () use ($prefix) {
            $sequence = DB::table('invoice_sequences')
                //->where('prefix', $prefix)
                ->lockForUpdate()
                ->first();

            if (!$sequence) {
                // Création de la série si elle n'existe pas
                DB::table('invoice_sequences')->insert([
                    //'prefix' => $prefix,
                    'last_number' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return 1;
            }

            $newNumber = $sequence->last_number + 1;

            // Mise à jour du dernier numéro
            DB::table('invoice_sequences')
                ->where('id', $sequence->id)
                ->update([
                    'last_number' => $newNumber,
                    'updated_at' => now(),
                ]);

            return $newNumber;
        });
    }
}


