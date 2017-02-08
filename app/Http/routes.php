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

setlocale(LC_MONETARY, "en_IN");

//Route::get('leads/update', 'LeadsController@update');

/*Route::get('leads/conversions',function(){
    $leads =  App\Models\Leads::
            with('patient')
            ->wherebetween('created_at', ['2016-09-01', '2016-09-31 23:59:59'])
            //->where('state', '<>', 'IN.07')
            ->where('country', '<>' ,'IN')
            //->orWhereNull('country')
            ->groupBy('country')
            ->selectRaw('country, count(*) as count')
            ->get();
    //dd($leads);
    foreach ($leads as $lead) {
        echo $lead->country . " - " . $lead->count . "<p>";
    }
});
*/

Route::group([
    'middleware' => ['auth', 'roles', 'checkip']],
    function() { 
    /** Tracking**/
    Route::get('shippings','ShippingController@index');
    Route::get('api/getShippings','ShippingController@get');

    Route::get('shipping/fedex','FedExController@index');
    Route::get('shipping/track/{id}','FedExController@modal');

    Route::get('invoice/{id}','InvoiceController@show');
    Route::patch('invoice/{id}','InvoiceController@update');

    Route::get('cart/{id}/invoice','InvoiceController@modal');
    Route::post('cart/{id}/invoice','InvoiceController@store');

    Route::get('api/getTrackings','FedExController@getTrackings');
    Route::get('api/sync','FedExController@sync');

    /**Cart Goods Tracking for Ashish**/
    Route::get('carts/goods', 'CartController@goods');
    Route::post('carts/goods', 'CartController@goods');

    Route::get('getPaymentMethods', 'PaymentMethodController@get');

    Route::get('/posts', 'PostController@showPosts');
    Route::get('/getPosts', 'PostController@getPosts');
    Route::get('/getPost/{id}', 'PostController@getPost');
    Route::get('/getLikes/{id}', 'PostController@getLikes');
    Route::patch('/updateLike/{id}', 'PostController@updateLike');
    Route::post('/commentSubmit/{id}', 'PostController@commentSubmit');
    Route::get('/getComments/{id}', 'PostController@getComments');
    Route::get('/getComment/{id}', 'PostController@getComment');
    Route::get('/getChildComments/{id}', 'PostController@getChildComments');
    Route::get('/getPosts/user/{id}', 'PostController@getUserPosts');
    
    Route::get('/editPosts', 'PostController@editPost');
    Route::post('/storePost', 'PostController@storePost');
    Route::get('/addPost', 'PostController@addPost');


    Route::get('/getBenefits', 'ReferenceBenefitController@getBenefits');
    Route::post('/applyBenefit', 'ReferenceBenefitController@applyBenefit');
    Route::get('/getReferenceBenefit/{id}', 'ReferenceBenefitController@getReferenceBenefit');
    Route::get('/cart/isBenefitCart/{id}', 'ReferenceBenefitController@isBenefitCart');
    Route::get('/getVoice/{id}', 'LeadController@getVoice');
    Route::get('/canCreateReferenceCart', 'CartController@canCreateReferenceCart');

    Route::get('api/getCarts/benefitReference', 'CartController@get');
    Route::get('referenceCarts/approval', 'ReferenceCartApprovalController@index');
    Route::post('referenceCart/{id}/approve', 'ReferenceCartApprovalController@approve');
    Route::get('categoryProducts/{id}', 'CartProductController@getCategoryProducts');


    Route::get('api/products', 'ProductController@get');
    Route::get('api/categories', 'ProductController@getCategories');

    Route::get('lead/{id}/viewReferences', 'LeadController@showReferences');
    Route::get('lead/{id}/leadReferences', 'LeadController@leadReferences');
    Route::get('downloadRecipies', 'RecipeController@downloadRecipies');

    
    Route::get('download/sourceLeads', 'LeadController@sourceLeads');

    
});


Route::group([
    'middleware' => ['auth', 'roles','checkip'], 
    'roles' => ['admin', 'logistics']], 
    function() {
        Route::patch('shipping/{id}', 'ShippingController@update');
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'], 
    'roles' => ['admin','service']], 
    function() {
        Route::post('/patient/{id}/initialWeight', 'PatientWeightController@updateInitialWeight');
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'logistics', 'quality', 'registration']], 
    function() {
        Route::get('cart/{id}/proforma', 'ProformaController@show');
        Route::get('cart/{id}/proforma/download', 'ProformaController@download');
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin','quality','service_tl','service']], 
    function() {
        Route::get('quiz/user/{id}/{id2}/report', 'QuizController@showUserReport');
        Route::get('quiz/user/{id}/answer', 'QuizController@setAnswer');

        Route::get('quiz/admin', 'QuizController@admin');
        Route::post('quiz/admin', 'QuizController@readfile');
        Route::post('quiz/edit/addToGroup', 'QuizController@addToGroup');
        Route::get('quiz/edit/{quiz_id}', 'QuizController@editQuiz');
        Route::post('quiz/edit/{id}', 'QuizController@saveQuiz');
        Route::get('quiz/report/{id}', 'QuizController@showReport');
        Route::post('quiz/reattempt', 'QuizController@reattempt');
        Route::get('quiz/report/{id}/download', 'QuizController@downloadReport');
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin']], 
    function() {
        Route::get('admin', 'AdminController@index');
        //Route::get('admin/viewUsers', 'UserController@viewUsers');
        Route::get('admin/employees', 'EmployeeController@index');
        Route::get('api/getEmployees', 'EmployeeController@get');

        Route::post('api/toggleDeleteUser', 'UserController@toggleDelete');

        Route::get('admin/employee/{id}', 'EmployeeController@showUserRegistrationForm');
        Route::post('admin/employee/{id}/user/add', 'UserController@store');

        Route::get('admin/user/{id}/role', 'UserController@viewRole');
        Route::post('admin/user/{id}/role', 'UserController@addRole');

        Route::get('admin/viewUserRoles', 'RoleController@viewUserRoles');
        Route::get('admin/addUserRole', 'RoleController@viewAddUserRole');
        Route::post('admin/addUserRole', 'RoleController@addUserRole');

        /* Delete Herb */
        Route::post('patient/herb/{id}/delete', 'PatientController@deleteHerb');

        /* Delete User Role */
        Route::post('user/role/delete', 'RoleUserController@destroy');

        Route::get('admin/messages', 'MessageController@index');
        Route::post('message/recipient/delete', 'MessageRecipientController@destroy');

        Route::get('admin/ip/roles', 'IPController@viewIPRoles');
        Route::post('admin/ip/roles/save', 'IPController@saveIPRole');
        Route::get('admin/ip/role/delete', 'IPController@deleteIPRole');
        Route::get('dndQueue', 'LeadController@DndQueue');
        Route::get('checkDnd', 'LeadController@checkDnd');
        
});


Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'registration', 'finance', 'marketing', 'sales', 'sales_tl', 'service_tl', 'service']], 
    function() {

        Route::get('report/messages', 'MessageController@messages');

        /** Sales Report**/
        Route::get('getSales', 'CartReportController@getSales');

        /** Cart Activate for Extension or Balance Payment **/
        Route::post('cart/{id}/activate', 'CartController@activate');

        /* Cart Approval */
        Route::get('getCartApproverStatuses', 'CartApproverController@getStatuses');
        Route::get('carts/approval', 'CartApprovalController@index');
        Route::get('canApprovePayment', 'CartApprovalController@canApprovePayment');
        Route::get('canApproveDiscount', 'CartApprovalController@canApproveDiscount');
        Route::post('cart/{id}/approve', 'CartApprovalController@approve');

        /** Old **/
        Route::get('cart/approval', 'CartApprovalController@show');
        Route::post('cart/approval', 'CartApprovalController@show');
        Route::get('cart/approval/{pending?}', 'CartApprovalController@show');
        Route::post('cart/approval/save', 'CartApprovalController@store');

        Route::get('orders', 'OrderController@index');
        Route::post('orders', 'OrderController@index');

        Route::get('sales/report/balancepayments', 'CartReportController@showBalancePayments');
        Route::get('getBalancePayments', 'CartReportController@getBalancePayments');

        Route::get('api/getPayments', 'CartPaymentController@get');

});

