<?php

namespace App\Services\Invoice;

use Carbon\Carbon;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Models\Operations\Assistance;
use App\Models\Operations\AssistanceLine;
use Illuminate\Support\Facades\DB;
use NumberFormatter;

class InvoiceCalculator
{

    public function calculate(
        string $companyCode,
        Carbon $startDate,
        Carbon $endDate,
        $is_generate = false
    ) {
        $company = Company::with("wheel_chairs")
            ->where("code", $companyCode)
            ->firstOrFail();

    //     $quantities = AssistanceLine::query()
    // ->select('wheel_chair_id', DB::raw('COUNT(*) as qty'))
    // ->whereHas('assistance', function ($q) use ($company, $startDate, $endDate) {
    //     $q->has('signature')
    //       ->whereBetween('flight_date', [$startDate, $endDate])
    //       ->whereNull('invoice_id') ;
    // })
    // ->groupBy('wheel_chair_id')
    // ->pluck('qty', 'wheel_chair_id'); // [wheel_chair_id => qty]

    $quantities = AssistanceLine::query()
    ->select(
        'wheel_chair_id',
        DB::raw('COUNT(*) as qty'),
        DB::raw('GROUP_CONCAT(DISTINCT assistance_id) as assistance_ids')
    )
    ->whereHas('assistance', function ($q) use ($company, $startDate, $endDate) {
        $q->has('signature')
          ->whereBetween('flight_date', [$startDate, $endDate])
          ->whereNull('invoice_id');
    })
    ->groupBy('wheel_chair_id')
    ->get()
    ->mapWithKeys(function ($row) {
        return [
            $row->wheel_chair_id => [
                'qty' => (int) $row->qty,
                'assistance_ids' => explode(',', $row->assistance_ids), // transforme en tableau
            ]
        ];
    });



        //dd($assistances);

        $items = collect();

// foreach ($company->wheel_chairs as $wc) {
//     $qty = $quantities[$wc->id] ?? 0;

//     if ($qty === 0) {
//         continue;
//     }

//     $items->push([
//         'is_mensual_fee' => false,
//         'label'  => $wc->name,
//         'qty'    => $qty,
//         'pu'     => $wc->pivot->price,
//         'amount' => $qty * $wc->pivot->price,
//     ]);
// }

foreach ($company->wheel_chairs as $wc) {
    $data = $quantities[$wc->id] ?? null;

    if (!$data || $data['qty'] === 0) {
        continue;
    }

    $items->push([
        'is_mensual_fee' => false,
        'label'  => $wc->name,
        'qty'    => $data['qty'],
        'pu'     => $wc->pivot->price,
        'amount' => $data['qty'] * $wc->pivot->price,
        'assistance_ids' => $data['assistance_ids'], // optionnel, si tu veux garder les IDs
    ]);
}
      


        // dd($items);
        // ➕ abonnement
        $items->push([
    'is_mensual_fee' => true,
    'label'  => 'Abonnement Mensuel',
    'qty'    => 1,
    'pu'     => $company->mensual_fee,
    'amount' => $company->mensual_fee,
]);

        // 🧮 Totaux
        $totalHT = $items->sum("amount");
        $tvaRate = 0.1925;
        $tva = (int) round($totalHT * $tvaRate);
        $ttc = $totalHT + $tva;

        $base = [
            "company" => $company,
            "stats" => [
                //'total'    => $assistances->count(),
                //'signed'   => $assistances->count(), // car has('signature')
                //'unsigned' => 0,
            ],
            "items" => $items,
            "totals" => [
                "ht" => $totalHT,
                "tva" => $tva,
                "ttc" => $ttc,
            ],
        ];

        if ($is_generate == false) {
            return $base;
        }

        $formatter = NumberFormatter::create(
            "fr_FR",
            NumberFormatter::SPELLOUT
        );
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
        $formatter->setAttribute(
            NumberFormatter::ROUNDING_MODE,
            NumberFormatter::ROUND_HALFUP
        );

        $allAssistanceIds = array_merge(...array_map(fn($v) => $v['assistance_ids'], $quantities->toArray()));


        $str_ttc = $formatter->format($ttc);

        // Créer un objet date
        //   $carbon = \Carbon\Carbon::parse($month);

        $startFormatted = $startDate->translatedFormat("d F Y");
        $endFormatted = $endDate->translatedFormat("d F Y");

        // Formatter en "Mois Année"
        //   $formatted = $carbon->translatedFormat("F Y");

        // dd($company);
        return (object) [
             "company" => $company,
            "allAssistanceIds"=>$allAssistanceIds,
            "logo_provider" => asset("images/LOGO_CAMEROUN_ASSIST.png"),
            "logo_customer" => $company->image_path
                ? asset("storage/company_images/" . $company->image_path)
                : "",
            "number" => $company->prefix . "-" . Carbon::now()->format("d/m/Y"),
            "date" => Carbon::now()->format("d/m/Y"), //'19/08/2025',
            "reference" => Str::upper($company->billing_address),
            "airport" => Str::upper($company->city->name),
            // "month" => $formatted,
            "period" => "Du $startFormatted Au $endFormatted",
            "items" => $items,
            "total_ht" => $totalHT,
            "tva" => $tva,
            "ttc" => $ttc,

            // 🔹 Nouvelles infos société
            "po_box" => $company->post_box ?? "N/A",
            "city_name" => $company->city->name ?? "N/A",
            "unique_id" => $company->uni ?? "N/A",
            "rc" => $company->rc ?? "N/A",

            "amount_letters" => $str_ttc,
            "bank_name" => "CAMEROUN ASSISTANCE SANITAIRE SA",
            "bank" => "SOCIETE GENERALE CAMEROUN Douala - Joss",
            "code_banque" => "10003",
            "guichet" => "00100",
            "compte" => "05 01 0224449-19",
            "iban" => "CM21 10003 00100 05010224449-19",
            "bic" => "SGCMCMCX",
        ];
    }
    // public function calculate(
    //     string $companyCode,
    //     Carbon $startDate,
    //     Carbon $endDate,
    //     $is_generate = false
    // ) {
    //     $company = Company::with("wheel_chairs")
    //         ->where("code", $companyCode)
    //         ->firstOrFail();

