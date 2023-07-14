<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Nunito', sans-serif;
            box-sizing: border-box;
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
            width: 95%;
            background-color: white;
            max-width: 600px;
            min-height: 20vh;
            padding: 1em;
            box-shadow: 0 0 10px rgba(100, 100, 100, .5);
            margin: 0 auto;
        }

        .title {
            /* font-size: 16pt; */
            color: rgb(27, 133, 0);
            text-transform: uppercase;
        }

        p {
            color: #666;
        }

        h3 {
            color: #666;
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
        }

        .logo {
            display: block;
            margin: 0 auto;
            width: 80%;
            max-width: 350px;
        }

        /* .text-right{
            text-align: right;
        } */
        svg {
            width: 40px;
            position: relative;
            top: 10px;
            color: rgba(100, 100, 100, 1);
        }
    </style>
</head>

<body>
    <div class="reset-wrapper">
        <img src="https://chf.emgeresources.com/images/formCoverLogo.png" alt="" class="logo">
        <h1 class="title"><svg version="1.1" id="Capa_1" x="0px" y="0px" width="45.311px" height="45.311px" viewBox="0 0 45.311 45.311" style="enable-background:new 0 0 45.311 45.311;" xml:space="preserve">
                <g>
                    <path d="M22.675,0.02c-0.006,0-0.014,0.001-0.02,0.001c-0.007,0-0.013-0.001-0.02-0.001C10.135,0.02,0,10.154,0,22.656
                    c0,12.5,10.135,22.635,22.635,22.635c0.007,0,0.013,0,0.02,0c0.006,0,0.014,0,0.02,0c12.5,0,22.635-10.135,22.635-22.635
                    C45.311,10.154,35.176,0.02,22.675,0.02z M22.675,38.811c-0.006,0-0.014-0.001-0.02-0.001c-0.007,0-0.013,0.001-0.02,0.001
                    c-2.046,0-3.705-1.658-3.705-3.705c0-2.045,1.659-3.703,3.705-3.703c0.007,0,0.013,0,0.02,0c0.006,0,0.014,0,0.02,0
                    c2.045,0,3.706,1.658,3.706,3.703C26.381,37.152,24.723,38.811,22.675,38.811z M27.988,10.578
                    c-0.242,3.697-1.932,14.692-1.932,14.692c0,1.854-1.519,3.356-3.373,3.356c-0.01,0-0.02,0-0.029,0c-0.009,0-0.02,0-0.029,0
                    c-1.853,0-3.372-1.504-3.372-3.356c0,0-1.689-10.995-1.931-14.692C17.202,8.727,18.62,5.29,22.626,5.29
                    c0.01,0,0.02,0.001,0.029,0.001c0.009,0,0.019-0.001,0.029-0.001C26.689,5.29,28.109,8.727,27.988,10.578z" />
                </g>
            </svg> Transaction Resolution</h1>
        <p>You are receiving this mail because you are a stakeholder on a flagged transaction on the CHF Program.</p>
        <h3><strong>Detail: </strong></h3>
        <p>Query raised for transaction with ID <strong>{{$transaction_dispute->transaction_id}}</strong> has been marked as resolved</p>
        <!-- <p><strong>Transaction ID: </strong> {{$transaction_dispute->transaction_id}}</p> -->

        <a href="https://chf.emgeresources.com/dashboard" class="btn"> Login to CHF here!</a>
        <p class="foot-note"></p>
        <p class="foot-note">
            <span class="d-block">Federal Secretariat Complex, Phase III, Shehu Shagari Way, Central Business District. Abuja.</span>
            &copy; CHF 2021
        </p>
    </div>
</body>

</html>