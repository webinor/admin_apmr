<?php

namespace App\Http\Controllers;

use App\Addons\DataConstructor;
use Illuminate\Support\Str;
use App\Services\HomeService;
use App\Http\Controllers\Controller;
use App\Models\Operations\ExtractionSetting;
use App\Models\Operations\Provider;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class HomeController extends Controller
{
  use DataConstructor;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( HomeService $homeService )
    {
       
      //  dd(session('menus'));

          $index_variables = $homeService->getIndexVariables();
       
          return  $homeService->getView('home', $index_variables);
  
         
    }


    public function scan()  {


      $extraction_settings = ExtractionSetting::whereProviderId(44)->first();
      $provider = Provider::
      with("provider_category")
      ->whereId(44)
     // ->whereId(4)
      ->first();

      $pathsArray = ["v1t405pTkuX7bzF.pdf"];
     // $pathsArray = ["0Tvi28ft1CT2Ym2.pdf"];
      $home  = $_SERVER['SERVER_NAME'] == '192.168.43.84' ||/**/ $_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1' ? "marcel" : ( $_SERVER['SERVER_NAME'] == "51.254.121.221" || $_SERVER['SERVER_NAME'] == "51.254.121.44" ?  "ubuntu" : "thsailid64");

    //  dd($extraction_settings);

    $extract_params = json_decode(
        $extraction_settings->extract_params,
        true
    );

    //  //echo json_encode($extract_params);
    // //echo  "--pages ".implode(' ', $extract_params["pages"])." --paths ".implode(' ', $this->pathsArray);//".implode(',', $extract_params["pages"]) ,"

    //$faker = Faker\Factory::create();

    //       $process = new Process(['/usr/bin/python3', "/home/".($this->home)."/environments/scan.py" , "--pages ".implode(' ', $extract_params["pages"])." --paths ".implode(' ', $this->pathsArray)]);
    $process = new Process([
        "/usr/bin/python3",
      //  "/home/" . $home . "/environments/scan.py",
        "/home/" . $home . "/environments/scan.py",
        "--words=" . ( json_encode($extract_params["words"])) ,
        //"--words=" . ( !isset($extract_params["prestations_exploded"]) || $extract_params["prestations_exploded"] == "0" ? implode(",", $extract_params["words"]) : json_encode($extract_params["words"])) ,
        "--pages=" . implode(",", $extract_params["pages"]),
        "--paths=" . (storage_path("app/public/extracted_doc/".$pathsArray[0])),
        "--provider_category=" . Str::upper($provider->provider_category->name),
        "--process_image=" . (isset($extract_params["process_image"]) ? $extract_params["process_image"] : "1"),
        "--prestations_exploded=" . (isset($extract_params["prestations_exploded"]) ? $extract_params["prestations_exploded"] : "0"),

       // "--multipage=" . (isset($extract_params["multipage"]) ? $extract_params["multipage"] : "0"),

    ]);

    //echo (!isset($extract_params["prestations_exploded"]) || $extract_params["prestations_exploded"] == "0") ? implode(",", $extract_params["words"]) : json_encode($extract_params["words"])."\n\n"  ;

    $process->setTimeout(3600);
    $process->run();

   // echo   "BEGIN--------------- $this->original_file_name ------------\n\n";

    // executes after the command finishes
    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }

     $out = $process->getOutput();

    return  $result = json_decode($out, true);
      
    }


    public function construct()  {

     $test = [
        "vouchers"=> 
        [
        "case"=> "3276F34139",
        "index_page"=> 1,
        "file_string"=> "B BENEFICIAL 3\nGENERAL INSURANCE ~ So\na\npe a BON DE PRISE EN CHARGE A oo\nie SC .\na (CENTRE MEDICO SOCIAL DE MAROUA(CNPS a\na\na CONCERNANT/ CONCERNING : [(CAS)AYUK Melvis\n— TT Demandeur: (CAS)AYUK Melvis\nDossier/File N° : 9B57C1BE Ref : A774468B\nOuvert le/ Created : | 03/01/2023 Taux de couverture/Coverage rate : 100%\nIDENTIFICATION DE L'ASSURE/BENEFICIAIRE / INSURED IDENTIFICATION\nASSURE / Insured: MOUKOUDI MAMBO Ernest MATRICULE: M35599\nBENEFICIAIRE / Beneficiary: MOUKOUDI MAMBO Ernest\nADRESSE / Address: N/D TELEPHONE /Phone : 690404481\n4 és¢ fy ,\nCLIENT / Customer: ENEQ ae “ GONTRAT / Contract: ENEO\n& 74 ™_ ~ / / =\nELEMENTS DE LA FACTURATION / BILLING ELEMENTS\nENTITE DEFFAGTURATION, Paiegk os daa Ne a |\nADRESSE DE FACTURATION / Billing Address: BP'2985 Rue Bali Koumassi (PASS24 CAMEROUN)\nEMAIL : feedback@pas¢24mobile.com BP : undefined\nPRESTATION / SERVICE\nPHARMACIE\nViet Vir fh\n| Montant Estimatif / Estimated Amount : 7400\nCONDITIONS ET LIMITES / CONDITIONS AND LIMITATIONS\nVisualiser le detail des Conditions de ce code / View the details of the Conditions of this code :\nhttps://prestataires.pass24mobile.com\nCONDITIONS GENERALES / GENERAL CONDITIONS\n|\n¢ Mentionner la référence du bon de prise en charge sur toutes vos factures.\nInclude the GOP reference on all your invoices.\n¢ Exiger une référence pour chaque prise en charge.\nRequire a reference for each GOP.\n¢ Envoyer vos factures a l'adresse de facturation BP 2265 Rue Bali Koumassi (PASS24 CAMEROUN) .\nSend your invoices to the BP 2265 Rue Bali Koumassi (PASS24 CAMEROUN) billing address.\n¢ Faire accompagner les factures par les feuilles de soins de la compagnie d'assurance, signées par vos praticiens.\nHave your invoices accompanied by the insurance company's ca’e sheets, signed by your practitioners.\n",
        
          "prestations"=> [
        [
        "folder"=> "",
        "name"=> "CONSULTATIONS ET VISITES MEDICALES",
        "reference"=> "",
        "amount"=> 0,
        "page_index"=> 1,
        "invoice_string"=> "PRUDENTIAL >>. | Ca | a\nIB BENEFICIAL d., |\nGENERAL INSURANCE . rr\neo BON DE PRISE EN CHARGE A ct\nee (CENTRE MEDICO SOCIAL DE MAROUA(CNPS ae\n.* yg CONCERNANT/ CONCERNING : 3\n_— as Demandeur: | (CAS)BEYANGUE Olive\nDossier/File N° : 9B57C1BE Ref : D659E4ED\nOuvert le/ Created: | 03/01/2023 Taux de couverture/Coverage rate : 100%\nIDENTIFICATION DE L'ASSURE/BENEFICIAIRE / INSURED IDENTIFICATION\nASSURE / Insured: MOUKOUDI MAMBO Ernest MATRICULE: M35599\nBENEFICIAIRE / Beneficiary: MOUKOUDI MAMBO Ernest\nADRESSE / Address: N/D TELEPHONE /Phone : 690404481\n; ' ts fs L\nCLIENT / Customer: ENEQ: ae / / “-GONTRAT / Contract: ENEO\nELEMENTS DE LA FACTURATION / BILLING ELEMENTS\nENTITE DEFFACTURATION:/ Billing Entity’ PRUDENTIAL’ BENEFICIAL.\n] ec mS ROR FS no\nADRESSE DE FACTURA iN / Billing Kadress- BP 2265 Rue Bali Koumassi (PASS24 CAMEROUN)\nEMAIL : feedback@pas$24mobile.com BP : undefined\nPRESTATION / SERVICE\nCONSULTATIONS ET VISITES MEDICALES\nUN f) Ohi NERA\nMontant Estimatif / Estimated Amount : 3000\nCONDITIONS ET LIMITES / CONDITIONS AND LIMITATIONS\nVisualiser le detail des Conditions de ce code / View the details of the Conditions of this code :\n: https://prestataires.pass24mobile.com\nCONDITIONS GENERALES / GENERAL CONDITIONS\n|\n¢ Mentionner la référence du bon de prise en charge sur toutes vos factures.\nInclude the GOP reference on all your invoices.\n¢ Exiger une référence pour chaque prise en charge.\nRequire a reference for each GOP.\n¢ Envoyer vos factures a l'adresse de facturation BP 2265 Rue Bali Koumassi (PASS24 CAMEROUN) .\nSend your invoices to the BP 2265 Rue Bali Koumassi (PASS24 CAMEROUN) billing address.\n¢ Faire accompagner les factures par les feuilles de soins de la compagnie d'assurance, signées par vos praticiens.\nHave your invoices accompanied by the insurance company's care sheets, signed by your practitioners.\n"
        ],
        [
        "folder"=> "",
        "name"=> "ANALYSES BIOLOGIQUES",
        "reference"=> "",
        "amount"=> 0,
        "page_index"=> 2,
        "invoice_string"=> "PRUDENTIAL >\">, | a |\nB BENEFICIAL yen\nGENERAL INSURANCE : Se\nee ie\n+: Be BON DE PRISE EN CHARGE A Ofag\nae (CENTRE MEDICO SOCIAL DE MAROUA(CNPS ane\n‘§ ae ) CONCERNANT/ CONCERNING :\n™ TT Demandeur: | (CAS)AYUK Melvis\nDossier/File N° : 9B57C1BE Ref ; 9AAE632F\nOuvert le/ Created : | 03/01/2023 Taux de couverture/Coverage rate : 100%\nIDENTIFICATION DE L'ASSURE/BENEFICIAIRE / INSURED IDENTIFICATION\nASSURE / Insured: MOUKOUDI MAMBO Ernest MATRICULE: M35599\nBENEFICIAIRE / Beneficiary: MOUKOUDI MAMBO Ernest\nADRESSE / Address: N/D | TELEPHONE /Phone : 690404481\n~ po PLL\nCLIENT / Customer: ENEQ. vt / LY ~~“ GONTRAT / Contract: ENEO\nELEMENTS DE LA FACTURATION / BILLING ELEMENTS\nENTITE DEFFAGTURATION: Billing: Entity® PRU DENTIALBENEFICIAL—\nee SEES Poe cai perscanta 0 pe OR\nADRESSE ‘DE FACTURATION 7 Billing Address: BP 2265 Rue Bali Koumassi (PASS24 CAMEROUN)\nEMAIL : feedback@pas$24mobile.com | | BP : undefined\nPRESTATION / SERVICE\nANALYSES BIOLOGIQUES\nA AA TVIE IN\nMontant Estimatif / Estimated Amount : 18000\nCONDITIONS ET LIMITES / CONDITIONS AND LIMITATIONS\nVisualiser le detail des Conditions de ce code / View the details of the Conditions of this code :\nhttps://prestataires.pass24mobile.com\nCONDITIONS GENERALES / GENERAL CONDITIONS\n¢ Mentionner la référence du bon de prise en charge sur toutes vos factures.\nInclude the GOP reference on all your invoices.\ne Exiger une référence pour chaque prise en charge.\nRequire a reference for each GOP.\n¢ Envoyer vos factures a I'adresse de facturation BP 2265 Rue Bali Koumassi (PASS24 CAMEROUN) .\nSend your invoices to the BP 2265 Rue Bali Koumassi (PASS24 CAMEROUN) billing address.\n¢ Faire accompagner les factures par les feuilles de soins de la compagnie d'assurance, signées par vos praticiens.\nHave your invoices accompanied by the insurance company's care sheets, signed by your practitioners.\n"
        ],
        [
        "folder"=> "",
        "name"=> "PHARMACIE",
        "reference"=> "",
        "amount"=> 0,
        "page_index"=> 3,
        "invoice_string"=> "B BENEFICIAL 3\nGENERAL INSURANCE ~ So\na\npe a BON DE PRISE EN CHARGE A oo\nie SC .\na (CENTRE MEDICO SOCIAL DE MAROUA(CNPS a\na\na CONCERNANT/ CONCERNING : [(CAS)AYUK Melvis\n— TT Demandeur: (CAS)AYUK Melvis\nDossier/File N° : 9B57C1BE Ref : A774468B\nOuvert le/ Created : | 03/01/2023 Taux de couverture/Coverage rate : 100%\nIDENTIFICATION DE L'ASSURE/BENEFICIAIRE / INSURED IDENTIFICATION\nASSURE / Insured: MOUKOUDI MAMBO Ernest MATRICULE: M35599\nBENEFICIAIRE / Beneficiary: MOUKOUDI MAMBO Ernest\nADRESSE / Address: N/D TELEPHONE /Phone : 690404481\n4 és¢ fy ,\nCLIENT / Customer: ENEQ ae “ GONTRAT / Contract: ENEO\n& 74 ™_ ~ / / =\nELEMENTS DE LA FACTURATION / BILLING ELEMENTS\nENTITE DEFFAGTURATION, Paiegk os daa Ne a |\nADRESSE DE FACTURATION / Billing Address: BP'2985 Rue Bali Koumassi (PASS24 CAMEROUN)\nEMAIL : feedback@pas¢24mobile.com BP : undefined\nPRESTATION / SERVICE\nPHARMACIE\nViet Vir fh\n| Montant Estimatif / Estimated Amount : 7400\nCONDITIONS ET LIMITES / CONDITIONS AND LIMITATIONS\nVisualiser le detail des Conditions de ce code / View the details of the Conditions of this code :\nhttps://prestataires.pass24mobile.com\nCONDITIONS GENERALES / GENERAL CONDITIONS\n|\n¢ Mentionner la référence du bon de prise en charge sur toutes vos factures.\nInclude the GOP reference on all your invoices.\n¢ Exiger une référence pour chaque prise en charge.\nRequire a reference for each GOP.\n¢ Envoyer vos factures a l'adresse de facturation BP 2265 Rue Bali Koumassi (PASS24 CAMEROUN) .\nSend your invoices to the BP 2265 Rue Bali Koumassi (PASS24 CAMEROUN) billing address.\n¢ Faire accompagner les factures par les feuilles de soins de la compagnie d'assurance, signées par vos praticiens.\nHave your invoices accompanied by the insurance company's ca’e sheets, signed by your practitioners.\n"
        ]
        ],
        "doc_path"=> "/var/www/extract/storage/app/public/extracted_doc/v1t405pTkuX7bzF.pdf",
        "items"=> [
        "CONSULTATIONS ET VISITES MEDICALES"=> [
        "items_string"=> "' or\na fi™® —\n= CNPs3(,) Nssiz\nCease Nationse de Préwoyance Socide ee Nacenal Social inswrancr Fund\ncis has dike Ween San +. Theo toil “shar deo atone\na ee ee ee\nRedevable ou Bénéficiaire :\nNom : MOUKOUDI MAMBO ERNEST\nAdresse: DOUGOI\nTelephone : 237699620000\nCENTRE MEDICO-SOCIAL DE MAROUA eg\nNote d'honoraires ou de remboursement des prestations AU : 2023-01-11\n°.\n> ein ses /2023 Compagnie d'assurance : °\n: PASS24/PRUDENTIAL BENEFICIAL\nCode assuré : M35599\nTaux : 100%\nEmployeur : ENEO\nNote N° : 05332/2023/CNPS/CMS/GEST/SMVTS\nCode Désignation PU Qte Montant Taux Part Part Assurance\nPatient\nHS-ACT-000002 Consultations assure 3 000 1 3000 100% 0 3 000\nHS-PHARM-006910 PRODUITS PHARMACEUTIQUE 13050 1 13050 100% 0 13 050\nHS-ACT-LABO-000349 EXAMENS LABORATOIRE 18 000 1 18000 100% 0 18 000\nPart 34 050 XAF\nAssurance\nLa présente note est arrétée ala somme de: Trente quatre mille cinquante FCFA CW , :\n4 “7\nMOUVEMENT \\e\\ LE COMPTABLE oy, — LE GES ONNAIRE\neS Me — = 4 > a = Ws age a ——_- “\n=, ge al ‘ r Y ge “= LA ¢ ‘ e -\nCENTRAMEDICO-SOCIAL CNPSSEMAZOdS (CHMSM) - & , ~A o> Js 593626) FA\nresse : 0 x yrs. «SAP i Us N° ROM ROD LENO JbCL\nSeal ound amen ‘ ‘5 satel : ene a RIB : 10096 10020 005221001091 41\n=\n",
        "page_index"=> 4,
        "index_page"=> 4
        ],
        "ANALYSES BIOLOGIQUES"=> [
        "items_string"=> "me € . ~~\nCNress ee Nissi\nNom du patient: MOUKOUDI MAMBO\nCentre Médico-Social de Maroua ERNEST\nB.P.: 120 Maroua-Tél.: 242 050 521 Assurance: PASS24/PRUDENTIAL\nPO BOX Email: cms.maroua@cnps.cm BENEFICIAL\nTaux: 100%\nConsommation laboratoire\n(Période allant du 01-01-2023 au 12-01-2023)\nes\nexamen\n2023-01-09\na\nFROTTIS\nASSURE ie\n2023-01-09\nCs [ewsssme | ae | | a ‘| a\n* 2023-01-09\na\nBe een ee eee eee\nFf fot assurance: 18000 FCFA |\nMaroua le 11-01-2023\n\\s\nWy\nr} ‘\nC/ cyalou 4601\nCENTRE MEDICO-SOCIAL CNPS DE MARQUA (CMSM) N* CONT. . 4931812693326Y\n",
        "page_index"=> 5,
        "index_page"=> 5
        ],
        "PHARMACIE"=> [
        "items_string"=> ", . LY é 2 ee\nCNP S35 (,) NSsliz\n, cae Navonnte de rivoance Seuss OUGGEES J Necona Sec gurnce Pd\na i ee en\nCentre Médico-Social de Maroua Nom du patient: MOUKOUDI MAMBO ERNEST\nB.P.: 120 Maroua-Tél.: 242 050 521 Assurance: PASS24/PRUDENTIAL BENEFICIAL\nPO BOX Email: cms.maroua@cnps.cm Taux: 100%\n(Période allant du 01-01-2023 au 13-01-2023)\n—g—Nom du produ] Prixunitaire | Quantité | Partassurance | _Date__\nCELESTENE 2023-01-08\n| See [ao [| tem ost\nAK Se KZN ZK A\nOOF\nNec uP” _ - #1 ey ZN — re 4 08:45:45\n2023-01-08\na Sefer Cone] GOON NI NIX CS To\n\\/4 : WV q Wy, “,\n) be a ©, A \\ AK Doo \\ 2023-01-08\nA 0 :\nfy, 45/10) \\ xX 4 fy, sine\n4 A /N\\ é\\\nCELESTENE 2023-01-03\n| 2023-01-03\nTe fos come] om [|| nc\nOTRIVINE 0.1%\n7 AD GTE 1500 1 1500 eae\nNAS/10ML oe\nZYLORIC 200MG 2022-08-26\nre\nAMOXICILLINE\n500MG GEL 500 1 500 sro\nBLISTER /10 an\nLITACOLD 2021-12-03\nee a i eke\n[rota assurance fn 5i2e4se ECFA\nMaroua le 11-01-2023\n\\ i gone 7\nHiyaton Suc\n~ wyalou se:\nSP.\nCENTRE MEDICO-SOCIAL CNPS DE MAROUA iCMSM) N° CONT. - M031912693326Y\nCOLE DIEANT ELLIE 3 ag es Te 1 ee Mate Senn ate eare em ea TI - ;- = ed EAN oo nates 08 Pree tes Soe y j , ee es ‘\n: 3 “ a ie: PY ? | . :\n",
        "page_index"=> 6,
        "index_page"=> 6
        ]
        ]
        ],
        "duration"=> 164.8415925502777
      ];

      $output = $test['vouchers'];

      $extraction_settings = ExtractionSetting::whereProviderId(44)->first();
      $provider = Provider::
      with("provider_category")
      ->whereId(44)
     // ->whereId(4)
      ->first();


     // return $output["prestations"];

    return  $data_constructed = $this->construct_items(
         $output["items"],
         $extraction_settings,
         $provider,
         $output["prestations"]
     );
      
    }