    //     $assistances = Assistance::select("id")
    //         ->has("signature")
    //         // ->whereHas('ground_agent.company', function ($q) use ($company) {
    //         //     $q->whereCompanyId($company->id);
    //         // })
    //         ->whereBetween("flight_date", [$startDate, $endDate])
    //         ->get();

    //     //dd($assistances);

    //     $lines = AssistanceLine::select(
    //         "id",
    //         "assistance_agent_id",
    //         "assistance_id",
    //         "wheel_chair_id"
    //     )
    //         ->whereIn("assistance_id", $assistances->pluck("id"))
    //         ->get();

    //     // 🧮 LIGNES FACTURABLES
    //     $items = $company->wheel_chairs
    //         ->map(function ($wc) use ($lines) {
    //             $qty = $lines->where("wheel_chair_id", $wc->id)->count();
    //             return [
    //                 "is_mensual_fee" => false,
    //                 "label" => $wc->name,
    //                 "qty" => $qty,
    //                 "pu" => $wc->pivot->price,
    //                 "amount" => $qty * $wc->pivot->price,
    //             ];
    //         })
    //         ->filter(fn($i) => $i["qty"] > 0)
    //         ->values();

    //     // dd($items);
    //     // ➕ abonnement
    //     $items->push([
    //         "is_mensual_fee" => true,
    //         "label" => "Abonnement Mensuel",
    //         "qty" => 1,
    //         "pu" => $company->mensual_fee,
    //         "amount" => $company->mensual_fee,
    //     ]);

    //     // 🧮 Totaux
    //     $totalHT = $items->sum("amount");
    //     $tvaRate = 0.1925;
    //     $tva = (int) round($totalHT * $tvaRate);
    //     $ttc = $totalHT + $tva;

    //     $base = [
    //         "company" => $company,
    //         "stats" => [
    //             //'total'    => $assistances->count(),
    //             //'signed'   => $assistances->count(), // car has('signature')
    //             //'unsigned' => 0,
    //         ],
    //         "items" => $items,
    //         "totals" => [
    //             "ht" => $totalHT,
    //             "tva" => $tva,
    //             "ttc" => $ttc,
    //         ],
    //     ];

    //     if ($is_generate == false) {
    //         return $base;
    //     }

    //     $formatter = NumberFormatter::create(
    //         "fr_FR",
    //         NumberFormatter::SPELLOUT
    //     );
    //     $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
    //     $formatter->setAttribute(
    //         NumberFormatter::ROUNDING_MODE,
    //         NumberFormatter::ROUND_HALFUP
    //     );

    //     $str_ttc = $formatter->format($ttc);

    //     // Créer un objet date
    //     //   $carbon = \Carbon\Carbon::parse($month);

    //     $startFormatted = $startDate->translatedFormat("d F Y");
    //     $endFormatted = $endDate->translatedFormat("d F Y");

    //     // Formatter en "Mois Année"
    //     //   $formatted = $carbon->translatedFormat("F Y");

    //     //dd($company);
    //     return (object) [
    //         "logo_provider" => asset("images/LOGO_CAMEROUN_ASSIST.png"),
    //         "logo_customer" => $company->image_path
    //             ? asset("storage/company_images/" . $company->image_path)
    //             : "",
    //         "number" => $company->prefix . "-" . Carbon::now()->format("d/m/Y"),
    //         "date" => Carbon::now()->format("d/m/Y"), //'19/08/2025',
    //         "reference" => Str::upper($company->billing_address),
    //         "airport" => Str::upper($company->city->name),
    //         // "month" => $formatted,
    //         "period" => "Du $startFormatted Au $endFormatted",
    //         "items" => $items,
    //         "total_ht" => $totalHT,
    //         "tva" => $tva,
    //         "ttc" => $ttc,

    //         // 🔹 Nouvelles infos société
    //         "po_box" => $company->post_box ?? "N/A",
    //         "city_name" => $company->city->name ?? "N/A",
    //         "unique_id" => $company->uni ?? "N/A",
    //         "rc" => $company->rc ?? "N/A",

    //         "amount_letters" => $str_ttc,
    //         "bank_name" => "CAMEROUN ASSISTANCE SANITAIRE SA",
    //         "bank" => "SOCIETE GENERALE CAMEROUN Douala - Joss",
    //         "code_banque" => "10003",
    //         "guichet" => "00100",
    //         "compte" => "05 01 0224449-19",
    //         "iban" => "CM21 10003 00100 05010224449-19",
    //         "bic" => "SGCMCMCX",
    //     ];
    // }
}
