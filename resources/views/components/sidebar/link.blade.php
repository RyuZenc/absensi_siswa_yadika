@props(['href', 'icon', 'title', 'active' => false, 'sidebarOpen' => true])

<a href="{{ $href }}" title="{{ $title }}"
    class="flex items-center gap-3 h-12 px-4 rounded transition duration-200 bg-gray-500 hover:bg-gray-700 {{ $active ? 'bg-gray-900' : '' }}"
    :class="!{{ json_encode($sidebarOpen) }} && 'justify-center'">
    <i class="bi {{ $icon }} text-xl w-6 text-center"></i>
    <span x-show="{{ json_encode($sidebarOpen) }}">{{ $title }}</span>
</a>
