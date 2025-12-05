<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class APMRExportPdfService
{
    /**
     * Génère le PDF récapitulatif final APMR.
     *
     * @param  array  $data  Données assemblées par le job final (depuis le cache)
     * @param  \App\Models\Export $export  L’export contenant les infos société + période
     * @return string  Chemin du PDF généré
     */
    public static function generate(array $data, $export): string
    {

        $exportData = json_decode($export->data, true); // array associatif

          //   echo "--------------------------".json_encode($export)."--------------------------\n\n";

        // Variables précises de ta vue
        $pdf = Pdf::loadView('pdf.apmr_recap', [
               'companyImage'     => $exportData['companyImage'] ?? null,
    'companyName'      => $exportData['companyName'] ?? null,
    'month'            => $exportData['month'] ?? null,
    'year'             => $exportData['year'] ?? null,
    'dateDebut'        => $exportData['dateDebut'] ?? null,
    'dateFin'          => $exportData['dateFin'] ?? null,
    'agent'            => $exportData['agent'] ?? null,

            // Données calculées par les jobs
            'lines'            => $data['lines'],
            'wheelChairTypes'  => $data['wheelChairTypes'],
            'totals'           => $data['totals_chairs'],
            'totalAgents'      => $data['total_agents_unique'],
            'agents_per_assistance' =>$data['agents_per_assistance']
        ]);

        $pdf->setPaper("A4", "landscape");

        // Stockage
        $directory = "exports/{$export->id}";
        Storage::makeDirectory($directory);

        $filename =  "APMR_RECAP_". date("d_m_Y_H_i_s").".pdf";// "apmr_recap_{$export->id}.pdf";

        Storage::put("$directory/$filename", $pdf->output());

        return "$directory/$filename";
    }
}
