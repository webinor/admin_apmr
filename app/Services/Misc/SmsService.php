<?php

namespace App\Services\Misc;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;

class SmsService //implements ShouldQueue
{
    // use Queueable;

    public $contacts;
    public $orderLink;

    const USERNAME = "lecheaz.cm";
    const PASSWORD = "dGsMy9sH";
    // public $order ;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //  $this->contacts = $contacts;
        //  $this->order = $ord;
    }

    public function sendOtpSms(array $sendTo, $verification_code)
    {
        // $c = $this->contacts;
        //  dd($this->orderLinks);

        $urls = [];

        $sender = urlencode("MyIndexpert");

        foreach ($sendTo as $j => $contact) {
            $indicatif = strlen($contact) == 9 ? "237" : "";

            if ($this->isCorrect($contact)) {
                // $message= urlencode('Waaouh, utilise cette application vraiment géniale ( Smart Register ) pour calculer les totaux, moyennes, cotes et statistiques de tes élèves en 5 minutes ! Télécharge gratuitement en cliquant sur ce lien : https://bit.ly/2HdSvm5');

                $message =
                    /*urlencode*/ "Chèr(e) utilisateur(trice), votre code de vérification est le : " .
                    $verification_code;

                //"https://obitsms.com/api/bulksms?username=thedigitspace&password=wS4P06yG&sender=DigitSpace&destination=".$indicatif.$phone_number."&message=".urlencode("Chèr(e) $first_name $last_name, votre code d'activation du forfait $type est: $activation_code, montant de la transaction: $amount FCFA. Merci pour votre confiance.")

                // $link= "https://obitsms.com/api/bulksms?username=thedigitspace&password=wS4P06yG&sender=$sender&destination=$indicatif".$contact['number']."&message=$message" ;

                $link =
                    "https://obitsms.com/api/v2/bulksms?key_api=8Vc3fKgJlHrS19tJ2TrZ6XhTsfAffGgl&sender=$sender&destination=$indicatif" .
                    $contact .
                    "&message=" .
                    urlencode($message);
                $urls[$j] = $link;
            }
        }
        // return $urls;
        /*  $post = [
        'sender_id'=> 'Trusted',// Le nom qui s'affiche comme emetteur du sms
        'login' => '691504990',
        'password' => '691504990',
        'destinataire' => $contact, // tu pourras modifier 
        'message' => $message// et sa
    ];
     $response = Http::get('https://sms.etech-keys.com/ss/api.php', $post); */
        $this->SendSms($urls);
    }

    /*$post = [
                    'sender_id'=> 'SILDY SI',// Le nom qui s'affiche comme emetteur du sms
                    'login' => '691504990',
                    'password' => '691504990',
                    'destinataire' => '699277072', // tu pourras modifier 
                    'message' => 'Je test l\'envoie de message'// et sa
                ];
                $response = Http::get('https://sms.etech-keys.com/ss/api.php', $post); */

    public function isCorrect($phone_number)
    {
        $phone_number = preg_replace("/\s+/", "", $phone_number);

        if (strlen($phone_number) >= 8) {
            $phone_number =
                strlen($phone_number) == 8
                    ? "6" . $phone_number
                    : $phone_number;

            $re = '/^6{1}[56789]{1}[0-9]{7}$/';

            $str = $phone_number;

            if (preg_match($re, $str)) {
                // $phone_number= Encryptor::crypt($phone_number);

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function SendSms($urls)
    {
        $send = false; //$_SERVER["SERVER_NAME"]!='127.0.0.1' ? true :false;

        if ($send /**/) {
            $requests = [];

            //Initiate a multiple cURL handle
            $mh = curl_multi_init();

            //Loop through each URL.
            foreach ($urls as $k => $url) {
                $requests[$k] = [];
                $requests[$k]["url"] = $url;
                //Create a normal cURL handle for this particular request.
                $requests[$k]["curl_handle"] = curl_init($url);
                //Configure the options for this request.
                curl_setopt(
                    $requests[$k]["curl_handle"],
                    CURLOPT_RETURNTRANSFER,
                    true
                );
                curl_setopt(
                    $requests[$k]["curl_handle"],
                    CURLOPT_FOLLOWLOCATION,
                    true
                );
                curl_setopt($requests[$k]["curl_handle"], CURLOPT_TIMEOUT, 20);
                curl_setopt(
                    $requests[$k]["curl_handle"],
                    CURLOPT_CONNECTTIMEOUT,
                    20
                );
                curl_setopt(
                    $requests[$k]["curl_handle"],
                    CURLOPT_SSL_VERIFYHOST,
                    false
                );
                curl_setopt(
                    $requests[$k]["curl_handle"],
                    CURLOPT_SSL_VERIFYPEER,
                    false
                );
                //Add our normal / single cURL handle to the cURL multi handle.
                curl_multi_add_handle($mh, $requests[$k]["curl_handle"]);
            }

            //Execute our requests using curl_multi_exec.
            $stillRunning = false;
            do {
                curl_multi_exec($mh, $stillRunning);
            } while ($stillRunning);

            //Loop through the requests that we executed.
            foreach ($requests as $k => $request) {
                //Remove the handle from the multi handle.
                curl_multi_remove_handle($mh, $request["curl_handle"]);
                //Get the response content and the HTTP status code.
                $requests[$k]["content"] = curl_multi_getcontent(
                    $request["curl_handle"]
                );
                $requests[$k]["http_code"] = curl_getinfo(
                    $request["curl_handle"],
                    CURLINFO_HTTP_CODE
                );

                //Close the handle.
                curl_close($requests[$k]["curl_handle"]);
            }
            //Close the multi handle.
            curl_multi_close($mh);

            /*
$requests = array();
 
 
//Initiate a multiple cURL handle
$mh = curl_multi_init();
 
//Loop through each URL.
foreach($urls as $k => $url){
    $requests[$k] = array();
    $requests[$k]['url'] = $url;
    //Create a normal cURL handle for this particular request.
    $requests[$k]['curl_handle'] = curl_init($url);
    //Configure the options for this request.
    curl_setopt($requests[$k]['curl_handle'], CURLOPT_RETURNTRANSFER, true);
    curl_setopt($requests[$k]['curl_handle'], CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($requests[$k]['curl_handle'], CURLOPT_TIMEOUT, 20);
    curl_setopt($requests[$k]['curl_handle'], CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($requests[$k]['curl_handle'], CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($requests[$k]['curl_handle'], CURLOPT_SSL_VERIFYPEER, false);
    //Add our normal / single cURL handle to the cURL multi handle.
    curl_multi_add_handle($mh, $requests[$k]['curl_handle']);
}
 
//Execute our requests using curl_multi_exec.
$stillRunning = false;
do {
    curl_multi_exec($mh, $stillRunning);
} while ($stillRunning);
 
//Loop through the requests that we executed.
foreach($requests as $k => $request){
    //Remove the handle from the multi handle.
    curl_multi_remove_handle($mh, $request['curl_handle']);
    //Get the response content and the HTTP status code.
    $requests[$k]['content'] = curl_multi_getcontent($request['curl_handle']);
    $requests[$k]['http_code'] = curl_getinfo($request['curl_handle'], CURLINFO_HTTP_CODE);
    
    //Close the handle.
    curl_close($requests[$k]['curl_handle']);
}
//Close the multi handle.
curl_multi_close($mh);    

//return $mh;

*/
        } else {
            //dd('envoi');
        }
    }

    function compareObject($a, $b)
    {
        return $a - $b;
    }
}
