<?php

$prefix = session('applocale');
$lang = App\Models\Lang::where('default', 1)->first();

Route::get('/', 'PagesController@index')->name('home');

Route::get('/sitemap.xml', 'SitemapController@xml')->name('sitemap.xml');

Route::group(['prefix' => $prefix, 'middleware' => 'auth_front'], function() {

  Route::get('/cabinet/personalData', 'CabinetController@index')->name('cabinet');
  Route::post('/cabinet/savePersonalData', 'CabinetController@savePersonalData')->name('cabinet.savePersonalData');
  Route::post('/cabinet/changePass', 'CabinetController@savePass')->name('cabinet.savePass');
  Route::post('cabinet/filterCountries', 'CabinetController@filterByCountries')->name('cabinet.filterByCountries');
  Route::post('cabinet/filterRegions', 'CabinetController@filterByRegions')->name('cabinet.filterByRegions');
  Route::post('/cabinet/addAddress', 'CabinetController@addAddress')->name('cabinet.addAddress');
  Route::post('/cabinet/saveAddress/{id?}', 'CabinetController@saveAddress')->name('cabinet.saveAddress');
  Route::delete('/cabinet/deleteAddress/{id?}', 'CabinetController@deleteAddress')->name('cabinet.deleteAddress');
  Route::post('/cabinet/priorityAddress', 'CabinetController@priorityAddress')->name('cabinet.priorityAddress');

  Route::get('/cabinet/history', 'CabinetController@history')->name('cabinet.history');
  Route::post('/cabinet/historyCart/{id}', 'CabinetController@historyCart')->name('cabinet.historyCart');
  Route::get('/cabinet/history/order/{order}', 'CabinetController@historyOrder')->name('cabinet.historyOrder');
  Route::post('/cabinet/historyCartSet/{id}', 'CabinetController@historyCartSet')->name('cabinet.historyCartSet');
  Route::post('/cabinet/historyCartProduct/{id}', 'CabinetController@historyCartProduct')->name('cabinet.historyCartProduct');

  Route::get('/cabinet/return', 'CabinetController@return')->name('cabinet.return');
  Route::get('/cabinet/return/order/{order}', 'CabinetController@returnOrder')->name('cabinet.returnOrder');
  Route::post('/cabinet/return/addProductsToReturn/{order}', 'CabinetController@addProductsToReturn')->name('cabinet.addProductsToReturn');
  Route::post('/cabinet/return/addSetsToReturn/{order}', 'CabinetController@addSetsToReturn')->name('cabinet.addSetsToReturn');
  Route::post('/cabinet/return/saveReturn/{return}', 'CabinetController@saveReturn')->name('cabinet.saveReturn');

  Route::get('/cabinet/wishList', 'CabinetController@wishList')->name('cabinet.wishList');
});

