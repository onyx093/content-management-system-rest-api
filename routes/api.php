<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group( function () {
        Route::get('/user', [AuthController::class, 'getUser']);

        Route::post('/logout', [AuthController::class, 'logout']);
    });


    /* Route::apiResource('/users', 'UserController');
    Route::apiResource('/roles', 'RoleController');
    Route::apiResource('/permissions', 'PermissionController');
    Route::apiResource('/clients', 'ClientController');
    Route::apiResource('/scopes', 'ScopeController');
    Route::apiResource('/personal-access-tokens', 'PersonalAccessTokenController');
    Route::apiResource('/password-resets', 'PasswordResetController');
    Route::apiResource('/oauth-clients', 'OauthClientController');
    Route::apiResource('/oauth-personal-access-clients', 'OauthPersonalAccessClientController');
    Route::apiResource('/oauth-personal-access-tokens', 'OauthPersonalAccessTokenController');
    Route::apiResource('/oauth-refresh-tokens', 'OauthRefreshTokenController');
    Route::apiResource('/oauth-auth-codes', 'OauthAuthCodeController');
    Route::apiResource('/oauth-access-tokens', 'OauthAccessTokenController');
    Route::apiResource('/oauth-authorization-codes', 'OauthAuthorizationCodeController');
    Route::apiResource('/oauth-scopes', 'OauthScopeController');
    Route::apiResource('/oauth-clients-scopes', 'OauthClientScopeController');
    Route::apiResource('/oauth-personal-access-clients-scopes', 'OauthPersonalAccessClientScopeController');
    Route::apiResource('/oauth-personal-access-tokens-scopes', 'OauthPersonalAccessTokenScopeController');
    Route::apiResource('/oauth-access-tokens-scopes', 'OauthAccessTokenScopeController');
    Route::apiResource('/oauth-authorization-codes-scopes', 'OauthAuthorizationCodeScopeController'); */
});
