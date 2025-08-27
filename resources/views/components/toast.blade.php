@props(['type' => 'success', 'message' => ''])

@php
    $colors = [
        'success' => 'bg-green-500',
        'error' => 'bg-red-500',
        'info' => 'bg-yellow-500',
        'warning' => 'bg-orange-500',
    ];

    // Pastikan message tidak null atau empty
    $displayMessage = $message ?: 'No message';
@endphp

@if ($message)
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
        class="fixed top-6 right-6 z-50 w-auto max-w-sm {{ $colors[$type] ?? 'bg-gray-800' }} text-white px-4 py-2 rounded shadow-md text-sm"
        style="display: none;" x-cloak>
        {{ $displayMessage }}
        <button @click="show = false" class="ml-2 text-white hover:text-gray-200">
            <i class="bi bi-x"></i>
        </button>
    </div>
@endif
