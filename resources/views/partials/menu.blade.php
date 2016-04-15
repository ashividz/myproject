<?php 
    $menu = isset($menu) ? $menu : "Dashboard";
?>
<div class="menu">
    
    <ul>
    
        <li>
            <a href="#" id="menu_memenu" class="firstLevelMenu"><b>MeMenu</b></a>
            
            <ul>
                                    
                <li>
                    <a href="/" id="menu_dashboard" class="firstLevelMenu"><b>Dashboard</b></a>
                </li>
                <li>
                    <a href="/" id="menu_notification" class="firstLevelMenu"><b>Notifications</b></a>
                </li><li>
                    <a href="#" id="menu_message" class="firstLevelMenu"><b>Messages</b></a>
                    <ul>
                        <li>
                            <a href="/message/compose" id="menu_message_compose"><b>Compose</b></a>
                            <a href="/message/inbox" id="menu_message_inbox"><b>Inbox</b></a>
                            <a href="/message/outbox" id="menu_message_outbox"><b>Outbox</b></a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/quiz" id="menu_quiz">Quiz</a>
                </li>
                            
            </ul> <!-- second level -->                        
        </li>

    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('admin'))
        <li class="{{ $menu == 'admin' ? 'current' : '' }}">
            <a href="/admin" id="menu_admin" class="firstLevelMenu"><b>Admin</b></a>
            
            <ul>
                <li>
                    <a href="#" id="menu_admin_UserManagement" class="arrow">User Management</a>
                    <ul>
                        <li>
                            <a href="/admin/viewUsers" id="menu_admin_users">Users</a>
                        </li>
                        <li>
                            <a href="/admin/viewUserRoles" id="menu_admin_roles">Roles</a>
                        </li>                                  
                                                                
                    </ul> <!-- third level -->                            
                                                    
                </li>  

                <li>
                    <a href="#" id="menu_admin_email" class="arrow">Email</a>
                    <ul>
                        <li>
                            <a href="/email/template/edit" id="menu_email_template_edit">Edit Template</a>
                        </li>                                
                                                                
                    </ul> <!-- third level -->   
                </li>
                                                
            </ul> <!-- second level -->    
        </li>
    @endif
            
        <li class="{{ $menu == 'lead' ? 'current' : '' }}">
            <a href="/lead" id="menu_lead" class="firstLevelMenu"><b>Leads</b></a>
            
            <ul>
                <li>
                    <a href="/lead" id="menu_lead_dashboard" class="arrow">Dashboard</a>
                </li>
                <li>
                    <a href="/lead/search" id="menu_lead_search" class="arrow">Search</a>
                </li>

                <li>
                    <a href="/lead/addLead" id="menu_lead_add">Add Lead</a>
                </li>

                <li>
                    <a href="#" id="menu_cart" class="firstLevelMenu"><b>Cart Order</b></a>
                    <ul>
                        <li>
                            <a href="/cart/approval" id="menu_cart_approval">Cart Approval</a>
                        </li>
                        <li>
                            <a href="/orders" id="menu_orders">Orders</a>
                        </li>
                    </ul>
                </li>                 
                                                
            </ul> <!-- second level -->                        
        </li>

    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('cre') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('sales_tl'))    
        <li class="{{ $menu == 'cre' ? 'current' : '' }}">
            <a href="/cre" id="menu_pim_viewPimModule" class="firstLevelMenu"><b>CRE</b></a>
            
            <ul>
                <li>
                    <a href="/cre" id="menu_cre_dashboard" class="arrow">Dashboard</a>
                </li>
                <li>
                    <a href="/cre/leads" id="menu_cre_leads" class="arrow">Leads</a>
                </li>

                <li>
                    <a href="/cre/pipelines" id="menu_cre_pipelines">Pipelines</a>
                </li>   
                    
                <li>
                    <a href="/cre/callbacks" id="menu_cre_callbacks">Callbacks</a>
                </li>    
                    
                <li>
                    <a href="/cre/viewProgramEndList" id="menu_cre_end">Program End</a>
                </li>

                    
                <li>
                    <a href="#" id="menu_cre_end">Reports</a>
                    <ul>                            
                        <li>
                            <a href="/cre/viewChannelPerformance" id="menu_cre_channel_performance">Channel Performance</a>
                        </li>
                        <li>
                            <a href="/cre/payments" id="menu_cre_payments">Payments</a>
                        </li>                            
                        <li>
                            <a href="/cre/viewCountryPerformance" id="menu_cre_country_perormance">Country Performance</a>
                        </li>
                    </ul>
                </li>    
                    
                                           
                                                
            </ul> <!-- second level -->                        
        </li>    
    @endif        
    
    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('nutritionist') || Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl'))                 
        <li class="{{ $menu == 'nutritionist' ? 'current' : '' }}">
            <a href="/nutritionist" id="menu_nutritionist" class="firstLevelMenu"><b>Nutritionist</b></a>
            
            <ul>
                                    
                <li><a href="/nutritionist/diets" id="menu_nutritionist_diets">Diets</a></li>
                <li><a href="/nutritionist/patients" id="menu_nutritionist_patients">Patients</a></li>
                <li><a href="/nutritionist/programEnd" id="menu_nutritionist_program-end">Program End</a></li>
                <li><a href="/nutritionist/audit" id="menu_nutritionist_audit">Audit</a></li>
                            
            </ul> <!-- second level -->                        
        </li>
    @endif     

    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('doctor') || Auth::user()->hasRole('service')  || Auth::user()->hasRole('service_tl'))
        <li class="{{ $menu == 'doctor' ? 'current' : '' }}">
            <a href="/doctor" id="menu_doctor" class="firstLevelMenu"><b>Doctor</b></a>
            
            <ul>  
                <li>
                    <a href="/doctor/calls" id="menu_doctor_calls">Calls</a>
                </li>
                <li>
                    <a href="/doctor/patients" id="menu_doctor_patients">Patients</a>
                </li>
                <li>
                    <a href="#" id="menu_master">Master</a>
                    <ul>
                        <li>
                            <a href="/herb/add" id="menu_herb_add">Herbs</a>
                            <a href="/herb/template/add" id="menu_herb_template_add">Herb Templates</a>
                        </li>
                    </ul>
                </li>
                            
            </ul> <!-- second level -->                        
        </li>
    @endif

    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('sales_tl'))
        <li class="{{ $menu == 'sales' ? 'current' : '' }}">
            <a href="/sales" id="menu_sales" class="firstLevelMenu"><b>Sales</b></a>
            
            <ul>       
                <li>
                    <a href="/sales/hot" id="menu_sales_hot">Hot Pipelines</a>
                </li>
                <li>
                    <a href="/sales/payments" id="menu_sales_payments">Online Payments</a>
                </li> 
                <li>
                    <a href="#" id="menu_sales_reports">Reports</a>
                    <ul>
                        <li>
                            <a href="/sales/pipelineStatus" id="menu_sales_pipeline_status">Pipeline Status</a>
                        </li>
                    </ul>
                </li>   
                            
            </ul> <!-- second level -->                        
        </li>
    @endif
    
    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl'))    
        <li class="{{ $menu == 'service' ? 'current' : '' }}">
            <a href="/service" id="menu_service" class="firstLevelMenu"><b>Service</b></a>
            
            <ul>                                    
                <li>
                    <a href="/service/assignNutritionist" id="menu_service_nutritionist">Assign Nutritionist & Doctor</a>
                </li>
                <li>
                    <a href="/service/bulk" id="menu_service_bulk">Bulk Email & SMS</a>
                </li>                                   
                <li>
                    <a href="#" id="menu_service_reports">Reports</a>
                    <ul>
                        <li>
                            <a href="/service/reports/diet_not_started" id="menu_service_diet-not-started">Diet Not Started</a>
                        </li>  
                        <li>
                            <a href="/service/diets" id="menu_diets">Diets</a>
                        </li>
                        <li>
                            <a href="/service/audit" id="menu_service_audit">Audit Report</a>
                        </li> 
                        <li>
                            <a href="/service/viewSurveys" id="menu_survey_results">Survey Results</a>
                        </li>
                        <li>
                            <a href="/service/reports/appointments" id="menu_service_appointments">Nutritionist Appointments</a>
                        </li>
                        <li>
                            <a href="/service/reports/yuwow/feedback" id="menu_service_diet-not-started">Yuwow Feedback</a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a href="#" id="yuwow">YuWoW</a>
                    <ul>
                        <li>
                            <a href="/yuwow/yuwowUsageReport" id="menu_service_yuwow_usage_report">YuWoW Usage Report</a>
                        </li>                          
                        <li>
                            <a href="/yuwow/yuwowUsers" id="menu_service_yuwow">YuWoW Users</a>
                        </li>                          
                    </ul>
                </li>

                            
            </ul> <!-- second level -->                        
        </li>  
    @endif  

    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))                 
        <li class="{{ $menu == 'marketing' ? 'current' : '' }}">
            <a href="/marketing" id="menu_marketing" class="firstLevelMenu"><b>Marketing</b></a>
            
            <ul>
                                    
                <li>
                    <a href="/marketing/lead_distribution" id="menu_lead_distribution">Lead Distribution</a>
                </li>

                <li>
                    <a href="/marketing/addLead" id="menu_lead_add">Lead Add</a>
                </li>

                <li>
                    <a href="/marketing/dialer_push" id="menu_lead_dialer-push">Dialer Push</a>
                </li>

                <li>
                    <a href="/marketing/search" id="menu_search">Search</a>
                </li>
                <li>
                    <a href="/marketing/leads/churn" id="menu_search">Churn Leads</a>
                </li>  

                <li>
                    <a href="/marketing/leads/dead" id="menu_marketing_dead">Dead Leads</a>
                </li>
                <li>
                    <a href="/marketing/upgradeLeads" id="menu_search">Upgrade Leads</a>
                </li>
                <li>
                    <a href="/marketing/viewProgramEnd" id="menu_program-end">Program End</a>
                </li>
                <li>
                    <a href="/marketing/uploadLead" id="menu_upload_lead">Upload Lead</a>
                </li> 
                    
                <li>
                    <a href="#" id="menu_cre_end">Reports</a>
                    <ul>
                        <li>
                            <a href="/marketing/leads" id="menu_leads">Leads</a>
                        </li>
                        <li>
                            <a href="/marketing/noCre" id="menu_noCre">No CRE Assigned</a>
                        </li>
                        <li>
                            <a href="/marketing/viewDuplicatePhone" id="menu_duplicate_phone">Duplicate Phone</a>
                        </li>
                        <li>
                            <a href="/marketing/viewDuplicateEmail" id="menu_duplicate_email">Duplicate Email</a>
                        </li>
                        <li>
                            <a href="/marketing/viewNoContact" id="menu_no_contact">No Contact Details</a>
                        </li>
                        <li>
                            <a href="/marketing/reports/dialerPush" id="menu_no_contact">Dialer Push</a>
                        </li>
                        <li>
                            <a href="/marketing/reports/dnc" id="menu_dnc">DNC</a>
                        </li>
                        <li>
                            <a href="/patientReport" id="menu_dnc">Patient Report</a>
                        </li>
                    </ul>
                </li>
                            
            </ul> <!-- second level -->                        
        </li>
    @endif   

    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('hr')) 
        <li class="{{ $menu == 'hr' ? 'current' : '' }}">
            <a href="/hr" id="menu_hr" class="firstLevelMenu"><b>HR</b></a>
            
            <ul>
                                    
                <li>
                    <a href="#" id="menu_admin_UserManagement" class="arrow">Employee Management</a>
                    <ul>
                        <li>
                            <a href="/hr/employees" id="menu_admin_users">Employees</a>
                        </li> 
                        <li>
                            <a href="/hr/employee/add" id="menu_admin_users">Add Employee</a>
                        </li>                                
                                                                
                    </ul> <!-- third level -->                            
                                                    
                </li> 
                            
            </ul> <!-- second level -->                        
        </li> 
    @endif             
    
    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('finance'))                 
        <li class="{{ $menu == 'finance' ? 'current' : '' }}">
            <a href="/finance" id="menu_finance" class="firstLevelMenu"><b>Finance</b></a>
            
            <ul>
                <li>
                    <a href="/finance/breakAdjust" id="menu_break_adjust">Break Adjust</a>
                        
                </li>   
                    
                                        
                <li>
                    <a href="/finance/payments">Payments</a>                   
                </li>                  
                                                
            </ul> <!-- second level -->                        
        </li>
    @endif 

    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('quality'))                 
        <li class="{{ $menu == 'quality' ? 'current' : '' }}">
            <a href="/quality" id="menu_quality" class="firstLevelMenu"><b>Quality</b></a>
            
            <ul>
                                                        
                <li>
                    <a href="#" id="menu_quality_csat">CSAT</a>  
                    <ul>
                        <li>
                            <a href="/quality/patients" id="menu_quality_patients">Patients</a>
                        </li> 
                        <li>
                            <a href="/quality/viewSurveySummary" id="menu_quality_survey_summary">Survey Summary</a>
                        </li> 
                        <li>
                            <a href="/quality/viewSurveyResults" id="menu_survey_results">Survey Results</a>
                        </li> 
                    </ul>                 
                </li>                  
                                                
            </ul> <!-- second level -->                        
        </li>
    @endif

                    
        <li class="{{ $menu == 'reports' ? 'current' : '' }}">
            <a href="/report" id="menu_report" class="firstLevelMenu"><b>Reports</b></a>
        
        
            <ul>

            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('finance') || Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl')  || Auth::user()->hasRole('sales') || Auth::user()->hasRole('sales_tl')  || Auth::user()->hasRole('marketing') || Auth::user()->hasRole('registration')) 
                <li>
                    <a href="#" id="menu_report_registration">Registration</a>  
                    <ul>
                        <li>
                            <a href="/report/registration/fees" id="menu_report_registration_fee">Patient Fee Audit</a>
                        </li> 
                    </ul>                 
                </li>
            @endif
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('finance') || Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('marketing')) 
                                                        
                <li>
                    <a href="#" id="menu_report_payment">Payments</a>  
                    <ul>
                        <li>
                            <a href="/report/payments" id="menu_payment">Payment Details</a>
                        </li> 
                    </ul>                 
                </li>
                <li>
                    <a href="/report/messages" id="menu_report_payment">Messages</a>
                </li>
                <li>
                    <a href="#" id="menu_leads">Leads</a>
                    <ul>
                        <li>
                            <a href="/report/leads/performance" id="menu_lead_performance" class="arrow">Daily Performace</a>
                        </li>
                        <li>
                            <a href="/report/viewReferences" id="menu_lead_references" class="arrow">References</a>
                        </li>
                        <li>
                            <a href="/report/viewUpgrades" id="menu_lead_references" class="arrow">Upgrades</a>
                        </li>
                        <li>
                            <a href="/report/viewPlatinumCustomers" id="menu_lead_platinum_customers" class="arrow">Platinum Customers</a>
                        </li>
                        <li>
                            <a href="/report/viewChannelConversion" id="menu_channel_conversion" class="arrow">Channel Conversion</a>
                        </li>
                    </ul>
                </li> 
                <li>
                    <a href="#" id="menu_quality">Quality</a>
                    <ul>
                        <li>
                            <a href="/report/quality/survey" id="menu_quality_survey" class="arrow">Survey</a>
                        </li> 
                    </ul>
                </li> 
                <li>
                    <a href="#" id="menu_demographics">Demographics</a>
                    <ul>
                        <li>
                            <a href="/report/demographics/active" id="menu_demographics_active" class="arrow">Active</a>
                        </li> 
                        <li>
                            <a href="/report/demographics/gender" id="menu_demographics_gender" class="arrow">Gender</a>
                        </li> 
                    </ul>
                </li>

                <li>
                    <a href="#" id="menu_patients">Patients</a>
                    <ul>
                        <li>
                            <a href="/report/patients/country" id="menu_patients_country" class="arrow">Country</a>
                        </li> 
                        <li>
                            <a href="/report/patients/new" id="menu_patients_new" class="arrow">New Patients</a>
                        </li> 
                        <li>
                            <a href="/report/patients/age" id="menu_patients_age" class="arrow">Patients Age</a>
                        </li> 
                        <li>
                            <a href="/report/patients/occupation" id="menu_patients_occupation" class="arrow">Occupation</a>
                        </li> 
                    </ul>
                </li> 

                <li>
                    <a href="#" id="menu_patients">CRE</a>
                    <ul>
                        <li>
                            <a href="/report/cre/sourcePerformance" id="menu_cre_source-performance" class="arrow">Source Performance</a>
                        </li> 
                        <li>
                            <a href="/report/cre/newLeadsourcePerformance" id="menu_cre_source-performance" class="arrow">New Leads Source Performance</a>
                        </li> 
                    </ul>
                </li>
                <li>
                    <a href="/report/emails" id="menu_report_email">Emails</a> 
                </li>                
                                                
            </ul> <!-- second level -->

        @endif  
        </li>  
        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))                
        <li class="{{ $menu == 'settings' ? 'current' : '' }}">
            <a href="/settings" id="menu_settings" class="firstLevelMenu"><b>Settings</b></a>
            
            <ul>
                <li>
                    <a href="/settings/program" id="menu_settings_program">Programs</a>
                </li>
                <li>
                    <a href="#" id="menu_cart">Cart</a>
                    <ul>
                        <li>
                            <a href="/settings/cart/status" id="menu_cart_status">Cart Status</a>
                        </li>
                        <li>
                            <a href="/settings/cart/payment/method" id="menu_payment_method">Payment Method</a>
                        </li>
                        <li>
                            <a href="/settings/cart/discount" id="menu_discount_approver">Discount Approver</a>
                        </li> 
                    </ul>
                        
                </li>  
                <li>
                    <a href="/settings/products" id="menu_cart">Products</a>
                </li>  
            </ul>
        </li>
        @endif  

        

                    
    </ul> <!-- first level -->
    
</div> <!-- menu -->