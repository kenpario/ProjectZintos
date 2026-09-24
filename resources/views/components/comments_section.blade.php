@props(['post_comments'])

<div class="gap-2">
    @foreach ($post_comments as $post_comment)
        @if ($post_comment->is_approved || Auth::user()->group?->is_mod || Auth::user()->group?->is_admin)
            <div class="p-2">
                <div class="flex justify-end w-full">
                    @if((Auth::user()->group?->is_mod || Auth::user()->group?->is_admin) && !$post_comment->is_approved)
                        <div class="flex justify-end gap-2 bg-base-300 p-2">
                            <form method="POST" action="{{ route('approve_comments', $post_comment) }}"
                                onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled');">
                                @csrf
                                @method('PUT')
                                <div class="aura aura-dual">
                                    <button type="submit" onclick="return confirm('Are you sure you want to approve this comment?')"
                                        class="btn btn-square" aria-label="Approve comment" title="Approve comment">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                            stroke="currentColor" class="size-[1.2em]">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                    @if(Auth::user()->id === $post_comment->user?->id || Auth::user()->group?->is_admin || Auth::user()->group?->is_mod)
                        <div class="flex justify-end gap-2 bg-base-300 p-2 w-full">
                            <a href="{{ route('edit_comments', ['comment' => $post_comment]) }}">
                                <button class="btn btn-square" aria-label="Edit Post" title="Edit Post">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                        stroke="currentColor" class="size-[1.2em]">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                            </a>
                            <form method="POST" action="/comments/{{ $post_comment->id }}"
                                onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this comment?')"
                                    class="btn btn-square" aria-label="Delete post" title="Delete post">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                        stroke="currentColor" class="size-[1.2em]">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-10.978.562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.5a2.25 2.25 0 0 0-2.25-2.25h-3A2.25 2.25 0 0 0 9.272 4.5v.615m9.968 0a48.667 48.667 0 0 0-9.968 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="flex items-center">
                    <div class="m-2">
                        <a href="/users/{{ $post_comment->user->id }}"><img
                                src="{{ $post_comment->user->avatar ? asset('storage/' . $post_comment->user->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                                alt="{{ $post_comment->user->name }}'s avatar" class="size-10 rounded-box mt-2" /></a>
                    </div>
                    <div class="flex w-full">
                        <div class="flex flex-col text-xs font-semibold gap-2 w-full">
                            <div class="flex justify-between uppercase gap-2 opacity-60">
                                <a class="link link-hover" href="/users/{{ $post_comment->user->id }}"><span>By
                                        {{ $post_comment->user->name }}</span></a>
                                <span>Commented
                                    {{ $post_comment->created_at->diffForHumans() }}
                            </div>
                            <span>{{ $post_comment->message }}</span>
                        </div>
                    </div>
                </div>
                <div class="divider p-2"></div>
            </div>
        @endif
    @endforeach
</div>