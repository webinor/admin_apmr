<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\UpdateReferenceRequest;
use App\Models\Adjustment;
use App\Models\Company;
use App\Models\Misc\Invoice;
use App\Models\Misc\InvoiceLine;
use App\Models\Operations\Assistance;
use App\Models\Operations\AssistanceLine;
use App\Services\Invoice\InvoiceCalculator;
use App\Services\Misc\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use NumberFormatter;

class InvoiceController extends Controller
{
    protected $invoice_service;

    public function __construct(InvoiceService $invoice_service)
    {
        $this->invoice_service = $invoice_service;

        //$this->authorizeResource(Folder::class, "folder");
    }

    public function index(Request $request)
    {
        $this->authorize("viewAny", Invoice::class);

        // return view("invoice.index" , compact('invoices','companies'));

        $variables = $this->invoice_service->getIndexVariables($request);

        return $this->invoice_service->getView("invoice.index", $variables);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Misc\UpdateReferenceRequest  $request
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function update_reference(
        UpdateReferenceRequest $request,
        Invoice $invoice
    ) {
        return $this->invoice_service->update_reference($request->validated());
    }

    public function generate(Request $request, InvoiceCalculator $calculator)
    {
        $data = $calculator->calculate(
            $request->company,
            Carbon::parse($request->date_debut),
            Carbon::parse($request->date_fin),true
        );

        $action = $request->action;

        /* ===========================
         |  MODE FINAL (PDF + DB)
         =========================== */
        // DB::transaction(function () use (&$invoice, $data) {

          try {
            DB::beginTransaction();

              $invoice = Invoice::create([
                "code" => Str::random(10),
                "invoice_number" => Str::upper(Str::random(8)),
                "created_by" => auth()->id(),
            ]);

            $invoice->invoice_lines()->createMany(
                collect($data->items)
                    ->map(
                        fn($i) => [
                            "designation" => $i["label"],
                            "quantity" => $i["qty"],
                            "unit_price" => $i["pu"],
                            "amount" => $i["amount"],
                        ]
                    )
                    ->toArray()
            );

             Assistance::whereIn('id', $data->allAssistanceIds)
        ->update(['invoice_id' => $invoice->id, 'invoiced_at' => now(),
        'start_date'=>Carbon::parse($request->date_debut),
        'end_date'=>Carbon::parse($request->date_fin),
    'invoiced_by' => auth()->id(),]);

            DB::commit();
          } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
          }
       

        $pdf = Pdf::loadView("invoice.template", [
            "invoice" => $data,
            "watermark" => $action == "preview" ? true : false, // ❌ PAS DE FILIGRANE
        ]);

        $filename = $action == "preview"
            ? "aperçu-facture-{$invoice->invoice_number}.pdf"
            : "facture-{$invoice->invoice_number}.pdf";

        return $pdf->download($filename);
    }

