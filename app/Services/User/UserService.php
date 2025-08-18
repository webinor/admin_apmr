<?php

namespace App\Services\User;


use App\Models\User\User;
use App\Models\User\LoginDetail;
use Illuminate\Support\Facades\DB;
use App\Models\User\ActionMenuUser;
use App\Notifications\DefinePassword;
use Illuminate\Support\Facades\Redis;
use App\Models\HumanResource\Employee;
use App\Notifications\Auth\PasswordRequest;
use App\Notifications\WelcomeUserNotification;

class UserService
{
    public function verifyPermission($menu, array $actions, User $user): bool
    {
        $user_permission_service = new UserPermissionService();

          return   $user_permission_service->verifyPermission($menu,  $actions,  $user);
    }

    public function resetPassword(array $user_data)
    {
        $update_user_service = new UpdateUserService();
        return $update_user_service->resetPassword($user_data);
    }

    public function updateCredential(array $user_data)
    {
        try {
            // return $user_data;
            DB::beginTransaction();

            //  return
            $employee = Employee::select("id", "main_phone_number")
                ->with("user:id,employee_id,email")
                ->whereId($user_data["employee"])
                ->first();

            if ($employee->user != null) {
                $user = $employee->user; // User::select('id')->where('email' ,  $user_data['email'])->first();

                if ($user->email == $user_data["email"]) {
                    ActionMenuUser::where("admin_id", $user->id)->delete();
                    Redis::del("distincts_actions_" . $user->id);
                } else {
                    /* $new_user = User::select('id')->where('email' , $user_data['email'])->first();

            if ($new_user) {
                
                return [
                    'status'=>false,
                    'errors'=> ["email" => ["Cette adress email est deja utilisee"]]
                ];
                
            } else {

               // $user->email = $user_data['email'];
               // $user->save();


            }
            */
                }
            } else {
                $register_user_service = new RegisterUserService();

                $response = $register_user_service->execute(
                    $user_data + [
                        "phone_number" => $employee->main_phone_number,
                    ]
                );

                if (!$response["status"]) {
                    return $response;
                }

                $user = $response["user"];

                $user->notify(new PasswordRequest());
            }

            $user_permission_service = new UserPermissionService();

            $response = $user_permission_service->createPermission(
                $user,
                $user_data
            );

            DB::commit();

            return [
                "status" => true,
                "data" => ["user" => ["Utilisateur creé avec succes"]],
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return $th;
        }
    }

    public function updateUser(
        array $user_data,
        $is_update = true,
        $request
    ): User {
        $update_user_service = new UpdateUserService();
        return $update_user_service->updateUser(
            $user_data,
            $is_update,
            $request
        );
    }

    public function setPassword($user_data)
    {
        $update_user_service = new UpdateUserService();
        return $update_user_service->setPassword($user_data);
    }

    public function definePassword($user_data)
    {
        $affected_row = $this->setPassword($user_data);

        if (!$affected_row) {
            return \response()->json([
                "status" => false,
                "errors" => [
                    "email" => [
                        "Une erreur s'est produite lors de la modification veuillez ressayer",
                    ],
                ],
            ]);
        }

        $authenticate_user_Service = new AuthenticateUserService();
        $response = $authenticate_user_Service->execute($user_data);

        return $response;
    }

    public function authenticateUser($user_data)
    {
        $authenticate_user_Service = new AuthenticateUserService();
       return $response = $authenticate_user_Service->execute($user_data);

    }



    public function getFinalVerifyParams($fields, $userAgent)
    {
        $user = User::whereVerificationCode($fields["verification_code"])
            ->whereHas("employee", function ($query) use ($fields) {
                $query->whereCode($fields["code"]);
            })
            ->first();

        if (!$user) {
            $response = [
                "status" => false,
                "errors" => [
                    "verification_code" => ["Code de vérification incorrect"],
                ],
            ];
        } else {
            $loginDetail = new LoginDetail();
            $loginDetail->user_agent = $userAgent;
            $user->login_details()->save($loginDetail);

            
           // $home = $user->isExtractor() ? url('/slip') : url("/");
            $home = url("/");
            $login_link = url("/app/login");

            // $reset_params = compact('home','login_link');

            $response = [
                "status" => true,
                "redirect_to" => $home,
                "data" => "Code valide, vous allez etre redirigé...",
            ];
        }

        return \response()->json($response);
    }

    public function notify_user($user)
    {
        $user->notify(new DefinePassword());

        $response = [
            "status" => true,
            "data" =>
                "Un mail a été envoyé à l'adresse : " .
                $user->email .
                ". Ce mail contient un lien de réinitialilsation du mot de passe.",
        ];

        return $response;
    }

    public function sendWelcomeEmail(User $user)
    {
        $user->notify(new WelcomeUserNotification());
    }

    public function getUser(array $fields)
    {
        $email = $fields["email"];

        $user = User::whereEmail($email)->first();

        if ($user) {
            $user->notify(new DefinePassword());

            $response = [
                "status" => true,
                "data" =>
                    "Un mail a été envoyé à l'adresse : " .
                    $user->email .
                    ". Ce mail contient un lien de réinitialilsation du mot de passe.",
            ];
            //"Un mail de reininitialisation a été envoyé à "Str::mask($user->email,-20,4)
        } else {
            $response = [
                "status" => false,
                "errors" => [
                    "email" => [
                        "Cette adresse email ne correspond à aucun utilisateur",
                    ],
                ],
            ];
        }

        return $response;
    }

}
