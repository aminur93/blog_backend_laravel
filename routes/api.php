<?php

//Frontend Routes
Route::get('front/get_category','Api\FrontController@getAllCategory');
Route::get('front/get_subcategory','Api\FrontController@getAllSubCategory');
Route::get('front/get_Tag','Api\FrontController@getAllTag');
Route::get('front/get_recent_blog','Api\FrontController@getRecentBlog');
Route::get('front/get_popular_blog','Api\FrontController@getPopularBlog');
Route::get('front/get_main_blog','Api\FrontController@getMainBlog');
Route::get('front/blog_view_update','Api\FrontController@BlogViewUpdate');
Route::get('front/singleBlog/{id}','Api\FrontController@singleBlog');
Route::post('front/blog_comment/{id}','Api\FrontController@BlogComments');
Route::get('front/get_comment','Api\FrontController@getBlogComment');
Route::get('front/get_Category_blog/{category_id}','Api\FrontController@getCategoryBlog');
Route::get('front/get_SubCategory_blog/{sub_cat_id}','Api\FrontController@getSubCategoryBlog');
Route::get('front/get_Tag_blog/{tag_id}','Api\FrontController@getTagBlog');
Route::get('front/BlogSearchList','Api\FrontController@getBlogSearchList');
Route::get('front/SearchBlog','Api\FrontController@getBlogSearch');
Route::post('front/contact_us','Api\FrontController@contactUs');


//Auth Routes
Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function (){

    Route::post('signin', 'SignInController');

    Route::group(['middleware' => 'auth:api'], function(){

        Route::get('signout', 'SignOutController');

    });

    Route::get('me', 'MeController');

    Route::post('forgetPassword','ForgetPasswordController@forgetPassword');
    Route::post('changePassword','ChangePasswordController@saveResetPassword');
});


//DashBoard Routes
Route::group(['middleware' => 'auth:api'], function (){

    //Dashboard Routes
    Route::get('/dashboard/categoryCount','Api\DashboardController@countCategory');
    Route::get('/dashboard/subCategoryCount','Api\DashboardController@countSubCategory');
    Route::get('/dashboard/tagCount','Api\DashboardController@countTag');
    Route::get('/dashboard/blogCount','Api\DashboardController@countBlog');
    Route::get('/dashboard/get_category','Api\DashboardController@getCategory');
    Route::get('/dashboard/get_tag','Api\DashboardController@getTag');
    Route::get('/dashboard/get_blog','Api\DashboardController@getBlog');
    Route::get('/dashboard/get_user','Api\DashboardController@getUser');

    //Category Routes
    Route::get('/category', 'Api\CategoryController@index');
    Route::post('/category/store', 'Api\CategoryController@store');
    Route::get('/category/edit/{id}', 'Api\CategoryController@edit');
    Route::post('/category/update/{id}', 'Api\CategoryController@update');
    Route::delete('/category/destroy/{id}', 'Api\CategoryController@destroy');

    //Sub Category Routes
    Route::get('/subcategory', 'Api\SubCategoryController@index');
    Route::post('/subcategory/store', 'Api\SubCategoryController@store');
    Route::get('/subcategory/edit/{id}', 'Api\SubCategoryController@edit');
    Route::post('/subcategory/update/{id}', 'Api\SubCategoryController@update');
    Route::delete('/subcategory/destroy/{id}', 'Api\SubCategoryController@destroy');


    //Tag Routes
    Route::get('/tag','Api\TagController@index');
    Route::post('/tag/store','Api\TagController@store');
    Route::get('/tag/edit/{id}','Api\TagController@edit');
    Route::post('/tag/update/{id}','Api\TagController@update');
    Route::delete('/tag/destroy/{id}','Api\TagController@destroy');

    //Blog Post
    Route::get('/blogpost','Api\BlogPostController@index');
    Route::get('/blogpost/get_subcategories/{category_id}','Api\BlogPostController@getSubCategory');
    Route::post('/blogpost/store','Api\BlogPostController@store');
    Route::get('/blogpost/edit/{id}','Api\BlogPostController@edit');
    Route::post('/blogpost/update/{id}','Api\BlogPostController@update');
    Route::delete('/blogpost/destroy/{id}','Api\BlogPostController@destroy');
    Route::post('/blogpost/delete_image/{id}','Api\BlogPostController@deleteImage');
    Route::post('/blogpost/approve/{id}','Api\BlogPostController@approve');
    Route::post('/blogpost/unapprove/{id}','Api\BlogPostController@unapprove');
    Route::post('/blogpost/publish/{id}','Api\BlogPostController@publish');
    Route::post('/blogpost/unpublish/{id}','Api\BlogPostController@unpublish');
    Route::post('/blogpost/feature/{id}','Api\BlogPostController@feature');
    Route::post('/blogpost/notfeature/{id}','Api\BlogPostController@Notfeature');

    //Roles and Permission And User Crud
    /*
     * start User Routes
    */
        Route::get('/users','Api\UsersController@index');
        Route::get('/users/get_role','Api\UsersController@getRole');
        Route::post('/users/store','Api\UsersController@store');
        Route::get('/users/edit/{id}','Api\UsersController@edit');
        Route::post('/users/update/{id}','Api\UsersController@update');
        Route::delete('/users/destroy/{id}','Api\UsersController@destroy');
        Route::post('/users/password_change/{id}','Api\UsersController@changePassword');
    /*
     * End User Routes
     * */

    /*
    * Start Roles Routes
    */
        Route::get('/roles','Api\RoleController@index');
        Route::post('/roles/store','Api\RoleController@store');
        Route::get('/roles/edit/{id}','Api\RoleController@edit');
        Route::post('/roles/update/{id}','Api\RoleController@update');
        Route::delete('/roles/destroy/{id}','Api\RoleController@destroy');
    /*
     * End Roles Routes
   */

    /*
    * Start Permissions Routes
    */
        Route::get('/permission','Api\PermissionController@index');
        Route::post('/permission/store','Api\PermissionController@store');
        Route::get('/permission/edit/{id}','Api\PermissionController@edit');
        Route::post('/permission/update/{id}','Api\PermissionController@update');
        Route::delete('/permission/destroy/{id}','Api\PermissionController@destroy');
    /*
     * End Permissions Routes
   */
});