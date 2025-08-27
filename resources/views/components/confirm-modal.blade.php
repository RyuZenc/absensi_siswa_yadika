@props(['name', 'message' => 'Apakah anda yakin?', 'show' => false, 'maxWidth' => '2xl'])

@php
    $maxWidth =
        [
            'sm' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'lg' => 'sm:max-w-lg',
            'xl' => 'sm:max-w-xl',
            '2xl' => 'sm:max-w-2xl',
        ][$maxWidth] ?? 'sm:max-w-2xl';

    $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '', $name);
@endphp

<div x-data="{
    show: @js($show),
    open() {
        this.show = true;
        this.$nextTick(() => {
            const firstFocusable = this.firstFocusable();
            if (firstFocusable) firstFocusable.focus();
        });
    },
    close() {
        this.show = false;
    },
    confirm() {
        try {
            this.$dispatch('confirmed', { modal: '{{ $safeName }}' });
            this.close();
        } catch (error) {
            console.error('Error in confirm:', error);
            this.close();
        }
    },
    focusables() {
        try {
            let selector = 'a, button, input:not([type=hidden]), textarea, select, details, [tabindex]:not([tabindex=-1])';
            return [...this.$el.querySelectorAll(selector)].filter(el => !el.hasAttribute('disabled'));
        } catch (error) {
            console.error('Error getting focusables:', error);
            return [];
        }
    },
    firstFocusable() {
        const focusables = this.focusables();
        return focusables.length > 0 ? focusables[0] : null;
    },
    lastFocusable() {
        const focusables = this.focusables();
        return focusables.length > 0 ? focusables.slice(-1)[0] : null;
    },
    nextFocusable() {
        const focusables = this.focusables();
        const currentIndex = focusables.indexOf(document.activeElement);
        return focusables[(currentIndex + 1) % focusables.length] || this.firstFocusable();
    },
    prevFocusable() {
        const focusables = this.focusables();
        const currentIndex = focusables.indexOf(document.activeElement);
        return focusables[Math.max(0, currentIndex - 1)] || this.lastFocusable();
    }
}" x-init="$watch('show', value => {
    try {
        if (value) {
            document.body.classList.add('overflow-y-hidden');
        } else {
            document.body.classList.remove('overflow-y-hidden');
        }
    } catch (error) {
        console.error('Error in modal init:', error);
    }
})" x-on:keydown.escape.window="close()"
    x-on:keydown.tab.prevent="$event.shiftKey ? prevFocusable().focus() : nextFocusable().focus()" x-show="show"
    style="display: none;" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    @open-modal.window="if ($event.detail === '{{ $safeName }}') open()"
    @close-modal.window="if ($event.detail === '{{ $safeName }}') close()">

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
        role="dialog" aria-modal="true" aria-labelledby="{{ $safeName }}-title">

        <h2 class="text-lg font-semibold text-gray-900" id="{{ $safeName }}-title">{{ $message }}</h2>

        <div class="mt-6 flex justify-end space-x-3">
            <button type="button"
                class="inline-flex justify-center px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
                @click="close()">
                Batal
            </button>
            <button type="button"
                class="inline-flex justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                @click="confirm()">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>
