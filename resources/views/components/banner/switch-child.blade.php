@props(['child'])

<li>
    <h5 class="text-xs font-semibold">{{ __($child['label']) }}</h5>
    @if(!empty($child['description']))
        <p class="text-xs text-gray-500">{{ __($child['description']) }}</p>
    @endif
</li>
