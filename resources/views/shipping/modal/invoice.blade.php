<div class="{{ !$shipping->invoices->isEmpty() ? 'col-md-8 col-md-offset-2' : 'col-md-4 col-md-offset-4' }}">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Invoice</div> 
        </div>
        <div class="panel-body">

        @foreach($shipping->invoices as $invoice)
            <object width='100%' height='450px' data='data:{{ $invoice->mime }};base64,{{ $invoice->file }}'></object>
            <hr>
        @endforeach
            <form class="form" action="/cart/{{ $shipping->cart_id }}/invoice" method="post"  enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <label  class="col-md-5">Invoice</label>
                    <input type="file" name="invoice" required>
                </div>
                <div>
                    <button class="btn btn-primary">Save</button>
                    <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>