Route::group(['prefix' => $prefix], function() {

    Route::get('/404', 'PagesController@get404')->name('404');

    Route::get('/sitemap', 'SitemapController@html')->name('sitemap.html');

    Route::post('/changeLang', 'LanguagesController@changeLang');

    Route::get('/register', 'Auth\RegistrationController@create');
    Route::post('/register', 'Auth\RegistrationController@store');
    Route::get('/register/authorizeUser/{user}', 'Auth\RegistrationController@authorizeUser');
    Route::get('/register/changePass/{user}', 'Auth\RegistrationController@changePass');

    Route::get('/login', 'Auth\AuthController@create')->name('front.login');
    Route::post('/login', 'Auth\AuthController@store');
    Route::get('/logout', 'Auth\AuthController@logout');

    Route::get('/login/{provider}', 'Auth\AuthController@redirectToProvider');
    Route::get('/login/{provider}/callback', 'Auth\AuthController@handleProviderCallback');

    Route::get('/password/email', 'Auth\ForgotPasswordController@getEmail')->name('password.email');
    Route::post('/password/email', 'Auth\ForgotPasswordController@postEmail');

    Route::get('/password/code', 'Auth\ForgotPasswordController@getCode')->name('password.code');
    Route::post('/password/code', 'Auth\ForgotPasswordController@postCode');

    Route::get('/password/reset', 'Auth\ForgotPasswordController@getReset')->name('password.reset');
    Route::post('/password/reset', 'Auth\ForgotPasswordController@postReset');

    // Ajax request
    Route::post('/addToCart', 'CartController@addToCart');
    Route::post('/addSetToCart', 'CartController@addSetToCart');
    Route::post('/cartQty/minus', 'CartController@changeQtyMinus');
    Route::post('/cartQty/plus', 'CartController@changeQtyPlus');
    Route::post('/cartQty/changeQty', 'CartController@changeQty');
    Route::post('/cartQty/changeQtySet', 'CartController@changeQtySet');
    Route::post('/setQty/minus', 'CartController@changeSetQtyMinus');
    Route::post('/setQty/plus', 'CartController@changeSetQtyPlus');
    Route::post('/removeItemCart', 'CartController@removeItemCart');
    Route::post('/removeSetCart', 'CartController@removeSetCart');
    Route::post('/cart/set/promocode', 'CartController@setPromocode');
    Route::post('/filterCountries', 'CartController@filterByCountries');
    Route::post('/filterRegions', 'CartController@filterByRegions');
    Route::post('/moveFromCartToWishList', 'CartController@moveFromCartToWishList');
    Route::post('/moveSetFromCartToWishList', 'CartController@moveSetFromCartToWishList');

    Route::post('/search/autocomplete', 'SearchController@index');
    Route::get('/search', 'SearchController@search');
    Route::post('/search/sort/highPrice', 'SearchController@sortByHighPrice');
    Route::post('/search/sort/lowPrice', 'SearchController@sortByLowPrice');
    Route::post('/search/sort/newest', 'SearchController@sortByDesc');

    Route::get('/', 'PagesController@index')->name('home');

    Route::get('/cart', 'CartController@index')->name('cart');

    Route::get('/wishList', 'WishListController@index')->name('wishList');
    Route::post('/addToWishList', 'WishListController@addToWishList');
    Route::post('/addSetToWishList', 'WishListController@addSetToWishList');
    Route::post('/changeSubproductSizeWishList', 'WishListController@changeSubproductSizeWishList');
    Route::post('/moveFromWishListToCart', 'WishListController@moveFromWishListToCart');
    Route::post('/moveSetFromWishListToCart', 'WishListController@moveSetFromWishListToCart');
    Route::post('/removeItemWishList', 'WishListController@removeItemWishList');
    Route::post('/removeSetWishList', 'WishListController@removeSetWishList');

    Route::post('/order', 'OrderController@index');
    Route::get('/thanks', 'OrderController@thanks')->name('thanks');

    Route::get('/catalog', 'ProductsController@getAllProducts')->name('all-product');
    Route::get('/catalog/{collection}', 'ProductsController@getCollection')->name('collection');
    Route::get('/catalog/{collection}/{product}', 'ProductsController@getProduct')->name('product');
    Route::post('/changeSubProduct', 'ProductsController@changeSubProduct');
    Route::post('/changeSubProductOneItem', 'ProductsController@changeSubProductOneItem');
    Route::post('/filter', 'ProductsController@filter');
    Route::post('/filter/property', 'ProductsController@filterProperty');
    Route::post('/filter/price', 'ProductsController@filterPrice');
    Route::post('/filter/order', 'ProductsController@filterOrder');
    Route::get('/filter/reset', 'ProductsController@filterReset');

    Route::get('contacts', 'ContactController@index');
    Route::post('contacts', 'ContactController@feedBack');

    Route::get('promocode/{promocodeId}', 'PagesController@getPromocode');

    Route::get('/{pages}', 'PagesController@getPages')->name('pages');
});
