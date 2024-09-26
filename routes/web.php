<?php

use App\CaseStudies;
use App\Http\Controllers\Admin\OfflinePayment;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\VerifyUserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Access\Gate;

Route::get('/clear-cache', function () {
    Artisan::call("optimize:clear");
});

Route::get('/', 'FrontHomeController@index')->name('home');
Route::get('setCurrency', 'FrontHomeController@setCurrency')->name('setCurrency');

Route::get('about',  function () {
    return view('welcome');
})->name('about');
Route::get('thank-you',  function () {
    return view('thank-you');
})->name('thank-you');
Route::get('support',  function () {
    return view('welcome');
})->name('support');
Route::get('feedback/{ticket_id}', '\App\Http\Controllers\TicketFeedbackController@index')->name('feedback');
Route::post('contact/store', '\App\Http\Controllers\ContactUsController@store')->name('contact.store');
Route::get('contact/', '\App\Http\Controllers\ContactUsController@index')->name('contact.index')->middleware('auth');
Route::get('contact/{id}', '\App\Http\Controllers\ContactUsController@show')->name('contact.show')->middleware('auth');
Route::get('/verify-user/{service_id?}', '\App\Http\Controllers\Admin\TicketsController@verifyUser')->name('verifyUser');
Route::post('feedback/store', '\App\Http\Controllers\TicketFeedbackController@store')->name('feedback_store');
Route::get('terms-and-condition',  function () {
    $terms = \App\Pages::where('page_name', 'terms_and_condition')->first();
    return view('terms_and_condition', compact('terms'));
})->name('terms-and-condition');
Route::get('/home', function () {
    $route = Gate::denies('dashboard_access') ? 'admin.user' : (Gate::denies('user_dashboard_access') ? 'admin.home' : 'admin.tickets.index');
    if (session('status')) {
        return redirect()->route($route)->with('status', session('status'));
    }

    return redirect()->route($route);
});

Route::post('/verifyUserEmailId', [VerifyUserController::class, 'verifyUser']);
// Route::post('verifyUserEmailId', function () {
//     return response()->json(['message' => 'Route is working!'])->name('verify_User_EmailId');
// });

Auth::routes(['register' => false]);
// Route::get('login', 'App\Http\Controllers\LoginController@showLoginForm')->name('login_form');
// Route::post('login', 'App\Http\Controllers\LoginController@login')->name('login');
Route::get('create', '\App\Http\Controllers\CustomRegisterController@showForm')->name('register_form');
Route::post('create', '\App\Http\Controllers\CustomRegisterController@register')->name('createUser');

/*Route::post('tickets/media', 'ServiceController@storeMedia')->name('tickets.storeMedia');
Route::post('tickets/comment/{ticket}', 'ServiceController@storeComment')->name('tickets.storeComment');*/
Route::resource('services', 'ServiceController')->only(['index', 'show']);

Route::post('ckeditor/upload', 'CKEditorController@upload')->name('ckeditor.image-upload');
Route::get('learnMore/{id}', function ($id) {
    $caseStudy = CaseStudies::find($id);
    return view('caseStudies.learnMore', compact('caseStudy'));
})->name('caseStudies.learnMore');

