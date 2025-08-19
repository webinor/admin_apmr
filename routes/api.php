<?php

use App\Http\Controllers\AssistanceAgentController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyWheelChairController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\ExtractionSettingController;
use App\Http\Controllers\GroundAgentController;
use App\Http\Controllers\HumanResource\EmployeeController;
use App\Http\Controllers\Misc\FileController;
use App\Http\Controllers\Misc\FreeSearchController;
use App\Http\Controllers\Misc\InvoiceController;
use App\Http\Controllers\Misc\SearchController;
use App\Http\Controllers\Misc\TherapeuticClassController;
use App\Http\Controllers\Operations\ExtractController;
use App\Http\Controllers\Operations\FolderController;
use App\Http\Controllers\Operations\ServiceController;
use App\Http\Controllers\Operations\ServiceCostController;
use App\Http\Controllers\Operations\SlipController;
use App\Http\Controllers\Prestations\ProductController;
use App\Http\Controllers\Prestations\MedicineController;
use App\Http\Controllers\Prestations\PrestationController;
use App\Http\Controllers\Prestations\ProviderController;
use App\Http\Controllers\WheelChairController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get("getProviders", [ProviderController::class, "getProviders"]);
Route::get("getDci", [MedicineController::class, "getDci"]);
Route::get("getServiceNames", [ServiceController::class, "getServiceNames"]);

Route::get("fetch-folder/{folder}", [FolderController::class, "fetch_folder"]);
Route::post("get-search", [FreeSearchController::class, "get_search"]);
Route::get("getInvoices", [FolderController::class, "getInvoices"]);
Route::get("getSlips", [SlipController::class, "getSlips"]);
Route::get("getSearches", [SearchController::class, "getSearches"]);
Route::get("getProducts", [ProductController::class, "getProducts"]);
Route::get("getServices", [ServiceController::class, "getServices"]);
Route::get("getPrestations", [PrestationController::class, "getPrestations"]);


Route::middleware(["auth:sanctum", "throttle_recaptcha:150"])->group(function () {
        Route::prefix("human_resource")->group(function () {
            Route::apiResource("employee", EmployeeController::class)->only([
                "store",
                "update",
                "destroy",
            ]);
        });


        Route::apiResource("city", CityController::class)->only([
            "store",
            "update",
            "destroy",
        ]);

        Route::apiResource("company", CompanyController::class)->only([
            "store",
            "update",
            "destroy",
        ]);

        


        Route::apiResource("wheel-chair", WheelChairController::class)->only([
            "store",
            "update",
            "destroy",
        ]);

        Route::apiResource("ground-agent", GroundAgentController::class)->only([
            "store",
            "update",
            "destroy",
        ]);

        Route::apiResource("assistance-agent", AssistanceAgentController::class)->only([
            "store",
            "update",
            "destroy",
        ]);

        Route::apiResource("company-wheel-chair", CompanyWheelChairController::class)->only([
            "store",
            "update",
            "destroy",
        ]);

        

       
        //Route::post("save-invoices", [FolderController::class, "save_invoices"]);
        //Route::post("validate-invoices", [FolderController::class, "validate_invoices"]);
        Route::post("update-reference", [InvoiceController::class, "update_reference"]);
        Route::post("update-identification", [FolderController::class, "update_identification"]);
        Route::post("count-filtered-results", [FolderController::class, "count_filtered_results"]);

        

        Route::post("export", [FileController::class, "store"]);
        Route::post("extract", [ExtractController::class, "store"]);

        

        Route::prefix("access")->group(function () {
            Route::put("credential", [
                UserController::class,
                "update_credential",
            ]);

            Route::post("reset", [UserController::class, "reset"]);



            Route::apiResource("user", UserController::class)->only([
                "store",
                "update",
                "destroy",
            ]);
        });

        Route::apiResource(
            "extraction-settings",
            ExtractionSettingController::class
        )->only(["store", "update", "destroy"]);

        Route::apiResource("provider", ProviderController::class)->only([
            "store",
            "update",
            "destroy",
        ]);


        Route::apiResource("slip", SlipController::class)->only([
            "store",
            "destroy",
        ]);

        Route::apiResource("folder", FolderController::class)->only([
            "destroy",
        ]);

        Route::apiResource("file", FileController::class)->only([
            "store",
            "update",
            "destroy",
        ]);

        Route::apiResource("product", ProductController::class)->only([
            "store",
            "update",
            "destroy",
        ]);

        Route::apiResource("service", ServiceController::class)->only([
            "store",
            "update",
            "destroy",
        ]);

        Route::resource("therapeutic-class", TherapeuticClassController::class)->only([
            "store",
            "update",
            "destroy",
        ]);

        Route::post("/therapeutic-class/import", [TherapeuticClassController::class, "import"])->name(
            "import"
        );

        Route::resource("active-ingredient", MedicineController::class)->only([
            "store",
            "update",
            "destroy",
        ]);

        
    }
);
