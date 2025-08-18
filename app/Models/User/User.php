<?php

namespace App\Models\User;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Addons\ShouldAccess;
use Illuminate\Http\Request;
use App\Models\User\LoginDetail;
use Laravel\Sanctum\HasApiTokens;
use App\Models\HumanResource\Employee;
use App\Models\HumanResource\Role;
use App\Models\Provider;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Auth\QueuedVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements ShouldAccess //implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table ="admins";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'code',
        'email',
        'password',
        'city',
        'address',
        'gender',
        'birthday',
        "country_code",
        "phone_number",
        "whatsapp_phone_number",
        "subscribe_method",
        "verification_code",
        "remember_token",
        "defined_token",
        "employee_id"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_number_verified_at' => 'datetime',
        'birthday'=>'date',
        
    ];

    public function isAdministrator() {

       // dd($this->employee()->first()->role()->where('role_name', 'administrator')->get());
        return $this->employee->role->role_name=='administrator';
        //return $this->employee()->first()->role()->where('role_name', 'administrator')->exists();
     }

     public function isExtractor() {
        return $this->employee->role->role_name=='extractor';

      //  return $this->employee()->first()->role()->where('role_name', 'extractor')->exists();
     }


     public function isValidator() {
        return $this->employee->role->role_name=='validator';

     //   return $this->employee()->first()->role()->where('role_name', 'validator')->exists();
     }

  

    /**
     * Get all of the login_details for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function login_details(): HasMany
    {
        return $this->hasMany(LoginDetail::class);
    }

     /**
     * Get the employee that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class,);
    }



    /**
 * Accessor for Age.
 */
    public function age()
    {
        return Carbon::parse($this->attributes['birthday'])->age;
    }

    //Overrideen sendEmailVerificationNotification implementation
    public function sendEmailVerificationNotification()
    {
        $this->notify(new QueuedVerifyEmail());
    }

    

    /**
     * Get all of the Providers for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function providers(): HasMany
    {
        return $this->hasMany(Provider::class);
    }


    public function fullName(): String
    {
      //  return Str::headline($this->last_name);
        return Str::headline($this->last_name.' '.$this->first_name);
    }

    public function getLoginParams(Request $request) {
        
        $user = $request->code ? Employee::whereCode($request->code)->with('user')->first()->user : null;
        $admin = false;
        $reset_password_link=url('/reset_password');
        $register_link=url('/register');
        $login_end_point=url('/authenticate');

        return compact('admin','user','reset_password_link','register_link','login_end_point');

    }

    public function getRegisterParams(Request $request)
    {
        
        $user = $request->code ? Employee::whereCode($request->code)->with('user')->first()->user : null;
        $admin = false;
        $reset_password_link=url('/reset_password');
        $register_link=url('/register');
        $login_end_point=url('/authenticate');

        return compact('admin','user','reset_password_link','register_link','login_end_point');

    }

    public function getResetPasswordParams()
    {
        
        $reset_password_endpoint = '/reset-password';
        $login_link=url('/login');
        
        return compact('reset_password_endpoint','login_link');

    }

    public function getDefinePasswordParams($code, $token )
    {
    
        
        $user = User::whereCode($code)->first();
    
          
        if ($user->defined_token != $token) {
            abort(403);
        }
        
        $define_new_password_endpoint = '/define-password?_method=PUT';

        return compact('user' , 'token', 'define_new_password_endpoint');

    }

}
