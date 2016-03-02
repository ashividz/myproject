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

Route::group([
	'middleware' => ['auth','roles'], 
	'roles' => ['admin']], 
	function() {
		Route::get('admin', 'AdminController@index');
		Route::get('admin/viewUsers', 'UserController@viewUsers');
		Route::get('admin/user/{id}', 'UserController@showUserRegistrationForm');
		Route::post('admin/user/{id}', 'UserController@store');

		Route::get('admin/user/{id}/viewRole', 'UserController@viewRole');
		Route::post('admin/user/{id}/addRole', 'UserController@addRole');

		Route::get('admin/viewUserRoles', 'RoleController@viewUserRoles');
		Route::get('admin/addUserRole', 'RoleController@viewAddUserRole');
		Route::post('admin/addUserRole', 'RoleController@addUserRole');

		/* Delete Herb */
		Route::post('patient/herb/{id}/delete', 'PatientController@deleteHerb');

		/* Delete User Role */
		Route::post('user/role/delete', 'RoleUserController@destroy');

		Route::POST('lead/{id}/selfAssign', 'LeadController@selfAssign');
});

Route::group([
	'middleware' => ['auth','roles'], 
	'roles' => ['admin', 'marketing']], 
	function() {
		Route::get('marketing', 'MarketingController@index');

		Route::get('marketing/leads', 'MarketingController@viewLeads');
		Route::POST('marketing/leads', 'MarketingController@viewLeads');

		Route::get('marketing/lead_distribution', 'MarketingController@viewLeadDistribution');
		Route::post('marketing/lead_distribution', 'MarketingController@saveLeadDistribution');
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

        Route::get('marketing/dialer_push', 'DialerPushController@getLeads');
        Route::post('marketing/dialer_push', 'DialerPushController@getLeads');

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

});

Route::group([
	'middleware' => ['auth','roles'], 
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
	'middleware' => ['auth','roles'], 
	'roles' => ['admin', 'sales', 'sales_tl']], 
	function() {
		Route::get('sales', 'SalesController@index');
		Route::get('sales/hot', 'SalesController@viewHotPipelines');
		Route::POST('sales/hot', 'SalesController@viewHotPipelines');	
		Route::get('sales/pipelineStatus', 'SalesController@viewPipelineStatus');
		Route::post('sales/pipelineStatus', 'SalesController@viewPipelineStatus');
});

Route::group([
	'middleware' => ['auth','roles'], 
	'roles' => ['admin', 'cre', 'marketing', 'sales', 'sales_tl']], 
	function() {
		Route::get('cre', 'CREController@index');
		Route::post('cre', 'CREController@index');
		Route::get('cre/leads', 'CREController@viewLeads');
		Route::post('cre/leads', 'CREController@viewLeads');
		Route::get('cre/pipelines', 'CREController@showPipelines');
		Route::post('cre/pipelines', 'CREController@showPipelines');
		Route::get('cre/viewDispositions', 'CallDispositionController@viewDispositions');
		Route::post('cre/dispositions', 'CREController@saveCallDisposition');
		Route::get('cre/callbacks', 'CREController@viewCallbacks');
		Route::get('cre/viewChannelPerformance', 'CREController@viewChannelPerformance');
		Route::post('cre/viewChannelPerformance', 'CREController@viewChannelPerformance');
		Route::get('cre/viewCountryPerformance', 'CREController@viewCountryPerformance');
		Route::post('cre/viewCountryPerformance', 'CREController@viewCountryPerformance');
		Route::get('cre/viewProgramEndList', 'CREController@viewProgramEndList');
		Route::post('cre/viewProgramEndList', 'CREController@viewProgramEndList');
		Route::get('cre/viewPayments', 'CREController@viewPayments');
		Route::post('cre/viewPayments', 'CREController@viewPayments');
});

Route::group([
	'middleware' => ['auth','roles'], 
	'roles' => ['admin', 'doctor', 'service', 'service_tl']], 
	function() {
		Route::get('doctor', 'DoctorController@index');
		Route::get('doctor/calls', 'DoctorController@calls');
		Route::post('doctor/calls', 'DoctorController@calls');


		Route::get('doctor/patients', 'DoctorController@patients');
		Route::post('doctor/patients', 'DoctorController@patients');

		Route::post('patient/saveHerb', 'MedicalController@savePatientHerb');

		Route::post('patient/{id}/herb/add', 'MedicalController@addPatientHerb');

		Route::get('herb/add', 'HerbController@show');
		Route::post('herb/add', 'HerbController@store');
		Route::post('herb/update', 'HerbController@update');

		Route::get('herb/template/add', 'HerbController@templateForm');
		Route::post('herb/template/add', 'HerbController@templateSave');

		Route::get('yuwow/yuwowUsers' , 'YuWoWController@yuwowUsers');
		Route::post('yuwow/yuwowUsers' , 'YuWoWController@yuwowUsers');
		Route::get('service/reports/appointments', 'ServiceController@appointments');	

});

