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
                                @if(Auth::user()->group?->is_admin)
                                    <x-categories_editdelete :category="$category" />
                                @endif
                            </div>
                        </summary>
                        <div class="collapse-content text-sm">
                            <a class="btn btn-sm m-2" href="{{ route('categories.posts', $category) }}">
                                View all posts ({{ $category->posts_count }})
                            </a>
                        </div>
                    </details>
                @endforeach
            </div>

            <div class="flex m-2 justify-center">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-layout>