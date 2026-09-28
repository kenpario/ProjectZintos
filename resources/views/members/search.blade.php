<x-layout>
    <x-slot:title>
        Search Member
    </x-slot:title>
    <div class="rounded-box mx-auto w-fit flex flex-col justify-center items-center shadow-md bg-base-200 p-4">
        <form method="GET" action="{{ route('user_search') }}">
            <div class="m-2 flex justify-center">
                <label class="input shadow-md">
                    <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                            stroke="currentColor">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </g>
                    </svg>
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Search a member"
                        class="input" />
                </label>
            </div>
        </form>
        @if (request()->filled('search'))
            <div class="w-fit max-w-full flex flex-col items-center overflow-x-auto rounded-box shadow-md m-2 bg-base-100">
                <table class="table table-zebra w-auto">
                    <span class="text-sm font-bold p-4">Members</span>
                    <div class="divider p-2"></div>
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user_data as $user)
                            <tr>
                                <th>@if ($user->avatar)
                                    <a href="/users/{{ $user->id }}"><img src="{{ asset('storage/' . $user->avatar) }}"
                                            alt="{{ $user->name }}'s avatar"
                                            class="avatar object-cover rounded w-8 shadow-md m-2 w-[36px] h-[36px]" /></a>
                                @else
                                        <a href="/users/{{ $user->id }}"><img
                                                src="https://img.daisyui.com/images/profile/demo/superperson@192.webp"
                                                alt="{{ $user->name }}'s avatar"
                                                class="avatar object-cover rounded w-8 shadow-md m-2 w-[36px] h-[36px]" /></a>
                                    @endif
                                </th>
                                <td><a class="link-hover" href="/users/{{ $user->id }}">{{ $user->name }}</a></td>
                            </tr>
                        @empty
                            <tr>
                                <th>Nothing Here</th>
                                <th>Nothing Here</th>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div>
                    {{ $user_data->links() }}
                </div>
            </div>
        @endif
    </div>
</x-layout>