@props(['name', 'message' => 'Apakah anda yakin?', 'show' => false, 'maxWidth' => '2xl'])

@php
    $maxWidth = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
    ][$maxWidth];
@endphp

<div x-data="{
    show: @js($show),
    open() { this.show = true },
    close() { this.show = false },
    confirm() {
        this.$dispatch('confirmed', { modal: '{{ $name }}' });
        this.close();
    },
    focusables() {
        let selector = 'a, button, input:not([type=hidden]), textarea, select, details, [tabindex]:not([tabindex=-1])';
        return [...$el.querySelectorAll(selector)].filter(el => !el.hasAttribute('disabled'));
    },
    firstFocusable() { return this.focusables()[0]; },
    lastFocusable() { return this.focusables().slice(-1)[0]; },
    nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable(); },
    prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable(); },
    nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1); },
    prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1; }
}" x-init="$watch('show', value => {
    if (value) {
        document.body.classList.add('overflow-y-hidden');
        setTimeout(() => firstFocusable().focus(), 100);
    } else {
        document.body.classList.remove('overflow-y-hidden');
    }
})" x-on:keydown.escape.window="close()"
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()" x-show="show" style="display: none;"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    @open-modal.window="if ($event.detail === '{{ $name }}') open()"
    @close-modal.window="if ($event.detail === '{{ $name }}') close()">
    <!-- backdrop -->
    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="close()" aria-hidden="true"></div>

    <!-- modal box -->
    <div x-show="show" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto p-6"
        role="dialog" aria-modal="true" aria-labelledby="{{ $name }}-title">
        <h2 class="text-lg font-semibold text-gray-900" id="{{ $name }}-title">{{ $message }}</h2>

        <div class="mt-6 flex justify-end space-x-3">
            <button type="button"
                class="inline-flex justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                @click="close()">
                Cancel
            </button>
            <button type="button"
                class="inline-flex justify-center px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
                @click="confirm()">
                OK
            </button>
        </div>
    </div>
</div>
