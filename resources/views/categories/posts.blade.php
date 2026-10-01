<x-layout>
    <x-slot:title>
        {{ $category->name }} Subcategories and Threads
    </x-slot:title>
    <div class="relative overflow-hidden rounded">
        <img class="absolute opacity-60 rounded-md w-screen h-full object-cover bg-image"
            src="{{ asset('storage/assets/img/items/background.gif') }}">
        <div class="relative z-10 sm:m-2 sm:p-4">
            <div class="flex m-2 justify-center">
                <div class="hover-3d">
                    <a href="{{ route('add_posts') }}" class="btn skeleton shadow-md">New Thread</a>
                </div>
            </div>
            <div class="bg-base-100 rounded-box shadow-md p-4">
                <form method="GET" action="{{ route('categories_posts', ['category' => $category]) }}">
                    <div class="m-2 flex justify-end">
                        <label class="input shadow-md">
                            <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                    stroke="currentColor">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.3-4.3"></path>
                                </g>
                            </svg>
                            <input type="search" name="search" value="{{ request('search') }}"
                                placeholder="Search title, content or member" class="input" />
                        </label>
                    </div>
                </form>
                <div class="m-2 shadow-md rounded-box" id="category-{{ $category->id }}-posts">
                    <details class="collapse collapse-arrow bg-base-200 border border-base-300 mb-2"
                        name="{{ $category->name }}" id="category-{{ $category->name }}" open>
                        <summary class="collapse-title bg-base-300 rounded-box font-semibold shadow-md">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex flex-col">
                                    <span>
                                        {{ Str::limit($category->name, 30) }}
                                    </span>
                                    <span class="text-xs opacity-60 font-semibold">
                                        {{ Str::limit($category->description, 30) }}
                                    </span>
                                </div>
                                @if(Auth::user()->group?->is_admin)
                                    <x-categories_editdelete :category="$category" />
                                @endif
                            </div>
                        </summary>

                        @foreach($subcategories->where('post_category_id', $category->id) as $subcategory)
                            <details class="collapse collapse-arrow bg-base-200 border border-base-300 mb-2 p-2"
                                name="{{ $subcategory->name }}" id="category-{{ $subcategory->name }}" open>
                                <summary class="collapse-title bg-base-300 rounded-box font-semibold shadow-md">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex flex-col">
                                            <span>
                                                {{ Str::limit($subcategory->name, 30) }}
                                            </span>
                                            <span class="text-xs opacity-60 font-semibold">
                                                {{ Str::limit($subcategory->description, 30) }}
                                            </span>
                                        </div>
                                        @if(Auth::user()->group?->is_admin)
                                            <x-subcategories_editdelete :subcategory="$subcategory" />
                                        @endif
                                    </div>
                                </summary>
                                <div class="collapse-content text-sm mx-2 mt-2 sm:p-4">
                                    <ul class="list gap-2 mx-2 mb-2">
                                        @foreach($pinned_posts->where('post_subcategory_id', $subcategory->id) as $post)
                                            <li class="w-full gap-2 hover-3d">
                                                <div
                                                    class="flex flex-col justify-between bg-base-100 rounded-box shadow-md p-2">
                                                    <div class="flex items-center">
                                                        <span class="indicator">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                                class="size-[1.2em]">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M8 4h8" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M9 4v6l-2 4v2h10v-2l-2-4V4" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M12 16v5" />
                                                            </svg>
                                                        </span>
                                                        <span class="text-xs opacity-60">Pinned Post</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <div class="flex gap-4 items-center">
                                                            <div><a href="/users/{{ $post->user->id }}"><img
                                                                        src="{{ $post->user?->avatar ? asset('storage/' . $post->user->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                                                                        alt="{{ $post->user?->name }}'s avatar"
                                                                        class="size-10 rounded-box" /></a></div>
                                                            <div>
                                                                <div><a class="link link-hover"
                                                                        href="/threads/{{ $post->id }}">{{ Str::limit($post->title, 20) }}</a>
                                                                </div>
                                                                <div class="text-xs font-semibold opacity-60"><a
                                                                        class="link link-hover" href="/threads/{{ $post->id }}">
                                                                        {{ Str::limit(strip_tags($post->message), 50) }}</a>
                                                                </div>
                                                                <div>by <a class="link link-hover"
                                                                        href="/users/{{ $post->user->id }}">{{ $post->user?->name }}</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex gap-2">
                                                            <div class="flex items-center gap-1">
                                                                <span>{{ $post->like_count }}</span>
                                                                <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 24 24" aria-label="Likes">
                                                                    <path
                                                                        d="M7 10v10H4a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1h3Zm0 10h9.5a2 2 0 0 0 1.94-1.53l1.5-6A2 2 0 0 0 18 10h-4.11l.58-3.48A2.98 2.98 0 0 0 11.53 3L7 10v10Z"
                                                                        fill="none" stroke="currentColor"
                                                                        stroke-linejoin="round" stroke-width="2" />
                                                                </svg>
                                                            </div>
                                                            <div class="flex items-center gap-1">
                                                                <span>{{ $post->view_count }}</span>
                                                                <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 24 24" aria-label="Views">
                                                                    <path
                                                                        d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"
                                                                        fill="none" stroke="currentColor"
                                                                        stroke-linejoin="round" stroke-width="2" />
                                                                    <circle cx="12" cy="12" r="2.5" fill="none"
                                                                        stroke="currentColor" stroke-width="2" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <ul class="list gap-2 mx-2">
                                        @forelse ($posts->where('post_subcategory_id', $subcategory->id) as $post)
                                            <li class="w-full gap-2 hover-3d">
                                                <div class="flex justify-between bg-base-100 rounded-box shadow-md gap-4 p-2">
                                                    <div class="flex gap-4 items-center">
                                                        <div><a href="/users/{{ $post->user->id }}"><img
                                                                    src="{{ $post->user?->avatar ? asset('storage/' . $post->user->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                                                                    alt="{{ $post->user?->name }}'s avatar"
                                                                    class="size-10 rounded-box" /></a>
                                                        </div>
                                                        <div class="min-w-0 break-words">
                                                            <div><a class="link link-hover"
                                                                    href="/threads/{{ $post->id }}">{{ Str::limit($post->title, 20) }}</a>
                                                            </div>
                                                            <div class="text-xs font-semibold opacity-60"><a
                                                                    class="link link-hover" href="/threads/{{ $post->id }}">
                                                                    {{ Str::limit(strip_tags($post->message), 50) }}</a>
                                                            </div>
                                                            <div>by <a class="link link-hover"
                                                                    href="/users/{{ $post->user->id }}">{{ $post->user?->name }}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <div class="flex items-center gap-1">
                                                            <span>{{ $post->like_count }}</span>
                                                            <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" aria-label="Likes">
                                                                <path
                                                                    d="M7 10v10H4a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1h3Zm0 10h9.5a2 2 0 0 0 1.94-1.53l1.5-6A2 2 0 0 0 18 10h-4.11l.58-3.48A2.98 2.98 0 0 0 11.53 3L7 10v10Z"
                                                                    fill="none" stroke="currentColor" stroke-linejoin="round"
                                                                    stroke-width="2" />
                                                            </svg>
                                                        </div>
                                                        <div class="flex items-center gap-1">
                                                            <span>{{ $post->view_count }}</span>
                                                            <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" aria-label="Views">
                                                                <path
                                                                    d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"
                                                                    fill="none" stroke="currentColor" stroke-linejoin="round"
                                                                    stroke-width="2" />
                                                                <circle cx="12" cy="12" r="2.5" fill="none"
                                                                    stroke="currentColor" stroke-width="2" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="w-full gap-2">
                                                <div class="flex justify-center bg-base-100 rounded-box shadow-md gap-4 p-4">
                                                    <div class="text-s font-semibold opacity-60">
                                                        Nothing here.
                                                    </div>
                                                </div>
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </details>
                        @endforeach
                        <div class="flex justify-between collapse-content text-sm">
                            <a class="btn btn-md bg-base-300 shadow-md mt-2" href="{{ route('categories') }}">
                                Back to all categories
                            </a>
                            {{ $posts->links() }}
                        </div>
                    </details>
                </div>
            </div>
        </div>
    </div>
</x-layout>