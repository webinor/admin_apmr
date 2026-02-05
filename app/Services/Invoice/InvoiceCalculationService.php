<?php

namespace App\Services\Invoice;

use Carbon\Carbon;

class InvoiceCalculationService
{
    public function calculate(
        string $companyCode,
        string $dateDebut,
        string $dateFin,
        bool $isFinal,
         $invoice_number = null,
         $is_regenerate = false
    ) {

         $calculator = new  InvoiceCalculator();

        return $calculator->calculate(
            $companyCode,
            Carbon::parse($dateDebut),
            Carbon::parse($dateFin),
            true,
            !$isFinal, // mode preview si pas final
            $invoice_number,
            $is_regenerate
        );
    }
}
