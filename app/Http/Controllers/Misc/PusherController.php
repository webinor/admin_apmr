<?php

namespace App\Http\Controllers\Misc;

use Pusher\Pusher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PusherController extends Controller
{
    /**
     * Authenticates logged-in user in the Pusher JS app
     * For private channels
     */
    public function pusherAuth(Request $request)
    {
        $user = auth()->user();
        $socket_id = $request["socket_id"]; //"138468.39263933" ;
        $channel_name = $request["channel_name"]; //;"private-posts"
        $key = getenv("PUSHER_APP_KEY");
        $secret = getenv("PUSHER_APP_SECRET");
        $app_id = getenv("PUSHER_APP_ID");

        if ($user) {
            //$string=($socket_id.":".$channel_name);

            $json_user_data = json_encode([
                "user_id" => $user->id,
                "user_info" => ["id" => $user->id, "email" => $user->email],
            ]);

            //$signature = hash_hmac('sha256', $string, $secret);
            $pusher = new Pusher($key, $secret, $app_id);
            // $auth = $pusher->socket_Auth($channel_name, $socket_id /*, $json_user_data/**/);
            $auth = $pusher->authorizeChannel(
                $channel_name,
                $socket_id,
                $json_user_data /**/
            );

            return response($auth, 200);
        } else {
            header("", true, 403);
            echo "Forbidden";
            return;
        }
    }
}
