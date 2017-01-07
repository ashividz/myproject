<?php 
namespace App\Amikus;

use Auth;

use App\Models\ApproverPayment;
use App\Models\ApproverDiscount;

Trait Permission {

    public function canSearchLead()
    {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('service')|| Auth::user()->hasRole('goods_sale') || Auth::user()->hasRole('finance') || Auth::user()->hasRole('registration') || Auth::user()->hasRole('logistics') || Auth::user()->hasRole('sales_tl') || Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('yuwow_support') || Auth::user()->hasRole('quality') || Auth::user()->hasRole('shs_sale')) {
            return true;
        }
        return false;
    }

    public function canEditLeadContact()
    {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('service') || Auth::user()->hasRole('finance') || Auth::user()->hasRole('registration')) {
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

    public function canCreateReferenceCart()
    {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing') || Auth::user()->hasRole('nutritionist') || Auth::user()->hasRole('service')) {
            return true;
        }
        return false;
    }

    /** Activate Cart for Extension or Balance Payment **/
    public function canActivateCart($cart)
    {
        if (!($cart->state_id == 3 && $cart->status_id == 4)) {
            return false;
        }
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('sales_tl')) {
            return true;
        }
        return false;
    }

    public function canApprovePayment($cart)
    {
        $methods = $cart->payments->pluck('payment_method_id');
        return ApproverPayment::whereIn('payment_method_id', $methods)
                        ->whereIn('approver_role_id', Auth::user()->roles->pluck('id'))
                        ->first();
    }

    public function canApproveDiscount($cart)
    {
        $discount = $cart->discountSteps();

        if (!$discount) {
            return true;
        }

        return ApproverDiscount::where('discount_id', $discount->id)
                        ->whereIn('approver_role_id', Auth::user()->roles->pluck('id'))
                        ->first();
    }

    public function canPost()
    {
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing')) {
            return true; 
        }

        return false;
    }

    public function canUpdateInitialWeight()
    {
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('service')) {
            return true; 
        }

        return false;
    }
}