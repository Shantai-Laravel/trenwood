<?php
Route::group(['middleware' => ['web']], function ()
{
    $namespace = 'Admin\Http\Controllers';

    Route::group(['namespace' => $namespace, 'prefix' => 'back', 'middleware' => 'auth'], function ()
    {
        Route::get('/', 'AdminController@index')->name('back');

        Route::get('/set-language/{lang}', 'LanguagesController@set')->name('set.language');

        Route::get('quick-upload', 'QuickUploadController@index')->name('quick-upload.index');
        Route::get('quick-upload/download/{id}', 'QuickUploadController@downloadCSV')->name('quick-upload.download');
        Route::post('quick-upload/upload', 'QuickUploadController@uploadCSV')->name('quick-upload.upload');
        Route::post('save-products', 'QuickUploadController@saveProducts')->name('quick-upload.save');
        Route::post('upload-files', 'QuickUploadController@uploadFiles')->name('quick-upload.upload-files');

        Route::get('/users', 'AdminUserController@index');

        Route::resource('/brands', 'BrandsController');
        Route::resource('/brands/changePosition', 'BrandsController@changePosition');
        Route::resource('/promotions', 'PromotionsController');
        Route::resource('/promotions/changePosition', 'PromotionsController@changePosition');
        Route::resource('/promocodes', 'PromocodesController');
        Route::resource('/promocodesType', 'PromocodeTypesController');
        Route::post('promocode/setType', 'PromocodeTypesController@getPromocodeTypes');

        Route::resource('/galleries', 'GalleriesController');

        Route::patch('/brands/{id}/change-status', 'BrandsController@status')->name('brands.change.status');
        Route::patch('/promotions/{id}/change-status', 'PromotionsController@status')->name('promotions.change.status');

        Route::resource('/pages', 'PagesController');

        Route::patch('/pages/save/traductions', 'PagesController@saveTraductions')->name('pages.save.traductions');
        Route::post('/pages/changePosition', 'PagesController@changePosition');
        Route::patch('/pages/{id}/change-status', 'PagesController@status')->name('pages.change.status');

        Route::resource('/modules', 'ModulesController');
        Route::post('/modules/changePosition', 'ModulesController@changePosition');

        Route::resource('submodules', 'SubModulesController');

        Route::resource('/forms', 'FormsController');

        Route::resource('/categories', 'CategoriesController');
        Route::post('/categories/move/posts', 'CategoriesController@movePosts')->name('categories.move.posts');
        Route::post('/categories/change', 'CategoriesController@change')->name('categories.change');
        Route::post('/categories/part', 'CategoriesController@partialSave')->name('categories.partial.save');
        Route::post('/categories/move/posts_', 'CategoriesController@movePosts_')->name('categories.move.posts_');
        Route::post('/categories/part', 'CategoriesController@partialSave')->name('categories.partial.save');

        Route::resource('/menus', 'MenusController');
        Route::post('/menus/move/posts', 'MenusController@movePosts')->name('menus.move.posts');
        Route::post('/menus/change', 'MenusController@change')->name('menus.change');
        Route::post('/menus/part', 'MenusController@partialSave')->name('menus.partial.save');
        Route::post('/menus/move/posts_', 'MenusController@movePosts_')->name('menus.move.posts_');
        Route::post('/menus/part', 'MenusController@partialSave')->name('menus.partial.save');
        Route::post('/menus/categories/assignment', 'MenusController@assignmentCategory')->name('menus.assignment.category');
        Route::get('/menus/items/clean', 'MenusController@cleanMenus')->name('menus.clean');
        Route::get('/menus/group/{id}', 'MenusController@getMenuByGroup')->name('menus.group');

        Route::resource('/product-categories', 'ProductCategoryController');
        Route::post('/product-categories/move/posts', 'ProductCategoryController@movePosts')->name('product-categories.move.posts');
        Route::post('/product-categories/change', 'ProductCategoryController@change')->name('product-categories.change');
        Route::post('/product-categories/part', 'ProductCategoryController@partialSave')->name('product-categories.partial.save');
        Route::post('/product-categories/move/posts_', 'ProductCategoryController@movePosts_')->name('product-categories.move.posts_');
        Route::post('/product-categories/part', 'ProductCategoryController@partialSave')->name('product-categories.partial.save');
        Route::post('/product-categories/categories/assignment', 'ProductCategoryController@assignmentCategory')->name('product-categories.assignment.category');
        Route::get('/product-categories/items/clean', 'ProductCategoryController@cleanProductCategory')->name('product-categories.clean');
        Route::get('/product-categories/group/{id}', 'ProductCategoryController@getMenuByGroup')->name('product-categories.group');

        // Menu groups
        Route::resource('/groups', 'MenuGroupsController');

        Route::resource('/tags', 'TagsController');

        Route::resource('/properties', 'ProductPropertiesController');
        Route::post('/properties/makeFilter/{id}', 'ProductPropertiesController@makeFilter')->name('properties.makeFilter');

        Route::resource('/properties-groups', 'PropertyGroupController');

        Route::resource('/posts', 'PostsController');
        Route::get('/posts/category/{category}', 'PostsController@getPostsByCategory')->name('posts.category');

        Route::resource('/collections', 'CollectionsController');
        Route::post('/collections/changePosition', 'CollectionsController@changePosition');

        Route::resource('/sets', 'SetsController');
        Route::get('/sets/collection/{collectionId}', 'SetsController@getByCollection');
        Route::get('/sets/delete/gallery-item/{id}', 'SetsController@deleteGalleryItem');
        Route::post('/sets/changePosition', 'SetsController@changePosition');
        Route::post('/sets/gallery/main', 'SetsController@addMainSetImages')->name('sets.images.add.main');
        Route::post('/sets/gallery/background', 'SetsController@addBackgroundSetImages')->name('sets.images.add.back');

        Route::resource('/products', 'ProductsController');
        Route::get('/products/sets/{set}', 'ProductsController@getProductsBySet')->name('products.set');
        Route::get('/products/category/{category}', 'ProductsController@getProductsByCategory')->name('products.category');

        Route::post('/products/category/{category}/changePosition', 'ProductsController@changePosition')->name('products.changePosition');

        Route::post('/products/gallery/add/{product}', 'ProductsController@addProductImages')->name('products.images.add');
        Route::post('/products/gallery/edit/{product}', 'ProductsController@editProductImages')->name('products.images.edit');

        Route::post('/products/gallery/main', 'ProductsController@addMainProductImages')->name('products.images.add.main');
        Route::post('/products/gallery/background', 'ProductsController@addBackgroundProductImages')->name('products.images.add.back');
        Route::post('/products/gallery/delete', 'ProductsController@deleteProductImages')->name('products.images.add.delete');

        Route::get('/products/video/delete/{video}', 'ProductsController@deleteProductVideo')->name('products.video.delete');

        Route::post('/gallery/images/delete', 'GalleriesController@deleteGalleryImages')->name('gallery.images.delete');

        Route::resource('/parameters', 'ParametersController');

        Route::post('/parameters/{id}/field', 'FieldsController@store')->name('fields.store');

        Route::resource('/autometa', 'AutoMetasController');

        Route::post('/autometa/changeCategory', 'AutoMetasController@changeCategory');
        Route::post('/autometa/changeCategoryEdit', 'AutoMetasController@changeCategoryEdit');
        Route::post('/autometa/checkAutometasCategory', 'AutoMetasController@checkAutometasCategory');

        Route::resource('/autoalt', 'AutoAltController');

        Route::post('/autoalt/exportCategories', 'AutoAltController@exportCategories')->name('autoalt.exportCategories');

        Route::resource('/review', 'ReviewController');
        Route::patch('/review/{id}/change-status', 'ReviewController@changeStatus')->name('review.change.status');

        Route::resource('/subproducts', 'SubProductsController');
        Route::post('/subproducts/filterProperties', 'SubProductsController@filterProperties');

        Route::resource('/order', 'OrderController');
        Route::post('/order/productQty/plus', 'OrderController@changeQtyPlus')->name('order.changeQtyPlus');
        Route::post('/order/setQty/plus', 'OrderController@changeSetQtyPlus')->name('order.changeSetQtyPlus');
        Route::post('/order/productQty/minus', 'OrderController@changeQtyMinus')->name('order.changeQtyMinus');
        Route::post('/order/setQty/minus', 'OrderController@changeSetQtyMinus')->name('order.changeSetQtyMinus');
        Route::post('/order/productPrice', 'OrderController@changeProductPrice')->name('order.changeProductPrice');
        Route::post('/order/setPrice', 'OrderController@changeSetPrice')->name('order.changeSetPrice');
        Route::post('/order/productDiscount', 'OrderController@changeProductDiscount')->name('order.changeProductDiscount');
        Route::post('/order/setDiscount', 'OrderController@changeSetDiscount')->name('order.changeSetDiscount');
        Route::post('/order/addToOrder', 'OrderController@addToOrder')->name('order.addToOrder');
        Route::post('/order/addProductByCode', 'OrderController@addProductByCode')->name('order.addProductByCode');
        Route::post('/order/{order_id}/saveAddress', 'OrderController@saveAddress')->name('order.saveAddress');
        Route::delete('/order/{order_id}/deleteAddress/{address_id}', 'OrderController@deleteAddress')->name('order.deleteAddress');
        Route::post('/order/changePayment', 'OrderController@changePayment')->name('order.changePayment');
        Route::post('/order/filterOrders', 'OrderController@filterOrders')->name('order.filterOrders');
        Route::post('/order/removeOrderItem', 'OrderController@removeOrderItem')->name('order.removeOrderItem');
        Route::post('/order/removeOrderSet', 'OrderController@removeOrderSet')->name('order.removeOrderSet');
        Route::post('/order/removeAllOrderItems', 'OrderController@removeAllOrderItems')->name('order.removeAllOrderItems');
        Route::post('/order/filterUsers', 'OrderController@filterUsers')->name('order.filterUsers');
        Route::post('/order/set/promocode', 'OrderController@setPromocode')->name('order.setPromocode');
        Route::post('/order/changeSubproductSize', 'OrderController@changeSubproductSize');

        Route::resource('/userfields', 'UserFieldController');

        Route::resource('/frontusers', 'FrontUserController');
        Route::get('/frontusers/{id}/editPassword', 'FrontUserController@editPassword')->name('frontusers.editPassword');
        Route::patch('/frontusers/{id}/updatePassword', 'FrontUserController@updatePassword')->name('frontusers.updatePassword');
        Route::post('/frontusers/{user_id}/addAddress', 'FrontUserController@addAddress')->name('frontusers.addAddress');
        Route::post('/frontusers/{user_id}/updateAddress/{address_id}', 'FrontUserController@updateAddress')->name('frontusers.updateAddress');
        Route::delete('frontusers/{user_id}/deleteAddress/{address_id}', 'FrontUserController@deleteAddress')->name('frontusers.deleteAddress');
        Route::post('frontusers/filterCountries', 'FrontUserController@filterByCountries')->name('frontusers.filterByCountries');
        Route::post('frontusers/filterRegions', 'FrontUserController@filterByRegions')->name('frontusers.filterByRegions');

        Route::resource('/returns', 'ReturnController');
        Route::post('/returns/changeAmount', 'ReturnController@changeAmount')->name('returns.changeAmount');
        Route::post('/returns/filterReturns', 'ReturnController@filterReturns')->name('returns.filterReturns');
        Route::post('/returns/filterUsers', 'ReturnController@filterUsers')->name('returns.filterUsers');
        Route::post('/returns/filterOrders', 'ReturnController@filterOrders')->name('returns.filterOrders');
        Route::post('/returns/productQty/plus', 'ReturnController@changeQtyPlus')->name('returns.changeQtyPlus');
        Route::post('/returns/productQty/minus', 'ReturnController@changeQtyMinus')->name('returns.changeQtyMinus');
        Route::post('/returns/setQty/plus', 'ReturnController@changeSetQtyPlus')->name('returns.changeSetQtyPlus');
        Route::post('/returns/setQty/minus', 'ReturnController@changeSetQtyMinus')->name('returns.changeSetQtyPlus');
        Route::post('/returns/productPrice', 'ReturnController@changeProductPrice')->name('returns.changeProductPrice');
        Route::post('/returns/productDiscount', 'ReturnController@changeProductDiscount')->name('returns.changeProductDiscount');
        Route::post('/returns/removeOrderItem', 'ReturnController@removeOrderItem')->name('returns.removeOrderItem');
        Route::post('/returns/removeOrderSet', 'ReturnController@removeOrderSet')->name('returns.removeOrderSet');
        Route::post('/returns/removeAllOrderItems', 'ReturnController@removeAllOrderItems')->name('returns.removeAllOrderItems');
        Route::post('/returns/addProduct', 'ReturnController@addProduct')->name('returns.addProduct');

        Route::group(['prefix' => 'settings'], function ()
        {

            Route::resource('/languages', 'LanguagesController');
            Route::patch('/languages/set-default/{id}', 'LanguagesController@setDefault')->name('languages.default');

            Route::get('/reviews', 'PostsRatingController@index')->name('reviews.index');
            Route::patch('/reviews', 'PostsRatingController@update')->name('reviews.update');

            Route::get('/general', 'GeneralController@index')->name('general.index');
            Route::post('/general/updateMenu', 'GeneralController@updateMenu')->name('general.updateMenu');
            Route::post('/general/updateSettings', 'GeneralController@updateSettings')->name('general.updateSettings');

            Route::get('/contacts', 'ContactController@index')->name('contacts.index');
            Route::post('/contacts', 'ContactController@store')->name('contacts.store');
            Route::post('/contacts/storeMultilang', 'ContactController@storeMultilang')->name('contacts.storeMultilang');

            Route::get('/crop', 'CropController@index')->name('crop.index');
            Route::post('/crop', 'CropController@update')->name('crop.update');

            Route::get('/meta', 'MetasController@index')->name('metas.index');
            Route::patch('/meta', 'MetasController@update')->name('metas.update');

        });

    });
});
