@php
    $classes = [
        'mliječno' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
        'meso' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
        'voće-povrće' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
        'pekara' => 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300',
        'smočnica' => 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300',
        'pića' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900 dark:text-cyan-300',
        'smrznuto' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300',
        'čišćenje' => 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300',
        'ostalo' => 'bg-gray-100 text-gray-700 dark:bg-gray-900 dark:text-gray-300',
    ];
@endphp
<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium capitalize ml-2 {{ $classes[$category] ?? $classes['ostalo'] }}">{{ $category }}</span>
