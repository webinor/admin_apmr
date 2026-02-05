<?php

namespace App\Services\Invoice;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoicePdfService
{
    public function generateAndStore($data, string $action): array
    {
        $pdf = Pdf::loadView("invoice.template", [
            "invoice"    => $data,
            "watermark"  => $action === "preview",
        ]);

        $filename = $action === "preview"
            ? "preview-invoice-{$data->number}.pdf"
            : "invoice-{$data->number}.pdf";

        $path = "invoices/{$filename}";

        Storage::disk('public')->put($path, $pdf->output());

       /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return [
            'path' => $path,
            'url'  => $disk->url($path),
        ];
    }
}
