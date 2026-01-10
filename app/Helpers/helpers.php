<?php

use Illuminate\Support\Facades\DB;

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


