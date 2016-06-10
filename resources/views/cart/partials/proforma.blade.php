<!DOCTYPE html>
<html>
<head>
    <title>Proforma Invoice - {{ $cart->lead->name or "" }}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<style type="text/css">
    body {
        font-size: 12px;
        font-family: 'Open Sans';
    }
</style>
<body>
    <div class="container">
        <table class="tableq1" width="100%">
            <tr>
                <td>
                    <div style="text-align:center">
                        <img src="{{ url('/')}}/images/logo.jpg" width="300px">
                        <div>
                            <h3>Nutriwel Health (India) Private Limited</h3>
                            232 B, 2nd Floor, Okhla Industrial Area, Phase III, New Delhi - 110020
                            <p>
                            Phone No: 011-41343500, 011-79411250
                            <p>
                            nutrihealthsystems.com 
                            <h3>Proforma Invoice</h3>
                        </div>
                    </div>
                    <div>
                        <span>
                            <b>Proforma Invoice No :</b> {{ $cart->proforma->id or "" }}
                        </span>
                        <span class="pull-right">
                            <b>Date :</b> {{ $cart->proforma? $cart->proforma->created_at->format('jS M, Y') : "" }}
                        </span>
                    </div>
                    <div>
                        <div>
                            <b>Name : </b> {{ $cart->lead->name or "" }}
                        </div>
                        <div>
                            <b>Address : </b> {{ $cart->lead->address }}<br>
                            {{ $cart->lead->city or '' }}{{ $cart->lead->region ? ', '.$cart->lead->region->region_name : "" }}{{ $cart->lead->m_country ? ', '.$cart->lead->m_country->country_name : "" }}
                        </div>
                        <div>
                            <b>Mobile : </b> {{ $cart->lead->phone or $cart->lead->mobile }}
                        </div>
                        <div>
                            <b>Cart Id : </b> {{ $cart->id or "" }}
                        </div>
                        <div>
                            <b>CRE : </b> {{ $cart->cre->employee->name or "" }}
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <hr>
            </tr>
            <tr>
                <td>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Discount</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart->products as $product)
                            <tr>
                                <td>
                                    {{ $product->name }}                            
                                </td>
                                <td>
                                    {{ $cart->currency->name or "" }} {{ $product->price }}                            
                                </td>
                                <td>
                                    {{ $product->pivot->quantity}}
                                </td>
                                <td>
                                    {{ $product->pivot->discount > 0 ? $product->pivot->discount . '%' : '' }}
                                </td>
                                <td>
                                    {{ $cart->currency->name or "" }} {{ $product->pivot->amount}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfooter>
                            <tr>
                                <td colspan="4">
                                    <div class="pull-right">
                                        <b>Total</b>
                                    </div>
                                </td>
                                <td>
                                    <b>{{ $cart->currency->name or "" }} {{ $cart->amount }}</b>
                                </td>
                            </tr>
                        </tfooter>
                    </table>                    
                </td>
            </tr>

            <tr>
                <td>
                    <?php 
                        $f = new \NumberFormatter("en", NumberFormatter::SPELLOUT);
                    ?>
                    <b>Rupees :</b> {{ ucwords($f->format($cart->amount)) }} Only
                </td>
            </tr>
            <tr>
                <td style="font-size:11px">
                    <b>Terms & Conditions :</b>
                    <ul>
                        <li>The fee once received will not be refunded.</li>
                        <li>Cheque realization subject to Delhi jurisdiction.</li>
                        <li>Keeping in mind the complexity of the human body & behaviour it is not possible to gurantee weight loss.</li>
                        <li>By making this payment you agree to receive phone calls, SMS and emailers from Dr Shikha's Nutri-Health</li>
                        <li>Cheque should be in favour of <b>Nutriwel Health (India) Pvt. Ltd</b></li>
                    </ul>
                </td>                    
            </tr>
            <tr>
                <td>
                    <hr style="border:1px dashed #111">
                </td>
            </tr>
            <tr>
                <td>
                    <table class="table1" width="100%">
                        <tr>
                            <td width="50%">
                                <h3>PAYMENT ADVICE</h3>
                                To:
                                <div style="margin-left:20px">
                                    Nutriwel Health (India) Pvt. Ltd<br>
                                    232B, 2nd Floor<br>
                                    Okhla Industrial Area<br>
                                    Phase III<br>
                                    New Delhi - 110020<br>
                                </div>
                            </td>
                            <td>
                                <table class="table1" width="100%">
                                    <tr>
                                        <td><label>Name: </label></td>
                                        <td>{{ $cart->lead->name or "" }}</td>
                                    </tr>
                                    <tr>
                                        <td><label>Cart Id: </label></td>
                                        <td>{{ $cart->id or "" }}</td>
                                    </tr>
                                    <tr>
                                        <td><label>Proforma Id: </label></td>
                                        <td>{{ $cart->proforma->id or "" }}</td>
                                    </tr>
                                    <tr>
                                        <td><label>Proforma Date: </label></td>
                                        <td>{{ $cart->proforma->created_at->format('jS M, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><label>Amount: </label></td>
                                        <td>{{ $cart->currency->name or "" }} {{ $cart->amount or "" }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <hr>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Customer's Signature: </label>
                                        </td>
                                        <td>
                                            --------------------------
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

    