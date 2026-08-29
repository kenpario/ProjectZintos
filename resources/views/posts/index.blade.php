<x-layout>
    <x-slot:title>
        {{ $post->title }}
    </x-slot:title>
    <div class="max-w-full py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sm:flex max-sm:flex-col p-4 sm:p-8 shadow-md rounded-md">
                <div class="flex justify-center">
                    <div class="flex flex-col items-center shadow-md rounded-md p-2 text-center">
                        <h1 class="m-2 text-xl font-semibold">{{ $post->user?->name }}</h1>
                        <div class="divider"></div>
                        <img src="{{ $post->user?->avatar ? asset('storage/' . $post->user?->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                            alt="{{ $post->user?->name }}'s avatar"
                            class="w-[150px] h-[150px] object-cover rounded-full shadow-md m-2" />
                        @if ($post->user?->group?->is_admin)
                            <div class="aura aura-rainbow m-2">
                                <span class="badge badge-xl shadow">{{ $post->user?->group?->name }}</span>
                            </div>
                        @elseif ($post->user?->group?->is_mod)
                            <div class="aura aura-silver m-2">
                                <span class="badge badge-xl shadow">{{ $post->user?->group?->name }}</span>
                            </div>
                        @elseif ($post->user?->group?->is_premium)
                            <div class="aura aura-gold m-2">
                                <span class="badge badge-xl shadow">{{ $post->user?->group?->name }}</span>
                            </div>
                        @else
                            <div class="m-2">
                                <span class="badge badge-xl shadow">{{ $post->user?->group?->name }}</span>
                            </div>
                        @endif
                        <div class="m-2">
                            <p class="rounded-box shadow m-2 p-2">
                                {{ $post->user?->bio ? $post->user?->bio : 'This user has not filled the biography yet.'}}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="divider lg:divider-horizontal"></div>
                <div class="w-full">
                    {{ $post->title }}
                    {{ $post->message }}
                </div>
            </div>
        </div>
</x-layout>