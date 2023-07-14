@component('mail::message')
# REFERRAL SERVICE PROVIDED NOTIFICATION

You are receiving this email because services for a patient referral from <strong>{{ $referral->referringCOE->coe_name }}</strong> to <strong>{{ $referral->referenceCOE->coe_name }}</strong> have been provided. Consequently, the transaction amount has been credited to the reference COE's wallet on the CHF portal.

{{ $referral->referringCOE->coe_name }} is hereby advised to credit the bank account of {{ $referral->referenceCOE->coe_name }} in the same sum.

Please find details of transaction below.

<h3>Transaction Details: </h3>

<strong>Referring Hospital: </strong> {{ $referral->referringCOE->coe_name }}

<strong>Reference Hospital: </strong> {{ $referral->referenceCOE->coe_name }}

<strong>Patient ID: </strong> {{ $referral->patient_chf_id }}

<h3>Services Billed</h3>
<h4>Transaction Reference: {{ $transaction_id }} </h4>

<table style="width: 100%;">
    <!-- <thead> -->
    <tr>
        <td style="font-weight:bolder">Service</td>
        <td style="font-weight:bolder">Quantity</td>
        <td style="font-weight:bolder">Unit Cost</td>
        <td style="font-weight:bolder">Subtotal</td>
    </tr>
    <!-- </thead> -->
    <!-- <tbody> -->
    @foreach($referral->services as $service)
    <tr>
        <td>{{ $service->service_name }}</td>
        <td>{{ $service->quantity }}</td>
        <td>{{ $service->cost }}</td>
        <td>{{ $service->cost * $service->quantity }}</td>
    </tr>
    @endforeach
    <!-- </tbody> -->
</table>

<h3>Total: {{ $referral->total }}</h3>


@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Login to CHF
@endcomponent


Thanks,<br>
CHF Team.

<small style="text-align: center; display: block; font-size: 8pt"> For inquiry, send an email to support@chf.emgeresources.com or call +2349137125415</small>
@endcomponent