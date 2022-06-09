<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('app/organizations/dis', 'OrganizationController@discovery');
Route::get('app/organizations/createSnmp/{id}', 'MonitoringSnmpController@create');
Route::post('app/organizations/showSnmp', 'MonitoringSnmpController@showSnmp');
Route::post('app/organizations/alert', 'MonitoringSnmpController@alert');
Route::post('app/organizations/editSnmp', 'MonitoringSnmpController@edit');
Route::any('/app/organizations/update', 'MonitoringSnmpController@update');
Route::any('/app/organizations/deleteSnmp', 'MonitoringSnmpController@destroy');
//Route::post('app/servers/logs', 'ServerController@logs');
Route::get('app/organizations/dis/add/{id}/{ip}/{name}', 'MonitoringSnmpController@createDisc');
Route::get('app/organizations/dis/add/{name}', 'ServerController@createDisc');
Route::get('app/organizations/correlation', 'ServerController@correlation');

//snmp routes
Route::get('app/organizations/settinghost', 'OidHostController@setting');
Route::any('/app/organizations/deleteOidH', 'OidHostController@destroy');
Route::any('/app/organizations/editOidH', 'OidHostController@edit');
Route::get('app/organizations/createOidH', 'OidHostController@create');
Route::any('/app/organizations/updateH', 'OidHostController@update');
Route::post('/app/organizations/storeH', 'OidHostController@store');

Route::get('app/organizations/settingrouter', 'OidRouterController@setting');
Route::any('/app/organizations/deleteOidR', 'OidRouterController@destroy');
Route::any('/app/organizations/editOidR', 'OidRouterController@edit');
Route::get('app/organizations/createOidR', 'OidRouterController@create');
Route::any('/app/organizations/updateR', 'OidRouterController@update');
Route::post('/app/organizations/storeR', 'OidRouterController@store');

Route::get('app/organizations/settingswich', 'OidSwichController@setting');
Route::any('/app/organizations/deleteOidS', 'OidSwichController@destroy');
Route::any('/app/organizations/editOidS', 'OidSwichController@edit');
Route::get('app/organizations/createOidS', 'OidSwichController@create');
Route::any('/app/organizations/updateS', 'OidSwichController@update');
Route::post('/app/organizations/storeS', 'OidSwichController@store');
//set snmp
Route::any('/app/organizations/setSnmp', 'MonitoringSnmpController@setSnmp');
// physical sensors
Route::get('app/organizations/createPh/{id}', 'PhysicalSensorController@create');
Route::any('/app/organizations/updateph', 'PhysicalSensorController@update');
Route::any('/app/organizations/deletePh', 'PhysicalSensorController@destroy');
Route::any('app/organizations/editPh', 'PhysicalSensorController@edit');
Route::post('app/organizations/showPh', 'PhysicalSensorController@show');



Auth::routes();

Route::get("home", function () {
    return redirect(action("OrganizationController@index"));
});

Route::get('app/dashboard', function () {
    return redirect(action("OrganizationController@index"));
})->name('dashboard');

Route::get('app/organizations/{organization}/dashboard', 'OrganizationController@dashboard');
Route::get(
    'app/organizations/{organization}/reset-token',
    'OrganizationController@resetToken'
);
Route::get(
    'app/organizations/{organization}/dashboard/{token}',
    function (\App\Organization $organization, string $token) {

        if ($organization->dashboard_token != $token) {
            abort(403);
        }

        return view("organization.dashboard", array("organization" => $organization));
    }
)->name("organization.public.dashboard");
Route::resource('app/organizations', 'OrganizationController');
Route::resource("app/organizations.user", "OrganizationUserController");
Route::resource('app/servers', 'ServerController');
Route::resource('app/monitoringSnmp', 'MonitoringSnmpController');
Route::resource('app/oidSnmp', 'OidHostController');
Route::resource('app/oidSnmp', 'OidRouterController');
Route::resource('app/oidSnmp', 'OidSwichController');
Route::resource('app/physicalSensor', 'PhysicalSensorController');

