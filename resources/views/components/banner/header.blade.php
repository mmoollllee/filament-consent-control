@props(['links' => []])

<header :class="collapsed ? 'pt-2 px-3' : 'pt-4 px-4'">
    <h3 x-show="!collapsed" class="mb-1 text-base font-semibold">
        {{ __('consent-control::consent.banner.title') }}
    </h3>
    <p :class="collapsed ? '!mb-0 text-xs' : 'text-sm'">
        {!! __('consent-control::consent.banner.description', [
            'privacy_url' => $links['privacy'] ?? '/datenschutz/',
            'imprint_url' => $links['imprint'] ?? '/impressum/',
        ]) !!}
    </p>
    <button
        x-show="collapsed"
        @click="collapsed = false"
        class="text-xs text-gray-500 underline hover:text-gray-700"
    >
        {{ __('consent-control::consent.banner.settings_button') }}
    </button>
</header>
