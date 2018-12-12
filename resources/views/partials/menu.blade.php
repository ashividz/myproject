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
                 
                 @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('quality') || Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('service'))
                <li>                   
                    <a href="#" id="menu_quiz"><b>Quiz</b></a>                    
                    <ul>
                        <li>
                            <a href="/quiz/admin" id="menu_quiz_admin"><b>Admin</b></a>
                            
                        </li>
                    </ul>                    
                </li>
                @endif
                
                <li>
                    <a href="/posts" id="menu_notification" class="firstLevelMenu"><b>Posts</b></a>
                </li>

                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('nutritionist') || Auth::user()->hasRole('cre'))
                <li> 
                    <a href="/quiz" id="menu_quiz">Attempt Quiz</a>
                </li>
                @endif
                            
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
                            <a href="/admin/employees" id="menu_admin_users">Employees</a>
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

                 <li>
                    <a href="#" id="menu_admin_ip" class="arrow">IP</a>
                    <ul>
                        <li>
                            <a href="/admin/ip/roles" id="menu_email_ip_roles">IP Roles</a>
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

                                
                                                
            </ul> <!-- second level -->                        
        </li>
        <li>
            <a href="#" class="firstLevelMenu"><b>Carts</b></a>
            <ul>
                <li>
                    <a href="/carts/">Carts</a>
                </li>
                <li>
                    <a href="/carts/approval" id="menu_cart_approval">Cart Approval</a>
                </li>
            @if(Auth::user()->canUploadInvoice())   
                <li>
                    <a href="/carts/invoices">Carts Invoices</a>
                </li>
            @endif
                <li>
                    <a href="/cart/approval/pending" id="menu_cart_approval_pending">Pending Carts</a>
                </li>
                @if(Auth::user()->hasRole('admin') ||Auth::user()->hasRole('finance'))
                    <li>
                        <a href="/cart/approval/cod" id="menu_cart_approval_cod">Pending Carts(COD)</a>
                    </li>
                @endif
                @if(Auth::user()->hasRole('admin') ||Auth::user()->hasRole('registration'))
                    <li>
                        <a href="/cart/approval/pending_registration" id="menu_cart_approval_registration">Pending Registration</a>
                    </li>
                @endif
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('logistics') || Auth::user()->hasRole('quality'))  
                <li>
                    <a href="/shippings">Shippings</a>
                    <ul>
                        <li>
                            <a href="/shipping/fedex">Fedex</a>
                        </li>
                        <li>
                            <a href="/shipping/trackOrder">BMP</a>
                        </li>
                    </ul>
                </li>     
            @endif                 
                <li>
                    <a href="/orders" id="menu_orders">Orders</a>
                </li>
                <li>
                    <a href="#" id="menu_orders">Reports</a>
                    <ul>
                        <li>
                            <a href="/carts/reports/funnel">Carts Funnel</a>
                        </li>
                        <li>
                            <a href="/carts/reports/shippings">Carts Shippings</a>
                        </li>
                        <li>
                            <a href="/carts/reports/invoices">Carts Invoices</a>
                        </li>
                    </ul>
                </li>
                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('logistics')) 
                <li>
                    <a href="/carts/getdetails">Search Carts</a>
                </li>
                @endif
            </ul>
        </li> 

    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('cre') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('sales_tl') || Auth::user()->hasRole('marketing'))    
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
                    <a href="/cre/interested" id="menu_Intrested">Interested </a>
                </li>   

                <li>
                    <a href="/cre/activeClient" id="menu_feedback">Feedback</a>
                </li> 
                    
                <li>
                    <a href="/cre/callbacks" id="menu_cre_callbacks">Callbacks</a>
                </li>    
                    
                <li>
                    <a href="/cre/viewProgramEndList" id="menu_cre_end">Program End</a>
                </li>
                 <li>
                    <a href="https://drive.google.com/a/drshikha.com/file/d/0B4HO0bxQ6qePT0haRVo3X0M4RTA/view?usp=sharing" target="_blank"id="menu_cre_knowledge">Knowledge bank</a>
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
                <li><a href="/nutritionist/performance" id="menu_nutritionist_performance">Performance</a></li>
                <li>
                    <a href="https://drive.google.com/a/drshikha.com/file/d/0B4HO0bxQ6qePT0haRVo3X0M4RTA/view?usp=sharing" target="_blank"id="menu_cre_knowledge">Knowledge bank</a>
                </li>
                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl'))        
                    <li>
                        <a href="/sales/new/payments" id="menu_sales_new_payments">New Online Payments</a>
                        <ul>
                            <li>
                                <a href="/sales/new/paymentsNew" id="menu_sales_new_pipeline_status">New Online Payments New</a>
                            </li>
                        </ul>
                    </li> 
                @endif             
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
                            <a href="/disease/add" id="menu_disease_add">Add Disease</a>
                        </li>
                    </ul>
                </li>
                            
            </ul> <!-- second level -->                        
        </li>
    @endif

    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('sales_tl') || Auth::user()->hasRole('marketing'))
        <li class="{{ $menu == 'sales' ? 'current' : '' }}">
            <a href="/sales" id="menu_sales" class="firstLevelMenu"><b>Sales</b></a>
            
            <ul>       
                <li>
                    <a href="/leads/churn" id="menu_sales_hot">Leads Churn</a>
                </li>       
                <li>
                    <a href="/sales/hot" id="menu_sales_hot">Hot Pipelines</a>
                </li>
                <li>
                    <a href="/sales/payments" id="menu_sales_payments">Online Payments</a>
                    <ul>
                        <li>
                            <a href="/sales/paymentsNew" id="menu_sales_pipeline_status">Online Payments New</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/sales/new/payments" id="menu_sales_new_payments">New Online Payments</a>
                    <ul>
                        <li>
                            <a href="/sales/new/paymentsNew" id="menu_sales_new_pipeline_status">New Online Payments New</a>
                        </li>
                    </ul>
                </li>  
                <li>
                    <a href="#" id="menu_sales_reports">Reports</a>
                    <ul>
                        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('marketing'))
                            <li>
                                <a href="/sales/leads" id="menu_leads">Leads</a>
                            </li>
                            <li>
                                <a href="/sales/upgradeLeads" id="menu_upgrade_leads_upgrade_leads">Upgrade Leads</a>
                            </li>
                            <li>
                                <a href="/sales/viewProgramEnd" id="menu_program-end">Program End</a>
                            </li>
                            <li>
                            <a href="/report/viewReferences" id="menu_lead_references" class="arrow">References</a>
                            </li>
                       @endif
                        <li>
                            <a href="/sales/pipelineStatus" id="menu_sales_pipeline_status">Pipeline Status</a>
                        </li>
                        <li>
                            <a href="/sales/report/lead/status" id="menu_sales_lead_status">Lead Status</a>
                        </li>
                         <li>
                            <a href="/creLeadConversion" id="menu_sales_lead_status">CRE Leads & Conversion</a>
                        </li>
                        <li>
                            <a href="/creLeadConversionWithChurned" id="menu_sales_lead_status">CRE Leads & Conversion With Churned</a>
                        </li>
                        <li> 
                            <a href="/channelWiseLeadConversion" id="menu_sales_lead_status">Channel Wise Leads Created & Assigned</a>
                        </li> 
                        <li>
                            <a href="/sales/report/pipelines" id="menu_sales_lead_status">Hot Pipelines</a>
                        </li>
                          <li>
                            <a href="selfAssignCount" id="menu_sales_lead_status">Self Assign</a>
                        </li>
                        <li>
                            <a href="/report/patients/bmi" id="menu_patients_weight" class="arrow">Patient Weight </a>
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
                            <a href="/service/break" id="menu_break">Break Adjustment</a>
                        </li> 
                        <li>
                            <a href="/service/diets" id="menu_diets">Diets</a>
                        </li>
                        <li>
                            <a href="/service/leave" id="menu_leave">Leave</a>
                        </li>
                        <li>
                            <a href="/service/audit" id="menu_service_audit">Audit Report</a>
                        </li> 
                        <li>
                            <a href="/service/reports/appointments" id="menu_service_appointments">Nutritionist Appointments</a>
                        </li>
                        <li>
                            <a href="/service/reports/yuwow/feedback" id="menu_service_diet-not-started">Yuwow Feedback</a>
                        </li>
                        <li>
                            <a href="/service/reports/vediquediet/feedback" id="vedique_diet">Vediquediet Feedback</a>
                        </li>
                        <li>
                            <a href="/service/reports/fabReport" id="menu_service_fab_report">FAB Report</a>
                        </li>
                        <li>
                            <a href="/service/reports/weightLoss" id="menu_service_weight_loss">Weight Loss</a>
                        </li>
                        <li>
                            <a href="/sales/upgradeLeads" id="menu_service_upgradeLeads">UpgradeLeads</a>
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
                <li>
                    <a href="/service/messages">Messages</a>
                </li>
                <li>
                    <a href="#" id="MasterDiet">Master Diet</a>
                     <ul>
                        <li>
                            <a href="/service/addMasterDiet" id="menu_service_addmasterdiet">Add MasterDiet</a>
                        </li>
                        <li>
                            <a href="/service/verifyMasterDiet" id="menu_service_verifyMasterDiet">Verify MasterDiet</a>
                        </li>                            
                        <li>
                            <a href="/service/viewMasterDiet" id="menu_service_viewmasterdiet">View MasterDiet </a>
                        </li> 
                        <li>
                            <a href="/service/reports/verifySentDiet" id="menu_service_verifysentDiet">Verify SentDiet</a>
                        </li>                   
                    </ul>
                </li> 
                <li>
                    <a href="#" id="csat">CSAT</a>
                    <ul>
                        <li>
                            <a href="/service/viewSurveySummary" id="menu_quality_survey_summary">Survey Summary</a>
                        </li>                          
                        <li>
                            <a href="/service/viewSurveyResults" id="menu_survey_results">Survey Results</a>
                        </li>   
                        <li>
                            <a href="/service/viewscore" id="menu_survey_csat_score">Customer Satisfaction</a>
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
                     <ul>
                        <li>
                            <a href="/marketing/shs_lead_distribution" id="menu_leads">SHA Lead Distribution</a>
                        </li>
                        <li>
                            <a href="/marketing/vediqueDietLeads" id="menu_vediqueDiet">VediqueDiet Leads</a>
                        </li>
                        </ul>
                </li>

                <li>
                    <a href="/marketing/addLead" id="menu_lead_add">Lead Add</a>
                </li>
                @if(Auth::user()->employee->name == 'Aashima Saini')
                    <li>
                        <a href="/yuwow/sendNotification" id="push_yuwow_notification">Mobile Notification</a>
                    </li>
                @endif

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
                    <a href="/marketing/upgradeLeads" id="menu_upgrade_leads">Upgrade Leads</a>
                    <ul>
                        <li>
                            <a href="/marketing/upgradeLeads" id="menu_upgrade_leads_upgrade_leads">Upgrade Leads</a>
                        </li>
                        <li>
                            <a href="/marketing/upgradeLeads/durationWise" id="menu_upgrade_leads_duration_upgrade_leads">upgrade Leads(duration wise)</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/marketing/viewProgramEnd" id="menu_program-end">Program End</a>
                    <ul>
                        <li>
                             <a href="/marketing/viewAMP" id="menu_program-AMP">annual maintenance program</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/marketing/uploadLead" id="menu_upload_lead">Upload Lead</a>
                    <ul>
                        <li>
                            <a href="/download/sourceLeads" id="menu_leads">Download Leads</a>
                        </li>
                        <li>
                            <a href="/marketing/salesReport" id="menu_sales">Cre Revenue</a>
                        </li>
                        </ul>
                </li>  
                <li>
                    <a href="#" id="menu_cre_end">Reports</a>
                    <ul>
                        <li>
                            <a href="/marketing/leads" id="menu_leads">Leads</a>
                        </li>
                        <li>
                            <a href="/marketing/reports/package">Package Extension</a>
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

                        <li>
                            <a href="/lead/interested" id="menu_dnc">Interested</a>
                        </li>

                        <li>
                            <a href="/lead/converted" id="menu_dnc">Converted</a>
                        </li>
                        <li>
                            <a href="/reports/products" id="menu_reports_products">Products</a>
                        </li>
                        <li>
                            <a href="/reports/products/repeat_orders" id="menu_reports_products_repeat_orders">Repeat Orders</a>
                        </li>
                        <li>
                            <a href="/marketing/vediqueDietUsers" id="vedique_diet">VediqueDiet User</a>
                        </li>
                        <li>
                         <a href="/marketing/YuwowLeads" id="menu_reports_yuwow">Yuwow Leads</a>  
                        </li>   
                    </ul>
                </li>

                <li>
                    <a href="#">SMS</a>
                    <ul>
                        <li>
                            <a href="/marketing/sms/patients">Patients</a>
                        </li>
                        <li>
                            <a href="/marketing/sms/leads">Leads</a>
                        </li>
                        <li>
                            <a href="/marketing/sms/birthday">Birthday</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#">Promotion Data</a>
                    <ul>
                        <li>
                            <a href="/marketing/activeInactive">Active and Inactive</a>
                        </li>
                        <li>
                            <a href="/marketing/notInterestedleads">NI Leads</a>
                        </li>
                        <li>
                            <a href="/marketing/notConverted">Not Converted Leads</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#">VediqueDiet</a>
                    <ul>
                        <li>
                            <a href="/marketing/addRecipe">Add Recipe</a>
                        </li>
                        <li>
                            <a href="/marketing/addFood">Food Comparison</a>
                        </li>
                        <li>
                            <a href="/marketing/addProducts">Add Products</a>
                        </li>
                        <li>
                            <a href="/marketing/addBrunchArticle">Add Brunch Articles</a>
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
                <li>
                    <a href="/sales/payments" id="menu_sales_payments">Online Payments</a>
                    <ul>
                        <li>
                            <a href="/sales/paymentsNew" id="menu_sales_pipeline_status">Online Payments New</a>
                        </li>
                    </ul>
                </li> 
                <li>
                    <a href="/sales/new/payments" id="menu_sales_new_payments">New Online Payments</a>
                    <ul>
                        <li>
                            <a href="/sales/new/paymentsNew" id="menu_sales_new_pipeline_status">New Online Payments New</a>
                        </li>
                    </ul>
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
                        <li>
                            <a href="/quality/viewscore" id="menu_survey_csat_score">Customer Satisfaction</a>
                        </li>                         
                    </ul>                    
                </li> 
                <li>
                    <a href="/marketing/dialer_push" id="menu_quality_dialer">Dialer</a>
                </li>  
                <li>
                    <a href="#" id="menu_quality_csat">Service Reports</a>  
                    <ul>
                        <li>
                            <a href="/service/reports/diet_not_started" id="menu_service_diet-not-started">Diet Not Started</a>
                        </li> 
                        <li>
                            <a href="/service/break" id="menu_break">Break Adjustment</a>
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
                        <li>
                            <a href="/service/reports/fabReport" id="menu_service_fab_report">FAB Report</a>
                        </li>
                        <li>
                            <a href="/service/reports/weightLoss" id="menu_service_weight_loss">Weight Loss</a>
                        </li>                         
                    </ul>                    
                </li>                               
                
            </ul> <!-- second level -->                        
        </li>
    @endif

                    
        <li class="{{ $menu == 'reports' ? 'current' : '' }}">
            <a href="/report" id="menu_report" class="firstLevelMenu"><b>Reports</b></a>
            
        
            <ul>
            @if(Auth::user()->hasRole('bdm'))
            <li>
                <a href="#">Leads</a>  
                <ul>
                    <li>
                        <a href="/report/viewChannelConversion" id="menu_channel_conversion" class="arrow">Channel Conversion</a>
                    <li>
                    <li>
                        <a href="/marketing/leads" id="menu_leads">Leads</a>
                    </li>                        
                </ul>
            </li>
            @endif

            @if(Auth::user()->canViewOnlinePayments())
            <li>
                <a href="#">Online Payments</a>  
                <ul>
                    <li>
                        <a href="/sales/payments" id="menu_sales_payments">Online Payments</a>
                    </li>
                    <li>
                        <a href="/sales/paymentsNew" id="menu_sales_pipeline_status">Online Payments New</a>
                    </li>
                </ul>
            </li> 

            @endif

             @if(Auth::user()->canViewOnlinePayments())
            <li>
                <a href="/sales/new/payments" id="menu_sales_new_payments">New Online Payments</a>
                    <ul>
                        <li>
                            <a href="/sales/new/paymentsNew" id="menu_sales_new_pipeline_status">New Online Payments New</a>
                        </li>
                    </ul>
            </li> 

            @endif

            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('finance') || Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl')  || Auth::user()->hasRole('sales') || Auth::user()->hasRole('sales_tl')  || Auth::user()->hasRole('marketing') || Auth::user()->hasRole('registration')) 
                <li>
                    <a href="#">Sales</a>  
                    <ul>
                        <li>
                            <a href="/sales/report/performance">Performance</a>
                        </li> 
                        <li>
                            <a href="/sales/report/balancepayments">Balance Payments</a>
                        </li>
                         <li>
                            <a href="/report/viewChannelConversion" id="menu_channel_conversion" class="arrow">Channel Conversion</a>
                        </li>
                    </ul>
                </li>
            @endif

            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('finance') || Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl')  || Auth::user()->hasRole('sales') || Auth::user()->hasRole('sales_tl')  || Auth::user()->hasRole('marketing') || Auth::user()->hasRole('registration')) 
                <li>
                    <a href="#" id="menu_report_registration">Registration</a>  
                    <ul>
                        <li>
                            <a href="/report/registration/fees" id="menu_report_registration_fee">Patient Fee Audit</a>
                        </li> 
                         <li>
                            <a href="/report/registration/lateStart" id="menu_report_registration_late_start">Late Start</a>
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
                            <a href="/report/referencesReport" id="menu_lead_regionwise" class="arrow">Region Wise References</a>
                        </li>
                        <li>
                            <a href="/report/refereeReport" id="menu_lead_regionwise_report" class="arrow">Region Wise Referee Report </a>
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
                        <li>
                            <a href="/report/quality/nutritionistAppointments" id="menu_quality_nutritionistAppointments" class="arrow">Nutritionist Appointments</a>
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
                            <a href="/report/patients/bmi" id="menu_patients_bmi" class="arrow">Patient BMI </a>
                        </li>
                        <li>
                            <a href="/report/patients/allergy" id="menu_patients_allergy" class="arrow">Food Allergy</a>
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
                         <li>
                            <a href="/report/patients/suitnotsuit" id="menu_patients_suitnotsuit" class="arrow">Suit not Suit </a>
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
            

        @endif
        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('finance') || Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('marketing')  || Auth::user()->hasRole('quality')||Auth::user()->hasRole('registration')||Auth::user()->hasRole('sales_tl'))                 
                <li>
                    <a href="#" id="menu_cart">Carts</a>  
                    <ul>
                        <li>
                                <a href="/carts" id="menu_cart_carts">Cart Status</a>
                        </li>                 
                    </ul>
                </li>  
        </li>
        @endif 

        @if(Auth::user()->hasRole('admin')||Auth::user()->hasRole('yuwow_support'))                 
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
        @endif

        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing') || Auth::user()->hasRole('goods_sale') || Auth::user()->hasRole('service') ||Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('sales_tl'))                 
        <li>
            <a href="#" id="herbs">Herbs Sale</a>
            <ul>
                <li>
                    <a href="/reports/products/no_repeat_purchases" id="menu_herbs_no_repeat_purchases">No Repeat Purchases</a>
                </li>                          
                <li>
                    <a href="/reports/products/no_purchases" id="menu_herbs_no_purchases">No Purchases</a>
                </li>                          
            </ul>
        </li>
        @endif
        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('upgrade_team') || Auth::user()->hasRole('sales_tl'))
        <li>
            <a href="#" id="CSAT">CSAT</a>
            <ul>
                <li>
                    <a href="/quality/viewSurveySummary" id="menu_quality_survey_summary">Survey Summary</a>
                </li> 
                <li>
                    <a href="/quality/viewSurveyResults" id="menu_survey_results">Survey Results</a>
                </li> 
                <li>
                    <a href="/quality/viewscore" id="menu_survey_csat_score">Customer Satisfaction</a>
                </li> 
            </ul>
        </li>
        @endif

        </ul> <!-- second level -->  
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

        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('reference_team'))                
        <li class="{{ $menu == 'settings' ? 'current' : '' }}">
            <a href="/settings" id="menu_settings" class="firstLevelMenu"><b>Reference Tracker</b></a>
            
            <ul>
                <li>
                    <a href="/report/viewReferences" id="menu_lead_references" class="arrow">References</a>
                </li>  
            </ul>
        </li>
        @endif
        

                    
    </ul> <!-- first level -->
    
</div> <!-- menu -->