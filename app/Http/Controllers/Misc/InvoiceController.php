<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\UpdateReferenceRequest;
use App\Models\AssistanceLine;
use App\Models\Company;
use App\Models\Misc\Invoice;
use App\Models\Operations\Assistance;
use App\Services\Misc\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use NumberFormatter;

class InvoiceController extends Controller
{
    
    protected  $invoice_service;

    public function __construct(InvoiceService $invoice_service) {
          $this->invoice_service = $invoice_service;
          
          //$this->authorizeResource(Folder::class, "folder");

        }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Misc\UpdateReferenceRequest  $request
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function update_reference(UpdateReferenceRequest $request, Invoice $invoice)
    {
        return $this->invoice_service->update_reference($request->validated());
    }

    public function preview(Request $request)
    {
       // Récupérer les données depuis la query string
    $companyCode = $request->query('company');
    $month       = $request->query('month');

    // Validation manuelle
    $errors = [];

    if (!$companyCode) {
        $errors['company'] = 'Veuillez sélectionner une compagnie.';
    } elseif (!Company::where('code', $companyCode)->exists()) {
        $errors['company'] = 'La compagnie sélectionnée est invalide.';
    }

    if (!$month) {
        $errors['month'] = 'Veuillez choisir un mois.';
    } elseif (!preg_match('/^\d{4}-\d{2}$/', $month)) {
        $errors['month'] = 'Le mois doit être au format YYYY-MM.';
    }

    if (!empty($errors)) {
        return redirect()->back()->withErrors($errors);
    }

    // Récupérer la compagnie
    $company = Company::with('wheel_chairs')->where('code', $companyCode)->first();

    // Déterminer la période
    $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
    $endDate   = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

    $assistances = Assistance::has('signature')->whereBetween('flight_date', [$startDate, $endDate])->get();

    // Récupérer les lignes
    $lines = AssistanceLine::whereIn('assistance_id', $assistances->pluck('id'))
        //->whereBetween('date', [$startDate, $endDate])
        ->get();
    
        if ($lines->isEmpty()) {
            return back()->withErrors(['msg' => 'Aucune donnée trouvée pour ce mois.']);
        }
    
        // Ici tu peux générer l’aperçu ou la facture
        // return view('invoices.preview', compact('lines'));
        // ou générer directement un PDF


// Construire le tableau
  $items = $company->wheel_chairs->map(function($wc) use ($lines, $company) {
    // Quantité = nombre de lignes d’assistance pour ce type de chaise
    $qty = $lines->where('wheel_chair_id', $wc->id)->count();

    // Prix unitaire depuis la table pivot
    $pu = $wc->pivot->price;

    return [
        'label'  => $wc->name,                 // ex: "Chaises C"
        'qty'    => $qty,                      // ex: 4
        'pu'     => $pu,                       // ex: 20000
        'amount' => $qty * $pu,                // ex: 80000
    ];
});

$items[] = [
    'label'  => 'Abonnement Mensuel',
    'qty'    => 1,
    'pu'     => 1,
    'amount' => $company->mensual_fee,
];


// 1️⃣ Somme HT
$total_ht = collect($items)->sum('amount');

// 2️⃣ TVA (exemple 19,25%)
$tva_rate = 0.1925;
$tva = (int) round($total_ht * $tva_rate);

// 3️⃣ TTC
$ttc = $total_ht + $tva;

// 4️⃣ Ajouter au tableau résultat
$totaux = [
    'total_ht' => $total_ht,
    'tva'      => $tva,
    'ttc'      => $ttc,
];

//$created_date = $martinDateFactory->make(Carbon::parse($material_quote->created_at,'UTC'))->isoFormat('D MMM Y');

$formatter = NumberFormatter::create('fr_FR', NumberFormatter::SPELLOUT);
$formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
$formatter->setAttribute(NumberFormatter::ROUNDING_MODE, NumberFormatter::ROUND_HALFUP);

//////////////////

//$date = '2025-07-15'; // valeur venant de ton input date

// Créer un objet date
$carbon = \Carbon\Carbon::parse($month);

// Formatter en "Mois Année"
$formatted = $carbon->translatedFormat('F Y');

//echo $formatted; // "juillet 2025"


//////////////


$amount_ht=0;
$amount_ttc=0;
$currency=" XAF";
$tva=0;//0.1925;
$tax = "19,25";


$amount_ttc = (int)($amount_ht*(1+$tva));

$str_ttc = $formatter->format($totaux["ttc"]);

//return $totaux;

        $invoice = (object)[
            'logo_provider' => asset("images/LOGO_CAMEROUN_ASSIST.png"),
            'logo_customer' => $company->image_path ? asset('storage/company_images/' . $company->image_path) : "",
            'number' => '25-0629',
            'date' => Carbon::now()->format('d/m/Y'),//'19/08/2025',
            'reference' => Str::upper($company->billing_address),
            'airport' => Str::upper($company->city->name),
            'month' => $formatted,
            'items' => $items,
            'total_ht' => $totaux["total_ht"],
            'tva' => $totaux["tva"],
            'ttc' => $totaux["ttc"],

               // 🔹 Nouvelles infos société
                'po_box' => $company->po_box ?? 'N/A',
                'city_name' => $company->city->name ?? 'N/A',
                'unique_id' => $company->unique_id ?? 'N/A',
                'rc' => $company->rc ?? 'N/A',


            'amount_letters' => $str_ttc,
            'bank_name' => 'CAMEROUN ASSISTANCE SANITAIRE SA',
            'bank' => 'SOCIETE GENERALE CAMEROUN Douala - Joss',
            'code_banque' => '10003',
            'guichet' => '00100',
            'compte' => '05 01 0224449-19',
            'iban' => 'CM21 10003 00100 05010224449-19',
            'bic' => 'SGCMCMCX',
        ]; 
        

        /*
        $invoice = (object)[
            // --- Logos ---
            'logo_provider' => asset("images/LOGO_CAMEROUN_ASSIST.png"),
            'logo_customer' => $company->image_path ? asset('storage/company_images/' . $company->image_path) : "",
        
            // --- Infos facture ---
            'number' => '25-0629',
            'date' => Carbon::now()->format('d/m/Y'),
            'reference' => Str::upper($company->billing_address ?? 'N/A'),
            'airport' => Str::upper($company->city->name ?? 'N/A'),
            'month' => $formatted,
        
            // --- Client (compagnie) ---
            'customer' => [
                'name' => $company->name ?? 'N/A',
                'address' => $company->billing_address ?? 'N/A',
                'po_box' => $company->po_box ?? 'N/A',
                'city' => $company->city->name ?? 'N/A',
                'unique_id' => $company->unique_id ?? 'N/A',
                'rc' => $company->rc ?? 'N/A',
                'contact' => $company->contact ?? 'N/A',
                'phone' => $company->phone ?? 'N/A',
                'email' => $company->email ?? 'N/A',
            ],
        
            // --- Prestataire (infos fixes) ---
            'provider' => [
                'name' => 'ASSISTANCE SANITAIRE SA',
                'address' => 'Douala - Bonapriso, Rue Koloko',
                'po_box' => 'BP 12345 Douala - Cameroun',
                'phone' => '+237 6 99 99 99 99',
                'email' => 'contact@assistancesanitaire.cm',
                'website' => 'www.assistancesanitaire.cm',
            ],
        
            // --- Articles ---
            'items' => $items,
        
            // --- Totaux ---
            'total_ht' => $totaux["total_ht"],
            'tva' => $totaux["tva"],
            'ttc' => $totaux["ttc"],
            'amount_letters' => $str_ttc,
        
            // --- Coordonnées bancaires ---
            'bank' => [
                'bank_name' => 'ASSISTANCE SANITAIRE SA',
                'bank_full' => 'SOCIETE GENERALE CAMEROUN Douala - Joss',
                'code_banque' => '10003',
                'guichet' => '00100',
                'compte' => '05 01 0224449-19',
                'iban' => 'CM21 10003 00100 05010224449-19',
                'bic' => 'SGCMCMCX',
            ],
        ];
        
     */   

        $pdf = Pdf::loadView('invoice.template', compact('invoice'))
        ->setPaper('A4', 'portrait');
        
return $pdf->stream("facture-{$invoice->number}.pdf");

return $pdf->download("facture-{$invoice->number}.pdf");
    }

    
}
