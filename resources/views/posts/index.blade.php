<x-layout>
    <x-slot:title>
        {{ $post->title }}
    </x-slot:title>
    <div class="max-w-full py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sm:flex max-sm:flex-col p-4 sm:p-8 shadow-md rounded-md">
                <div class="flex justify-center">
                    <div class="flex flex-col items-center shadow-md rounded-md p-2 text-center">
                        <h1 class="m-2 text-xl font-semibold"><a
                                href="/users/{{ $post->user->id }}">{{ $post->user?->name }}</a></h1>
                        <div class="divider"></div>
                        <a href="/users/{{ $post->user->id }}"><img
                                src="{{ $post->user?->avatar ? asset('storage/' . $post->user?->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                                alt="{{ $post->user?->name }}'s avatar"
                                class="w-[150px] h-[150px] object-cover rounded-full shadow-md m-2" /></a>
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
                    <div class="collapse-title font-semibold bg-base-300 rounded"> {{ $post->title }}
                    </div>
                    <div class="flex justify-between gap-2 m-2">
                        <span class="text-xs uppercase font-semibold opacity-60"><a href="/categories/{{ $post->category->id }}/posts"> Posted in
                            {{ $post->category?->name }}</a>
                        </span>
                        <span class="text-xs uppercase font-semibold opacity-60"> Posted
                            {{ $post->created_at->diffForHumans() }}
                            @if ($post->updated_at->gt($post->created_at->addSeconds(5)))
                                <span class="text-xs uppercase font-semibold">-</span>
                                <span class="text-xs uppercase font-semibold">Edited</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex flex-col m-2 w-full">
                        <div class="flex justify-center w-full">
                            <img src="{{ $post->media ? asset('storage/' . $post->media) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                                alt="Post Media" class="w-[500px] h-[500px] object-cover rounded shadow-md" />
                        </div>
                        <div class="text-xs uppercase font-semibold opacity-60 mt-2">
                            {{ $post->message }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-layout>