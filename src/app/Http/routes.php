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

/* INI Rodolfo*/
    /* INI Subastas*/
        Route::get('/subastas', 'AuctionFrontController@listaSubastas');
        Route::get('/subastas/agregar','AuctionFrontController@addAuction');
        Route::get('subastas/editar/{auction}', [
            'as' => 'auction.edit', 'uses' => 'AuctionFrontController@editAuction'
        ]);
        Route::get('subastas/replicar/{auction}', [
            'as' => 'auction.replicate', 'uses' => 'AuctionFrontController@replicateAuction'
        ]);
        Route::post('/subastas/guardar','AuctionFrontController@storeAuction');
        Route::get('subastas/ver/{auction}/info', 'AuctionFrontController@getInfo');
        Route::get('/ofertas/agregar', 'AuctionFrontController@offersAuctionFront');
        Route::get('/getusersauctionprivate','AuctionFrontController@getUsersAuctionPrivate');
    /* FIN Subastas*/
    /* INI Usuarios*/
        Route::get('/usuarios','UserController@userList');
        Route::post('/usuarios/guardar','UserController@userSave');
        Route::get('usuarios/editar/{username}', [
            'as' => 'user.edit', 'uses' => 'UserController@userEdit'
        ]);
        Route::get('usuarios/compras/{id}', [
            'as' => 'user.compras', 'uses' => 'UserController@userMyBids'
        ]);
        Route::get('usuarios/ofertas/{id}', [
            'as' => 'user.compras', 'uses' => 'UserController@userMyOffers'
        ]);
        Route::get('/usuarios/compras','UserController@userMyBids');
        Route::get('/usuarios/ofertas','UserController@userMyOffers');
        Route::get('/usuarios/editar/status/{id}','UserController@usersChangeApproval');
        Route::post('/usuarios/guardar','UserController@userSave');
    /* FIN Usuarios*/
    /* INI Puertos*/
        Route::get('/puertos','PortsController@portsList');
        Route::get('/puertos/agregar','PortsController@portsAdd');
        Route::get('puertos/editar/{port}', [
            'as' => 'port.edit', 'uses' => 'PortsController@portsEdit'
        ]);
        Route::post('/puertos/guardar','PortsController@portsSave');
        Route::get('/puertos/ver/preferido','BoatController@getPreferredPort');
    /*FIN Puertos*/
    /* INI Errores -> Esto esta seteado en App/Exceptions/Handler */
        Route::get('error/404', function(){
            return View('landing3/errors/404');
        });
        Route::get('error/500', function(){
            return View('landing3/errors/500');
        });
    /* FIN Errores*/
/* FIN Rodolfo*/

Route::get('/ofertas','AuctionFrontController@offerList');
Route::get('/barcos','BoatController@boatList');
Route::get('/productos/ver/unidades','ProductController@getUnitsFromProductIdCaliber');
Route::get('/productos/ver/calibres','ProductController@getCalibersFromProductId');

Route::get('/landing2', 'AuctionController@subastaHome');

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthControllerLogin@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::get('/home', 'HomeController@index');

Route::get('/subastas/ver/{auction}', 'AuctionFrontController@auctionDetails');





//---------------------------------------------------------------------------
// Auctions
//---------------------------------------------------------------------------
Route::get('subastas/exportar/{auction}', [
    'as' => 'auction.export', 'uses' => 'AuctionBackController@export'
]);
Route::get('auction/operations/process/{auction}', [
    'as' => 'auction.operations.process', 'uses' => 'AuctionController@process'
]);
Route::post('auction/operations/process/', [
    'as' => 'auction.saveProcess', 'uses' => 'AuctionController@saveProcess'
]);
Route::get('auction/operations/{auction}', [
    'as' => 'auction.operations', 'uses' => 'AuctionController@operations'
]);
Route::get('auction/offers/{auction}', [
    'as' => 'auction.offers', 'uses' => 'AuctionController@offersToBid'
]);
Route::get('auction/autofersbid/{auction}', [
    'as' => 'auction.offersToBid', 'uses' => 'AuctionController@autoOffersBid'
]);
Route::get('oferta/reschazar/{auction}', [
    'as' => 'auction.offersDecline', 'uses' => 'AuctionController@declineOffers'
]);
Route::get('oferta/reschazar/{auction}/{offer}', 'AuctionController@declineOffers');
//url para el cron job php + docker
Route::get('auction/autoffers',  'AuctionController@autoOffersToBid');


Route::get('auction/deactivate/{auction}', [
    'as' => 'auction.deactivate', 'uses' => 'AuctionBackController@deactivate'
]);

Route::group(['middleware' => ['auth']],function(){
    Route:resource('subasta','AuctionController');
});

//---------------------------------------------------------------------------
//User routhes
//---------------------------------------------------------------------------
Route::post('users/approve/{users}', [
    'as' => 'users.approve', 'uses' => 'UserController@approve'
]);
Route::post('users/reject/{users}', [
    'as' => 'users.reject', 'uses' => 'UserController@reject'
]);
Route::group(['middleware' => ['auth']],function(){
    Route:resource('users','UserController');
});

