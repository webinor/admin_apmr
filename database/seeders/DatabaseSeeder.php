<?php

namespace Database\Seeders;


use App\Models\Misc\Menu;
use App\Models\User\User;
use App\Models\Misc\Action;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\Providers\Supplier;
use App\Models\HumanResource\Role;
use Illuminate\Support\Facades\DB;
use App\Models\User\ActionMenuUser;
use App\Models\HumanResource\Employee;
use App\Models\Misc\Keyword;
use App\Models\Prestations\ProductType;
use App\Models\Operations\Provider;
use App\Models\Operations\ProviderCategory;
use App\Models\Operations\ProviderType;
use App\Models\Prestations\Imagery;
use App\Models\Prestations\Medicine;
use App\Models\Prestations\Service;
use App\Models\Prestations\ServiceType;
use App\Models\Prestations\TherapeuticClass;
use Illuminate\Database\Eloquent\Factories\Sequence;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         // \App\Models\User::factory(1 0)->create();


         $menus = 
         [
             
            
          

            ['menu_name'=>'Gestion des APMR signées', 
            'menu_slug'=>Str::slug('Gestion des APMR signees'),
            'menu_description'=>'',
            'menu_icon'=>'menu-icon mdi mdi-account-card-details',
            'children'=>[
            ['submenu_name'=>'Liste des APMR signées','link'=> ('assistance') ],
            ]
            ],

            ['menu_name'=>'Gestion des Factures', 
            'menu_slug'=>Str::slug('Gestion des Factures'),
            'menu_description'=>'',
            'menu_icon'=>'menu-icon mdi mdi-account-card-details',
            'children'=>[
            ['submenu_name'=>'Liste des Factures','link'=> ('invoice') ],
            ]
            ],


            ['menu_name'=>'Gestion des villes', 
            'menu_slug'=>Str::slug('Gestion des villes'),
            'menu_description'=>'',
            'menu_icon'=>'menu-icon mdi mdi-map-marker',
            'children'=>[
            ['submenu_name'=>'Liste des villes','link'=>('city') ],
          //  ['submenu_name'=>'Nouveau borderau','link'=>('extract/create') ],
            ]
            
            ],


            ['menu_name'=>'Gestion des opérateurs APMR', 
            'menu_slug'=>Str::slug('Gestion des opérateurs APMR'),
            'menu_description'=>'',
            'menu_icon'=>'menu-icon mdi mdi-account-multiple',
            'children'=>[
            ['submenu_name'=>'Liste des opérateurs APMR','link'=> ('registrator') ],
            ['submenu_name'=>'Nouvel operateur','link'=>('registrator/create')],
            ]
            ],


            ['menu_name'=>"Gestion des Chaises", 
            'menu_slug'=>Str::slug("Gestion des Chaises"),
            'menu_description'=>'',
            'menu_icon'=>'menu-icon mdi mdi-wheelchair-accessibility',
            'children'=>[
            ['submenu_name'=>"Liste des Chaises",'link'=>('wheel-chair') ],
            //['submenu_name'=>"Nouveau produit",'link'=>url('/product/create') ],
            ]
            ],


            ['menu_name'=>'Gestion des compagnies', 
            'menu_slug'=>Str::slug('Gestion des compagnies'),
            'menu_description'=>'',
            'menu_icon'=>'menu-icon mdi mdi-airplane',
            'children'=>[
            ['submenu_name'=>'Liste des compagnies','link'=> ('company') ],
            ['submenu_name'=>'Nouvelle compagnie','link'=>('company/create')],
            ]
            ],
 
 
             ['menu_name'=>'Gestion des chefs de vol', 
             'menu_slug'=>Str::slug('Gestion des chefs de vol'),
             'menu_description'=>'',
             'menu_icon'=>'menu-icon mdi mdi-account-switch',
             'children'=>[
             ['submenu_name'=>'Liste des chefs de vol','link'=>('ground-agent') ],
           //  ['submenu_name'=>'Nouveau borderau','link'=>('extract/create') ],
             ]
             
             ],
 
   
     
 
            
 
            
 
             ['menu_name'=>"Gestion des agents CAS", 
             'menu_slug'=>Str::slug("Gestion des agents CAS"),
             'menu_description'=>'',
             'menu_icon'=>'menu-icon mdi mdi-account-box-outline',
             'children'=>[
             ['submenu_name'=>"Liste des agents CAS",'link'=>('assistance-agent') ],
             //['submenu_name'=>"Liste des agents CAS",'link'=>url('/service') ],
             ]
             ],
 
             ['menu_name'=>"Gestion des ressources humaines", 
             'menu_slug'=>Str::slug("Gestion des ressources humaines"),
             'menu_description'=>'',
             'menu_icon'=>'menu-icon mdi mdi-account-check',
             'children'=>[
             ['submenu_name'=>"Liste du personnel",'link'=>('human_resource/employee') ],
             ]
             ],
 
 
          
 
           
             
         ];
 
 
         $providers = [
 
         ];
 
         DB::transaction( function ()  use ($menus , $providers)   {
 
             Role::factory()
             ->count(3)
             ->state(new Sequence(
                 ['id'=> 1 , 'role_name'=>'administrator','role_slug'=>'administrator'],
                 ['id'=> 2 , 'role_name'=>'accountant','role_slug'=>'accountant'],
                 ['id'=> 3 , 'role_name'=>'validator','role_slug'=>'validator'],
             ))
             ->create();
 
      
           
             $employee = Employee::factory()
             ->count(1)
             ->for(Role::find(1))
             ->create()->first();
 
             $employee2 = Employee::factory()
             ->count(1)
             ->for(Role::find(2))
             ->create()->first();
 
             $user =  User::factory()
             ->count(1)
             ->for($employee)
              ->create()->first();
 
             $user2 =  User::factory()
              ->count(1)
              ->for($employee2)
               ->create()->first();
 
 
 
 
 $actions = Action::factory()
 ->count(4)
 ->state(new Sequence(
     ['name'=>'create', 'slug'=>'create', 'description'=>''],
     ['name'=>'read', 'slug'=>'read', 'description'=>''],
     ['name'=>'update', 'slug'=>'update', 'description'=>''],
     ['name'=>'delete', 'slug'=>'delete', 'description'=>''],
 ))
 ->create();
 
 
 
 
     foreach ($menus as $menu) {
 
     $parent = $menu['menu_name'];
     $menu_icon = $menu['menu_icon'];
     $submenus = $menu['children'];
 
     $menu = Menu::create([
         'name'=>$parent,
         'icon'=>$menu_icon,
         'slug'=>Str::replace(' ','_',Str::lower($parent)),
         'description'=>'',
     ]);
 
     foreach ($submenus as $submenu) {
 
         $submenu = Menu::create([
             'name'=>$submenu['submenu_name'],
             'slug'=>Str::replace(' ','_',Str::lower($submenu['submenu_name'])),
             'link'=>$submenu['link'],
             'menu_id'=>$menu->id,
         ]);
 
 
         foreach ([$user , $user2] as $usr) {
 
         foreach ($actions as $action) {
      
           
             ActionMenuUser::create(
                 ['action_id'=>$action->id, 'menu_id'=>$submenu->id, 'admin_id'=>$usr->id],
              );
         
           
 
     }
 
 
 
       
     }
 }
 
 }
 
 
 
 });   
 
    }
}