//response call back
Route::get('callback_url', 'Admin\TicketsController@getResponse')->name('callback_url');
Route::get('/profile', 'FrontHomeController@getProfile')->name('getProfile')->middleware(['auth']);;
Route::post('/profile-store', 'FrontHomeController@profileStore')->name('profileStore')->middleware(['auth']);;
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/user-dashboard', 'HomeController@user')->name('user');
    Route::get('/admin-search', 'HomeController@adminSearch')->name('admin-search');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Departments
    Route::delete('dept/destroy', 'DeptsController@massDestroy')->name('dept.massDestroy');
    Route::resource('dept', 'DeptsController');

    // Statuses
    Route::delete('statuses/destroy', 'StatusesController@massDestroy')->name('statuses.massDestroy');
    Route::resource('statuses', 'StatusesController');

    // Priorities
    Route::delete('priorities/destroy', 'PrioritiesController@massDestroy')->name('priorities.massDestroy');
    Route::resource('priorities', 'PrioritiesController');

    // Categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoriesController');

    //service manager
    Route::delete('services/destroy', 'ServicesController@massDestroy')->name('services.massDestroy');
    Route::post('services/media', 'ServicesController@storeMedia')->name('services.storeMedia');
    Route::resource('services', 'ServicesController');

    // Tickets
    Route::delete('tickets/destroy', 'TicketsController@massDestroy')->name('tickets.massDestroy');
    Route::post('tickets/media', 'TicketsController@storeMedia')->name('tickets.storeMedia');
    Route::post('tickets/comment/{ticket}', 'TicketsController@storeComment')->name('tickets.storeComment');
    Route::get('tickets/getServices', 'TicketsController@getServices')->name('tickets.getServices');
    Route::get('tickets/getTags', 'TicketsController@getTags')->name('tickets.getTags');
    Route::get('getTicketTags/{id}', 'TicketsController@getTicketTags')->name('tickets.getTicketTags');
    Route::get('getPreviousComment/{id}', 'TicketsController@getPreviousComment')->name('tickets.getPreviousComment');
    Route::get('tickets/create/{id}', 'TicketsController@createWithService')->name('tickets.createWithService');
    Route::resource('tickets', 'TicketsController');

    //payments
    Route::resource('payment', 'PaymentsController');


    //links
    Route::delete('links/destroy', 'LinksController@massDestroy')->name('links.massDestroy');
    Route::resource('links', 'LinksController');

    //bills
    Route::delete('bills/destroy', 'BillsController@massDestroy')->name('bills.massDestroy');
    Route::get('bills/createBill', 'BillsController@createBill')->name('bills.createBill');
    Route::get('bills/resentInvoice/{id}', 'BillsController@resentInvoice')->name('bills.resentInvoice');
   // Route::get('bills/download/{id}', 'BillsController@resentInvoice')->name('bills.download');


    Route::resource('bills', 'BillsController');

    //pages
    Route::resource('pages', 'PagesController');

    //case_studies
    Route::resource('testimonials', 'TestiMonialsController');
    Route::resource('partners', 'PartnersController');
    Route::resource('caseStudies', 'CaseStudiesController');
    Route::resource('whyChooseUs', 'WhyChooseUsController');
    Route::resource('faq', 'FaqController');
    Route::resource('emailLog', 'EmailLogController');
    Route::get('customer', 'UsersController@customerList')->name('customer.list');
    Route::get('customer/{id}', 'UsersController@customerShow')->name('customer.customerShow');
    Route::get('customer/user/show', 'UsersController@showallcustomer')->name('customer.showuser');

    // Comments
    Route::delete('comments/destroy', 'CommentsController@massDestroy')->name('comments.massDestroy');
    Route::resource('comments', 'CommentsController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    Route::get('setting/', 'SettingController@index')->name('setting.index');
    Route::post('store', 'SettingController@store')->name('setting.store');

    /* from here all route created by Rohit Kumar he is
    PHP FULL STACK Devloper
    email id rohit 83013@gmail.com
    */

    // offline payment route

});

// this route is responsible for login with password
Route::post('user/password', [LoginController::class, 'login_with_password'])->name('login.with.password');
Route::get('user/password', [LoginController::class, 'login_with_password'])->name('login.with.password');
// Route::get('payment/offline', [OfflinePayment::class, 'index'])->middleware('auth')->name('payment.offline');
Route::post('payment/offline', [OfflinePayment::class, 'store'])->middleware('auth')->name('payment.offline');
Route::get('payment/offline/{id}', [OfflinePayment::class, 'index1'])->middleware('auth')->name('payment.offline.id');
Route::get('payment/offline/onchange/{id}', [OfflinePayment::class, 'onchange_data'])->middleware('auth')->name('payment.offline.onchange');
Route::get('file/manager', [FileManagerController::class, 'index'])->middleware('auth')->name('file.manager');
Route::post('file/manager/get', [FileManagerController::class, 'get_file'])->middleware('auth')->name('file.manager.get');
Route::post('/otp',[OtpController::class, 'check_otp'])->name('otp.check');
Route::get('/get/otp',[OtpController::class, 'get_otp'])->name('otp.get');