Route::post('user/setbidlimit', 'UserController@editBidLimit');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordControllerIndex@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordControllerIndex@postReset');

//---------------------------------------------------------------------------
//Boats routhes
//---------------------------------------------------------------------------
Route::get('sellerboat/arrive/{boats}', [
    'as' => 'sellerboat.arrive', 'uses' => 'SellerBoatsController@arrive'
]);
Route::post('sellerboat/arrive', [
    'as' => 'sellerboat.arrive', 'uses' => 'SellerBoatsController@storeArrive'
]);

Route::post('barcos/store', [
    'as' => 'sellerboat.store', 'uses' => 'SellerBoatsController@store'
]);

Route::get('sellerboat/arrive/edit/{arrive}', [
    'as' => 'sellerboat.editarrive', 'uses' => 'SellerBoatsController@editArrive'
]);
Route::post('sellerboat/arrive/edit/', [
    'as' => 'sellerboat.updatearrive', 'uses' => 'SellerBoatsController@updateArrive'
]);

Route::get('sellerboat/batch/{arrive}', [
    'as' => 'sellerboat.batch', 'uses' => 'SellerBoatsController@batch'
]);
Route::post('sellerboat/batch', [
    'as' => 'sellerboat.batch', 'uses' => 'SellerBoatsController@storeBatch'
]);

Route::get('sellerboat/batch/delete/{batch}', [
    'as' => 'sellerboat.batch.delete', 'uses' => 'SellerBoatsController@deleteBatch'
]);



Route::group(['middleware' => ['auth']],function(){
    Route::post("/get/participantes","AuctionBackController@getParticipantes");
});
Route::group(['middleware' => ['auth']],function(){
    Route:resource('sellerAuction','SellerAuctionController');
});
Route::post('boats/approve/{boats}', [
    'as' => 'boats.approve', 'uses' => 'BoatController@approve'
]);

Route::get('boats/approve/{boats}', [
    'as' => 'boats.approve', 'uses' => 'BoatController@approve'
]);

Route::post('boats/reject/{boats}', [
    'as' => 'boats.reject', 'uses' => 'BoatController@reject'
]);

Route::get('boats/reject/{boats}', [
    'as' => 'boats.reject', 'uses' => 'BoatController@reject'
]);
Route::group(['middleware' => ['auth']],function(){
    Route:resource('boats','BoatController');
});

Route::post('products/restore/{products}', [
    'as' => 'products.restore', 'uses' => 'ProductController@restore'
]);
Route::post('products/trash/{products}', [
    'as' => 'products.trash', 'uses' => 'ProductController@trash'
]);

Route::group(['middleware' => ['auth']],function(){
    Route:resource('products','ProductController');
});

Route::get('privatesales', 'SellerAuctionController@privatesales');

Route::get('calculateprice', 'AuctionController@calculatePrice');
Route::get('makeBid', 'AuctionController@makeBid');
Route::post('offersAuction', 'AuctionController@offersAuction');

Route::post('subastas/ver/mas', 'AuctionFrontController@getauctions');

Route::group(['middleware' => ['auth']],function(){
    Route:resource('sellerbatch', 'BoatController@sellerbatch');
});

Route::post('editbatch', 'SellerBoatsController@editbatch');

Route::get('registro/comprador', 'RegisterController@getRegisterBuyer');
Route::post('registro/comprador', 'RegisterController@postRegisterBuyer');
Route::get('verifica/correo/{hash}', 'RegisterController@verifyUsersEmail');

Route::get('registro/vendedor', 'RegisterController@getRegisterSeller');
Route::post('registro/vendedor', 'RegisterController@postRegisterSeller');


Route::get('priavatesale/{batch}', 'SellerBoatsController@priavateSale');
Route::post('savePrivateSale', 'SellerBoatsController@savePrivateSale');
Route::group(['middleware' => ['auth']],function(){
    Route:resource('compra', 'AuctionBackController@buyerBid');
});


Route::get('bids/qualify/{bid}', [
    'as' => 'bid.qualify', 'uses' => 'AuctionBackController@qualifyBid'
]);


Route::get('bids/buyers/', [
    'as' => 'bid.buyers', 'uses' => 'SellerBoatsController@buyersName'
]);


Route::post('bids/qualify/{bid}', [
    'as' => 'bid.saveQualify', 'uses' => 'AuctionController@saveQualifyBid'
]);

Route::get('subscribe/{auction}','AuctionBackController@subscribeUser');

//---------------------------------------------------------------------------
//Get Fecha actual routhes
//---------------------------------------------------------------------------
Route::get('current-time', 'AuctionBackController@getCurrentTime');


//G.B filtrar barco por estatus
Route::get('/filtrar', 'BoatController@getStatusTheBoat');

Route::get('cuenta', 'userController@getInfoUser');

