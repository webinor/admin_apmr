<?php

namespace App\Services\Invoice;

use App\Models\Misc\Invoice;

class InvoiceResponseService
{
    public function success(string $pdfUrl, ?Invoice $invoice = null): array
    {
        return [
            "success" => true,
            "url"     => $pdfUrl,
            "invoice" => $invoice ? $this->formatInvoice($invoice) : null,
        ];
    }

    protected function formatInvoice(Invoice $invoice): array
    {
        return [
            "id"           => $invoice->id,
            "code"         => $invoice->code,
            "company_name" => $invoice->company->name,
            "generated_by" => optional(
                optional($invoice->invoicer)->employee
            )->full_name(),
            "created_at"   => $invoice->created_at->toDateTimeString(),
            "start_date"   => $invoice->start_date->toDateString(),
            "end_date"     => $invoice->end_date->toDateString(),
        ];
    }
}
