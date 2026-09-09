@props(['post_likes', 'post'])

<div class="flex items-center gap-2">
    <div>{{ $post_likes->count() }}</div>
    @if($post_likes->contains('user_id', Auth::id()))
        <form method="POST" action="/likes/{{ $post_likes->firstWhere('user_id', Auth::id())->id }}"
            class="mx-auto w-full max-w-md" enctype="multipart/form-data"
            onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-square" aria-label="Unlike post" title="Unlike post">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-[1.2em]">
                    <path
                        d="M7 10v10H4a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1h3Zm0 10h9.5a2 2 0 0 0 1.94-1.53l1.5-6A2 2 0 0 0 18 10h-4.11l.58-3.48A2.98 2.98 0 0 0 11.53 3L7 10v10Z" />
                </svg>
            </button>
        </form>
    @else
        <form method="POST" action="{{ route('like_posts', $post) }}" class="mx-auto w-full max-w-md"
            enctype="multipart/form-data"
            onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled');">
            @csrf
            <button type="submit" class="btn btn-square" aria-label="Like post" title="Like post">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="size-[1.2em]">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7 10v10H4a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1h3Zm0 10h9.5a2 2 0 0 0 1.94-1.53l1.5-6A2 2 0 0 0 18 10h-4.11l.58-3.48A2.98 2.98 0 0 0 11.53 3L7 10v10Z" />
                </svg>
            </button>
        </form>
    @endif
</div>