Route::group([
	'middleware' => ['auth','roles'], 
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
		Route::get('patient/{id}/diets', 'PatientDietController@all');

		Route::post('patient/{id}/diets/send', 'DietController@send');

		Route::get('diet/{id}/edit', 'DietController@edit');
		Route::post('diet/update', 'DietController@update');

		Route::post('diet/autocomplete', 'DietController@autocomplete');

		Route::post('patient/{id}/suit', 'PatientSuitController@store');

		Route::get('patient/{id}/weight', 'PatientWeightController@index');
		Route::post('patient/{id}/weight', 'PatientWeightController@store');

        Route::post('patient/{id}/bt', 'PatientBTController@upload');

        Route::get('patient/bt/edit/{id}', 'PatientBTController@edit');
        Route::post('patient/bt/edit/{id}', 'PatientBTController@update');

        //Patient Measurements
        Route::get('patient/{id}/measurements', 'PatientMeasurementController@index');
        Route::post('patient/{id}/measurements', 'PatientMeasurementController@store');
        Route::post('patient/{id}/measurements/copy', 'PatientMeasurementController@copy');


});

Route::group([
	'middleware' => ['auth','roles'], 
	'roles' => ['admin', 'nutritionist', 'service', 'doctor', 'service_tl']], 
	function() {
		Route::get('patient/{id}/notes', 'PatientController@notes');
		Route::post('patient/{id}/notes', 'PatientController@saveNote');

		Route::get('patient/{id}/prakriti', 'PatientController@prakriti');
		Route::post('patient/{id}/prakriti/save', 'PatientController@savePrakriti');


		Route::post('patient/{id}/prakriti/copy', 'PrakritiController@copy');


		Route::get('patient/{id}/yuwow', 'YuWoWController@progress');
		Route::post('patient/{id}/yuwow', 'YuWoWController@progress');

		Route::post('patient/{id}/advance_diet', 'PatientController@advanceDiet');

        Route::get('service/reports/yuwow/feedback', 'YuWoWController@customerFeedback');
        Route::post('service/reports/yuwow/feedback', 'YuWoWController@customerFeedback');

        //Doctor can also see the Diets & BT

        Route::get('patient/{id}/diet', 'PatientDietController@show');
        Route::get('patient/{id}/bt', 'PatientBTController@show');
        Route::get('bt/report/{id}', 'PatientBTController@fetchBTReport');
                
});

Route::group([
	'middleware' => ['auth','roles'], 
	'roles' => ['admin', 'hr']], 
	function() {
		Route::get('hr','HRController@index');
		Route::get('hr/employees','HRController@employees');
		Route::get('hr/employee/add', 'EmployeeController@showRegistrationForm');

		Route::post('hr/employee/add', 'EmployeeController@register');
		//Route::get('cre/leads/json', 'LeadController@showLeadsByCRE');


		Route::get('hr/employee/{id}/edit', 'EmployeeController@showEditForm');
		Route::post('hr/employee/{id}/edit', 'EmployeeController@update');

		Route::get('hr/employee/{id}/personalDetails', 'EmployeeController@personalDetails');
		Route::post('hr/employee/{id}/personalDetails', 'EmployeeController@personalDetails');

		Route::get('hr/employee/{id}/contactDetails', 'EmployeeController@contactDetails');
		Route::post('hr/employee/{id}/contactDetails', 'EmployeeController@contactDetails');
		
		Route::get('hr/employee/{id}/viewPhotograph', 'EmployeeController@viewPhotograph');
	

		Route::get('employee/{id}/supervisor','EmployeeController@supervisors');
		Route::post('employee/{id}/supervisor/add','EmployeeController@addSupervisor');
});

