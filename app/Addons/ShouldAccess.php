<?php

namespace App\Addons;

use Illuminate\Http\Request;



interface ShouldAccess
{ 
    public function getLoginParams(Request $request);
    public function getResetPasswordParams();
    public function getDefinePasswordParams($code, $token );
    public function getRegisterParams(Request $request);
   // public function getLoginParams(Request $request);
}