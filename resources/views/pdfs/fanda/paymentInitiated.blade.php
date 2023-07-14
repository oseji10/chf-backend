<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Nunito', sans-serif;
            box-sizing: border-box;
        }

        img {
            width: 100%;
        }

        body {
            background-color: #eee;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            width: 100%;
        }

        .heading {
            font-size: 20pt;
            color: #666;
        }

        .reset-wrapper {
            width: 100%;
            background-color: white;
            max-width: 1000px;
            min-height: 20vh;
            padding: 1em;
            box-shadow: 0 0 10px rgba(100, 100, 100, .5);
            align-items: center;
            margin: 0 auto;
        }

        .title {
            margin-top: 1rem;
            text-align: center;
            font-size: 16pt;
            color: rgb(27, 133, 0);
            text-transform: uppercase;
        }

        p,
        text-muted {
            color: #666;
        }

        h3 {
            text-align: left;
            font-size: 14pt;
            align-self: flex-start;
            text-transform: uppercase;
            font-weight: 900;
            letter-spacing: 4px;
            color: rgb(27, 133, 0);
        }

        .btn {
            display: block;
            text-align: center;
            font-size: 14pt;
            background-color: rgb(27, 133, 0);
            padding: .5em 1em;
            color: white;
            text-decoration: none;
        }

        .btn:hover {
            background-color: rgb(26, 83, 12);
        }

        .code {
            font-size: 16pt;
            letter-spacing: 2px;
        }

        .foot-note {
            font-size: 9pt;
            text-align: center;
            margin-top: 4em;
        }

        .d-block {
            display: block;
            width: 100%;
        }

        .div-flex {
            padding: 20px;
        }

        .logo {
            display: block;
            position: relative;
            margin: 0 auto;
            width: 80%;
            height: 70px;
            max-width: 150px;
        }

        table {
            width: 100%;

        }

        table tr {
            border-bottom: 1px solid rgba(100, 100, 100, .5);
        }

        table td {
            font-size: 8pt;
            color: #555;
        }

        table th {
            font-size: 9pt;
        }

        .total {
            background-color: #f5f5f5;
        }

        .total td {
            padding: 10px;
            box-sizing: border-box;
        }

        .users {
            width: 100%;
            display: block;
            justify-content: space-around;
        }

        .users .user {
            width: 30%;
            display: inline-block;
            /* padding: 1em; */
            /* margin-right: 2%; */
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="reset-wrapper">
        <div class="d-block">
            <img src="https://chf.emgeresources.com/images/formCoverLogo.png" alt="" class="logo">
            <div>
                <div class="d-block">
                    <h1 class="title">&nbsp;</h1>
                </div>
                <div class="d-block">
                    <h1 class="title">Payment Transactions</h1>
                    <p style='text-align: center'>This transactions document was generated {{date('d F Y h:i:s', time()) }} </p>
                    <h3>Details:</h3>
                    <div class="users">
                        @if(isset($transactions[0]) && isset($transactions[0][0]) && isset($transactions[0][0]->payment_initiated_by))
                        <div class="user">
                            <small>Initiated By:</small>
                            <p>{{$transactions[0][0]->initiatedBy->first_name}} {{$transactions[0][0]->initiatedBy->last_name}}</p>
                            <small class="text-muted">{{$transactions[0][0]->payment_initiated_on}}</small>
                        </div>
                        @endif

                        @if(isset($transactions[0]) && isset($transactions[0][0]) && isset($transactions[0][0]->payment_recommended_by))
                        <div class="user">
                            <small>Recommended By:</small>
                            <p>{{$transactions[0][0]->recommendedBy->first_name}} {{$transactions[0][0]->recommendedBy->last_name}}</p>
                            <small class="text-muted">{{$transactions[0][0]->payment_recommended_on}}</small>
                        </div>
                        @endif

                        @if(isset($transactions[0]) && isset($transactions[0][0]) && isset($transactions[0][0]->payment_approved_by))
                        <div class="user">
                            <small>Approved By:</small>
                            <p>{{$transactions[0][0]->approvedBy->first_name}} {{$transactions[0][0]->approvedBy->last_name}}</p>
                            <small class="text-muted">{{$transactions[0][0]->payment_approved_on}}</small>
                        </div>
                        @endif

                    </div>
                </div>
                <div class="d-block">
                    <h3 class="title">{{$transactions[0][0]->coe->coe_name}}</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Hospital</th>
                                <th>Transaction ID</th>
                                <th>Transaction Date</th>
                                <th>Patient ID</th>
                                <th>Service</th>
                                <th>Quantity</th>
                                <th>Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($transactions) && $transactions)
                            @php
                            $count = 1;
                            $total = 0;
                            @endphp
                            @foreach($transactions as $transaction)
                            @foreach($transaction as $trx)
                            @if($trx->is_drug)

                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$trx->coe->coe_name}}</td>
                                <td>{{$trx->transaction_id}}</td>
                                <td>{{$trx->created_at}}</td>
                                <td>{{$trx->user->patient->chf_id}}</td>
                                <td>CAP Drug</td>
                                <td>{{$trx->quantity}}</td>
                                <td><del>N</del>{{$trx->total}}</td>
                            </tr>
                            @else
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$trx->coe->coe_name}}</td>
                                <td>{{$trx->transaction_id}}</td>
                                <td>{{$trx->created_at}}</td>
                                <td>{{$trx->user->patient->chf_id}}</td>
                                <td>{{$trx->service->service_name}}</td>
                                <td>{{$trx->quantity}}</td>
                                <td><del>N</del>{{$trx->total}}</td>
                            </tr>
                            @endif

                            @php
                            $count++;
                            $total += $trx->total;
                            @endphp
                            @endforeach
                            @endforeach
                            @endif
                            <tr class="total">
                                <td colspan="6"><strong>Total:</strong></td>
                                <td colspan="2"><strong><del>N</del> {{$total}}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <p class="foot-note">

                    <span class="d-block">Federal Secretariat Complex, Phase III, Shehu Shagari Way, Central Business District. Abuja.</span>
                    &copy; CHF 2021
                </p>
            </div>
</body>

</html>