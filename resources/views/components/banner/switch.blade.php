@props(['categoryKey', 'category'])

<div
    class="border-b border-gray-100 pt-3"
    :class="{ '!inline-flex !items-center !gap-2 !border-0 !py-0': collapsed }"
>
    <label class="relative inline-flex cursor-pointer items-center gap-2">
        <input
            type="checkbox"
            value="{{ $categoryKey }}"
            class="peer sr-only"
            x-model="consents.{{ $categoryKey }}"
            @if($category['disabled'] ?? false) disabled @endif
        >
        <div class="h-5 w-9 shrink-0 rounded-full bg-gray-300 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-transform peer-checked:bg-primary peer-checked:after:translate-x-4 peer-disabled:cursor-not-allowed peer-disabled:opacity-50"></div>
        <span
            class="font-medium"
            :class="collapsed ? 'text-xs' : 'text-sm'"
        >
            {{ __($category['label']) }}
        </span>
    </label>

    @if(!empty($category['description']))
        <p x-show="!collapsed" class="mt-1 text-xs text-gray-500 !mb-0">
            {{ __($category['description']) }}
        </p>
    @endif

    @if(!empty($category['children']))
        <ul x-show="!collapsed" class="mt-2 list-none">
            @foreach($category['children'] as $child)
                <x-consent-control::banner.switch-child :child="$child" />
            @endforeach
        </ul>
    @endif
</div>