public function filter()  {
        
    $prestations = [["folder"=>"","name"=>"ANALYSES BIOLOGIQUES","reference"=>"","amount"=>0,"page_index"=>1,"invoice_string"=>"| i,\nPASS2445 oy)\nrr o,\n, . = <\na. BON DE PRISE E ( CHARGE A ory\n(HOPITAL GENERAK.DE DOWVALA) site\ngoose CONCERNANT :\n\u2014\u2014 \u2014 Demandeur: GENERAL AGENT HOPITAL\nDossier N\u00b0 : 647032FA R\u00e9f\u00e9rence de la PEC : S9EF8FA1\nOuvert le : 14\/09\/2022 Taux de couverture : 100%\nIDENTIFICATION DE L'ASSURE\/BENEFICIAIRE\nASSURE : TCHOUMBOU Flor\u00e9al ADRESSE : N\/D MATRICULE : COFO0002-1\nBENEFICIAIRE : TCHOUMBOU Flor\u00e9al TELEPHONE : 675779693\nCLIENT : COFACE Nditk CONTRAT : COFACE\nELEMENTS DE LA FACTURATION\nENTITE DE FACTURATION : PASS24-SAS CONTACT : N\/D\nADRESSE DE FACTURATION : PASS24-SAS _\nEMAIL : feedback@pass24mobile.com BP : undefined\n\u2018Bail Fal wiar= qd PEG ELees f\nPRESTATION\nANALYSES BIOLOGIQUES\nWA \\\nMontant Estimatif: 18600\nCONDITIONS ET LIMITES\nVisualiser le detail des Conditions de ce code : https:\/\/prestataires.pass24mobile.com\nCONDITIONS GENERALES\n\u00a2 Mentionner la r\u00e9f\u00e9rence du bon de prise en charge sur toutes vos factures.\n\u00a2 Exiger une r\u00e9f\u00e9rence pour chaque prise en charge.\n\u00a2 Envoyer vos factures a l'adresse de facturation PASS24-SAS.\n\u00a2 Faire accompagner les factures par les feuilles de soins de la compagnie d'assurance, sign\u00e9es par vos praticiens.\nLt 4b. AS\n"]];
    $current_services = ["ANALYSES BIOLOGIQUES","ACTES DE RADIOLOGIE","PHARMACIE","ANALYSES BIOLOGIQUES","ANALYSES BIOLOGIQUES"] ;
    $matches_services = [["" , "ANALYSES BIOLOGIQUES"] , ["" , "ACTES DE RADIOLOGIE"] , ["" , "PHARMACIE"],["" , "ANALYSES BIOLOGIQUES"],["" , "ANALYSES BIOLOGIQUES"]];
    $new_prestations = [] ;
    $has_prestations = false ;

    foreach ($current_services as $key => $current_service) {
        
        $new_prestations = $this->fill_services($prestations ,$current_service , $matches_services[$key] ,$new_prestations , $has_prestations);

    }
    
   return  $new_prestations ;
}

