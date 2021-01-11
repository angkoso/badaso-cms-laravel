<?php

use Illuminate\Support\Str;
use Uasoft\Badaso\Facades\Badaso;
use Uasoft\Badaso\Middleware\ApiRequest;
use Uasoft\Badaso\Middleware\BadasoAuthenticate;
use Uasoft\Badaso\Middleware\BadasoCheckPermissions;
use Uasoft\Badaso\Middleware\BadasoCheckPermissionsForBread;

$api_route_prefix = \config('badaso.api_route_prefix');
Route::group(['prefix' => $api_route_prefix, 'namespace' => 'Uasoft\Badaso\Controllers', 'as' => 'badaso.', 'middleware' => ApiRequest::class], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::group(['prefix' => 'data'], function () {
            Route::get('/components', 'BadasoDataController@getComponents');
            Route::get('/filter-operators', 'BadasoDataController@getFilterOperators');
            Route::get('/paginate', 'BadasoDataController@testPaginate');
        });

        Route::group(['prefix' => 'auth'], function () {
            Route::post('/login', 'BadasoAuthController@login');
            Route::post('/logout', 'BadasoAuthController@logout');
            Route::post('/register', 'BadasoAuthController@register');
            Route::post('/change-password', 'BadasoAuthController@changePassword');
            Route::post('/forgot-password', 'BadasoAuthController@forgetPassword');
            Route::post('/reset-password', 'BadasoAuthController@resetPassword');
            Route::post('/refresh-token', 'BadasoAuthController@refreshToken');
            Route::post('/verify', 'BadasoAuthController@verify');
            Route::post('/user', 'BadasoAuthController@getAuthenticatedUser');
        });

        Route::group(['prefix' => 'file'], function () {
            Route::get('/view', 'BadasoFileController@viewFile');
            Route::get('/download', 'BadasoFileController@downloadFile');
            Route::post('/upload', 'BadasoFileController@uploadFile');
            Route::delete('/delete', 'BadasoFileController@deleteFile');
        });

        Route::group(['prefix' => 'configurations', 'middleware' => BadasoAuthenticate::class], function () {
            Route::get('/applyable', 'BadasoConfigurationsController@applyable');

            Route::get('/', 'BadasoConfigurationsController@browse')->middleware(BadasoCheckPermissions::class.':browse_configurations');
            Route::get('/read', 'BadasoConfigurationsController@read')->middleware(BadasoCheckPermissions::class.':read_configurations');
            Route::put('/edit', 'BadasoConfigurationsController@edit')->middleware(BadasoCheckPermissions::class.':edit_configurations');
            Route::put('/edit-multiple', 'BadasoConfigurationsController@editMultiple')->middleware(BadasoCheckPermissions::class.':edit_configurations');
            Route::post('/add', 'BadasoConfigurationsController@add')->middleware(BadasoCheckPermissions::class.':add_configurations');
            Route::delete('/delete', 'BadasoConfigurationsController@delete')->middleware(BadasoCheckPermissions::class.':delete_configurations');
        });

        Route::group(['prefix' => 'menus', 'middleware' => BadasoAuthenticate::class], function () {
            Route::get('/', 'BadasoMenuController@browseMenu')->middleware(BadasoCheckPermissions::class.':browse_menus');
            Route::get('/read', 'BadasoMenuController@readMenu')->middleware(BadasoCheckPermissions::class.':read_menus');
            Route::put('/edit', 'BadasoMenuController@editMenu')->middleware(BadasoCheckPermissions::class.':edit_menus');
            Route::post('/add', 'BadasoMenuController@addMenu')->middleware(BadasoCheckPermissions::class.':add_menus');
            Route::delete('/delete', 'BadasoMenuController@deleteMenu')->middleware(BadasoCheckPermissions::class.':delete_menus');
            Route::put('/arrange-items', 'BadasoMenuController@editMenuItemsOrder')->middleware(BadasoCheckPermissions::class.':edit_menus');

            Route::get('/item', 'BadasoMenuController@browseMenuItem')->middleware(BadasoCheckPermissions::class.':browse_menu_items');
            Route::get('/item/read', 'BadasoMenuController@readMenuItem')->middleware(BadasoCheckPermissions::class.':read_menu_items');
            Route::put('/item/edit', 'BadasoMenuController@editMenuItem')->middleware(BadasoCheckPermissions::class.':edit_menu_items');
            Route::put('/item/edit-order', 'BadasoMenuController@editMenuItemOrder')->middleware(BadasoCheckPermissions::class.':edit_menu_items');
            Route::post('/item/add', 'BadasoMenuController@addMenuItem')->middleware(BadasoCheckPermissions::class.':add_menu_items');
            Route::delete('/item/delete', 'BadasoMenuController@deleteMenuItem')->middleware(BadasoCheckPermissions::class.':delete_menu_items');

            Route::get('/item-by-key', 'BadasoMenuController@browseMenuItemByKey');
        });

        Route::group(['prefix' => 'users', 'middleware' => BadasoAuthenticate::class], function () {
            Route::get('/', 'BadasoUserController@browse')->middleware(BadasoCheckPermissions::class.':browse_users');
            Route::get('/read', 'BadasoUserController@read')->middleware(BadasoCheckPermissions::class.':read_users');
            Route::put('/edit', 'BadasoUserController@edit')->middleware(BadasoCheckPermissions::class.':edit_users');
            Route::post('/add', 'BadasoUserController@add')->middleware(BadasoCheckPermissions::class.':add_users');
            Route::delete('/delete', 'BadasoUserController@delete')->middleware(BadasoCheckPermissions::class.':delete_users');
            Route::delete('/delete-multiple', 'BadasoUserController@deleteMultiple')->middleware(BadasoCheckPermissions::class.':delete_users');
        });

        Route::group(['prefix' => 'permissions', 'middleware' => BadasoAuthenticate::class], function () {
            Route::get('/', 'BadasoPermissionController@browse')->middleware(BadasoCheckPermissions::class.':browse_permissions');
            Route::get('/read', 'BadasoPermissionController@read')->middleware(BadasoCheckPermissions::class.':read_permissions');
            Route::put('/edit', 'BadasoPermissionController@edit')->middleware(BadasoCheckPermissions::class.':edit_permissions');
            Route::post('/add', 'BadasoPermissionController@add')->middleware(BadasoCheckPermissions::class.':add_permissions');
            Route::delete('/delete', 'BadasoPermissionController@delete')->middleware(BadasoCheckPermissions::class.':delete_permissions');
            Route::delete('/delete-multiple', 'BadasoPermissionController@deleteMultiple')->middleware(BadasoCheckPermissions::class.':delete_permissions');
        });

        Route::group(['prefix' => 'roles', 'middleware' => BadasoAuthenticate::class], function () {
            Route::get('/', 'BadasoRoleController@browse')->middleware(BadasoCheckPermissions::class.':browse_roles');
            Route::get('/read', 'BadasoRoleController@read')->middleware(BadasoCheckPermissions::class.':read_roles');
            Route::put('/edit', 'BadasoRoleController@edit')->middleware(BadasoCheckPermissions::class.':edit_roles');
            Route::post('/add', 'BadasoRoleController@add')->middleware(BadasoCheckPermissions::class.':add_roles');
            Route::delete('/delete', 'BadasoRoleController@delete')->middleware(BadasoCheckPermissions::class.':delete_roles');
            Route::delete('/delete-multiple', 'BadasoRoleController@deleteMultiple')->middleware(BadasoCheckPermissions::class.':delete_roles');
        });

        Route::group(['prefix' => 'user-roles', 'middleware' => BadasoAuthenticate::class], function () {
            Route::get('/', 'BadasoUserRoleController@browseByUser')->middleware(BadasoCheckPermissions::class.':browse_user_role');
            Route::get('/all', 'BadasoUserRoleController@browse')->middleware(BadasoCheckPermissions::class.':browse_user_role');
            Route::post('/add-edit', 'BadasoUserRoleController@addOrEdit')->middleware(BadasoCheckPermissions::class.':add_or_edit_user_role');
            Route::get('/all-role', 'BadasoUserRoleController@browseAllRole')->middleware(BadasoCheckPermissions::class.':browse_user_role');
        });

        Route::group(['prefix' => 'role-permissions', 'middleware' => BadasoAuthenticate::class], function () {
            Route::get('/', 'BadasoRolePermissionController@browseByRole')->middleware(BadasoCheckPermissions::class.':browse_role_permission');
            Route::get('/all', 'BadasoRolePermissionController@browse')->middleware(BadasoCheckPermissions::class.':browse_role_permission');
            Route::post('/add-edit', 'BadasoRolePermissionController@addOrEdit')->middleware(BadasoCheckPermissions::class.':add_or_edit_role_permission');
            Route::get('/all-permission', 'BadasoRolePermissionController@browseAllPermission')->middleware(BadasoCheckPermissions::class.':browse_role_permission');
        });

        Route::group(['prefix' => 'breads', 'middleware' => BadasoAuthenticate::class], function () {
            Route::get('/', 'BadasoBreadController@browse')->middleware(BadasoCheckPermissions::class.':browse_bread');
            Route::get('/read', 'BadasoBreadController@read')->middleware(BadasoCheckPermissions::class.':read_bread');
            Route::put('/edit', 'BadasoBreadController@edit')->middleware(BadasoCheckPermissions::class.':edit_bread');
            Route::post('/add', 'BadasoBreadController@add')->middleware(BadasoCheckPermissions::class.':add_bread');
            Route::delete('/delete', 'BadasoBreadController@delete')->middleware(BadasoCheckPermissions::class.':delete_bread');
            Route::get('/generate', 'BadasoBreadController@generate')->middleware(BadasoCheckPermissions::class.':add_bread');
            Route::get('/table', 'BadasoBreadController@readTable')->middleware(BadasoCheckPermissions::class.':read_bread');
            Route::get('/read-by-slug', 'BadasoBreadController@readBySlug')->middleware(BadasoCheckPermissions::class.':read_bread');
        });

        Route::group(['prefix' => 'entities', 'middleware' => BadasoAuthenticate::class], function () {
            try {
                foreach (Badaso::model('DataType')::all() as $data_type) {
                    $bread_controller = $data_type->controller
                                     ? Str::start($data_type->controller, '\\')
                                     : 'BadasoBaseController';
                    Route::get($data_type->slug, $bread_controller.'@browse')->name($data_type->slug.'.browse')->middleware(BadasoCheckPermissionsForBread::class.':'.$data_type->slug.',browse');
                    Route::get($data_type->slug.'/read', $bread_controller.'@read')->name($data_type->slug.'.read')->middleware(BadasoCheckPermissionsForBread::class.':'.$data_type->slug.',read');
                    Route::put($data_type->slug.'/edit', $bread_controller.'@edit')->name($data_type->slug.'.edit')->middleware(BadasoCheckPermissionsForBread::class.':'.$data_type->slug.',edit');
                    Route::post($data_type->slug.'/add', $bread_controller.'@add')->name($data_type->slug.'.add')->middleware(BadasoCheckPermissionsForBread::class.':'.$data_type->slug.',add');
                    Route::delete($data_type->slug.'/delete', $bread_controller.'@delete')->name($data_type->slug.'.delete')->middleware(BadasoCheckPermissionsForBread::class.':'.$data_type->slug.',delete');
                    Route::delete($data_type->slug.'/delete-multiple', $bread_controller.'@deleteMultiple')->name($data_type->slug.'.delete-multiple')->middleware(BadasoCheckPermissionsForBread::class.':'.$data_type->slug.',delete');
                }
            } catch (\InvalidArgumentException $e) {
                throw new \InvalidArgumentException("Custom routes hasn't been configured because: ".$e->getMessage(), 1);
            } catch (\Exception $e) {
                // do nothing, might just be because table not yet migrated.
            }
        });
    });
});