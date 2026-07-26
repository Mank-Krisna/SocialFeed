@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-600 dark:bg-[var(--text-primary)] dark:text-[var(--card-border)] focus:border-[var(--accent)] focus:ring-[var(--accent)] rounded-md shadow-sm']) }}>

