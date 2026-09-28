<x-layout>
    <x-slot:title>
        Comments Moderation
    </x-slot:title>
    <div class="max-w-full py-12 p-2">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 shadow-md rounded-md">
                <div class="font-semibold bg-base-300 rounded-box p-4">
                    <span>Comments waiting for approval</span>
                </div>
                <div class="w-full p-4 bg-base-200 rounded-md">
                    <form method="GET" action="{{ route('mod_comments') }}">
                        <div class="m-2 flex justify-end">
                            <label class="input shadow-md">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.3-4.3"></path>
                                    </g>
                                </svg>
                                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search content or member"
                                    class="input" />
                            </label>
                        </div>
                    </form>
                    @forelse ($all_unapproved_comments as $comment)
                        <div class="hover-3d flex gap-2 w-full">
                            <div class=" shadow-md rounded-box p-2 mt-2 bg-base-100 w-full">
                                <div class="flex justify-between gap-2 p-2">
                                    <span class="text-xs uppercase font-semibold opacity-60">Submitted for <a
                                            class="link link-hover" href="/posts/{{ $comment->post->id }}">
                                            {{ Str::limit($comment->post->title, 30)}}</a>
                                    </span>
                                    <span class="text-xs uppercase font-semibold opacity-60"> Submitted
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <div>
                                        <a href="/users/{{ $comment->user->id }}"><img
                                                src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                                                alt="{{ $comment->user->name }}'s avatar"
                                                class="size-10 w-[36px] h-[36px] rounded-box" /></a>
                                    </div>
                                    <div class="flex flex-col w-full">
                                        <span class="text-xs font-semibold">Submitted
                                            by <a class="link link-hover" href="/users/{{ $comment->user->id }}">
                                                {{ $comment->user->name }}</a></span>
                                        <div class="text-xs opacity-60 font-semibold">
                                            <a class="link link-hover"
                                                href="/posts/{{ $comment->post_id }}">{{ Str::limit($comment->message, 50) }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex justify-center bg-base-100 rounded-box shadow-md p-0 sm:gap-4 sm:p-4 gap-2">
                            <div class="text-s font-semibold opacity-60">
                                Nothing here.
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="mt-4 flex justify-center">
                    {{ $all_unapproved_comments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layout>