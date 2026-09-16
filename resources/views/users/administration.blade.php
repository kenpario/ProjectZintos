<x-layout>
    <x-slot:title>
        Users Administration
    </x-slot:title>
    <div class="rounded-box shadow-md p-4">
        <form method="GET" action="{{ route('user_administration') }}">
            <div class="m-2 flex justify-end">
                <label class="input shadow-md">
                    <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                            stroke="currentColor">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </g>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search"
                        class="input" />
                </label>
            </div>
        </form>
        <div class="overflow-x-auto rounded-box shadow-md m-2 bg-base-100">
            <table class="table table-zebra">
                <caption class="text-sm font-bold">Users Table</caption>
                <thead>
                    <tr>
                        <th>Nr.</th>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Group</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user_data as $user)
                        <tr>
                            <th>{{ $user->id }}</th>
                            <th>@if ($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}'s avatar"
                                    class="avatar object-cover rounded w-8 shadow-md m-2" />
                            @else
                                    <img src="https://img.daisyui.com/images/profile/demo/superperson@192.webp"
                                        alt="{{ $user->name }}'s avatar"
                                        class="avatar object-cover rounded w-8 shadow-md m-2" />
                                @endif
                            </th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->group?->is_admin)
                                    <div class="aura aura-rainbow">
                                        <span class="badge shadow">{{ $user->group?->name }}</span>
                                    </div>
                                @elseif($user->group?->is_mod)
                                    <div class="aura aura-silver">
                                        <span class="badge shadow">{{ $user->group?->name }}</span>
                                    </div>
                                @elseif ($user->group?->is_premium)
                                    <div class="aura aura-gold">
                                        <span class="badge shadow">{{ $user->group?->name }}</span>
                                    </div>
                                @else
                                <span class="badge shadow">{{ $user->group?->name }}</span> @endif
                            </td>
                            <td>
                                <div class="flex gap-1">
                                    <a href="{{ route('edit_user_profile', ['user' => $user]) }}">
                                        <button class="btn btn-square" aria-label="Edit user" title="Edit user">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2.5" stroke="currentColor" class="size-[1.2em]">
                                                <path stroke-linecap="round" stroke-linejoin=" round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg> </button>
                                    </a>
                                    <form method="POST" action="/users/{{ $user->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this user?')"
                                            class="btn btn-square" aria-label="Delete user" title="Delete user">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2.5" stroke="currentColor" class="size-[1.2em]">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-10.978.562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.5a2.25 2.25 0 0 0-2.25-2.25h-3A2.25 2.25 0 0 0 9.272 4.5v.615m9.968 0a48.667 48.667 0 0 0-9.968 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th>0</th>
                            <th>Nothing Here</th>
                            <td>Nothing Here</td>
                            <td>nothinghere@email.com</td>
                            <td>Nothing Here</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div>
                {{ $user_data->links() }}
            </div>
        </div>
    </div>
</x-layout>