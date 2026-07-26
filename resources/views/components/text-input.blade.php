@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-600 dark:bg-[#1a1c1f] dark:text-[#e2e2e6] focus:border-[#0058bc] focus:ring-[#0058bc] rounded-md shadow-sm']) }}>
