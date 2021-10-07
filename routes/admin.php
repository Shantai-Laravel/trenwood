<?php

Route::get('/auth/login', 'Auth\CustomAuthController@login')->name('login');
Route::post('/auth/login', 'Auth\CustomAuthController@checkLogin');
Route::get('/auth/register', 'Auth\CustomAuthController@register');
Route::post('/auth/register', 'Auth\CustomAuthController@checkRegister');
Route::get('/auth/logout', 'Auth\CustomAuthController@logout');

//
// Route::group(['prefix' => 'back', 'middleware' => 'auth'], function () {
//
//     Route::get('/set-language/{lang}', 'LanguagesController@set')->name('set.language');
//
//     Route::get('/', function() { return view('admin.welcome'); });
//
//     Route::get('quick-upload', 'Admin\QuickUploadController@index')->name('quick-upload.index');
//     Route::post('save-products', 'Admin\QuickUploadController@saveProducts')->name('quick-upload.save');
//     Route::post('upload-files', 'Admin\QuickUploadController@uploadFiles')->name('quick-upload.upload-files');
//
//     Route::get('/users', 'Admin\AdminUserController@index');
//
//     Route::resource('/brands', 'Admin\BrandsController');
//     Route::resource('/brands/changePosition', 'Admin\BrandsController@changePosition');
//     Route::resource('/promotions', 'Admin\PromotionsController');
//     Route::resource('/promotions/changePosition', 'Admin\PromotionsController@changePosition');
//     Route::resource('/promocodes', 'Admin\PromocodesController');
//     Route::resource('/promocodesType', 'Admin\PromocodeTypesController');
//     Route::post('promocode/setType', 'Admin\PromocodeTypesController@getPromocodeTypes');
//
//     Route::resource('/galleries', 'Admin\GalleriesController');
//
//     Route::patch('/brands/{id}/change-status', 'Admin\BrandsController@status')->name('brands.change.status');
//     Route::patch('/promotions/{id}/change-status', 'Admin\PromotionsController@status')->name('promotions.change.status');
//
//     Route::resource('/pages', 'Admin\PagesController');
//
//     Route::patch('/pages/save/traductions', 'Admin\PagesController@saveTraductions')->name('pages.save.traductions');
//     Route::post('/pages/changePosition', 'Admin\PagesController@changePosition');
//     Route::patch('/pages/{id}/change-status', 'Admin\PagesController@status')->name('pages.change.status');
//
//     Route::resource('/modules', 'Admin\ModulesController');
//     Route::post('/modules/changePosition', 'Admin\ModulesController@changePosition');
//
//     Route::resource('submodules', 'Admin\SubModulesController');
//
//     Route::resource('/forms', 'Admin\FormsController');
//
//     Route::resource('/categories', 'Admin\CategoriesController');
//     Route::post('/categories/move/posts', 'Admin\CategoriesController@movePosts')->name('categories.move.posts');
//     Route::post('/categories/change', 'Admin\CategoriesController@change')->name('categories.change');
//     Route::post('/categories/part', 'Admin\CategoriesController@partialSave')->name('categories.partial.save');
//     Route::post('/categories/move/posts_', 'Admin\CategoriesController@movePosts_')->name('categories.move.posts_');
//     Route::post('/categories/part', 'Admin\CategoriesController@partialSave')->name('categories.partial.save');
//
//     Route::resource('/menus', 'Admin\MenusController');
//     Route::post('/menus/move/posts', 'Admin\MenusController@movePosts')->name('menus.move.posts');
//     Route::post('/menus/change', 'Admin\MenusController@change')->name('menus.change');
//     Route::post('/menus/part', 'Admin\MenusController@partialSave')->name('menus.partial.save');
//     Route::post('/menus/move/posts_', 'Admin\MenusController@movePosts_')->name('menus.move.posts_');
//     Route::post('/menus/part', 'Admin\MenusController@partialSave')->name('menus.partial.save');
//     Route::post('/menus/categories/assignment', 'Admin\MenusController@assignmentCategory')->name('menus.assignment.category');
//     Route::get('/menus/items/clean', 'Admin\MenusController@cleanMenus')->name('menus.clean');
//     Route::get('/menus/group/{id}', 'Admin\MenusController@getMenuByGroup')->name('menus.group');
//
//     Route::resource('/product-categories', 'Admin\ProductCategoryController');
//     Route::post('/product-categories/move/posts', 'Admin\ProductCategoryController@movePosts')->name('product-categories.move.posts');
//     Route::post('/product-categories/change', 'Admin\ProductCategoryController@change')->name('product-categories.change');
//     Route::post('/product-categories/part', 'Admin\ProductCategoryController@partialSave')->name('product-categories.partial.save');
//     Route::post('/product-categories/move/posts_', 'Admin\ProductCategoryController@movePosts_')->name('product-categories.move.posts_');
//     Route::post('/product-categories/part', 'Admin\ProductCategoryController@partialSave')->name('product-categories.partial.save');
//     Route::post('/product-categories/categories/assignment', 'Admin\ProductCategoryController@assignmentCategory')->name('product-categories.assignment.category');
//     Route::get('/product-categories/items/clean', 'Admin\ProductCategoryController@cleanProductCategory')->name('product-categories.clean');
//     Route::get('/product-categories/group/{id}', 'Admin\ProductCategoryController@getMenuByGroup')->name('product-categories.group');
//
//     // Menu groups
//     Route::resource('/groups', 'Admin\MenuGroupsController');
//
//     Route::resource('/tags', 'Admin\TagsController');
//
//     Route::resource('/properties', 'Admin\ProductPropertiesController');
//     Route::post('/properties/makeFilter/{id}', 'Admin\ProductPropertiesController@makeFilter')->name('properties.makeFilter');
//
//     Route::resource('/properties-groups', 'Admin\PropertyGroupController');
//
//     Route::resource('/posts', 'Admin\PostsController');
//     Route::get('/posts/category/{category}', 'Admin\PostsController@getPostsByCategory')->name('posts.category');
//
//     Route::resource('/products', 'Admin\ProductsController');
//     Route::get('/products/category/{category}', 'Admin\ProductsController@getProductsByCategory')->name('products.category');
//
//     Route::post('/products/category/{category}/changePosition', 'Admin\ProductsController@changePosition')->name('products.changePosition');
//
//     Route::post('/products/gallery/add/{product}', 'Admin\ProductsController@addProductImages')->name('products.images.add');
//     Route::post('/products/gallery/edit/{product}', 'Admin\ProductsController@editProductImages')->name('products.images.edit');
//
//     Route::post('/products/gallery/main', 'Admin\ProductsController@addMainProductImages')->name('products.images.add.main');
//     Route::post('/products/gallery/delete', 'Admin\ProductsController@deleteProductImages')->name('products.images.add.delete');
//
//     Route::post('/gallery/images/delete', 'Admin\GalleriesController@deleteGalleryImages')->name('gallery.images.delete');
//
//     Route::resource('/parameters', 'Admin\ParametersController');
//
//     Route::post('/parameters/{id}/field', 'Admin\FieldsController@store')->name('fields.store');
//
//     Route::resource('/autometa', 'Admin\AutoMetasController');
//
//     Route::post('/autometa/changeCategory', 'Admin\AutoMetasController@changeCategory');
//     Route::post('/autometa/changeCategoryEdit', 'Admin\AutoMetasController@changeCategoryEdit');
//     Route::post('/autometa/checkAutometasCategory', 'Admin\AutoMetasController@checkAutometasCategory');
//
//     Route::resource('/autoalt', 'Admin\AutoAltController');
//
//     Route::post('/autoalt/exportCategories', 'Admin\AutoAltController@exportCategories')->name('autoalt.exportCategories');
//
//     Route::resource('/review', 'Admin\ReviewController');
//     Route::patch('/review/{id}/change-status', 'Admin\ReviewController@changeStatus')->name('review.change.status');
//
//     Route::resource('/subproducts', 'Admin\SubProductsController');
//     Route::post('/subproducts/filterProperties', 'Admin\SubProductsController@filterProperties');
//
//     Route::resource('/order', 'Admin\OrderController');
//     Route::post('/order/productQty/plus', 'Admin\OrderController@changeQtyPlus')->name('order.changeQtyPlus');
//     Route::post('/order/productQty/minus', 'Admin\OrderController@changeQtyMinus')->name('order.changeQtyMinus');
//     Route::post('/order/productPrice', 'Admin\OrderController@changeProductPrice')->name('order.changeProductPrice');
//     Route::post('/order/productDiscount', 'Admin\OrderController@changeProductDiscount')->name('order.changeProductDiscount');
//     Route::post('/order/addToOrder', 'Admin\OrderController@addToOrder')->name('order.addToOrder');
//     Route::post('/order/addProductByCode', 'Admin\OrderController@addProductByCode')->name('order.addProductByCode');
//     Route::post('/order/{order_id}/saveAddress', 'Admin\OrderController@saveAddress')->name('order.saveAddress');
//     Route::delete('/order/{order_id}/deleteAddress/{address_id}', 'Admin\OrderController@deleteAddress')->name('order.deleteAddress');
//     Route::post('/order/changePayment', 'Admin\OrderController@changePayment')->name('order.changePayment');
//     Route::post('/order/filterOrders', 'Admin\OrderController@filterOrders')->name('order.filterOrders');
//     Route::post('/order/removeOrderItem', 'Admin\OrderController@removeOrderItem')->name('order.removeOrderItem');
//     Route::post('/order/removeAllOrderItems', 'Admin\OrderController@removeAllOrderItems')->name('order.removeAllOrderItems');
//     Route::post('/order/filterUsers', 'Admin\OrderController@filterUsers')->name('order.filterUsers');
//     Route::post('/order/set/promocode', 'Admin\OrderController@setPromocode')->name('order.setPromocode');
//
//     Route::resource('/userfields', 'Admin\UserFieldController');
//
//     Route::resource('/frontusers', 'Admin\FrontUserController');
//     Route::get('/frontusers/{id}/editPassword', 'Admin\FrontUserController@editPassword')->name('frontusers.editPassword');
//     Route::patch('/frontusers/{id}/updatePassword', 'Admin\FrontUserController@updatePassword')->name('frontusers.updatePassword');
//     Route::post('/frontusers/{user_id}/addAddress', 'Admin\FrontUserController@addAddress')->name('frontusers.addAddress');
//     Route::post('/frontusers/{user_id}/updateAddress/{address_id}', 'Admin\FrontUserController@updateAddress')->name('frontusers.updateAddress');
//     Route::delete('frontusers/{user_id}/deleteAddress/{address_id}', 'Admin\FrontUserController@deleteAddress')->name('frontusers.deleteAddress');
//     Route::post('frontusers/filterCountries', 'Admin\FrontUserController@filterByCountries')->name('frontusers.filterByCountries');
//     Route::post('frontusers/filterRegions', 'Admin\FrontUserController@filterByRegions')->name('frontusers.filterByRegions');
//
//
//     Route::resource('/returns', 'Admin\ReturnController');
//     Route::post('/returns/changeAmount', 'Admin\ReturnController@changeAmount')->name('returns.changeAmount');
//     Route::post('/returns/filterReturns', 'Admin\ReturnController@filterReturns')->name('returns.filterReturns');
//     Route::post('/returns/filterUsers', 'Admin\ReturnController@filterUsers')->name('returns.filterUsers');
//     Route::post('/returns/filterOrders', 'Admin\ReturnController@filterOrders')->name('returns.filterOrders');
//     Route::post('/returns/productQty/plus', 'Admin\ReturnController@changeQtyPlus')->name('returns.changeQtyPlus');
//     Route::post('/returns/productQty/minus', 'Admin\ReturnController@changeQtyMinus')->name('returns.changeQtyMinus');
//     Route::post('/returns/productPrice', 'Admin\ReturnController@changeProductPrice')->name('returns.changeProductPrice');
//     Route::post('/returns/productDiscount', 'Admin\ReturnController@changeProductDiscount')->name('returns.changeProductDiscount');
//     Route::post('/returns/removeOrderItem', 'Admin\ReturnController@removeOrderItem')->name('returns.removeOrderItem');
//     Route::post('/returns/removeAllOrderItems', 'Admin\ReturnController@removeAllOrderItems')->name('returns.removeAllOrderItems');
//     Route::post('/returns/addProduct', 'Admin\ReturnController@addProduct')->name('returns.addProduct');
//
//     Route::group(['prefix' => 'settings'], function () {
//
//         Route::resource('/languages', 'Admin\LanguagesController');
//         Route::patch('/languages/set-default/{id}', 'Admin\LanguagesController@setDefault')->name('languages.default');
//
//         Route::get('/reviews', 'Admin\PostsRatingController@index')->name('reviews.index');
//         Route::patch('/reviews', 'Admin\PostsRatingController@update')->name('reviews.update');
//
//         Route::get('/general', 'Admin\GeneralController@index')->name('general.index');
//         Route::post('/general/updateMenu', 'Admin\GeneralController@updateMenu')->name('general.updateMenu');
//
//         Route::get('/contacts', 'Admin\ContactController@index')->name('contacts.index');
//         Route::post('/contacts', 'Admin\ContactController@store')->name('contacts.store');
//         Route::post('/contacts/storeMultilang', 'Admin\ContactController@storeMultilang')->name('contacts.storeMultilang');
//
//         Route::get('/crop', 'Admin\CropController@index')->name('crop.index');
//         Route::post('/crop', 'Admin\CropController@update')->name('crop.update');
//
//         Route::get('/meta', 'Admin\MetasController@index')->name('metas.index');
//         Route::patch('/meta', 'Admin\MetasController@update')->name('metas.update');
//
//     });
// });
//
//
// // $prefix = session('applocale');
// // $lang = App\Models\Lang::where('default', 1)->first();
// //
// // Route::get('/', function(){
// //     echo "home page";
// // })->name('home default');
// //
// // Route::get('404', 'PagesController@get404')->name('404');
// //
// //
// // Route::group(['prefix' => $prefix], function() {
// //     // require_once(__DIR__.'/routesFront.php');
// // });
// //
// //
// // // if ($prefix == $lang->lang) {
// // //     require_once(__DIR__.'/routesFront.php');
// // // }else{
// // //     Route::group(['prefix' => $prefix], function() {
// // //         require_once(__DIR__.'/routesFront.php');
// // //     });
// // // }
