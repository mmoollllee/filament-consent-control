{{-- Frontend: load JS + optional standalone CSS for projects without Tailwind --}}
@if($standaloneCss)
<link rel="stylesheet" href="{{ asset('vendor/consent-control/css/consent-control-standalone.css') }}">
@endif
<script src="{{ asset('vendor/consent-control/js/consent-control.js') }}" defer></script>