Route::group([
    'middleware' => ['auth', 'roles', 'checkip'],
    'roles' => [
        'admin', 'registration', 'finance', 'marketing', 'sales', 'sales_tl','service', 'quality','service_tl', 'logistics', 'cre', 'nutritionist'
    ]], 
    function() {
        Route::get('carts', 'CartReportController@index');

        Route::get('api/getCarts', 'CartController@get');
});

Route::group([
    'middleware' => ['auth', 'roles', 'checkip'],
    'roles' => ['admin', 'registration', 'finance', 'marketing', 'sales', 'sales_tl','service','quality','service_tl', 'logistics']], 
    function() {
        
        Route::post('carts', 'CartReportController@index');

        /** Cart Reports */
        Route::get('getCartsFunnel', 'CartReportController@funnel');

        Route::get('getCartInvoices', 'CartReportController@invoices');
        Route::get('getCartProformas', 'CartReportController@proformas');

        Route::get('carts/reports/funnel', function() {
            return view('carts.reports.funnel');
        });

        Route::get('carts/reports/invoices', function() {
            return view('carts.reports.invoices');
        });
        Route::get('carts/reports/shippings', function() {
            return view('carts.reports.shippings');
        });

        Route::get('getCartShippings', 'CartReportController@shippings');

        

});


Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'sales', 'finance', 'marketing', 'service', 'service_tl']], 
    function() {

        /* Carts */

        Route::get('orders', 'OrderController@index');
        Route::post('orders', 'OrderController@index');

});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'marketing','bdm']], 
    function() {
        Route::get('marketing/leads', 'MarketingController@viewLeads');
        Route::POST('marketing/leads', 'MarketingController@viewLeads');
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'marketing']], 
    function() {
        
        Route::get('leads/churn', 'LeadsController@viewChurn');
        Route::post('leads/churn', 'LeadsController@churn');
        Route::get('getLeadsByAssignedDate', 'LeadsController@getLeadsByAssignedDate');

        Route::get('marketing', 'MarketingController@index');
        
        Route::get('marketing/lead_distribution', 'MarketingController@viewLeadDistribution');
        Route::post('marketing/lead_distribution', 'MarketingController@saveLeadDistribution');
        Route::get('marketing/shs_lead_distribution', 'MarketingController@viewSHSLeadDistribution');
        Route::post('marketing/shs_lead_distribution', 'MarketingController@saveSHSLeadDistribution');
        Route::get('marketing/addLead', 'MarketingController@viewAddLead');
        Route::post('marketing/addLead', 'MarketingController@saveLead');
        Route::get('marketing/search', 'MarketingController@search');
        Route::post('marketing/search', 'MarketingController@search');

        Route::get('marketing/leads/churn', 'MarketingController@churnLeads');
        Route::post('marketing/leads/churn', 'MarketingController@churnLeads');
        Route::post('marketing/leads/churn/save', 'MarketingController@saveChurnLeads');

        Route::get('marketing/upgradeLeads', 'UpgradeController@viewLeads');
        Route::post('marketing/upgradeLeads', 'UpgradeController@viewLeads');
        Route::post('marketing/saveUpgradeLeads', 'UpgradeController@saveLeads');

        Route::get('marketing/upgradeLeads/durationWise','UpgradeController@viewUpgradeLeadsDurationWise');
        Route::post('marketing/upgradeLeads/durationWise','UpgradeController@viewUpgradeLeadsDurationWise');


        Route::get('marketing/viewProgramEnd', 'MarketingController@viewProgramEnd');
        Route::post('marketing/viewProgramEnd', 'MarketingController@viewProgramEnd');
        Route::post('marketing/saveRejoin', 'MarketingController@saveRejoin');

        Route::get('marketing/viewChannelDistribution', 'MarketingController@viewChannelDistribution');
        Route::post('marketing/viewChannelDistribution', 'MarketingController@viewChannelDistribution');

        Route::get('marketing/viewChannelPerformance', 'MarketingController@viewChannelPerformance');
        Route::post('marketing/viewChannelPerformance', 'MarketingController@viewChannelPerformance');
        Route::get('marketing/uploadLead', 'MarketingController@uploadLead');
        Route::post('marketing/uploadLead', 'LeadController@uploadLead');

        Route::get('marketing/referral', 'ReferralController@index');
        Route::post('marketing/referral', 'ReferralController@index');
        Route::get('marketing/noCre', 'MarketingController@noCre');
        Route::post('marketing/noCre', 'MarketingController@noCre');

        Route::get('marketing/viewDuplicateEmail', 'MarketingController@duplicateEmail');
        Route::get('marketing/viewDuplicatePhone', 'MarketingController@duplicatePhone');
        Route::get('marketing/viewNoContact', 'MarketingController@noContact');


        Route::get('marketing/reports/dialerPush', 'MarketingController@viewDialerPush');
        Route::post('marketing/reports/dialerPush', 'MarketingController@viewDialerPush');

        Route::post('lead/saveVoice', 'LeadController@saveVoice');

        Route::get('marketing/reports/dnc', 'DncController@index');
        Route::post('marketing/reports/dnc', 'DncController@index');


        Route::get('marketing/leads/dead', 'MarketingController@deadLeads');
        Route::post('marketing/leads/dead', 'MarketingController@deadLeads');
        Route::post('marketing/leads/dead/churn', 'MarketingController@churnDeadLeads');

        /*Route::get('marketing/dialer_push', 'DialerPushController@getLeads');
        Route::post('marketing/dialer_push', 'DialerPushController@getLeads');*/

        Route::get('marketing/dialer_push', 'DialerPushController@PredictiveQueue');
        Route::post('marketing/dialer_push', 'DialerPushController@PredictiveQueue');


        Route::post('dialer/push/leads', 'DialerPushController@execute');

        


        /*Debojit wants to see diets*/
        Route::get('patient/{id}/diets', 'PatientController@diets');
        

        /*
            Reports
        */

        /** Delete 
        **/
        Route::get('lead/{id}/delete', 'LeadController@delete');
        Route::post('lead/{id}/delete', 'LeadController@delete');
        Route::post('lead/deleteSource', 'LeadController@deleteSource');
        Route::post('lead/deleteCre', 'LeadController@deleteCre');

        Route::get('lead/{id}/dnc', 'DncController@show');
        Route::post('lead/{id}/dnc', 'DncController@store');
        Route::get('selfAssignCount', 'DialerPushController@selfAssignCount');
        Route::post('selfAssignCount', 'DialerPushController@selfAssignCount');

        Route::get('patientReport', 'PatientBTController@groupBTReport');
        Route::post('patientReport', 'PatientBTController@groupBTReport');

        /* Settings */
        Route::get('settings', 'SettingController@index');
        
        /* Cart Payment Method */
        Route::get('settings/cart/payment/method', 'PaymentMethodController@index');
        Route::post('settings/cart/payment/method/update', 'PaymentMethodController@update');
        Route::post('settings/cart/payment/method/add', 'PaymentMethodController@store');

       

        /* Cart Payment Method Approver */
        Route::get('settings/cart/payment/method/{id}/approver', 'PaymentApproverController@index');
        /*Route::post('settings/cart/payment/method/approver/update', 'PaymentApproverController@update');*/
        Route::post('settings/cart/payment/method/approver/{id}/delete', 'PaymentApproverController@delete');
        Route::post('settings/cart/payment/method/{id}/approver/add', 'PaymentApproverController@store');

         /* Cart Discount Approver */
        Route::get('settings/cart/discount', 'DiscountController@index');
        Route::post('settings/cart/discount/update', 'DiscountController@update');
        Route::post('settings/cart/discount/add', 'DiscountController@store');

        Route::get('settings/cart/discount/{id}/approver', 'DiscountApproverController@index');

        /* Cart Status */
        Route::get('settings/cart/status', 'CartStatusController@index');
        Route::post('settings/cart/status/add', 'CartStatusController@store');

        Route::get('settings/cart/status/{id}/approver', 'CartApproverController@modal');
        Route::post('settings/cart/status/{id}/approver/add', 'CartApproverController@store');
        Route::post('settings/cart/status/approver/{id}/delete', 'CartApproverController@delete');

        /*Route::post('settings/cart/discount/approver/update', 'DiscountApproverController@update');*/
        Route::post('settings/cart/discount/approver/{id}/delete', 'DiscountApproverController@destroy');
        Route::post('settings/cart/discount/{id}/approver/add', 'DiscountApproverController@store');

        /* Settings Products*/
        Route::get('settings/products', 'ProductController@index');
        Route::get('settings/product/add', 'ProductController@modal');
        Route::post('settings/product/add', 'ProductController@store');

        Route::get('settings/product/{id}/edit', 'ProductController@modal');
        Route::patch('settings/product/{id}', 'ProductController@update');


        /* Settings Product Categories*/
        Route::get('settings/product/categories', 'ProductCategoryController@index');
        Route::post('settings/product/category/name/update', 'ProductCategoryController@updateName');
        Route::post('settings/product/category/unit/update', 'ProductCategoryController@updateUnit');
        Route::post('settings/product/category/add', 'ProductCategoryController@store');

        /* Settings Product Units */
        Route::get('settings/product/units', 'ProductUnitController@index');
        Route::post('settings/product/unit/update', 'ProductUnitController@update');
        Route::post('settings/product/unit/add', 'ProductUnitController@store');

        /* Settings Product Offer */
        Route::get('settings/product/{id}/offer', 'ProductOfferController@index');
        Route::post('settings/product/{id}/offer/add', 'ProductOfferController@store');

        /* Settings Lead Program*/
        Route::get('settings/program', 'ProgramController@index');
        Route::post('settings/program/update', 'ProgramController@update');
        Route::post('settings/program/add', 'ProgramController@store');

        /** Bulk SMS **/
        Route::get('marketing/sms/patients', 'SMSController@patients');
        Route::get('marketing/sms/leads', 'SMSController@leads');
        Route::post('api/getLeads', 'LeadsController@get');
        Route::post('api/getPatients', 'SMSController@getPatients');
        Route::post('api/sendSMS', 'SMSController@send');

        

        Route::get('lead/interested', 'LeadController@interested');
        Route::post('lead/interested', 'LeadController@interested');
        Route::get('lead/converted', 'LeadController@converted');
        Route::post('lead/converted', 'LeadController@converted');
        Route::post('api/churnLeads', 'MarketingController@churn');

        /*Product,BT reports*/
        Route::get('reports/products', 'ProductReportController@products');
        Route::get('/api/getProductPurchases', 'ProductReportController@getProducts');
        Route::get('reports/products/repeat_orders', 'ProductReportController@repeatOrders');

        
});


Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'marketing','goods_sale','service']], 
    function() {
        Route::get('reports/products/no_purchases', 'ProductReportController@noPurchases');
        Route::get('api/getNoPurchases', 'ProductReportController@getNoPurchases');
        Route::get('reports/products/no_repeat_purchases','ProductReportController@noRepeatPurchases');
        Route::get('api/getNoRepeatPurchases', 'ProductReportController@getNoRepeatPurchases');
});



Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'finance', 'logistics', 'quality', 'registration']], 
    function() {
        Route::get('getCartsForInvoices', 'InvoiceController@getCartsForInvoices');

        Route::post('searchCart', 'CartController@search');

        Route::get('carts/invoices', function() {
            return view('carts.invoices');
        });
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'finance']], 
    function() {
        Route::get('finance', 'FinanceController@index');
        Route::get('finance/breakAdjust', 'FinanceController@searchPatient');
        Route::post('finance/breakAdjust', 'FinanceController@searchPatient');
        Route::get('finance/breakAdjust/patient/{id}', 'FinanceController@viewBreakAdjust');
        Route::post('finance/saveBreakAdjust', 'FinanceController@saveBreakAdjust');
        Route::get('finance/payments', 'FinanceController@viewPayments');
        Route::post('finance/payments', 'FinanceController@viewPayments');

        Route::post('finance/saveCre', 'FinanceController@saveCre');
        Route::post('finance/saveSource', 'FinanceController@saveSource');
        Route::post('finance/saveAudit', 'FinanceController@saveAudit');

        Route::post('finance/updatePayment', 'FeeController@update');
});
Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'finance', 'registration']], 
    function() {

        Route::patch('cart/payment/{id}', 'CartPaymentController@update');
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'sales', 'sales_tl', 'marketing']], 
    function() {
        Route::get('sales', 'SalesController@index');
        Route::get('sales/hot', 'SalesController@viewHotPipelines');
        Route::POST('sales/hot', 'SalesController@viewHotPipelines');   
        Route::get('sales/pipelineStatus', 'SalesController@viewPipelineStatus');
        Route::post('sales/pipelineStatus', 'SalesController@viewPipelineStatus');


        Route::get('sales/report/lead/status', 'SalesReportController@viewLeadStatus');
        Route::get('api/leadStatusReport', 'SalesReportController@leadStatusReport');

        Route::get('creLeadConversion', 'SalesReportController@viewCreLeadStatus');
        Route::get('api/creConversionReport', 'SalesReportController@creConversionReport');

        Route::get('creLeadConversionWithChurned', 'SalesReportController@viewCreLeadStatus2');
        Route::get('api/creLeadConversionWithChurned', 'SalesReportController@creLeadConversionWithChurned');
        Route::get('channelWiseLeadConversion', 'SalesReportController@viewChannelWiseLead');
        Route::get('api/channelWiseLeadConversion', 'SalesReportController@channelWiseLeadConversion');
        Route::get('channelWiseLeadConversionDownload', 'SalesReportController@downloadChannelCreatedAssigned');

        Route::get('sales/report/pipelines', 'PipelineController@index');
        Route::get('api/getHotPipelines', 'PipelineController@hotPipelines');

        Route::get('selfAssignCount', 'DialerPushController@selfAssignCount');
        Route::post('selfAssignCount', 'DialerPushController@selfAssignCount');

});
Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'sales', 'sales_tl', 'marketing']], 
    function() {
        
        Route::get('cre/{id}/leads', 'CREController@viewLeads');
        Route::post('cre/{id}/leads', 'CREController@viewLeads');

        Route::get('cre/{id}/leads', 'CreReportController@leads');
        Route::get('cre/{id}/leads/includeChurned', 'SalesReportController@LeadsIncludeChurned');
        Route::get('cre/{id}/leads/converted', 'SalesReportController@convertedLeads');
        Route::get('cre/{id}/leads/churnedConverted', 'SalesReportController@churnedConverted');
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'cre', 'marketing', 'sales', 'sales_tl']], 
    function() {

        Route::get('cre', 'CREController@index');
        Route::post('cre', 'CREController@index');
        Route::get('cre/leads', 'CREController@viewLeads');
        Route::post('cre/leads', 'CREController@viewLeads');
        Route::get('cre/pipelines', 'CREController@showPipelines');
        Route::post('cre/pipelines', 'CREController@showPipelines');
        Route::get('cre/interested', 'CREController@interested');
        Route::post('cre/interested', 'CREController@interested');
        Route::get('cre/viewDispositions', 'CallDispositionController@viewDispositions');
        Route::post('cre/dispositions', 'CREController@saveCallDisposition');
        Route::get('cre/callbacks', 'CREController@viewCallbacks');
        Route::get('cre/viewChannelPerformance', 'CREController@viewChannelPerformance');
        Route::post('cre/viewChannelPerformance', 'CREController@viewChannelPerformance');
        Route::get('cre/viewCountryPerformance', 'CREController@viewCountryPerformance');
        Route::post('cre/viewCountryPerformance', 'CREController@viewCountryPerformance');
        Route::get('cre/viewProgramEndList', 'CREController@viewProgramEndList');
        Route::post('cre/viewProgramEndList', 'CREController@viewProgramEndList');
        Route::get('cre/payments', 'CREController@payments');
        Route::post('cre/payments', 'CREController@payments');
        Route::POST('lead/{id}/selfAssign', 'LeadController@selfAssign');
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'cre', 'nutritionist']], 
    function() {
        Route::get('quiz', 'QuizController@index');
        Route::get('quiz/start', 'QuizController@show');
        Route::post('quiz/{questionNumber}', 'QuizController@proposeSolution');
        Route::post('getQuestion', 'QuizController@getQuestion');
        Route::get('quiz/report', 'QuizController@showReport');
        Route::get('quiz/user/{id}/report', 'QuizController@showUserReport');
        Route::get('quiz/user/{id}/answer', 'QuizController@setAnswer');
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'doctor', 'service', 'service_tl']], 
    function() {
        Route::get('doctor', 'DoctorController@index');
        Route::get('doctor/calls', 'DoctorController@calls');
        Route::post('doctor/calls', 'DoctorController@calls');

        Route::get('patient/{id}/doctordialercalls', 'DoctorController@dialercalls');
        
        Route::get('doctor/patients', 'DoctorController@patients');
        Route::post('doctor/patients', 'DoctorController@patients');

        Route::post('patient/saveHerb', 'MedicalController@savePatientHerb');

        Route::post('patient/{id}/herb/add', 'MedicalController@addPatientHerb');

        Route::get('herb/add', 'HerbController@show');
        Route::post('herb/add', 'HerbController@store');
        Route::post('herb/update', 'HerbController@update');

        Route::get('herb/template/add', 'HerbController@templateForm');
        Route::post('herb/template/add', 'HerbController@templateSave');
        
        Route::get('service/reports/appointments', 'ServiceController@appointments');   
        Route::post('service/reports/appointments', 'ServiceController@appointments');   

        
        /** APIs **/
        Route::get('api/getPatient', 'PatientController@get');

});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'doctor', 'service', 'service_tl','nutritionist','yuwow_support']], function() {

        Route::get('patient/{id}/yuwow', 'YuWoWController@progress');
        Route::post('patient/{id}/yuwow', 'YuWoWController@progress');

        Route::get('yuwow/yuwowUsageReport' , 'YuWoWController@yuwowUsageReport');
        Route::post('yuwow/yuwowUsageReport' , 'YuWoWController@yuwowUsageReport');
        
        Route::get('yuwow/yuwowUsers' , 'YuWoWController@yuwowUsers');
        Route::post('yuwow/yuwowUsers' , 'YuWoWController@yuwowUsers');

        Route::get('patientFab/{id}', 'PatientFABController@patientFab');
        Route::post('patient/add/eatingTip' , 'PatientFABController@addEatingTip');
        Route::get('patient/eatingTips', 'PatientFABController@getAllEatingTips');
        Route::get('patient/sampleDiets', 'PatientFABController@getSampleDiets');
        Route::post('patient/eatingTip/{id}/delete', 'PatientFABController@deleteEatingTip');
        Route::get('patient/getFabData', 'PatientFABController@getFabData');
        Route::post('patient/editMeasurements/{id}', 'PatientFABController@editMeasurements');
        Route::post('patient/editSymptoms/{id}', 'PatientFABController@editSymptoms');
        Route::patch('patient/eatingTip/{id}', 'PatientFABController@editEatingTip');
        Route::post('patient/saveFABDiet/{id}', 'PatientFABController@saveFABDiet');
        Route::get('patient/getFabDiet', 'PatientFABController@getFabDiet');
        Route::get('patient/symptoms/{id}', 'PatientFABController@getSymptoms');
        Route::get('patient/fab/preview/{id}', 'PatientFABController@previewMail');
        Route::get('patient/sendFabMails/{id}', 'PatientFABController@sendFabMail');
        Route::post('patient/weightUpdate', 'PatientFABController@weightUpdate');
        Route::get('service/reports/fabReport', 'PatientFABController@fabReport');
        Route::post('service/reports/fabReport', 'PatientFABController@fabReport');
        Route::get('getSentFab/{id}', 'PatientFABController@getSentFab');
        Route::get('/service/reports/weightLoss', 'ServiceController@weightLoss');        
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'nutritionist', 'service', 'service_tl']], 
    function() {
        Route::get('nutritionist', 'NutritionistController@index');
        Route::get('nutritionist/viewUpgradeList', 'NutritionistController@viewUpgradeList');
        Route::get('nutritionist/diets', 'DietController@diets');
        Route::post('nutritionist/diets', 'DietController@diets');
        Route::post('nutritionist/diet/delete', 'DietController@destroy');

        Route::post('nutritionist/sendDiet', 'NutritionistController@sendDiet');

        Route::get('nutritionist/patients', 'NutritionistController@patients');
        Route::post('nutritionist/patients', 'NutritionistController@patients');

        Route::get('nutritionist/appointments', 'NutritionistAppointmentController@show');
        Route::post('nutritionist/appointments', 'NutritionistAppointmentController@show');

        Route::get('nutritionist/programEnd', 'NutritionistController@programEnd');
        Route::post('nutritionist/programEnd', 'NutritionistController@programEnd');


        Route::get('nutritionist/audit', 'NutritionistController@audit');
        Route::post('nutritionist/audit', 'NutritionistController@audit');

        Route::post('patient/{id}/tags', 'PatientController@saveTag');

        
        Route::post('patient/{id}/diet', 'DietController@store');
        Route::post('patient/{id}/diet/save', 'DietController@store');

        Route::post('patient/{id}/diets/send', 'DietController@send');

        Route::get('diet/{id}/edit', 'DietController@edit');
        Route::post('diet/update', 'DietController@update');

        Route::post('diet/autocomplete', 'DietController@autocomplete');

        Route::post('patient/{id}/suit', 'PatientSuitController@store');

        Route::post('patient/{id}/weight', 'PatientWeightController@store');

        Route::post('patient/{id}/bt', 'PatientBTController@upload');

        Route::get('patient/bt/edit/{id}', 'PatientBTController@edit');
        Route::post('patient/bt/edit/{id}', 'PatientBTController@update');

        //Patient Measurements
        Route::post('patient/{id}/measurements', 'PatientMeasurementController@store');
        Route::post('patient/{id}/measurements/copy', 'PatientMeasurementController@copy');

        Route::get('patient/{id}/recipes', 'RecipeController@show');
        Route::post('patient/{id}/recipes', 'RecipeController@show');
        Route::post('patient/{id}/recipe/send', 'RecipeController@sendRecipe');
        Route::get('patient/{id}/sentRecipe/{id2}', 'RecipeController@sentRecipe');

});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'nutritionist', 'service', 'doctor', 'service_tl']], 
    function() {
        Route::get('patient/{id}/notes', 'PatientController@notes');
        Route::post('patient/{id}/notes', 'PatientController@saveNote');

        Route::get('patient/{id}/prakriti', 'PatientController@prakriti');
        Route::post('patient/{id}/prakriti/save', 'PatientController@savePrakriti');


        Route::post('patient/{id}/prakriti/copy', 'PrakritiController@copy');



        Route::post('patient/{id}/advance_diet', 'PatientController@advanceDiet');

        Route::get('service/reports/yuwow/feedback', 'YuWoWController@customerFeedback');
        Route::post('service/reports/yuwow/feedback', 'YuWoWController@customerFeedback');

        //Doctor can also see the Diets & BT & all diets & weights & measurements

        Route::get('patient/{id}/diet', 'PatientDietController@show');
        Route::get('patient/{id}/bt', 'PatientBTController@show');
        Route::get('bt/report/{id}', 'PatientBTController@fetchBTReport');
        Route::get('patient/{id}/diets', 'PatientDietController@all');
        Route::get('patient/{id}/weight', 'PatientWeightController@index');
        Route::get('patient/{id}/fullIfitterProfile', 'PatientWeightController@fullIfitterProfile');
        Route::post('patient/{id}/copyWeightFromIfitter', 'PatientWeightController@copyWeightFromIfitter');
        Route::get('patient/{id}/measurements', 'PatientMeasurementController@index');

        Route::get('patient/{id}/medicalTest', 'PatientBTController@showMedicalTest');
        Route::post('patient/{id}/medicalTest', 'PatientBTController@storeMedicalTest');
        
                
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'hr']], 
    function() {
        Route::get('hr','HRController@index');
        Route::get('hr/employees','HRController@employees');
        Route::get('hr/employee/add', 'EmployeeController@showRegistrationForm');

        Route::post('hr/employee/add', 'EmployeeController@register');
        //Route::get('cre/leads/json', 'LeadController@showLeadsByCRE');


        Route::get('hr/employee/{id}/edit', 'EmployeeController@showEditForm');
        Route::post('hr/employee/{id}/edit', 'EmployeeController@update');
        Route::patch('employee/{employee_id}/title/{id}', 'EmployeeController@updateTitle');
        Route::post('employee/{employee_id}/title', 'EmployeeController@storeTitle');

        Route::get('hr/employee/{id}/personalDetails', 'EmployeeController@personalDetails');
        Route::post('hr/employee/{id}/personalDetails', 'EmployeeController@personalDetails');

        Route::get('hr/employee/{id}/contactDetails', 'EmployeeController@contactDetails');
        Route::post('hr/employee/{id}/contactDetails', 'EmployeeController@contactDetails');
        
        Route::get('hr/employee/{id}/viewPhotograph', 'EmployeeController@viewPhotograph');
    

        Route::get('employee/{id}/supervisor','EmployeeController@supervisors');
        Route::post('employee/{id}/supervisor/add','EmployeeController@addSupervisor');
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'service', 'service_tl']], 
    function() {
        Route::get('service', 'ServiceController@index');
        Route::get('service/audit', 'ServiceController@audit');
        Route::get('service/assignNutritionist', 'ServiceController@viewAssginedNutritionist');
        
        Route::POST('service/saveNutritionist', 'ServiceController@saveNutritionist');
        Route::POST('service/saveDoctor', 'ServiceController@saveDoctor');
        Route::get('service/viewSurveys', 'SurveyController@viewNutritionistWiseSurvey');
        Route::post('service/viewSurveys', 'SurveyController@viewNutritionistWiseSurvey');

        

        Route::get('service/bulk', 'ServiceController@bulk');
        Route::post('service/bulk', 'ServiceController@bulk');

        Route::post('service/bulk/send', 'ServiceController@sendBulk');

        Route::get('service/survey', 'SurveyController@nutritionist');

        Route::get('service/diets', 'ServiceController@diets');
        Route::post('service/diets', 'ServiceController@diets');

        Route::post('service/diets/send', 'ServiceController@sendDiets');

        
        Route::get('service/reports/diet_not_started', 'ServiceController@dietNotStarted');

        Route::get('service/messages', 'ServiceController@showMessages');

        Route::get('patient/{id}/calls','PatientController@calls');

});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin']], 
    function() {

        Route::get('dnd', 'DNDController@index');
        Route::get('dnd/{phone}', 'DNDController@scrub');
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin', 'registration']], 
    function() {

        /*Route::get('lead/{id}/register', 'PatientRegistrationController@index');
        Route::post('lead/{id}/register', 'PatientRegistrationController@store');*/

        Route::get('patient/{id}/fee', 'FeeController@show');
        Route::post('patient/{id}/fee', 'FeeController@store');
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'],
    'roles' => ['admin','sales', 'sales_tl', 'service', 'service_tl','registration','marketing','finance']], 
    function() {
            
        Route::get('sales/payments', 'SalesController@viewPayments');
        Route::POST('sales/payments', 'SalesController@viewPayments');

        Route::get('sales/paymentsNew', 'SalesController@viewPaymentsNew');
        Route::POST('sales/paymentsNew', 'SalesController@viewPaymentsNew');

        Route::get('selfAssignCount', 'DialerPushController@selfAssignCount');
        Route::post('selfAssignCount', 'DialerPushController@selfAssignCount');
});

