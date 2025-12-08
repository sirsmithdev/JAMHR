@props(['active' => false, 'href'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'flex items-center gap-3 px-3 py-2.5 rounded-md transition-all duration-200 group cursor-pointer ' . ($active ? 'bg-sidebar-primary text-sidebar-primary-foreground shadow-sm font-medium' : 'text-sidebar-foreground/80 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground')]) }}>
    <span class="{{ $active ? 'text-sidebar-primary-foreground' : 'text-sidebar-foreground/60 group-hover:text-sidebar-accent-foreground' }}">
        {{ $slot }}
    </span>
</a>
