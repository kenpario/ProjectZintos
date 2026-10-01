<x-layout>
    <x-slot:title>
        Page Not Found
    </x-slot:title>

    <div class="hero min-h-screen rounded-box shadow-xl overflow-hidden"
        style="background-image: url('{{ asset('storage/assets/img/items/background.gif') }}');">

        <div class="hero-overlay"></div>
        <div class="hero-content text-neutral-content text-center">
            <div class="max-w-full">
                <h1 class="mb-5 text-5xl font-bold"><span class="text-7xl">
                        <span class="flex flex-col justify-items-center">
                            <span>404</span>
                            <span>Page not found!</span>
                        </span>
                    </span></h1>
            </div>
        </div>
    </div>
</x-layout>