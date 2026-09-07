@props(['hot_topic'])

<li class="list-row">
    <div class="flex flex-col gap-2 list-col-grow">
        <div>
            <div class="font-bold"><a
                    href="/categories/{{ $hot_topic->category->id }}/posts">{{ Str::limit($hot_topic->category->name, 40) }}</a>
            </div>
        </div>
        <div class="flex justify-between">
            <div class="flex gap-4">
                <div><a href="/users/{{ $hot_topic->user->id }}"><img
                            src="{{ $hot_topic->user?->avatar ? asset('storage/' . $hot_topic->user->avatar) : 'https://img.daisyui.com/images/profile/demo/1@94.webp' }}"
                            alt="{{ $hot_topic->user?->name }}'s avatar" class="size-10 rounded-box" /></a></div>
                <div>
                    <div><a href="/posts/{{ $hot_topic->id }}">{{ Str::limit($hot_topic->title, 20) }}</a></div>
                    <div class="text-xs font-semibold opacity-60"><a
                            href="/posts/{{ $hot_topic->id }}">{{ Str::limit($hot_topic->message, 50)}}</a>
                    </div>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex items-center gap-1">
                    <span>{{ $hot_topic->likes }}</span>
                    <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-label="Likes">
                        <path
                            d="M7 10v10H4a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1h3Zm0 10h9.5a2 2 0 0 0 1.94-1.53l1.5-6A2 2 0 0 0 18 10h-4.11l.58-3.48A2.98 2.98 0 0 0 11.53 3L7 10v10Z"
                            fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </div>
                <div class="flex items-center gap-1">
                    <span>{{ $hot_topic->views }}</span>
                    <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-label="Views">
                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z" fill="none"
                            stroke="currentColor" stroke-linejoin="round" stroke-width="2" />
                        <circle cx="12" cy="12" r="2.5" fill="none" stroke="currentColor" stroke-width="2" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</li>