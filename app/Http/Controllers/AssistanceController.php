<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssistanceRequest;
use App\Http\Requests\UpdateAssistanceRequest;
use App\Models\Operations\Assistance;
use App\Models\Operations\AssistanceLine;
use App\Services\Misc\AssistanceService;
use Illuminate\Http\Request;

class AssistanceController extends Controller
{
    protected $assistance_service;

    public function __construct(AssistanceService $assistance_service) {
          
      $this->assistance_service = $assistance_service;
        
     // $this->authorizeResource(Company::class, "employee");
  
      }
      /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
      public function index(Request $request)  {
          
          $results = $request->results ? (int)$request->results : 10;
    
    
            $variables = $this->assistance_service->getIndexVariables($results);

           
            return  $this->assistance_service->getView('assistance.index', $variables);
    
        }
  
      /**
       * Show the form for creating a new resource.
       *
       * @return \Illuminate\Http\Response
       */
      public function create()
      {
          
          $variables = $this->assistance_service->getCreateVariables();
           
          return  $this->assistance_service->getView('assistance.manage', $variables);
      }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAssistanceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAssistanceRequest $request)
    {
        //
    }

    public function count_filtered_results(Request $request)  {
        
        return $this->assistance_service->count_filtered_results($request);
    }

    public function countFiltered(Request $request)
    {


    // On part directement des lignes d'assistance
    $query = AssistanceLine::query()
        ->whereHas('assistance.signature') // s'assure que l'assistance a une signature
        ->whereHas('assistance', function($q) use ($request) {

            // Compagnie
            if ($request->filled('compagnie')) {
                $q->whereHas('ground_agent.company', function($qry) use ($request) {
                    $qry->whereCode($request->compagnie);
                });
            }

            // Période
            if ($request->filled('date_debut')) {
                $q->whereDate('created_at', '>=', $request->date_debut);
            }
            if ($request->filled('date_fin')) {
                $q->whereDate('created_at', '<=', $request->date_fin);
            }

            // Enregistré par
            if ($request->filled('user')) {
                $q->whereHas('registrator', function($qry) use ($request) {
                    $qry->whereCode($request->user);
                });
            }

            // Déjà facturées
            if ($request->filled('justificatifs')) {
                $q->where('is_invoiced', true);
            }

            // Min / Max prix
            if ($request->filled('min-price')) {
                $q->where('total', '>=', $request->input('min-price'));
            }
            if ($request->filled('max-price')) {
                $q->where('total', '<=', $request->input('max-price'));
            }
        });

    // Filtre sur Agent (ligne d'assistance)
    if ($request->filled('agent')) {
        $query->whereHas('assistance_agent', function($qry) use ($request) {
            $qry->whereCode($request->agent);
        });
    }

    // Filtre sur Ville (ligne d'assistance)
    if ($request->filled('city')) {
        $query->whereHas('assistance_agent.city', function($qry) use ($request) {
            $qry->whereCode($request->city);
        });
    }

    // Filtre sur Type de chaise (ligne d'assistance)
    if ($request->filled('wheel_chair')) {
        $query->whereHas('wheel_chair', function($qry) use ($request) {
            $qry->whereCode($request->wheel_chair);
        });
    }

    // Comptage exact des lignes filtrées
    $totalLines = $query->count();

    return response()->json([
        'count' => $totalLines
    ]);













//////////////////////old

        $query = Assistance::query()
        ->has('signature')
        ->with('assistance_lines');

        // Compagnie
        if ($request->filled('compagnie')) {
            $query->whereHas('ground_agent.company', function ($qry) use ($request) {

                $qry->whereCode($request->compagnie);
                
            });
        }

        // Agent
        if ($request->filled('agent')) {

            $query->whereHas('assistance_lines.assistance_agent', function ($qry) use ($request) {

                $qry->whereCode($request->agent);
                
            });
        }

        // Période
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        // Type de chaise
        if ($request->filled('wheel_chair')) {
           // $query->where('wheel_chair_code', $request->wheel_chair);

            $query->whereHas('assistance_lines.wheel_chair', function ($qry) use ($request) {

                $qry->whereCode($request->wheel_chair);
                
            });

        }

        // Enregistré par
        if ($request->filled('user')) {
          //  $query->where('user_code', $request->user);//

            $query->whereHas('registrator', function ($qry) use ($request) {

                $qry->whereCode($request->user);
                
            });
        }

        // Ville
        if ($request->filled('city')) {
           // $query->where('city_code', $request->city);

            $query->whereHas('assistance_lines.assistance_agent.city', function ($qry) use ($request) {

                $qry->whereCode($request->city);
                
            });
        }

        // Déjà facturées
        if ($request->filled('justificatifs')) {
            $query->where('is_invoiced', true);
        }

        // Min / Max prix
        if ($request->filled('min-price')) {
            $query->where('total', '>=', $request->input('min-price'));


        }
        if ($request->filled('max-price')) {
            $query->where('total', '<=', $request->input('max-price'));
        }

     return   $results = $query->get()->toArray();

        $totalLines = array_sum(array_map(function($assistance) {
            return count($assistance['assistance_lines'] ?? []);
        }, $results));

        return response()->json(['count' => $totalLines]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Operations\Assistance  $assistance
     * @return \Illuminate\Http\Response
     */
    public function show(Assistance $assistance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Operations\Assistance  $assistance
     * @return \Illuminate\Http\Response
     */
    public function edit(Assistance $assistance)
    {
        $variables = $this->assistance_service->getEditVariables($assistance);
           
        return  $this->assistance_service->getView('assistance.manage', $variables);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAssistanceRequest  $request
     * @param  \App\Models\Operations\Assistance  $assistance
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssistanceRequest $request, Assistance $assistance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Operations\Assistance  $assistance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assistance $assistance)
    {
        //
    }
}