Route::group([
	'middleware' => ['auth','roles'], 
	'roles' => ['admin', 'service', 'service_tl']], 
	function() {
		Route::get('service', 'ServiceController@index');
		Route::get('service/audit', 'ServiceController@audit');
		Route::get('service/assignNutritionist', 'ServiceController@viewAssginedNutritionist');
		
		Route::POST('service/saveNutritionist', 'ServiceController@saveNutritionist');
		Route::POST('service/saveDoctor', 'ServiceController@saveDoctor');
		Route::get('service/viewSurveys', 'SurveyController@viewNutritionistWiseSurvey');
		Route::post('service/viewSurveys', 'SurveyController@viewNutritionistWiseSurvey');

		Route::get('report/messages', 'MessageController@messages');

		Route::get('service/bulk', 'ServiceController@bulk');
		Route::post('service/bulk', 'ServiceController@bulk');

		Route::post('service/bulk/send', 'ServiceController@sendBulk');

		Route::get('service/survey', 'SurveyController@nutritionist');

		Route::get('service/diets', 'ServiceController@diets');
		Route::post('service/diets', 'ServiceController@diets');

		Route::post('service/diets/send', 'ServiceController@sendDiets');

		
        Route::get('service/reports/diet_not_started', 'ServiceController@dietNotStarted');

});

Route::group([
	'middleware' => ['auth','roles'], 
	'roles' => ['admin']], 
	function() {

		Route::get('dnd', 'DNDController@index');
		Route::get('dnd/{phone}', 'DNDController@scrub');
});

Route::group([
	'middleware' => ['auth','roles'], 
	'roles' => ['admin', 'registration']], 
	function() {

		Route::get('lead/{id}/register', 'PatientRegistrationController@index');
		Route::post('lead/{id}/register', 'PatientRegistrationController@store');

		Route::get('patient/{id}/fee', 'FeeController@show');
		Route::post('patient/{id}/fee', 'FeeController@store');
});

Route::group([
	'middleware' => ['auth','roles'], 
	'roles' => ['admin','sales', 'sales_tl', 'registration']], 
	function() {
			
		Route::get('sales/payments', 'SalesController@viewPayments');
		Route::POST('sales/payments', 'SalesController@viewPayments');
});

Route::group(['middleware' => 'auth'], function() {

	
	

	
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

	Route::get('lead/search', 'LeadController@searchLeads');
	Route::POST('lead/search', 'LeadController@searchLeads');


	
	
	Route::get('lead/{id}/viewCreModal', 'ModalController@viewCre');
	Route::POST('lead/saveCre', 'LeadController@saveCre');
	Route::get('lead/{id}/viewSourceModal', 'ModalController@viewSource');
	Route::POST('lead/saveSource', 'LeadController@saveSource');

	Route::get('lead/{id}/viewDispositions', 'LeadController@viewDispositions');
	Route::POST('lead/{id}/saveDisposition', 'CallDispositionController@saveDisposition');
	Route::get('lead/{id}/viewPersonalDetails', 'LeadController@showPersonalDetails');
	Route::POST('lead/{id}/savePersonalDetails', 'LeadController@savePersonalDetails');
	Route::get('lead/{id}/viewContactDetails', 'LeadController@showContactDetails');
	Route::POST('lead/{id}/saveContactDetails', 'LeadController@saveContactDetails');
	Route::get('lead/{id}/viewReferences', 'LeadController@showReferences');
	Route::POST('lead/{id}/saveReference', 'LeadController@saveReference');
	Route::get('lead/{id}/viewDetails', 'LeadController@viewDetails');
	Route::get('lead/{id}/saveDetails', 'LeadController@saveDetails');
	Route::get('lead/{id}/email', 'EmailController@show');
	Route::post('lead/{id}/email', 'EmailController@send');


	

	


	


	Route::get('lead/{id}', 'LeadController@showLead');

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
	Route::get('api/getMessages', 'MessageController@getMessages');

	Route::get('api/onlinePayments', 'WebsiteController@onlinePayments');
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

	Route::get('report/cre/sourcePerformance', 'ReportController@creWiseSourcePerformance');	
	Route::post('report/cre/sourcePerformance', 'ReportController@creWiseSourcePerformance');	

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

	Route::get('report/registration/fees', 'PatientRegistrationController@showPatientFeeStatus');
	Route::post('report/registration/fees', 'PatientRegistrationController@showPatientFeeStatus');

	
	Route::get('report/patients/occupation', 'ReportController@occupation');
	Route::post('report/patients/occupation', 'ReportController@occupation');


	Route::get('testimonial/videos', 'TestimonialController@show');

	Route::get('test', 'TestController@index');
	Route::get('test/show', 'TestController@show');
	Route::get('test/encrypt/{id}', 'TestController@encrypt');


	Route::get('/', function () {
		$data = array(
			'menu' 		=> 'welcome',
			'section'	=> ''
			);

		return view('home')->with($data);
	});

	Route::get('home', 'CREController@index');

    Route::get('email/template/edit', 'EmailTemplateController@show');
    Route::post('email/template/edit', 'EmailTemplateController@show');
    Route::post('email/template/update', 'EmailTemplateController@update');
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