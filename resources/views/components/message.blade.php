<div
    class="relative inline-block min-h-[200px] max-w-full bg-gray-200"
    x-data="consentMessage({
        consent: '{{ $consent }}',
        src: '{{ $src }}',
        cookieName: '{{ $cookie['name'] ?? 'consentcontrol' }}',
        cookieDays: {{ $cookie['days'] ?? 365 }},
        cookieDomain: '{{ $cookie['domain'] ?? '' }}',
        cookiePath: '{{ $cookie['path'] ?? '/' }}',
        cookieSameSite: '{{ $cookie['same_site'] ?? 'lax' }}',
        cookieSecure: {{ ($cookie['secure'] ?? false) ? 'true' : 'false' }}
    })"
    {{ $attributes }}
>
    <div
        x-show="!hasConsent"
        x-cloak
        class="absolute left-1/2 z-[1000] mx-8 mt-8 w-[85%] max-w-md -translate-x-1/2 rounded-md bg-white p-5 text-center text-sm shadow"
    >
        <button
            @click="grantAndLoad()"
            class="mb-3 rounded-md bg-red-700 px-4 py-2 text-sm text-white hover:bg-red-800"
        >
            {{ __('consent-control::consent.message.button') }}
        </button>
        <p class="text-xs text-gray-600">
            {!! __('consent-control::consent.message.text', [
                'source' => '<strong>' . e($srcName) . '</strong>',
                'privacy_url' => config('consent-control.links.privacy', '/datenschutz/'),
            ]) !!}
        </p>
    </div>

    @if($type === 'iframe')
        <iframe
            data-src="{{ $src }}"
            data-consent="{{ $consent }}"
            :src="hasConsent ? '{{ $src }}' : ''"
            @if($width) width="{{ $width }}" @endif
            @if($height) height="{{ $height }}" @endif
            {{ $attributes->only(['class', 'style', 'allow', 'allowfullscreen', 'frameborder', 'title']) }}
        ></iframe>
    @else
        <template x-if="hasConsent">
            {{ $slot }}
        </template>
    @endif
</div>
