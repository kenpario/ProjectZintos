<x-layout>
    <x-slot:title>
        Categories
    </x-slot:title>
    <div class="relative overflow-hidden rounded">
        <img class="absolute opacity-60 rounded-md w-screen" src="/storage/media/background_gif.gif">
        <div class="relative z-10">
            <div class="flex m-2 justify-center">
                <div class="hover-3d">
                    <a class="btn skeleton shadow-md">New Post</a>
                </div>
            </div>
            <div class="m-2" id="categories">
                @foreach($categories as $category)
                    <details class="collapse collapse-arrow bg-base-200 border border-base-300 mb-2"
                        name="{{ $category->name }}" id="category-{{ $category->id }}" open>
                        <summary class="collapse-title bg-base-300 rounded font-semibold">
                            <div class="flex items-center justify-between gap-2">
                                <span>
                                    {{ Str::limit($category->name, 30) }}
                                    --
                                    {{ Str::limit($category->description, 30) }}
                                </span>
                                @if(Auth::user()->id == $category->user_id || Auth::user()->group?->is_admin)
                                    <x-categories_editdelete :category="$category" />
                                @endif
                            </div>
                        </summary>
                        @forelse($category->posts as $post_detail)
                            <div class="collapse-content text-sm">
                                <div class="max-lg:w-full">

                                    <ul class="list bg-base-100 rounded-box shadow-md mt-2">
                                        <li class="list-row">
                                            <div><img class="size-10 rounded-box" alt="Tailwind CSS list item"
                                                    src="https://img.daisyui.com/images/profile/demo/1@94.webp" /></div>
                                            <div>
                                                <div>{{ Str::limit($post_detail->title, 20) }}</div>
                                                <div class="text-xs uppercase font-semibold opacity-60">
                                                    {{ Str::limit($post_detail->message, 50) }}
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <span>{{ $post_detail->likes }}</span>
                                                <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    aria-label="Likes">
                                                    <path
                                                        d="M7 10v10H4a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1h3Zm0 10h9.5a2 2 0 0 0 1.94-1.53l1.5-6A2 2 0 0 0 18 10h-4.11l.58-3.48A2.98 2.98 0 0 0 11.53 3L7 10v10Z"
                                                        fill="none" stroke="currentColor" stroke-linejoin="round"
                                                        stroke-width="2" />
                                                </svg>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <span>{{ $post_detail->views }}</span>
                                                <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    aria-label="Views">
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
                                                <div>Nothing here.</div>
                                                <div class="text-xs uppercase font-semibold opacity-60">
                                                    Nothing here as well.
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endforelse
                        @if ($category->posts->hasPages())
                            <div class="flex justify-end p-4">
                                {{ $category->posts->links() }}
                            </div>
                        @endif
                    </details>
                @endforeach
            </div>

            <div class="flex m-2 justify-center">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-layout>