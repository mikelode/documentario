<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('controlador','WelcomeController@index');

Route::get('/','HomeController@index');
Route::get('homei','HomeController@index_section');

/*Route::get('/', function () {
    return view('welcome');
});*/

/*
 * AUTENTIFICACIÓN DEL USUARIO
 * */

Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');


Route::controllers([
    'users' => 'UsersController'
]);

Route::get('example',function(){

    $user = 'Miguel';

    return view('examples/template',compact('user'));

});

Route::get('fire', function(){
    //event(new \aidocs\Events\TramiteEvent());
    return 'event fired';
});
Route::get('test', function(){
    return view('tramite.prueba');
});

/*
 *  REGISTRO DE NUEVO DOCUMENTO
 * */

Route::get('doc/register', [
    'middleware' => ['acl:1'],
    'uses' => 'Document\DocumentController@index'
]);
Route::post('doc/register', 'Document\DocumentController@storeDocument');
Route::get('doc/manager', 'Document\DocumentController@getManagerDocument');
Route::get('doc/sender/{dni}', 'Document\DocumentController@getSenderDocument');
Route::get('doc/edit','Document\DocumentController@getEditDocument');

/*
 *  BANDEJA DE ENTRADA DE DOCUMENTOS
 * */

Route::get('doc/menu',[
    'middleware' => ['acl:2'],
    'uses' => 'Document\HistorialController@getHistorialDoc'
]);

Route::post('hist/register', 'Document\HistorialController@storeHistorialDerived');
Route::post('hist/registerdc', 'Document\HistorialController@storeHistorialDerivedDc');

Route::post('fhist/register', 'Document\HistorialController@firstHistorialDerived');

Route::put('hist/update/{id}', [
    'as' => 'receive', 'uses' => 'Document\HistorialController@acceptDocumentDerived'
]);

Route::put('hist/end/{id}', [
    'as' => 'attend', 'uses' => 'Document\HistorialController@attendDocument'
]);

Route::post('hist/del/{id}',[
    'as' => 'undo', 'uses' => 'Document\HistorialController@undoOperationDocument'
]);

Route::get('derivation/detail/{id}','Document\HistorialController@getDetailDerivation');

/*
 * BANDEJA DE SALIDA DE DOCUMENTOS
 * */

Route::get('doc/outbox',[
    'middleware' => ['acl:3'],
    'uses' => 'Document\ArcparticularController@getDocumentsFromPrivateFiler'
]);

Route::get('doc/detail/{id}', 'Document\DocumentController@detailDocument');

Route::get('operation/detail/{id}', 'Document\DocumentController@detailOperation');

Route::post('hist/doc/{id}',[
    'as' => 'undo_doc', 'uses' => 'Document\HistorialController@undoDocument'
]);

/*
 * RUTAS PARA LA CONSULTA Y SEGUIMIENTO DE DOCUMENTOS
 * */

Route::get('doc/consult',[
    'middleware' => ['acl:4'],
    'uses' => 'Document\DocumentController@consultDocument'
]);

Route::post('doc/find/{year}','Document\DocumentController@retrieveDocumentData');

Route::get('doc/list','Document\DocumentController@listDocument');

Route::get('doc/tracking/{id}','Document\HistorialController@documentaryTracking');

Route::post('doc/exp/{id}', [
    'as' => 'findExp', 'uses' => 'Document\ArchivadorController@findExpedient'
]);

Route::post('doc/doc/{id}', [
    'as' => 'findDoc', 'uses' => 'Document\ArchivadorController@findDocument'
]);

Route::post('doc/subject',[
    'as' => 'findSubject', 'uses' => 'Document\ArchivadorController@findBySubject'
]);

Route::post('doc/dates',[
    'as' => 'findDates', 'uses' => 'Document\ArchivadorController@findByDates'
]);

Route::post('doc/sender',[
    'as' => 'findSender', 'uses' => 'Document\ArchivadorController@findBySender'
]);

Route::post('doc/attach',[
    'as' => 'findAttach', 'uses' => 'Document\ArchivadorController@findByAttaches'
]);

/*
 *  NOTIFICACIONES y MURO DE PUBLICACION
 * */

Route::post('notifications/{dep}',[
    'as' => 'alerts', 'uses' => 'Document\HistorialController@getNotifications'
]);

/*
 *  CONFIGURACION DEL SISTEMA
 * */

Route::get('settings',[
    'middleware' => ['acl:6'],
    'uses' => 'Document\SettingsController@index'
]);
Route::get('settings/new_user', 'Document\SettingsController@getRegisterUser');
Route::post('settings/new_user', 'Document\SettingsController@postRegisterUser');
Route::get('settings/list_users', 'Document\SettingsController@getListUsers');
Route::post('settings/updt_profile', 'Document\SettingsController@postUpdateProfile');
Route::get('settings/updt_state', 'Document\SettingsController@postUpdateStateUser');
Route::get('settings/updt_pass', 'Document\SettingsController@getUpdatePasswordUser');
Route::post('settings/updt_pass', 'Document\SettingsController@postUpdatePasswordUser');
Route::get('settings/reset_pass', 'Document\SettingsController@getResetPasswordUser');
Route::get('settings/list_asoc','Document\SettingsController@getListAsoc');
Route::post('settings/new_asoc','Document\SettingsController@postRegisterAsociacion');
Route::get('settings/new_afil','Document\SettingsController@getFormularioAfiliado');
Route::post('settings/new_afil','Document\SettingsController@postRegisterAfiliado');

/*
 *  TIPO DE DOCUMENTO
 * */
Route::get('settings/add_doc', 'Document\TipoDocumentoController@getListTypeDocs');
Route::get('settings/updt_statetipodoc','Document\TipoDocumentoController@getUpdateShowTipo');
Route::post('settings/new_tdoc', 'Document\TipoDocumentoController@postNewTypeDocs');
Route::post('settings/del_tdoc/{id}', [
    'as' => 'delete_tdoc', 'uses' => 'Document\TipoDocumentoController@deleteTypeDocs'
]);

/*
 *  CONSULTAS PEQUEÑAS
 * */

Route::get('getProfile/{id}', 'Document\SettingsController@showProfileUser');

/*
 *  RESPORTES Y ESTADISTICAS
 * */


/*PRUEBA DE AUTOCOMPLETADO*/

Route::get('autocompletado', 'AutocompleteController@show');
Route::get('getdata', 'AutocompleteController@autocomplete');