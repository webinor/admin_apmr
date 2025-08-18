<?php

namespace App\Addons;

use App\Models\User\User;

interface ShouldGenerateSideBarVar
{ 
    public function generateSideBarVar(User $user , $request );
}