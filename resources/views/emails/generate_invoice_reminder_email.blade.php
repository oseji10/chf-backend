@component('mail::message')
# GENERATE INVOICE REMINDER

Dear CMD,

Kindly consider this as a subtle reminder to generate the billing invoice for all services rendered from the last payment date. This is usually bi-weekly.

<h2>Steps to generating invoice:</h2>

<ul>
    <li>Login to the CHF portal by clicking the button below or visiting https://chf.emgeresources.com</li>
    <li>Click on billings from the side menu</li>
    <li>Select a date range and click on the green pull record button</li>
    <li>Click on the blue generate invoice button</li>
    <li>Click confirm</li>
</ul>

<p><i>Note: Only transactions that are not disputed and have not been paid will be invoiced.</i></p>

@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Go to CHF Dashboard
@endcomponent

Thanks,<br>
CHF Team
@endcomponent
