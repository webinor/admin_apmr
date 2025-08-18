<?php

namespace App\Services\Misc;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Voter;
use App\Models\Lawyer;
use Illuminate\Support\Str;
use App\Addons\DataConstructor;
use TheIconic\NameParser\Parser;
use App\Addons\FileUploadHandler;
use Illuminate\Support\Facades\DB;
use App\Addons\Misc\ViewsResponder;
use Aspera\Spreadsheet\XLSX\Reader;
use App\Services\Misc\DisplayService;
use Illuminate\Support\Facades\Cache;
use App\Addons\RegistrationCodeExtractor;
use App\Addons\Misc\EditVariablesResponder;
use App\Addons\Misc\ShowVariablesResponder;
use App\Notifications\SendVerificationCode;
use App\Addons\Misc\IndexVariablesResponder;
use App\Addons\Misc\CreateVariablesResponder;
use App\Models\Prestations\TherapeuticClass;
use App\Models\TempLawyer;

class TherapeuticClassService implements
    IndexVariablesResponder,
    CreateVariablesResponder,
    ShowVariablesResponder,
    EditVariablesResponder,
    ViewsResponder
{
    use DataConstructor,
        FileUploadHandler
      //  DisplayService,
      //  RegistrationCodeExtractor
        ;

    public function getVoters()
    {
        $voters = Cache::remember("voters", 3600, function () {
            return Voter::select("id", "code", "participant_id")
                ->with([
                    "participant:id,code,lawyer_id,voting_session_id",
                    "participant.lawyer:id,code,registration_number,first_name,last_name",
                ])
                ->get();
        });

        $details = [];

        foreach ($voters->toArray() as $key => $item) {
            array_push(
                $details,
                Str::upper(
                    $item["participant"]["lawyer"]["first_name"] .
                        " " .
                        $item["participant"]["lawyer"]["last_name"]
                ) .
                    " ( " .
                    $item["participant"]["lawyer"]["registration_number"] .
                    " )"
            );
        }

        // dd($details);

        //  $sorted = collect($details)->sort();

        //$sorted->values()->all();

        sort($details);

        return $details;
    }

    public function getLawyerNotCandidates()
    {
        $lawyers = Cache::remember("lawyers_not_candidates", 10, function () {
            return Lawyer::select(
                "id",
                "code",
                "registration_number",
                "first_name",
                "last_name"
            )
                //->has('participant')
                ->doesntHave("current_participation.candidates")
                ->orderBy("first_name")
                ->with([
                    "current_participation:id,code,lawyer_id,voting_session_id",
                    "current_participation.lawyer:id,code,registration_number,first_name,last_name",
                ])
                ->get();
        });

        $details = [];

        foreach ($lawyers->toArray() as $key => $item) {
            array_push(
                $details,
                Str::upper($item["first_name"] . " " . $item["last_name"]) .
                    " ( " .
                    $item["registration_number"] .
                    " )"
            );
        }

        // dd($details);

        //  $sorted = collect($details)->sort();

        //$sorted->values()->all();

        sort($details);

        return $details;
    }

    public function getLawyerCandidatesOrNot()
    {
        $lawyers = Cache::remember("lawyers_not_candidates", 10, function () {
            return Lawyer::select(
                "id",
                "code",
                "registration_number",
                "first_name",
                "last_name"
            )
                //->has('participant')
                //->doesntHave("current_participation.candidates")
                ->orderBy("first_name")
                ->with([
                    "current_participation:id,code,lawyer_id,voting_session_id",
                    "current_participation.lawyer:id,code,registration_number,first_name,last_name",
                ])
                ->get();
        });

        $details = [];

        foreach ($lawyers->toArray() as $key => $item) {
            array_push(
                $details,
                Str::upper($item["first_name"] . " " . $item["last_name"]) .
                    " ( " .
                    $item["registration_number"] .
                    " )"
            );
        }

        // dd($details);

        //  $sorted = collect($details)->sort();

        //$sorted->values()->all();

        sort($details);

        return $details;
    }

    public function getAllLawyers()
    {
        $lawyers = Cache::remember("all_lawyers", 3600, function () {
            return Lawyer::select(
                "id",
                "code",
                "registration_number",
                "first_name",
                "last_name"
            )
                //->has('participant')
                //  ->doesntHave("current_participation.candidates")
                ->orderBy("first_name")
                /* ->with([
                    "current_participation:id,code,lawyer_id,voting_session_id",
                    "current_participation.lawyer:id,code,registration_number,first_name,last_name",
                ])*/
                // ->take(10)
                ->get();
        });

        $details = [];

        foreach ($lawyers->toArray() as $key => $item) {
            array_push(
                $details,
                Str::upper($item["first_name"] . " " . $item["last_name"]) .
                    " ( " .
                    $item["registration_number"] .
                    " )"
            );
        }

        // dd($details);

        //  $sorted = collect($details)->sort();

        //$sorted->values()->all();

        sort($details);

        return $details;
    }

    public function searchLawyer($lawyer_params)
    {
        $registration_number = $this->extract_registration_number(
            $lawyer_params
        );

        // dd($registration_number);
        $lawyers =
            //Cache::remember('lawyers', 0, function () {
            //return
            Lawyer::select(
                "id",
                "code",
                "registration_number",
                "first_name",
                "last_name",
                "city_id",
                "postbox",
                "email",
                "phone_number"
            )
                ->whereRegistrationNumber($registration_number)
                ->oldest()
                ->with([
                    "address" => function ($query) {
                        $query->select("id", "lawyer_id", "address");
                    },
                    "city" => function ($query) {
                        $query->select("id", "name");
                    },
                    "personal_info" => function ($query) {
                        $query->select(
                            "id",
                            "lawyer_id",
                            "gender",
                            "birthdate"
                        );
                    },
                ])
                ->orderBy("first_name")
                ->paginate(20)

                ->withQueryString();
        // });

        // dd($lawyers);

        $typeahead_url = url("api/getAllLawyers");

        $vars = compact("lawyers", "typeahead_url", "lawyer_params");

        return $vars;
    }
    public function getIndexVariables()
    { 
        $therapeutic_classes =
            //Cache::remember('lawyers', 0, function () {
            //return
            TherapeuticClass::select(
                "id",
                "code",
                "name",
                "slug",
                "coverage",
                "description"
            )
               // ->la()
               
                ->orderBy("name")
                ->paginate(20)

                ->withQueryString();
        // });

        //dd($lawyers);

        $typeahead_url = url("api/getAllLawyers");

        $vars = compact("therapeutic_classes", "typeahead_url");

        return $vars;
    }
    public function getCreateVariables()
    {
        $therapeutic_class = null;
        $action = "create";
        $disabled = "";
        $readonly = "";

    

        return compact("therapeutic_class", "action", "disabled", "readonly");
    }

    public function getShowVariables($lawyer)
    {
            //$lawyer->load(["phone_numbers"]);
            $action = "show";
            $disabled = "disabled=disabled";
            $readonly = "readonly";
            $instance_type = Lawyer::class;
            // $resource_types = $this->resource_types;

            $cities = City::select("id", "code", "name")
                ->orderBy("name")
                ->get();

            //  dd($lawyer);

            return compact(
                "lawyer",
                "action",
                "disabled",
                "readonly",
                "instance_type",
                "cities"
            );
    }

    public function generate_verification_code($therapeutic_class_details)
    {
        $lawyer = Lawyer::whereCode($therapeutic_class_details["lawyer"])->first();

        if (!$lawyer) {
            return [
                "status" => false,
                "errors" => [
                    "registration_number" => ["Avocat introuvable"],
                ],
            ];
        }

        if ($lawyer->get_phone_number() == "") {
            return [
                "status" => false,
                "errors" => [
                    "phone_number" => [
                        "Vous devez ajouter un numéro de téléphone avant de creer un code secret",
                    ],
                ],
            ];
        }

        try {
            DB::beginTransaction();

            $verification_code = rand(1000, 9999);
            $lawyer->verification_code = $verification_code;
            //$lawyer->password = Hash::make($verification_code);
            $lawyer->save();

            //SendVerificationCodeJob::dispatch($lawyer)->afterCommit();
            $lawyer->notify(
                (new SendVerificationCode($verification_code))->afterCommit()
            );

            DB::commit();

            return [
                "status" => true,
                "data" => [
                    "verification_code" => Str::mask(
                        $verification_code,
                        "*",
                        0,
                        2
                    ),
                ],
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function getEditVariables($lawyer)
    {
        //$lawyer->load(["phone_numbers"]);
        $action = "update";
        $disabled = "";
        $readonly = "";
        $instance_type = Lawyer::class;
        $cities = City::select("id", "code", "name")
            ->orderBy("name")
            ->get();

        return compact(
            "lawyer",
            "action",
            "disabled",
            "readonly",
            "instance_type",
            "cities"
        );
    }
    public function getView($view_name, $vars = [])
    {
        return view($view_name, $vars);
    }


    public function importTherapeuticClasses($therapeutic_class_details)
    {
            $newfile = $this->handleFileUpload(
                $therapeutic_class_details["therapeutic_classes"],
                //  $invoice_details["token"],
                storage_path("app/public/resources_doc")
            );

            if ($newfile) {
                $reader = new Reader();

                $reader->open(
                    storage_path("app/public/resources_doc") . "/" . $newfile->path
                );

                $therapeutic_classes = [];
                $empty_rows = [];
                $index = 0;
                $now = now();

              //  $parser = new Parser();

                foreach ($reader as $row) {

                    if (isset($row[0]) && $row[0] != "") {

                       
                      //  echo $row[0] . " ;; \n";

                      //  $therapeutic_class = new TherapeuticClass();

                        $sanitize_name = preg_replace(
                            "/(\s*\(.*\)\s*)/",
                            "",
                            $row[2]
                        );

                        /*$sanitize_name = $parser->parse($sanitize_name);

                        $registration_number = $row[1];
                        $first_name = Str::of(
                            $sanitize_name->getFirstname() .
                                " " .
                                $sanitize_name->getMiddlename()
                        )->trim();*/
                        $name = Str::of(Str::upper($row[0]))->trim();
                        $slug = Str::of(Str::upper($row[1]))->trim();
                        $coverage = Str::of($row[2])->trim();
                        $description =  $row[3];

                        //dd($oath_date);

                        $therapeutic_class =
                            //$this->fillModel(
                            [
                                "code" => Str::random(10),
                                "name" => $name,
                                "slug" => $slug,
                                "coverage" => $coverage,
                                "description" => $description,
                            ]; //,
                        //   $lawyer
                        //);

                        // echo $lawyer." \n\n";

                        if ( ! TherapeuticClass::whereName($name)->exists()) {
                            array_push($therapeutic_classes, $therapeutic_class);
                        }

                    } else {
                        if (isset($row[0]) && $row[0] != "") {
                            array_push($empty_rows, $row[0]);
                        }
                        //  echo $row[0]." ;; ";
                        // isset($row[0]) ? array_push($empty_rows,isset($row[0])): null ;
                    }

                    $index++;
                }

                //

                $reader->close();

                try {
                    
                    DB::beginTransaction();

                    TherapeuticClass::insert($therapeutic_classes);

                    DB::commit();

                    return [
                        "status" => true,
                        "data" => [
                            "therapeutic_classes" => $therapeutic_classes,
                            "empty_rows" => $empty_rows,
                        ],
                    ];
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
            }
    }

    public function storeTherapeuticClasses($therapeutic_class_details)
    {
        if ($therapeutic_class_details["generation_type"] == "A") {
            return $this->importTherapeuticClasses($therapeutic_class_details);
        } else {
            if (
                TherapeuticClass::select("registration_number")
                    ->whereRegistrationNumber(
                        $therapeutic_class_details["registration_number"]
                    )
                    ->exists()
            ) {
                return [
                    "status" => false,
                    "errors" => [
                        "registration_number" => [
                            "Un avocat avec ce matricule existe deja dans le systeme",
                        ],
                    ],
                ];
            }

            try {
                DB::beginTransaction();

                $lawyer = new Lawyer();

                $city = City::whereCode($therapeutic_class_details["city"])->first();

                $lawyer = $this->fillModel(
                    [
                        "code" => Str::random(10),
                        "first_name" => $therapeutic_class_details["first_name"],
                        "last_name" => $therapeutic_class_details["last_name"],
                        "city_id" => $city->id,
                        "phone_number" => $therapeutic_class_details["phone_number"],
                        "email" => $therapeutic_class_details["email"],
                        "registration_number" =>
                            $therapeutic_class_details["registration_number"],
                    ],
                    $lawyer
                );
                $lawyer->save();

                /*$phone_number = new PhoneNumber();
                $phone_number = $this->fillModel(
                    [
                        "code" => Str::random(5),
                        "phone_number" => $therapeutic_class_details["phone_number"],
                    ],
                    $phone_number
                );

                $lawyer->phone_numbers()->save($phone_number);
                */

                DB::commit();

                return [
                    "status" => true,
                    "data" => ["lawyer" => $lawyer],
                ];
                //code...
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }
        }
    }

    public function updateLawyer($new_therapeutic_class_details, $lawyer)
    {
        if (!$lawyer) {
            return [
                "status" => false,
                "errors" => [
                    "registration_number" => ["Avocat introuvable"],
                ],
            ];
        }

        $other_lawyer = Lawyer::select(
            "id",
            "code",
            "registration_number",
            "email",
            "phone_number"
        )
            ->whereRegistrationNumber(
                $new_therapeutic_class_details["registration_number"]
            )
            //->with("phone_numbers")
            ->first();

        // return $other_lawyer;
        //  return $lawyer;

        if ($other_lawyer && $other_lawyer->code != $lawyer->code) {
            return [
                "status" => false,
                "errors" => [
                    "registration_number" => [
                        "Un avocat avec ce matricule existe deja dans le systeme",
                    ],
                ],
            ];
        }

        try {
            DB::beginTransaction();

            //$lawyer->load(["phone_numbers"]);

            $city = City::whereCode($new_therapeutic_class_details["city"])->first();

            $lawyer = $this->fillModel(
                [
                    "first_name" => $new_therapeutic_class_details["first_name"],
                    "last_name" => $new_therapeutic_class_details["last_name"],
                    "city_id" => $city->id,
                    "registration_number" =>
                        $new_therapeutic_class_details["registration_number"],
                ],
                $lawyer
            );

            if (array_key_exists("email", $new_therapeutic_class_details)) {
                // return $this->can_update_email($other_lawyer , $new_therapeutic_class_details['email']);

                if (
                    !$this->can_update_email(
                        $other_lawyer,
                        $new_therapeutic_class_details["email"]
                    )
                ) {
                    return [
                        "status" => false,
                        "errors" => [
                            "email" => [
                                "Un avocat avec cette adresse email existe deja dans le systeme",
                            ],
                        ],
                    ];
                } else {
                    $lawyer->email = $new_therapeutic_class_details["email"];
                }
            }

            if (array_key_exists("phone_number", $new_therapeutic_class_details)) {
                $this->can_update_phone_number(
                    $lawyer,
                    $new_therapeutic_class_details["phone_number"]
                );

                if (
                    !$this->can_update_phone_number(
                        $lawyer,
                        $new_therapeutic_class_details["phone_number"]
                    )
                ) {
                    return [
                        "status" => false,
                        "errors" => [
                            "phone_number" => [
                                "Un avocat avec ce contact telephonique existe deja dans le systeme",
                            ],
                        ],
                    ];
                }

                $lawyer->phone_number = $new_therapeutic_class_details["phone_number"];

                /*
                if ($lawyer->phone_numbers) {
                    $lawyer->phone_numbers->update([
                        "phone_number" => $new_therapeutic_class_details["phone_number"],
                    ]);
                } else {
                    //////////create a new phone number

                    $phone_number = new PhoneNumber();
                    $phone_number = $this->fillModel(
                        [
                            "code" => Str::random(5),
                            "phone_number" =>
                                $new_therapeutic_class_details["phone_number"],
                        ],
                        $phone_number
                    );

                    $lawyer->phone_numbers()->save($phone_number);
                }
                */
            }

            $lawyer->save();

            DB::commit();

            return [
                "status" => true,
                "data" => ["lawyer" => $lawyer],
            ];
            //code...
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deleteSupplier($supplier)
    {
            $result = $supplier->delete();

            if ($result) {
                return [
                    "status" => true,
                    "success" => [
                        "deleting" => ["Suppression effectuee avec succes"],
                    ],
                ];
            }

            return [
                "status" => false,
                "errors" => [
                    "deleting" => ["Erreur survenue lors de la suppression"],
                ],
            ];
    }
}