Route::group(['middleware' => ['auth', 'roles','checkip']], 
    function() {

    
    

    
    Route::get('report/leadDistrbution','MarketingController@leadDistribution');

    Route::get('profile','EmployeeController@show');
    


    Route::get('password/change', 'UserController@showPasswordForm');
    Route::post('password/change', 'UserController@changePassword');

    

    
    
    

    Route::post('patient/herb/{id}/update', 'PatientController@updateHerb');
    Route::get('patient/{id}/survey', 'SurveyController@viewPatientSurvey');
    

    Route::get('quality', 'QualityController@index');
    Route::get('quality/viewSurveySummary', 'SurveyController@viewSurveySummary');
    Route::post('quality/viewSurveySummary', 'SurveyController@viewSurveySummary');
    Route::get('quality/viewSurveys', 'SurveyController@viewSurveys');
    Route::post('quality/viewSurveys', 'SurveyController@viewSurveys');

    Route::get('quality/patients', 'SurveyController@patients');
    Route::post('quality/patients', 'SurveyController@patients');

    
    
    Route::get('quality/patient/{id}/survey', 'SurveyController@patientSurvey');
    Route::post('quality/patient/{id}/survey', 'SurveyController@savePatientSurvey');

    Route::get('master/dispositions', 'DispositionMasterController@getDispositions');

    Route::get('workflow/{id}', 'WorkflowController@viewWorkflow');
    Route::post('workflow/{id}', 'WorkflowController@viewWorkflow');

    Route::get('lead', 'LeadController@dialerCall');
    Route::post('lead', 'LeadController@dialerCall');
    Route::get('lead/addLead', 'LeadController@viewAddLeadForm');
    Route::post('lead/saveLead', 'LeadController@saveLead');

    Route::get('lead/search', 'LeadController@search');
    Route::POST('lead/search', 'LeadController@search');


    
    
    Route::get('lead/{id}/viewCreModal', 'ModalController@viewCre');
    Route::POST('lead/saveCre', 'LeadController@saveCre');
    Route::get('lead/{id}/viewSourceModal', 'ModalController@viewSource');
    Route::POST('lead/saveSource', 'LeadController@saveSource');

    Route::get('lead/{id}/viewDispositions', 'LeadController@viewDispositions');
    Route::POST('lead/{id}/disposition', 'CallDispositionController@store');
    Route::get('lead/{id}/viewPersonalDetails', 'LeadController@showPersonalDetails');
    Route::POST('lead/{id}/savePersonalDetails', 'LeadController@savePersonalDetails');
    Route::get('lead/{id}/viewContactDetails', 'LeadController@showContactDetails');
    Route::POST('lead/{id}/saveContactDetails', 'LeadController@saveContactDetails');

    Route::get('lead/{id}/viewReferences', 'LeadController@showReferences');
    Route::get('lead/{id}/references', 'LeadController@showReferences');

    Route::POST('lead/{id}/saveReference', 'LeadController@saveReference');
    Route::get('lead/{id}/viewDetails', 'LeadController@viewDetails');
    Route::get('lead/{id}/saveDetails', 'LeadController@saveDetails');
    Route::get('lead/{id}/email', 'EmailController@show');
    Route::post('lead/{id}/email', 'EmailController@send');
    Route::get('address/{id}/edit','LeadController@editAddress')    ;
    Route::post('address/{id}/save','LeadController@updateAddress');
    Route::get('lead/{id}/address/add','LeadController@addAddress');
    Route::post('lead/{id}/address/save','LeadController@storeAddress');

    
    
    /** Hot Pipeline**/
    Route::get('lead/{id}/pipeline', 'PipelineController@modal'); 
    Route::post('lead/{id}/pipeline', 'PipelineController@store'); 


    Route::get('lead/{id}/program', 'LeadProgramController@show');
    Route::post('lead/{id}/program', 'LeadProgramController@store');
    Route::get('getLeadPrograms', 'LeadProgramController@get');

    Route::get('getLeadCarts', 'LeadController@getCarts');


    Route::get('lead/{id}', 'LeadController@showLead');

    Route::get('getCurrencies', function() {
        return App\Models\Currency::get();
    });

    Route::get('api/getStatusList', function() {
        return App\Models\Status::get();
    });
    Route::get('api/getTitles', function() {
        return App\Models\Title::get();
    });

    Route::get('api/getPrograms', 'ProgramController@get');
        
    Route::get('api/getCountryList', 'APIController@getCountryList');
    Route::get('api/getRegionList', 'APIController@getRegionList');
    Route::get('api/getCityList', 'APIController@getCityList');
    Route::get('api/getVoiceList', 'APIController@getVoiceList');
    Route::get('api/getSourceList', 'APIController@getSourceList');
    Route::get('api/getCallbacks', 'APIController@getCallbacks');
    Route::get('api/getUsersByRole', 'APIController@getUsersByRole');
    //For Jeditable
    Route::get('api/getNutritionists', 'APIController@getNutritionists');
    Route::get('api/getUsers', 'APIController@getUsers');
    Route::get('api/getCres', 'APIController@getCres');
    Route::get('api/getSources', 'APIController@getSources');
    Route::get('api/getVoices', 'APIController@getVoices');

    Route::get('api/getUnreadMessageCount', 'MessageController@getUnreadMessageCount');
    Route::get('getMessages', 'MessageController@get');
    Route::get('api/getAllMessages', 'MessageController@getAllMessages');
    Route::post('api/message/setRead', 'MessageController@setRead');
    Route::post('api/Message/setAction', 'MessageController@setAction');

    //Route::get('api/getUnreadNotificationCount', 'NotificationController@getUnreadNotificationCount');
    //Route::get('api/getUnreadNotifications', 'NotificationController@getUnreadNotifications');

    Route::get('api/onlinePayments', 'WebsiteController@onlinePayments');
    Route::get('api/onlinePaymentsNew', 'WebsiteController@onlinePaymentsNew');
    Route::get('api/getTagList', 'APIController@getTagList');

    Route::get('api/patients/age', 'APIController@ages');
    Route::get('api/lead/{id}/dispositions', 'APIController@dispositions');

    Route::get('api/survey/comments', 'SurveyController@comments');

    Route::get('api/template/{id}', 'HerbController@template');

    Route::get('message/compose', 'MessageController@compose');
    Route::get('message/inbox', 'MessageController@inbox');     
    Route::get('message/outbox', 'MessageController@outbox');
    Route::post('message/toggle', 'MessageController@toggle');
    Route::post('message/send', 'MessageController@send');
    

    Route::get('workflow/{id}/viewModal', 'ModalController@viewWorkflow');
    Route::post('workflow/updateModal/', 'WorkflowController@updateModal');

    

    Route::get('admin/migrate_leads', 'AdminController@migrateLeads');
    Route::get('cod', 'AdminController@cod');
    Route::post('cod', 'AdminController@saveCod');

    Route::get('modal/{id}/message', 'ModalController@message');


    
    Route::get('modal/{id}/payment', 'ModalController@payment');
    Route::get('modal/{id}/herb', 'ModalController@herb');

    Route::get('modal/{id}/viewBreakAdjust', 'ModalController@viewBreakAdjust');
    Route::get('modal/{id}/viewRegistration', 'ModalController@viewRegistration');
    Route::post('modal/saveRegistration', 'RegistrationController@store');

    Route::get('modal/{id}/mealtime', 'ModalController@mealtime');
    Route::post('modal/saveMealtime', 'MealtimeController@savePatientMealtime');

    Route::get('modal/{id}/mynutritionist', 'ModalController@mynutritionist');
    Route::get('modal/{id}/mycre', 'ModalController@mycre');

    Route::get('patient/{id}/app', 'YuWoWController@index');
    Route::post('patient/{id}/app', 'YuWoWController@index');
    /*Route::get('patient/{id}/prakriti', 'PrakritiController@prakriti');
    Route::post('patient/{id}/prakriti', 'PrakritiController@prakriti');
    Route::get('patient/{id}/constitution', 'PrakritiController@constitution');
    Route::post('patient/{id}/constitution', 'PrakritiController@constitution');*/


    Route::get('patient/{id}/medical', 'PatientMedicalController@show');
    Route::post('patient/{id}/medical', 'PatientMedicalController@store');
    
    Route::get('patient/{id}/herbs', 'HerbController@herbs');

    Route::get('patient/{id}/tags', 'PatientController@tags');

    Route::get('obd/checkExisting', 'OBDController@checkExisting');

    Route::get('report', 'ReportController@index');
    
    Route::get('report/payments', 'ReportController@payments');
    Route::post('report/payments', 'ReportController@payments');

    Route::get('report/viewUpgrades', 'UpgradeController@index');
    Route::post('report/viewUpgrades', 'UpgradeController@index');  
    Route::get('report/viewReferences', 'ReferenceController@index');
    Route::post('report/viewReferences', 'ReferenceController@index');
    Route::get('report/referencesReport', 'ReferenceController@churnLeads');
    Route::post('report/referencesReport', 'ReferenceController@churnLeads');
    Route::get('report/refereeReport',      'ReferenceController@reference');
    Route::post('report/refereeReport',     'ReferenceController@reference');
    Route::get('report/viewPlatinumCustomers', 'ReferenceController@platinum');
    Route::get('report/viewChannelConversion', 'ReportController@channelConversion');
    Route::post('report/viewChannelConversion', 'ReportController@channelConversion');

    Route::get('report/source/{id}/leads', 'LeadSourceController@sourceLeads');
    Route::post('report/source/{id}/leads', 'LeadSourceController@sourceLeads');
    
    Route::get('report/viewCustomerProfiles', 'ReportController@profiles');
    
    Route::get('report/patients/age', 'ReportController@getAge');
    Route::post('report/patients/age', 'ReportController@getAge');


    Route::get('report/patients/country', 'ReportController@getCountryWisePatientSummary');
    Route::post('report/patients/country', 'ReportController@getCountryWisePatientSummary');

    Route::get('report/patients/new', 'ReportController@getNewPatients');
    Route::post('report/patients/new', 'ReportController@getNewPatients');

    Route::get('report/patients/allergy', 'ReportController@getFoodAllergy');
    Route::post('report/patients/allergy', 'ReportController@getFoodAllergy');

    Route::get('report/cre/sourcePerformance', 'ReportController@creWiseSourcePerformance');    
    Route::post('report/cre/sourcePerformance', 'ReportController@creWiseSourcePerformance');  

    Route::get('report/cre/newLeadsourcePerformance', 'ReportController@creWiseNewLeadSourcePerformance');    
    Route::post('report/cre/newLeadsourcePerformance', 'ReportController@creWiseNewLeadSourcePerformance');  

    Route::get('report/leads/performance', 'ReportController@dailyPerformance');
    Route::post('report/leads/performance', 'ReportController@dailyPerformance');


    Route::get('report/emails', 'ReportController@emails');
    Route::post('report/emails', 'ReportController@emails');


    Route::get('report/demographics/gender', 'DemographicController@gender');
    Route::post('report/demographics/gender', 'DemographicController@gender');

    Route::get('report/demographics/active', 'DemographicController@active');
    Route::post('report/demographics/active', 'DemographicController@active');

    Route::get('report/quality/survey', 'SurveyController@survey');
    Route::post('report/quality/survey', 'SurveyController@survey');

    Route::get('report/quality/nutritionistAppointments', 'ServiceController@appointments');
    Route::post('report/quality/nutritionistAppointments', 'ServiceController@appointments');
    
    Route::get('report/registration/fees', 'PatientRegistrationController@showPatientFeeStatus');
    Route::post('report/registration/fees', 'PatientRegistrationController@showPatientFeeStatus');

    Route::get('report/registration/lateStart', 'PatientRegistrationController@showLateStart');
    Route::get('/api/getLateStart', 'PatientRegistrationController@getLateStart');
    
    Route::get('report/patients/occupation', 'ReportController@occupation');
    Route::post('report/patients/occupation', 'ReportController@occupation');

    Route::get('sales/report/performance', 'SalesReportController@performance');
    

    Route::get('api/getGoods', 'CartController@get');

    Route::get('sales/report/performance/download', 'CartPaymentController@download');


    Route::get('testimonial/videos', 'TestimonialController@show');

    Route::get('test', 'TestController@index');
    Route::get('test/show', 'TestController@show');
    Route::get('test/encrypt/{id}', 'TestController@encrypt');


    Route::get('/', function () {
        return view('dashboard');
    });

    

    Route::get('home', 'CREController@index');
});

