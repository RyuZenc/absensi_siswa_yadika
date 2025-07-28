@props(['type' => 'success', 'message'])

@php
    $colors = [
        'success' => 'bg-green-500',
        'error' => 'bg-red-500',
        'info' => 'bg-yellow-500',
    ];
@endphp

<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
    class="fixed top-6 right-6 z-50 w-auto max-w-sm {{ $colors[$type] ?? 'bg-gray-800' }} text-white px-4 py-2 rounded shadow-md text-sm"
    style="display: none;" x-cloak>
    {{ $message }}
</div>
