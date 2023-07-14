<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHF - Billing Invoice</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style>
        * {
            box-sizing: border-box;
        }

        .container {
            max-width: 800px;
            background-color: #fff;
        }

        .row {
            padding: 1em;
        }

        .invoice_section {
            border-top: 3px solid #f1f1f1;
        }

        .invoice_container>.row:first-of-type {
            background-color: #45ca78;
            /* display: flex; */
        }

        .invoice_container>.row:first-of-type div {

            padding-left: 3em;
            color: #fff;
            font-weight: 700;
        }

        .invoice_header span {
            display: block;
        }

        .invoice_header span:first-of-type {
            margin-top: 1em;
        }

        h4 {
            font-weight: 700;
        }

        body {
            background-color: #f1f1f1;
        }

        img {
            width: 100%;
            max-width: 200px;
        }
    </style>
</head>

<body>
    <?php $total = 0; ?>
    <div class="container">
        <div class='invoice_container'>
            <div class='row invoice_header'>
                <div class="col-md-6">
                    <img src='http://localhost:8000/logo.png' />
                </div>
                <div class='col-md-6'>
                    <span>Billing Invoice</span>
                    <span>{{$transactions[0]->transaction_id}}</span>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6'>
                    <h4>To</h4>
                    <p>
                        {{$transactions[0]->user->last_name}} {{$transactions[0]->user->first_name}} {{$transactions[0]->user->other_names}}
                    </p>
                    <p>{{$transactions[0]->user->address}}</p>
                    <p>{{$transactions[0]->user->phone_number}}</p>
                </div>
                <div class='col-md-6'>
                    <h4>From</h4>
                    <p>Cancer Health Fund</p>
                    <p>10 Abulu Akosa Crescent</p>
                    <p>Central Business District, FCT</p>
                    <p>PMB 1098 Wuse, Abuja.</p>

                </div>
            </div>
            <div class='row table table-responsive-sm invoice_section'>
                <div class='col-12'>
                    <div class='row'>
                        <div class='col-sm-3'>
                            <h4>Service Type</h4>
                        </div>
                        <div class='col-sm-3'>
                            <h4>Category</h4>
                        </div>
                        <div class='col-sm-3'>
                            <h4>Price</h4>
                        </div>
                        <div class='col-sm-3'>
                            <h4>Quantity</h4>
                        </div>
                    </div>
                </div>

                @foreach($transactions as $transaction)
                <?php $total += ($transaction->quantity * $transaction->service->price); ?>
                <div class='row'>
                    <div class='col-sm-3'>
                        <p>{{$transaction->service->service_name}}</p>
                    </div>
                    <div class='col-sm-3'>
                        <p>{{$transaction->service->category->category_name}}</p>
                    </div>
                    <div class='col-sm-3'>
                        <p><del>N</del> {{$transaction->service->price}}</p>
                    </div>
                    <div class='col-sm-3'>
                        <p>{{$transaction->quantity}}</p>
                    </div>

                </div>
                @endforeach

            </div>

        </div>
        <div class='row invoice_section'>
            <div class='col-sm-4'>
                <h4>Gross Payable</h4>
                <p> <del>N</del> {{$total}}</p>
            </div>
            <div class='col-sm-4'>
                <h4>Discount ({{$discount_percentage}}%)</h4>
                <p> <del>N</del> {{$total * $discount_percentage/100}}</p>
            </div>
            <div class='col-sm-4'>
                <h4>Net Total</h4>
                <p> <del>N</del> {{$total - ($total * $discount_percentage/100)}}</p>
            </div>
        </div>
        <div class='row invoice_section'>
            <div class='col-md-6'>
                <h4>Billing account</h4>
                <h4>{{$transactions[0]->coe->coe_name}}</h4>
                <p>{{$transactions[0]->coe->coe_address}}</p>
                <p>{{$transactions[0]->coe->coe_lga}}, {{$transactions[0]->coe->coe_state}}</p>

            </div>
            <div class='col-sm-12'>
                <small>
                    <strong>
                        <!-- <em>Note: The total payable for this invoice has a {{$discount_percentage}}% discount from the CHF program. This might change at anytime subjective to program values.</em> -->
                    </strong>
                </small>
            </div>
        </div>
    </div>
    </div>
</body>

</html>