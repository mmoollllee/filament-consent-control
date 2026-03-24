<div x-show="!collapsed" class="px-4 pb-2">
    <button
        @click="resetAll()"
        class="text-xs text-gray-400 underline hover:text-gray-600"
    >
        {{ __('consent-control::consent.banner.reset_button') }}
    </button>
</div>

<div class="flex flex-wrap items-center justify-center" :class="collapsed ? 'gap-1 pb-3 px-3' : 'gap-2 pb-4 px-4'">
    <button
        x-show="!collapsed"
        @click="collapsed = true"
        class="btn btn-secondary"
        :class="collapsed ? 'btn-sm' : 'btn-md'"
    >
        {{ __('consent-control::consent.banner.close_button') }}
    </button>
    <button
        @click="saveSelected()"
        class="btn btn-secondary"
        :class="collapsed ? 'btn-sm' : 'btn-md'"
    >
        {{ __('consent-control::consent.banner.ok_button') }}
    </button>
    <button
        @click="acceptAll()"
        class="btn btn-primary"
        :class="collapsed ? 'btn-sm' : 'btn-md'"
    >
        {{ __('consent-control::consent.banner.all_button') }}
    </button>
</div>
