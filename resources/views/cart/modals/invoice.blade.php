<div class="{{ $invoice ? 'container-fluid' : 'col-md-4 col-md-offset-4'}}">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Invoice</div> 
        </div>
        <div class="panel-body">
        @if($invoice)
            <object width='100%' height='450px' data='data:{{ $invoice->mime }};base64,{{ $invoice->file }}'></object>
            <hr>
        @endif
        @if(Auth::user()->canUploadInvoice())
            <form class="form" action="{{ $invoice ? '/invoice/'.$invoice->id : '/cart/'.$cart->id.'/invoice' }}" method="post"  enctype="multipart/form-data">
            @if($invoice)
                {{ method_field('PATCH') }}
            @endif
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-5">Invoice Number</label>
                    <input type="text" name="number" value="{{ $invoice->number or '' }}" required>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Amount</label>
                    <input type="text" name="amount" value="{{ $invoice->amount or '' }}" required>
                </div>
                <div class="form-group">
                    <input type="file" name="invoice">
                </div>
                <div>
                    <button class="btn btn-primary">{{ $invoice ? 'Update' : 'Save' }}</button>
                    <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        @endif
        </div>
    </div>
</div>