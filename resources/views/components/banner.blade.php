<div
    id="consent-control-banner"
    x-data="consentControl({
        cookieName: '{{ $cookie['name'] ?? 'consentcontrol' }}',
        cookieDays: {{ $cookie['days'] ?? 365 }},
        cookieDomain: '{{ $cookie['domain'] ?? '' }}',
        cookiePath: '{{ $cookie['path'] ?? '/' }}',
        cookieSameSite: '{{ $cookie['same_site'] ?? 'lax' }}',
        cookieSecure: {{ ($cookie['secure'] ?? false) ? 'true' : 'false' }},
        resetMessage: '{{ __('consent-control::consent.banner.reset_message') }}',
        categories: {{ Js::from(collect($categories)->map(fn($c, $key) => [
            'key' => $key,
            'checked' => $c['checked'] ?? false,
            'disabled' => $c['disabled'] ?? false,
            'scripts' => $c['scripts'] ?? [],
            'inlineScript' => $c['inline_script'] ?? null,
        ])->values()) }}
    })"
    x-show="showBanner"
    x-transition
    x-cloak
    @consent-control-open.window="open()"
    :class="{
        'translate-x-[calc(100%+2rem)]': collapsed && hide,
        '!max-w-xs': collapsed
    }"
    class="flex flex-col gap-2 fixed bottom-0 right-0 z-[9999] w-full max-w-md overflow-auto rounded-md bg-white text-center text-sm text-gray-800 shadow-lg transition-all duration-500 sm:bottom-4 sm:right-4 sm:w-[calc(100%-2rem)] sm:max-w-lg"
    role="dialog"
    aria-modal="true"
    aria-label="{{ __('consent-control::consent.banner.title') }}"
>
    <x-consent-control::banner.header :links="$links" />

    <div class="text-center flex flex-wrap justify-center gap-x-3 gap-y-1" :class="collapsed ? '' : 'bg-gray-50'">
        @foreach($categories as $key => $category)
            <x-consent-control::banner.switch :categoryKey="$key" :category="$category" />
        @endforeach
    </div>

    <x-consent-control::banner.footer />
</div>
