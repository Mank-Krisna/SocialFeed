<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#0058bc] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#004493] focus:bg-[#004493] active:bg-[#003d7a] focus:outline-none focus:ring-2 focus:ring-[#0058bc] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
