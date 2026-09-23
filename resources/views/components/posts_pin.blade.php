@props(['post'])

@if(!$post->is_pinned)
    <form method="POST" action="{{ route('pin_posts', $post) }}">
        @csrf
        @method('PUT')
        <div class="aura text-orange-600 bg-yellow-200">
            <button type="submit" onclick="return confirm('Are you sure you want to pin this post?')" class="btn btn-square"
                aria-label="Pin post" title="Pin post">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="size-[1.2em]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 4h8" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 4v6l-2 4v2h10v-2l-2-4V4" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v5" />
                </svg>
            </button>
        </div>
    </form>
@else
    <form method="POST" action="{{ route('unpin_posts', $post) }}">
        @csrf
        @method('PUT')
        <div class="aura aura-silver">
            <button type="submit" onclick="return confirm('Are you sure you want to unpin this post?')"
                class="btn btn-square" aria-label="Unpin post" title="Unpin post">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="size-[1.2em]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 4h8" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 4v6l-2 4v2h10v-2l-2-4V4" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v5" />
                </svg>
            </button>
        </div>
    </form>
@endif