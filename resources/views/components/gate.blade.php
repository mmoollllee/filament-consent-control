<div
    x-data="consentGate('{{ $consent }}', '{{ $cookieName }}')"
    x-show="hasConsent"
    x-cloak
    {{ $attributes }}
>
    {{ $slot }}
</div>
