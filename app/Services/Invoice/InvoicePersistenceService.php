<?php

namespace App\Services\Invoice;

use App\Models\Misc\Invoice;
use App\Models\Operations\Assistance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InvoicePersistenceService
{
    public function create(array $payload, $data ,  $previous_invoice = null): Invoice
    {
        return DB::transaction(function () use ($payload, $data , $previous_invoice) {

            $invoice = Invoice::create([
                "code"           => Str::random(10),
                "company_id"     => $data->company->id,
                "invoice_number" => $data->number,
                "start_date"     => Carbon::parse($payload['date_debut']),
                "end_date"       => Carbon::parse($payload['date_fin']),
                "created_by"     => auth()->id(),
            ]);

            $invoice->invoice_lines()->createMany(
                collect($data->items)->map(fn ($i) => [
                    "designation" => $i["label"],
                    "quantity"    => $i["qty"],
                    "unit_price"  => $i["pu"],
                    "amount"      => $i["amount"],
                ])->toArray()
            );

            Assistance::whereIn('id', $data->allAssistanceIds)
                ->update(['invoice_id' => $invoice->id]);

            if ($previous_invoice) {
                $previous_invoice->delete();
            }

            return $invoice;
        });
    }
}
