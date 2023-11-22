<x-mail::message>
    # From the Office of the MD/CEO of the NDDC
    Your document:
    {{$message}}
    has been received.

    <x-mail::button :url="'http://www.nddc.gov.ng'">
        Visit the NDDC Website
    </x-mail::button>

    Thank you,
    {{ config('app.name') }}
</x-mail::message>
