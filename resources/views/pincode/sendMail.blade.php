<x-mail::message>
This is Email sent to you To reset Pincode for your user {{ $user_name }}

<x-mail::button :url="route('send.pincode.reset',$token)">
Visit Page
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
