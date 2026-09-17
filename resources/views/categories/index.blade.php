<x-layout>
    <x-slot:title>
        Categories
    </x-slot:title>
    <div class="relative overflow-hidden rounded">
        <img class="absolute opacity-60 rounded-md w-screen"
            src="{{ asset('storage/assets/img/items/background.gif') }}">
        <div class="relative z-10 sm:m-2 sm:p-4">
            <div class="flex m-2 justify-center">
                <div class="hover-3d">
                    <a href="{{ route('add_posts') }}" class="btn skeleton shadow-md">New Post</a>
                </div>
            </div>
            <div class="bg-base-100 rounded-box shadow-md p-4">
                <form method="GET" action="{{ route('categories') }}">
                    <div class="m-2 flex justify-end">
                        <label class="input shadow-md">
                            <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                    stroke="currentColor">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.3-4.3"></path>
                                </g>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search"
                                class="input" />
                        </label>
                    </div>
                </form>
                <div class="m-2 shadow-md rounded-box">
                    @foreach($categories as $category)
                        <details class="collapse collapse-arrow bg-base-200 border border-base-300 mb-2"
                            name="{{ $category->name }}" id="category-{{ $category->name }}" open>
                            <summary class="collapse-title bg-base-300 rounded font-semibold">
                                <div class="flex items-center justify-between gap-2">
                                    <span>
                                        {{ Str::limit($category->name, 30) }}
                                        --
                                        {{ Str::limit($category->description, 30) }}
                                    </span>
                                    @if(Auth::user()->group?->is_admin)
                                        <x-categories_editdelete :category="$category" />
                                    @endif
                                </div>
                            </summary>
                            @forelse($all_posts->where('post_category_id', $category->id) as $post)
                                <div class="collapse-content text-sm mx-2 mt-2 sm:p-4">
                                    <div class="max-lg:w-full hover-3d w-full min-w-0 " id="posts">

                                        <ul class="list bg-base-100 rounded-box shadow-md mx-2">
                                            <li
                                                class="list-row grid-cols-[auto_minmax(0,1fr)_auto_auto] gap-2 p-0 sm:gap-4 sm:p-4">
                                                <div><a href="/users/{{ $post->user->id }}"><img
                                                            src="{{ $post->user?->avatar ? asset('storage/' . $post->user->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                                                            alt="{{ $post->user?->name }}'s avatar"
                                                            class="size-10 rounded-box" /></a>
                                                </div>
                                                <div class="min-w-0 break-words">
                                                    <div><a class="link link-hover"
                                                            href="/posts/{{ $post->id }}">{{ Str::limit($post->title, 20) }}</a>
                                                    </div>
                                                    <div class="text-xs font-semibold opacity-60"><a class="link link-hover"
                                                            href="/posts/{{ $post->id }}">
                                                            {{ Str::limit($post->message, 50) }}</a>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <span>{{ $post_likes->where('post_id', $post->id)->count() }}</span>
                                                    <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 24 24" aria-label="Likes">
                                                        <path
                                                            d="M7 10v10H4a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1h3Zm0 10h9.5a2 2 0 0 0 1.94-1.53l1.5-6A2 2 0 0 0 18 10h-4.11l.58-3.48A2.98 2.98 0 0 0 11.53 3L7 10v10Z"
                                                            fill="none" stroke="currentColor" stroke-linejoin="round"
                                                            stroke-width="2" />
                                                    </svg>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <span>{{ $post_views->where('post_id', $post->id)->count() }}</span>
                                                    <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 24 24" aria-label="Views">
                                                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"
                                                            fill="none" stroke="currentColor" stroke-linejoin="round"
                                                            stroke-width="2" />
                                                        <circle cx="12" cy="12" r="2.5" fill="none" stroke="currentColor"
                                                            stroke-width="2" />
                                                    </svg>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @empty
                                <div class="collapse-content text-sm">
                                    <div class="max-lg:w-full" id="posts">

                                        <ul class="list bg-base-100 rounded-box shadow-md mt-2">
                                            <li class="list-row">
                                                <div>
                                                    <div class="text-xs uppercase font-semibold opacity-60">There are no posts
                                                        in
                                                        this category.</div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endforelse
                            <div class="flex justify-between collapse-content text-sm">
                                <a class="btn btn-md bg-base-300 shadow-md mt-2"
                                    href="{{ route('categories_posts', ['category' => $category->id]) }}">
                                    View all posts ({{ $category->posts->count() }})
                                </a>
                                {{ $categories->links() }}
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layout>