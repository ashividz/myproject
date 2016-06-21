<?php 
namespace App\Amikus;

use Auth;

Trait Permission {

    public function canSearchLead()
    {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('service')|| Auth::user()->hasRole('goods_sale') || Auth::user()->hasRole('finance') || Auth::user()->hasRole('registration') || Auth::user()->hasRole('logistics') || Auth::user()->hasRole('sales_tl') || Auth::user()->hasRole('service_tl')) {
            return true;
        }
        return false;
    }

    public function canEditLeadContact()
    {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('service') || Auth::user()->hasRole('finance') || Auth::user()->hasRole('registration')) {
            return true;
        }
        return false;
    }

    public function canViewContactDetails()
    {
        return true;
    }
    
    public function canGeneratePI()
    {
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('logistics') || Auth::user()->hasRole('registration') || Auth::user()->hasRole('quality')) {
            return true;
        }

        return false;
    }

    public function canUploadInvoice()
    {
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('finance') || Auth::user()->hasRole('registration') || Auth::user()->hasRole('logistics')) {
            return true; 
        }

        return false;
    }

    public function canUploadTracking()
    {
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('logistics')) {
            return true; 
        }

        return false;
    }

    public function canViewLeadSource()
    {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing')) {
            return true;
        }
        return false;
    }
    
    //Auth::user()->hasRole('sales_tl') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('service') || 
    public function canCreateCartForOthers()
    {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing') || Auth::user()->hasRole('sales_tl')  || Auth::user()->hasRole('service_tl')) {
            return true;
        }
        return false;
    }

}