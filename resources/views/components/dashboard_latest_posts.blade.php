@props(['latest_post'])

<li class="list-row">
    <div class="flex flex-col gap-2 list-col-grow">
        <div class="flex gap-1">
            <div class="font-bold">Posted in <a class="link link-hover"
                    href="/categories/{{ $latest_post->category->id }}/threads">{{ Str::limit($latest_post->category->name, 40) }}</a>
            </div>
            <div>-</div>
            <div class="font-bold"><a class="link link-hover"
                    href="/subcategories/{{ $latest_post->subcategory->id }}/threads">{{ Str::limit($latest_post->subcategory->name, 40) }}</a>
            </div>
        </div>
        <div class="flex justify-between">
            <div class="flex gap-4 items-center">
                <div><a href="/members/{{ $latest_post->user->id }}"><img
                            src="{{ $latest_post->user?->avatar ? asset('storage/' . $latest_post->user->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                            alt="{{ $latest_post->user?->name }}'s avatar" class="size-10 rounded-box" /></a></div>
                <div>
                    <div><a class="link link-hover"
                            href="/threads/{{ $latest_post->id }}">{{ Str::limit($latest_post->title, 20) }}</a></div>
                    <div class="text-xs font-semibold opacity-60"><a class="link link-hover"
                            href="/threads/{{ $latest_post->id }}">{{ Str::limit(strip_tags($latest_post->message), 50)}}</a>
                    </div>
                    <div>by <a class="link link-hover"
                            href="/members/{{ $latest_post->user->id }}">{{ $latest_post->user?->name }}</a>
                    </div>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex items-center gap-1">
                    <span>{{ $latest_post->like_count }}</span>
                    <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-label="Likes">
                        <path
                            d="M7 10v10H4a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1h3Zm0 10h9.5a2 2 0 0 0 1.94-1.53l1.5-6A2 2 0 0 0 18 10h-4.11l.58-3.48A2.98 2.98 0 0 0 11.53 3L7 10v10Z"
                            fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </div>
                <div class="flex items-center gap-1">
                    <span>{{ $latest_post->view_count }}</span>
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