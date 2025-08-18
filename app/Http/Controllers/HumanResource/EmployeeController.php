<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Models\HumanResource\Employee;
use App\Services\HumanResource\EmployeeService;
use App\Http\Requests\HumanResource\StoreEmployeeRequest;
use App\Http\Requests\HumanResource\UpdateEmployeeRequest;
use App\Models\HumanResource\Role;
use App\Models\Misc\Action;
use App\Models\Misc\Menu;
use App\Models\User\ActionMenuUser;

class EmployeeController extends Controller
{

  protected $employee_service;

  public function __construct(EmployeeService $employee_service) {
        
    $this->employee_service = $employee_service;
      
    $this->authorizeResource(Employee::class, "employee");

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::selectRaw('id,code,personal_email,last_name,first_name,created_at')
        ->with('user:id,employee_id,email')
        ->orderBy('last_name','ASC')
        ->get();
 
        // return Arr::except($employees[0]->habilitations->toArray(),['pivot']);
         return view('human_resources.employee.index' , compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
      
      $distincts_actions = [];
      $distincts_actions_map=[];
      $employee = null;

          $distincts_actions = ActionMenuUser::select('action_menu_admins.menu_id')->join('actions' , 'actions.id' , 'action_menu_admins.action_id')
          ->join('menus' , 'menus.id' , 'action_menu_admins.menu_id')
          //->where('user_id', session('user')->id)
          ->distinct()->get();
  
         // MyModel::distinct()->get(['column_name']);
         $distincts_actions_map = [];
         foreach ($distincts_actions as $value) {
          array_push($distincts_actions_map ,$value->menu_id);
         } /**/
  
      //   $authorized_submenus=Menu::find($distincts_actions);
      
     
     
      
         $roles = Role::select('id' , 'role_name')->where('role_name' ,'!=' , 'admin' )
         ->orderBy('role_name' , 'asc')
         ->get();
          
         $menus = Menu::select('id', 'name' , 'slug')
       ->with(['submenus'=> function ($query) {
        $query->selectRaw('id,name,slug,menu_id');
        $query->orderBy('position', 'Asc');
    }])
       ->where('menu_id' , null)->get();/**/
         $actions=Action::select('id' , 'name' )->get();
    
         $disable="";
         $readOnly="";
         $action="create";

        return view('human_resources.employee.create', compact('disable', 'action', 'readOnly','distincts_actions', 'employee','roles', 'menus','actions' ,'distincts_actions_map' ) );

      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $request)
    {

      $response =  $this->employee_service->createEmployee($request->validated());

      return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $employee_code
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {

     // return $employee;

        $action='edit';
      //$positions = Position::select('id','title')->get();
      /*  $departments = Department::select('id','name','description','head_of_department_id')
        ->with('head_of_department')
        ->get();*/

   //  return Employee::all();
   $employee  =  $employee //= Employee::selectRaw('id,code,role_id,personal_email,last_name,first_name,main_phone_number,address')
        
        ->load(['user:id,employee_id,email','role:id,role_name']);
       // ->with('role:id,role_name')
        //->has('habilitations')
       // ->whereCode($employee_code)
      //  ->first();

        /* */$distincts_actions = $employee->user ? ActionMenuUser::select('action_menu_admins.menu_id','action_menu_admins.action_id')
        ->join('actions' , 'actions.id' , 'action_menu_admins.action_id')
        ->join('menus' , 'menus.id' , 'action_menu_admins.menu_id')
        ->where('admin_id', $employee->user->id)
        ->distinct()->get() : collect([]);

       // MyModel::distinct()->get(['column_name']);
       $distincts_actions_map = [];
       foreach ($distincts_actions as $value) {
        array_push($distincts_actions_map ,$value->menu_id);
       } /**/

    //   $authorized_submenus=Menu::find($distincts_actions);
    
   
   
    
       $roles = Role::select('id' , 'role_name')->where('role_name' ,'!=' , 'admin' )
       ->orderBy('role_name' , 'asc')
       ->get();
        
       $menus = Menu::select('id', 'name' , 'slug')
     ->with(['submenus'=> function ($query) {
      $query->selectRaw('id,name,slug,menu_id');
      $query->orderBy('position', 'Asc');
  }])
     ->where('menu_id' , null)->get();/**/
       $actions=Action::select('id' , 'name' )->get();
       $endpoint = "update";

       return view('human_resources.employee.create' , compact('action', 'endpoint','distincts_actions', 'employee','actions' , 'roles' ,  'menus' , 'distincts_actions_map'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\HumanResource\UpdateEmployeeRequest  $request
     * @param  \App\Models\HumanResource\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request , Employee $employee)
    {

      $response =  $this->employee_service->updateEmployee($request->validated());

      return $response; 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
