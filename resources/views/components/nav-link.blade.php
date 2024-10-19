@props(['active'])

@php
$classes = ($active ?? false)
? 'inline-flex items-center px-1 pt-1 border-b-2 border-yellow text-sm font-medium leading-5 text-yellow focus:outline-none focus:border-yellow transition duration-150 ease-in-out'
: 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-100 dark:text-gray-100 hover:text-gray-400 dark:hover:text-gray-200 hover:border-yellow dark:hover:border-yellow focus:outline-none focus:text-yellow dark:focus:text-gray-300 focus:border-yellow dark:focus:border-yellow transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>