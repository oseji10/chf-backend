<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;800&display=swap" rel="stylesheet">
    <style>
        *{
            font-family: 'Nunito', sans-serif;
            box-sizing: border-box;
        }
        body{
            background-color: #eee;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            width: 100%;
        }
        .heading{
            font-size: 20pt;
            color: #666;
        }
        .reset-wrapper{
            width: 95%;
            background-color: white;
            max-width: 600px;
            min-height: 20vh;
            padding: 1em;
            box-shadow: 0 0 10px rgba(100, 100, 100,.5);
            margin: 0 auto;
        }
        .title{
            /* font-size: 16pt; */
            color:rgb(27, 133, 0);
            text-transform: uppercase;
        }
        p{
            color: #666;
        }
        h3{
            color: #666;
            text-transform: uppercase;
            font-weight: 900;
            letter-spacing: 4px;
            color:rgb(27, 133, 0);
        }

        .em-text{
            font-weight: 500;
            color: rgb(27, 133, 0);
        }
        .btn{
            display: block;
            text-align: center;
            font-size: 14pt;
            background-color: rgb(27, 133, 0);
            padding: .5em 1em;
            color: white;
            text-decoration: none;
        }
        .btn:hover{
            background-color: rgb(26, 83, 12);
        }
        .code{
            font-size: 16pt;
            letter-spacing: 2px;
        }
        .foot-note{
            font-size: 9pt;
            text-align: center;
            margin-top: 4em;
        }
        .d-block{
            display: block;
        }
        .logo{
            display: block;
            margin: 0 auto;
            width: 80%;
            max-width: 350px;
        }
        /* .text-right{
            text-align: right;
        } */
        svg{
            width: 40px;
            position: relative;
            top:10px;
            color:rgba(100, 100, 100,1);
        }
    </style>
</head>
<body>
    <div class="reset-wrapper">
        <img src="https://chf.emgeresources.com/images/formCoverLogo.png" alt="" class="logo">
        <h1 class="title">Appointment Schedule</h1>
            <h3><strong>Dear {{$patientAppointment->patient->user->first_name}},  </strong></h3>    
        
        <p>You have been schedule for a hospital visit at {{$patientAppointment->coe->coe_name}}. You are required to call the CHF support a day before your appointment to confirm availability.</p> 
        <p>Remember to have your email or CHF ID with you during the visit. 
        Kindly note that you will not be attended to until you confirm avalability for this appointment.</p>
        
        <p><strong>Patient CHF ID:</strong> {{$patientAppointment->patient->chf_id}} </p>
        <p><strong> Hospital to visit (COE):</strong> {{$patientAppointment->coe->coe_name}} </p>
        <p><strong> Appointment date:</strong> {{ date("d-m-Y", strtotime($patientAppointment->appointment_date))}} </p>
        <p><strong> Appointment time:</strong> {{$patientAppointment->appointment_time}} </p>
        
        <p class="foot-note"><em>For further details and enquiries, communicate with your choosen hospital (Center of Excellence) for advice. You can also leave a chat for CHF support team using the chat on CHF at <a href="https://chf.emgeresources.com">https://chf.emgeresources.com</a> .</em></p>
        <p class="foot-note">
            <span class="d-block">2nd Floor, Right wing, Nicon Insurance Plaza, 262 Muhammadu Buhari way, Central Business District, FCT.</span>
            &copy; CHF 2021</p>
    </div>
</body>
</html>