Route::group([
    'middleware' => ['auth', 'roles','checkip'], 
    'roles' => ['admin', 'marketing','training']], 
    function() {
    Route::get('email/template/edit', 'EmailTemplateController@show');
    Route::post('email/template/edit', 'EmailTemplateController@show');
    Route::post('email/template/update', 'EmailTemplateController@update');
    Route::get('show/emailAttachment/{id}', 'EmailTemplateController@showAttachment');
    Route::get('update/emailAttachment/{id}', 'EmailTemplateController@getAttachment');
    Route::post('update/emailAttachment/{id}', 'EmailTemplateController@updateAttachment');
        
});

// Display all SQL executed in Eloquent
Event::listen('illuminate.query', function($query)
{
    //var_dump($query);
});


Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::group([
    'middleware' => ['auth', 'roles','checkip']],
    function() { 
    Route::get('findLead', 'LeadController@find');

    /* Cart */

    Route::get('findCart', 'CartController@find');
    //Route::delete('cart/{cart_id}/payment/{payment_id}', 'CartPaymentController@delete');

    Route::get('canCreateCart', 'CartController@canCreateCart');
    Route::get('lead/{id}/cart', 'CartController@index');
    Route::post('lead/{id}/cart', 'CartController@store');

    Route::get('cart/{id}/product/add', 'CartProductController@show');
    Route::post('cart/{id}/products', 'CartProductController@store');

    Route::delete('cart/{cart}/product/{id}', 'CartProductController@destroy');
    Route::delete('cart/{cart}/payment/{id}', 'CartPaymentController@destroy');

    Route::get('cart/product/{id}/edit', 'CartProductController@edit');
    Route::patch('cart/{cart}/product/{product}', 'CartProductController@update');
    //Route::post('cart/product/{id}/edit', 'CartProductController@update');

    Route::post('api/coupon/validate', 'CouponController@validateCoupon');

    Route::get('cart/{id}/payment', 'CartPaymentController@show');
    Route::post('cart/{id}/payment', 'CartPaymentController@store');

    Route::post('cart/{id}/process', 'CartController@process');

    Route::get('cart/{id}/shipping', 'CartController@shipping');
    Route::post('cart/{id}/shipping', 'ShippingController@store');


    Route::get('cart/{id}', 'CartController@show');

    Route::get('cart/{id}/approval/update', 'CartApprovalController@modal');
    Route::post('cart/{id}/approval/update', 'CartApprovalController@update');

    Route::get('cart/{id}/comment', 'CartCommentController@show');
    Route::post('cart/{id}/comment', 'CartCommentController@store');

    Route::get('marketing/reports/package', 'MarketingController@package');
    Route::get('marketing/reports/package/download', 'MarketingController@downloadPackage');
    Route::get('api/getPackageExtensions', 'MarketingController@getPackageExtensions');


    Route::get('getNotifications', 'NotificationController@get');
    Route::get('notifications', 'NotificationController@index');
    Route::patch('notification/{id}/read', 'NotificationController@read');

    Route::get('getCarriers', function(){
        return \App\Models\Carrier::get();
    });
});