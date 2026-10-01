<x-layout>
    <x-slot:title>
        {{ $post->title }}
    </x-slot:title>
    <div class="max-w-full py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sm:flex max-sm:flex-col p-4 sm:p-8 shadow-md rounded-md bg-base-200">
                <div class="flex justify-center max-sm:w-full w-80">
                    <div class="flex flex-col items-center shadow-md rounded-md p-2 text-center bg-base-300 w-full">
                        <h1 class="m-2 text-xl font-semibold"><a class="link link-hover"
                                href="/users/{{ $post->user->id }}">{{ $post->user?->name }}</a></h1>
                        <div class="divider"></div>
                        <a href="/users/{{ $post->user->id }}"><img
                                src="{{ $post->user?->avatar ? asset('storage/' . $post->user?->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                                alt="{{ $post->user?->name }}'s avatar"
                                class="w-[150px] h-[150px] object-cover rounded-full shadow-md m-2" /></a>
                        @if ($post->user?->group?->is_admin)
                            <div class="aura aura-rainbow m-2">
                                <span class="badge badge-xl shadow">{{ $post->user?->group?->name }}</span>
                            </div>
                        @elseif ($post->user?->group?->is_mod)
                            <div class="aura aura-silver m-2">
                                <span class="badge badge-xl shadow">{{ $post->user?->group?->name }}</span>
                            </div>
                        @elseif ($post->user?->group?->is_premium)
                            <div class="aura aura-gold m-2">
                                <span class="badge badge-xl shadow">{{ $post->user?->group?->name }}</span>
                            </div>
                        @else
                            <div class="m-2">
                                <span class="badge badge-xl shadow">{{ $post->user?->group?->name }}</span>
                            </div>
                        @endif
                        <div class="m-2">
                            <p class="p-2">
                                {{ $post->user?->bio ? $post->user?->bio : 'This user has not filled the biography yet.'}}
                            </p>
                        </div>
                        <div class="m-2 p-2">
                            <p>
                                Likes received:
                                {{ $total_likes }}
                            </p>
                            <p>
                                Threads made:
                                {{ $total_posts }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="divider lg:divider-horizontal"></div>
                <div class="w-full">
                    <div class="flex min-w-0 flex-1 flex-col gap-2">
                        <div class="flex justify-between items-center font-semibold bg-base-300 rounded-box p-4">
                            <div class="flex gap-2 items-center">
                                @if(Auth::user()->group?->is_mod || Auth::user()->group?->is_admin)
                                    <x-posts_pin :post="$post" />
                                @endif
                                <div class="flex flex-col">
                                    @if($post->is_pinned)
                                        <span class="text-xs opacity-60"> Pinned Thread </span>
                                    @endif
                                    <span> {{ $post->title }} </span>
                                </div>
                            </div>
                            <div class="flex flex-wrap justify-end gap-2">
                                @if((Auth::user()->group?->is_mod || Auth::user()->group?->is_admin) && !$post->is_approved)
                                    <div onclick="event.stopPropagation()">
                                        <div class="aura aura-dual">
                                            <button type="button"
                                                onclick="document.getElementById('approve_post_modal_{{ $post->id }}').showModal()"
                                                class="btn btn-square" aria-label="Approve post" title="Approve post">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2.5" stroke="currentColor" class="size-[1.2em]">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                            </button>
                                        </div>
                                        <dialog id="approve_post_modal_{{ $post->id }}" class="modal">
                                            <div class="modal-box">
                                                <form method="dialog">
                                                    <button
                                                        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                                </form>
                                                <h3 class="text-lg font-bold">Approve thread?</h3>
                                                <p class="py-4">This will approve
                                                    "{{ Str::limit($post->title, 10) }}". This can't be undone.
                                                </p>
                                                <div class="modal-action">
                                                    <form method="dialog">
                                                        <button class="btn">Cancel</button>
                                                    </form>
                                                    <button type="submit" form="approve_post_{{ $post->id }}"
                                                        id="approve_post_confirm_{{ $post->id }}"
                                                        class="btn btn-error">Confirm</button>
                                                </div>
                                            </div>
                                            <form method="dialog" class="modal-backdrop">
                                                <button>close</button>
                                            </form>
                                        </dialog>

                                        <form id="approve_post_{{ $post->id }}" method="POST"
                                            action="{{ route('approve_posts', $post) }}"
                                            onsubmit="const button = document.getElementById('approve_post_confirm_{{ $post->id }}'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Approving...';">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                    </div>
                                @endif
                                <x-posts_likes :post_likes="$post_likes" :post="$post" />
                                @if(Auth::user()->id === $post->user?->id || Auth::user()->group?->is_admin || Auth::user()->group?->is_mod)
                                    <a href="{{ route('edit_posts', ['post' => $post]) }}">
                                        <button class="btn btn-square" aria-label="Edit thread" title="Edit thread">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2.5" stroke="currentColor" class="size-[1.2em]">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </button>
                                    </a>
                                    <div>
                                        <button type="button"
                                            onclick="document.getElementById('delete_post_modal_{{ $post->id }}').showModal()"
                                            class="btn btn-square" aria-label="Delete thread" title="Delete thread">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2.5" stroke="currentColor" class="size-[1.2em]">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-10.978.562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.5a2.25 2.25 0 0 0-2.25-2.25h-3A2.25 2.25 0 0 0 9.272 4.5v.615m9.968 0a48.667 48.667 0 0 0-9.968 0" />
                                            </svg>
                                        </button>

                                        <dialog id="delete_post_modal_{{ $post->id }}" class="modal">
                                            <div class="modal-box">
                                                <form method="dialog">
                                                    <button
                                                        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                                </form>
                                                <h3 class="text-lg font-bold">Delete thread?</h3>
                                                <p class="py-4">This will permanently delete
                                                    "{{ Str::limit($post->title, 40) }}". This can't be undone.</p>
                                                <div class="modal-action">
                                                    <form method="dialog">
                                                        <button class="btn">Cancel</button>
                                                    </form>
                                                    <button type="submit" form="delete_post_form_{{ $post->id }}"
                                                        id="delete_post_confirm_{{ $post->id }}"
                                                        class="btn btn-error">Delete</button>
                                                </div>
                                            </div>
                                            <form method="dialog" class="modal-backdrop">
                                                <button>close</button>
                                            </form>
                                        </dialog>

                                        <form id="delete_post_form_{{ $post->id }}" method="POST"
                                            action="/threads/{{ $post->id }}"
                                            onsubmit="const button = document.getElementById('delete_post_confirm_{{ $post->id }}'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Deleting...';">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="shadow-md rounded-box p-2 mt-2 bg-base-100">
                            <div class="flex justify-between gap-2 m-2">
                                <div class="flex gap-1">
                                    <span class="text-xs uppercase font-semibold opacity-60"> Posted in <a
                                            class="link link-hover" href="/categories/{{ $post->category->id }}/threads">
                                            {{ $post->category?->name }}</a>
                                    </span>
                                    <span class="text-xs uppercase font-semibold opacity-60">-</span>
                                    <span class="text-xs uppercase font-semibold opacity-60"><a class="link link-hover"
                                            href="/subcategories/{{ $post->subcategory->id }}/threads">
                                            {{ $post->subcategory?->name }}</a>
                                    </span>
                                </div>
                                <span class="text-xs uppercase font-semibold opacity-60"> Posted
                                    {{ $post->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <div class="flex w-full flex-col">
                                <div class="flex flex-col items-center w-full">
                                    @if($post->media)
                                        @if ($post->isVideo())
                                            <video controls class="w-[700px] h-[500px] object-cover rounded-xl shadow-md">
                                                <source src="{{ asset('storage/' . $post->media) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @else
                                            <img src="{{ $post->media ? asset('storage/' . $post->media) : '' }}"
                                                alt="Post Media"
                                                class="w-[700px] h-[500px] object-cover rounded-xl shadow-md" />
                                        @endif
                                        <div class="divider"></div>
                                    @endif
                                </div>
                                <div class="post-content p-2">
                                    {!! $post->message !!}
                                    @push('js')
                                        <script>
                                            document.addEventListener('DOMContentLoaded', () => {
                                                document.querySelectorAll('.post-content pre[class*="language-"]').forEach((block) => {
                                                    const match = block.className.match(/language-(\w+)/);
                                                    if (match) {
                                                        block.setAttribute('data-language', match[1]);
                                                    }
                                                });
                                            });
                                        </script>
                                    @endpush
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($post->category?->can_comment)
                        <div>
                            <x-posts_comments :post="$post" />
                        </div>
                        <div class="p-6 shadow-md rounded-md bg-base-300 font-semibold mb-2">Replies</div>
                    @endif
                    <div class="bg-base-200 rounded-box">
                        <x-comments_section :post_comments="$post_comments" />
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-2 bg-base-200 p-4 sm:p-8 shadow-md rounded-md">
                <div class="rounded-box p-1 shadow-md bg-base-100">
                    <div class="m-2">
                        <span class="text-sm font-bold m-2">Liked by:</span>
                        @foreach ($post_likes as $post_like)
                            <a class="link link-hover"
                                href="/users/{{ $post_like->user?->id }}">{{ $post_like->user?->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="rounded-box p-1 shadow-md bg-base-100">
                    <div class="m-2">
                        <span class="text-sm font-bold m-2">Viewed by:</span>
                        @foreach ($post_views as $post_view)
                            <a class="link link-hover"
                                href="/users/{{ $post_view->user?->id }}">{{ $post_view->user?->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
</x-layout>