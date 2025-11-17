@props(['icon' => null, 'isActive' => false])

<li>
    <a class="flex items-center gap-2 px-2 py-2 rounded {{ $isActive ? 'bg-gray-100 font-semibold' : ' hover:text-black' }}"  {{ $attributes }}>
        {{ $slot }}
    </a>
</li>