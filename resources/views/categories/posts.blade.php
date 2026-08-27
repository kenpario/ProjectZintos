<x-layout>
    <x-slot:title>
        {{ $category->name }} Posts
    </x-slot:title>

    <div class="m-2">
        <div class="mb-2 flex items-center justify-between gap-2">
            <h1 class="text-xl font-semibold m-2">{{ $category->name }}</h1>
            <a class="btn btn-sm m-2" href="{{ route('categories') }}">Back to categories</a>
        </div>

        <ul class="list bg-base-100 rounded-box shadow-md">
            @forelse ($posts as $post)
                <li class="list-row">
                    <div>
                        <div class="font-semibold">{{ Str::limit($post->title, 30) }}</div>
                        <div class="text-xs uppercase font-semibold opacity-60">
                            By {{ $post->user->name }}
                        </div>
                        <div>{{ Str::limit($post->message, 100) }}</div>
                    </div>
                    <div class="flex items-center gap-1">
                        <span>{{ $post->likes }}</span>
                        <span aria-label="Likes">likes</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span>{{ $post->views }}</span>
                        <span aria-label="Views">views</span>
                    </div>
                </li>
            @empty
                <li class="list-row">There are no posts in this category.</li>
            @endforelse
        </ul>

        @if ($posts->hasPages())
            <div class="mt-2 flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</x-layout>