<x-layout>
    <x-slot:title>
        Service Unavailable
    </x-slot:title>

    <div class="hero min-h-screen rounded-box shadow-xl overflow-hidden"
        style="background-image: url('{{ asset('storage/assets/img/items/background.gif') }}');">

        <div class="hero-overlay"></div>
        <div class="hero-content text-neutral-content text-center">
            <div class="max-w-full">
                <h1 class="mb-5 text-5xl font-bold"><span class="text-7xl">
                        <span class="flex flex-col justify-items-center">
                            <span>503</span>
                            <span>We're making some improvements! Back shortly.</span>
                        </span>
                    </span></h1>
            </div>
        </div>
    </div>
</x-layout>