public function fill_services(array $prestations ,$current_service , $matches_service  ,$new_prestations , $has_prestations )  {

    //$has_prestations = false;
    //$new_prestations = [];
    
      //$current_service = $switch[$matches_service[1]];
    
    
      //  echo "------has_service-------yes---------\n";
       // echo "------matches_service-------".$matches_service[1]."---------\n";
       // echo "------current_service-------".$current_service."---------\n";
    
       // echo "\n----------B----------".(json_encode($prestations))."-------------B-----------\n\n";
    
    
    
        foreach ($prestations as $prestation) {
          
          $new_prestation = $prestation;
    
          if ( $prestation['name']!= "UNDEFINED SERVICE"  ) {
              
            if (/*($prestation['name']==$matches_service[1] && !$this->prestation_already_exists($new_prestations , $matches_service[1] ) ) && */ 
              ( $prestation['name']==$current_service && !$this->prestation_already_exists($new_prestations , $current_service )/* */ )) {
              
                      //  
                      
                      
           // echo "\n----------current_service----------".($current_service)."-------------TT-----------\n\n";


              $has_prestations = true;
              
              array_push($new_prestations , $new_prestation);
              
         //     echo "\n----------TT----------".(json_encode($new_prestations))."-------------TT-----------\n\n";
    
            }
            else if($prestation['name']==$current_service && $this->prestation_already_exists($new_prestations , $current_service )){
              $has_prestations = true;
          }
          else if($prestation['name']!=$current_service && $this->prestation_already_exists($new_prestations , $current_service )){
            $has_prestations = true;
        }
    
          }
          
        }
    
        if ($has_prestations == false) {
    
          //  echo "\n----------current_service----------".($current_service)."-------------TT-----------\n\n";

          array_push($new_prestations , ["folder"=>"","name"=>$current_service,"reference"=>"","amount"=>"","page_index"=>1,"invoice_string"=>""]);
       
       // echo "\n--------Temp == -----".json_encode($new_prestations)."---------\n";
    
        }
    
      //  echo "\n----------Temp----------".(json_encode($new_prestations))."------------End-Temp-----------\n\n\n\n";
        
        return $new_prestations;
    
    
    }





public function prestation_already_exists($prestations , $needle ) {
  
  $prestation_already_exists = false;


  foreach ($prestations as $prestation) {
          

      if ($prestation['name']==$needle) {
        
        $prestation_already_exists = true;
        break;

      }
      
    
    
  }

     return   $prestation_already_exists ;
         

}


    

  
}
