<x-layout>
    <x-slot:title>
        {{ $user->name }}'s Profile
    </x-slot:title>
    <div class="max-w-full py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sm:flex max-sm:flex-col p-4 sm:p-8 shadow-md rounded-md">
                <div class="flex justify-center">
                    <div class="flex flex-col items-center shadow-md rounded-md p-2 text-center">
                        <h1 class="m-2 text-xl font-semibold">{{ $user->name }}</h1>
                        <div class="divider"></div>
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                            alt="{{ $user->name }}'s avatar" class="w-[150px] h-[150px] object-cover rounded-full shadow-md m-2"/>
                        @if ($user->group?->is_admin)
                            <div class="aura aura-rainbow m-2">
                                <span class="badge badge-xl shadow">{{ $user->group?->name }}</span>
                            </div>
                        @elseif ($user->group?->is_mod)
                            <div class="aura aura-silver m-2">
                                <span class="badge badge-xl shadow">{{ $user->group?->name }}</span>
                            </div>
                        @elseif ($user->group?->is_premium)
                            <div class="aura aura-gold m-2">
                                <span class="badge badge-xl shadow">{{ $user->group?->name }}</span>
                            </div>
                        @else
                            <div class="m-2">
                                <span class="badge badge-xl shadow">{{ $user->group?->name }}</span>
                            </div>
                        @endif
                        <div class="m-2">
                            <p class="rounded-box shadow m-2 p-2">
                                {{ $user->bio ? $user->bio : 'This user has not filled the biography yet.'}}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="divider lg:divider-horizontal"></div>
                <div class="w-full">
                    <div class="collapse-title font-semibold bg-base-300 rounded"> {{ $user->name }}'s Activity
                    </div>
                    @forelse($user_posts as $post)
                        <ul class="list rounded-box gap-2">
                            <li class="list-row rounded-box shadow-md gap-2">
                                <div>
                                    <div><a href="/posts/{{ $post->id }}">{{ Str::limit($post->title, 20) }}</a></div>
                                    <div class="text-xs uppercase font-semibold opacity-60">
                                        <a href="/posts/{{ $post->id }}">{{ Str::limit($post->message, 50) }}</a>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-4">
                                    <div class="flex items-center gap-1">
                                        <span>{{ $post->likes }}</span>
                                        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            aria-label="Likes">
                                            <path
                                                d="M7 10v10H4a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1h3Zm0 10h9.5a2 2 0 0 0 1.94-1.53l1.5-6A2 2 0 0 0 18 10h-4.11l.58-3.48A2.98 2.98 0 0 0 11.53 3L7 10v10Z"
                                                fill="none" stroke="currentColor" stroke-linejoin="round"
                                                stroke-width="2" />
                                        </svg>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span>{{ $post->views }}</span>
                                        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            aria-label="Views">
                                            <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z" fill="none"
                                                stroke="currentColor" stroke-linejoin="round" stroke-width="2" />
                                            <circle cx="12" cy="12" r="2.5" fill="none" stroke="currentColor"
                                                stroke-width="2" />
                                        </svg>
                                    </div>
                                </div>
                            </li>
                    @empty
                            <div class="text-md uppercase font-semibold opacity-60 flex justify-center m-2">This user has no
                                activity yet.</div>
                        @endforelse
                    </ul>
                    <div class="mt-4 flex justify-center">
                        {{ $user_posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>