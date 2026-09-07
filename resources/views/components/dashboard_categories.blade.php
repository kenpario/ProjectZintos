<div class="collapse-content text-sm">
    <div class="max-lg:w-full" id="posts">

        <ul class="list bg-base-100 rounded-box shadow-md mt-2">
            <li class="list-row">
                <div><a href="/users/{{ $post->user->id }}"><img
                            src="{{ $post->user?->avatar ? asset('storage/' . $post->user->avatar) : 'https://img.daisyui.com/images/profile/demo/1@94.webp' }}"
                            alt="{{ $post->user?->name }}'s avatar" class="size-10 rounded-box" /></a></div>
                <div>
                    <div><a href="/posts/{{ $post->id }}">{{ Str::limit($post->title, 20) }}</a></div>
                    <div class="text-xs font-semibold opacity-60"><a href="/posts/{{ $post->id }}">
                            {{ Str::limit($post->message, 50) }}</a>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <span>{{ $post->likes }}</span>
                    <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-label="Likes">
                        <path
                            d="M7 10v10H4a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1h3Zm0 10h9.5a2 2 0 0 0 1.94-1.53l1.5-6A2 2 0 0 0 18 10h-4.11l.58-3.48A2.98 2.98 0 0 0 11.53 3L7 10v10Z"
                            fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </div>
                <div class="flex items-center gap-1">
                    <span>{{ $post->views }}</span>
                    <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-label="Views">
                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z" fill="none"
                            stroke="currentColor" stroke-linejoin="round" stroke-width="2" />
                        <circle cx="12" cy="12" r="2.5" fill="none" stroke="currentColor" stroke-width="2" />
                    </svg>
                </div>
            </li>
        </ul>
    </div>
</div>