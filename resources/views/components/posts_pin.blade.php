@props(['post'])

@if(!$post->is_pinned)
    <div onclick="event.stopPropagation()">
        <div class="aura text-orange-600 bg-yellow-200">
            <button type="button" onclick="document.getElementById('pin_post_modal_{{ $post->id }}').showModal()"
                class="btn btn-square" aria-label="Pin thread" title="Pin thread">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="size-[1.2em]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 4h8" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 4v6l-2 4v2h10v-2l-2-4V4" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v5" />
                </svg>
            </button>
        </div>
        <dialog id="pin_post_modal_{{ $post->id }}" class="modal">
            <div class="modal-box">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="text-lg font-bold">Pin post?</h3>
                <p class="py-4">This will pin
                    "{{ Str::limit($post->title, 10) }}".
                </p>
                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn">Cancel</button>
                    </form>
                    <button type="submit" form="pin_post_{{ $post->id }}" id="pin_post_confirm_{{ $post->id }}"
                        class="btn btn-error">Confirm</button>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>

        <form id="pin_post_{{ $post->id }}" method="POST" action="{{ route('pin_posts', $post) }}"
            onsubmit="const button = document.getElementById('pin_post_confirm_{{ $post->id }}'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Pinning...';">
            @csrf
            @method('PUT')
        </form>
    </div>
@else
    <div onclick="event.stopPropagation()">
        <div class="aura aura-silver">
            <button type="button" onclick="document.getElementById('unpin_post_modal_{{ $post->id }}').showModal()"
                class="btn btn-square" aria-label="Unpin post" title="Unpin post">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="size-[1.2em]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 4h8" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 4v6l-2 4v2h10v-2l-2-4V4" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v5" />
                </svg>
            </button>
        </div>
        <dialog id="unpin_post_modal_{{ $post->id }}" class="modal">
            <div class="modal-box">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="text-lg font-bold">Unpin post?</h3>
                <p class="py-4">This will unpin
                    "{{ Str::limit($post->title, 10) }}".
                </p>
                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn">Cancel</button>
                    </form>
                    <button type="submit" form="unpin_post_{{ $post->id }}" id="unpin_post_confirm_{{ $post->id }}"
                        class="btn btn-error">Confirm</button>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>

        <form id="unpin_post_{{ $post->id }}" method="POST" action="{{ route('unpin_posts', $post) }}"
            onsubmit="const button = document.getElementById('unpin_post_confirm_{{ $post->id }}'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Unpinning...';">
            @csrf
            @method('PUT')
        </form>
    </div>
@endif