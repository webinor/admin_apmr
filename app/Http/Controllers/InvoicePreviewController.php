<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Operations\Assistance;
use App\Services\Invoice\InvoiceCalculator;

class InvoicePreviewController extends Controller
{
    
    public function preview(Request $request, InvoiceCalculator $calculator)
{
    $company = $request->company;
    $start   = Carbon::parse($request->date_debut);
    $end     = Carbon::parse($request->date_fin);

    if (!$company) {
       
        return response()->json([]);
    }

    $data = $calculator->calculate($company, $start, $end);

       // Formater les dates : ex "12 novembre 2025"
    $startFormatted = $start->translatedFormat('d F Y');
    $endFormatted   = $end->translatedFormat('d F Y');


    $filters = $request->all();

           $query = Assistance::query()
    ->has("signature") // assistance doit être signée
    ;

// Compagnie
if (!empty($filters["compagny"])) {
    $query->whereHas("ground_agent.company", function ($qry) use ($filters) {
        $qry->whereCode($filters["compagny"]);
    });
}

// Période
if (!empty($filters["date_debut"])) {
    $query->whereDate("created_at", ">=", $filters["date_debut"]);
}
if (!empty($filters["date_fin"])) {
    $query->whereDate("created_at", "<=", $filters["date_fin"]);
}

$totalFichesSignees = (clone $query)->count();

$totalFichesSigneesFacturees = (clone $query)
    ->whereNotNull('invoice_id')
    ->count();

$totalFichesSigneesNonFacturees = $totalFichesSignees - $totalFichesSigneesFacturees;

    return response()->json([
        'count_total_signed'    => $totalFichesSignees,//$data['stats']['total'],
        'count_signed_invoiced'   => $totalFichesSigneesFacturees,//$data['stats']['signed'],
        'count_signed_uninvoiced' => $totalFichesSigneesNonFacturees,//$data['stats']['unsigned'],
        'items'          => $data['items'],
        'totals'         => $data['totals'],
        'company'=>$data['company'],
        'period'=> "Du $startFormatted Au $endFormatted"
    ]);
}
}