    public function preview(Request $request)
    {
        try {
            //code...

            // Récupérer les données depuis la query string
            $companyCode = $request->query("company");
            $month = $request->query("month");
            $should_generate_invoice = $request->query("invoice");

            // Validation manuelle
            $errors = [];

            if (!$companyCode) {
                $errors["company"] = "Veuillez sélectionner une compagnie.";
            } elseif (!Company::where("code", $companyCode)->exists()) {
                $errors["company"] = "La compagnie sélectionnée est invalide.";
            }

            if (!$month) {
                $errors["month"] = "Veuillez choisir un mois.";
            } elseif (!preg_match('/^\d{4}-\d{2}$/', $month)) {
                $errors["month"] = "Le mois doit être au format YYYY-MM.";
            }

            if (!empty($errors)) {
                return redirect()->back()->withErrors($errors);
            }

            // Récupérer la compagnie
            $company = Company::with("wheel_chairs")
                ->where("code", $companyCode)
                ->first();

            // Déterminer la période
            $startDate = Carbon::createFromFormat(
                "Y-m",
                $month
            )->startOfMonth();
            $endDate = Carbon::createFromFormat("Y-m", $month)->endOfMonth();

            $assistances = Assistance::has("signature")
                ->whereHas("ground_agent.company", function ($query) use (
                    $company
                ) {
                    $query->whereCompanyId($company->id);
                })
                ->whereBetween("flight_date", [$startDate, $endDate])
                ->get();

            // Récupérer les lignes
            $lines = AssistanceLine::whereIn(
                "assistance_id",
                $assistances->pluck("id")
            )
                //->whereBetween('date', [$startDate, $endDate])
                ->get();

            //    dd($lines);

            if ($lines->isEmpty()) {
                return back()->withErrors([
                    "msg" => "Aucune donnée trouvée pour ce mois.",
                ]);
            }

            // Ici tu peux générer l’aperçu ou la facture
            // return view('invoices.preview', compact('lines'));
            // ou générer directement un PDF

            ///////////////////on ajuste selon les ajustements

            $lineAdjustments = collect(
                []
            ); /*Adjustment::where('adjustable_type', "App\Models\Operations\AssistanceLine")
    ->whereIn('adjustable_id', $lines->pluck('id'))
    ->orderBy('created_at') // important
    ->get()
    ->groupBy(fn($a) => $a->adjustable_id . '_' . $a->field)
    ->map(fn($group) => $group->last()); // garder le dernier ajustement
    */

            //  dd($lineAdjustments);

            $linesCorrected = $lines
                ->map(function ($line) use ($lineAdjustments) {
                    // Cherche si ajustement existe pour ce champ
                    foreach (
                        [
                            "wheel_chair_id",
                            "beneficiary_name",
                            "assistance_agent_id",
                            "comment",
                        ]
                        as $field
                    ) {
                        $adjust = $lineAdjustments->get(
                            $line->id . "_" . $field
                        );
                        if ($adjust) {
                            if ($adjust->action === "update") {
                                $line->{$adjust->field} = $adjust->new_value;
                            }
                            if ($adjust->action === "delete") {
                                $line->deleted_by_adjustment = true;
                            }
                        }
                    }
                    return $line;
                })
                ->filter(fn($line) => empty($line->deleted_by_adjustment));

            // dd($linesCorrected);

            //////////////////

            // Construire le tableau
            $items = $company->wheel_chairs
                ->map(function ($wc) use ($linesCorrected, $company) {
                    // Quantité = nombre de lignes d’assistance pour ce type de chaise
                    $qty = $linesCorrected
                        ->where("wheel_chair_id", $wc->id)
                        ->count();

                    // Prix unitaire depuis la table pivot
                    $pu = $wc->pivot->price;

                    return [
                        "is_mensual_fee" => false,
                        "label" => $wc->name, // ex: "Chaises C"
                        "qty" => $qty, // ex: 4
                        "pu" => $pu, // ex: 20000
                        "amount" => $qty * $pu, // ex: 80000
                    ];
                })
                ->filter(fn($item) => $item["qty"] > 0) // garde seulement si qty > 0
                ->values(); // réindexe proprement

            $items[] = [
                "is_mensual_fee" => true,
                "label" => "Abonnement Mensuel",
                "qty" => 1,
                "pu" => 1,
                "amount" => $company->mensual_fee,
            ];

            ////////////////////facturation begin

            if ($should_generate_invoice = true) {
                DB::beginTransaction();

                $invoice = new Invoice();
                $invoice->code = Str::random(10);
                $invoice->invoice_number = Str::random(10);
                // $invoice->created_by = session('user')->id;
                $invoice->created_by = Auth::user()->id;
                $invoice->save();

                Assistance::whereIn("id", $assistances->pluck("id"))->update([
                    "invoice_id" => $invoice->id,
                ]);

                $invoice_lines = [];

                foreach ($items as $content) {
                    $content = (object) $content;
                    //  dd($content);
                    $invoice_lines[] = new InvoiceLine([
                        "designation" => $content->label, // ex: "Chaises C"
                        "quantity" => $content->qty, // ex: 4
                        "unit_price" => $content->pu, // ex: 20000
                        "amount" => $content->qty * $content->pu, // ex: 80000
                    ]);
                }

                // Ensuite tu sauvegardes tous les commentaires liés au post
                $invoice->invoice_lines()->saveMany($invoice_lines);

                //DB::commit();
            }

            ////////////////////facturation end

            // 1️⃣ Somme HT
            $total_ht = collect($items)->sum("amount");

            // 2️⃣ TVA (exemple 19,25%)
            $tva_rate = 0.1925;
            $tva = (int) round($total_ht * $tva_rate);

            // 3️⃣ TTC
            $ttc = $total_ht + $tva;

            // 4️⃣ Ajouter au tableau résultat
            $totaux = [
                "total_ht" => $total_ht,
                "tva" => $tva,
                "ttc" => $ttc,
            ];

            //$created_date = $martinDateFactory->make(Carbon::parse($material_quote->created_at,'UTC'))->isoFormat('D MMM Y');

            $formatter = NumberFormatter::create(
                "fr_FR",
                NumberFormatter::SPELLOUT
            );
            $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
            $formatter->setAttribute(
                NumberFormatter::ROUNDING_MODE,
                NumberFormatter::ROUND_HALFUP
            );

            //////////////////

            //$date = '2025-07-15'; // valeur venant de ton input date

            // Créer un objet date
            $carbon = \Carbon\Carbon::parse($month);

            // Formatter en "Mois Année"
            $formatted = $carbon->translatedFormat("F Y");

            //echo $formatted; // "juillet 2025"

            //////////////

            $amount_ht = 0;
            $amount_ttc = 0;
            $currency = " XAF";
            $tva = 0; //0.1925;
            $tax = "19,25";

            $amount_ttc = (int) ($amount_ht * (1 + $tva));

            $str_ttc = $formatter->format($totaux["ttc"]);

            //dd($company);
            $invoice = (object) [
                "logo_provider" => asset("images/LOGO_CAMEROUN_ASSIST.png"),
                "logo_customer" => $company->image_path
                    ? asset("storage/company_images/" . $company->image_path)
                    : "",
                "number" =>
                    $company->prefix . "-" . Carbon::now()->format("d/m/Y"),
                "date" => Carbon::now()->format("d/m/Y"), //'19/08/2025',
                "reference" => Str::upper($company->billing_address),
                "airport" => Str::upper($company->city->name),
                "month" => $formatted,
                "items" => $items,
                "total_ht" => $totaux["total_ht"],
                "tva" => $totaux["tva"],
                "ttc" => $totaux["ttc"],

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

            $pdf = Pdf::loadView(
                "invoice.template",
                compact("invoice")
            )->setPaper("A4", "portrait");

            return $pdf->stream("facture-{$invoice->number}.pdf");

            return $pdf->download("facture-{$invoice->number}.pdf");
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
