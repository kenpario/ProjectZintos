<x-layout>
    <x-slot:title>
        Comments Moderation
    </x-slot:title>
    <div class="max-w-full py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 shadow-md rounded-md">
                <div class="font-semibold bg-base-300 rounded-box p-4">
                    <span>Comments waiting for approval</span>
                </div>
                <div class="w-full">
                    @forelse ($all_unapproved_comments as $comment)
                        <div class="shadow-md rounded-box p-2 mt-2">
                            <div class="flex justify-between gap-2 m-2">
                                <span class="text-xs uppercase font-semibold opacity-60"><a class="link link-hover"
                                        href="/posts/{{ $comment->post->id }}"> Submitted for
                                        {{ Str::limit($comment->post->title, 30)}}</a>
                                </span>
                                <span class="text-xs uppercase font-semibold opacity-60"> Submitted
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <div class="m-2">
                                    <a href="/users/{{ $comment->user->id }}"><img
                                            src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                                            alt="{{ $comment->user->name }}'s avatar"
                                            class="size-10 rounded-box mt-2" /></a>
                                </div>
                                <div class="flex flex-col m-2 w-full gap-2">
                                    <span class="text-xs font-semibold"><a class="link link-hover"
                                            href="/users/{{ $comment->user->id }}">Submitted
                                            by
                                            {{ $comment->user->name }}</a></span>
                                    <div class="text-xs opacity-60 font-semibold">
                                        <a class="link link-hover"
                                            href="/posts/{{ $comment->post_id }}">{{ Str::limit($comment->message, 50) }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-md uppercase font-semibold opacity-60 flex justify-center m-2">No comments to be
                            approved.</div>
                    @endforelse
                </div>
                <div class="mt-4 flex justify-center">
                    {{ $all_unapproved_comments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layout>