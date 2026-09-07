<x-layout>
    <x-slot:title>
        {{ $post->title }}
    </x-slot:title>
    <div class="max-w-full py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sm:flex max-sm:flex-col p-4 sm:p-8 shadow-md rounded-md">
                <div class="flex justify-center max-sm:w-full w-80">
                    <div class="flex flex-col items-center shadow-md rounded-md p-2 text-center w-full">
                        <h1 class="m-2 text-xl font-semibold"><a
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
                            <p class="rounded-box shadow m-2 p-2">
                                {{ $post->user?->bio ? $post->user?->bio : 'This user has not filled the biography yet.'}}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="divider lg:divider-horizontal"></div>
                <div class="flex flex-col w-full gap-2">
                    <div class="w-full">
                        <div class="flex justify-between items-center font-semibold bg-base-300 rounded-box p-4">
                            <span> {{ $post->title }} </span>
                            <div class="flex gap-2">
                                @if((Auth::user()->group?->is_mod || Auth::user()->group->is_admin) && !$post->is_approved)
                                    <form method="POST" action="{{ route('approve_posts', $post) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to approve this post?')"
                                            class="btn btn-square" aria-label="Approve post" title="Approve post">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2.5" stroke="currentColor" class="size-[1.2em]">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                                @if(Auth::user()->id === $post->user?->id || Auth::user()->group?->is_admin || Auth::user()->group?->is_mod)
                                    <a href="{{ route('edit_posts', ['post' => $post]) }}">
                                        <button class="btn btn-square" aria-label="Edit Post" title="Edit Post">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2.5" stroke="currentColor" class="size-[1.2em]">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </button>
                                    </a>
                                    <form method="POST" action="/posts/{{ $post->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this post?')"
                                            class="btn btn-square" aria-label="Delete post" title="Delete post">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2.5" stroke="currentColor" class="size-[1.2em]">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-10.978.562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.5a2.25 2.25 0 0 0-2.25-2.25h-3A2.25 2.25 0 0 0 9.272 4.5v.615m9.968 0a48.667 48.667 0 0 0-9.968 0" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div class="shadow-md rounded-box p-2 mt-2">
                            <div class="flex justify-between gap-2 m-2">
                                <span class="text-xs uppercase font-semibold opacity-60"><a
                                        href="/categories/{{ $post->category->id }}/posts"> Posted in
                                        {{ $post->category?->name }}</a>
                                </span>
                                <span class="text-xs uppercase font-semibold opacity-60"> Posted
                                    {{ $post->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <div class="flex flex-col m-2 w-full">
                                <div class="flex justify-center w-full">
                                    @if($post->media)
                                        @if ($post->isVideo())
                                            <video controls class="w-[500px] h-[500px] object-cover rounded-xl shadow-md">
                                                <source src="{{ asset('storage/' . $post->media) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @else
                                            <img src="{{ $post->media ? asset('storage/' . $post->media) : '' }}"
                                                alt="Post Media"
                                                class="w-[500px] h-[500px] object-cover rounded-xl shadow-md" />
                                        @endif
                                    @endif
                                </div>
                                <div class="text-md font-semibold mt-2">
                                    {{ $post->message }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($post->category?->can_comment)
                        <div>
                            <x-posts_comments :post="$post" />
                        </div>
                        <div class="p-6 shadow-md rounded-md bg-base-300 font-semibold mb-2">Comment Section</div>
                    @endif
                    <div>
                        <x-comments_section :post_comments="$post_comments" />
                    </div>
                </div>
            </div>
        </div>
</x-layout>