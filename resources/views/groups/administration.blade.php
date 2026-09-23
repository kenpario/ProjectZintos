<x-layout>
    <x-slot:title>
        Groups Administration
    </x-slot:title>
    <div class="rounded-box shadow-md p-4">
        <div class="overflow-x-auto rounded-box shadow-md m-2 bg-base-100">
            <table class="table table-zebra">
                <caption class="text-sm font-bold">Groups Table</caption>
                <thead>
                    <tr>
                        <th>Nr.</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Administrator</th>
                        <th>Moderator</th>
                        <th>Premium</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groups_data as $group)
                        <tr>
                            <th>{{ $group->id }}</th>
                            <td>
                                @if ($group->is_admin)
                                    <div class="aura aura-rainbow">
                                        <span class="badge shadow">{{ $group->name }}</span>
                                    </div>
                                @elseif($group->is_mod)
                                    <div class="aura aura-silver">
                                        <span class="badge shadow">{{ $group->name }}</span>
                                    </div>
                                @elseif ($group->is_premium)
                                    <div class="aura aura-gold">
                                        <span class="badge shadow">{{ $group->name }}</span>
                                    </div>
                                @else
                                <span class="badge shadow">{{ $group->name }}</span> @endif
                            </td>
                            <td>{{ $group->description }}</td>
                            <td>
                                <p>{{ $group->is_admin ? 'True' : 'False' }}</p>
                            </td>
                            <td>
                                <p>{{ $group->is_mod ? 'True' : 'False' }}</p>
                            </td>
                            <td>
                                <p>{{ $group->is_premium ? 'True' : 'False' }}</p>
                            </td>

                            <td>
                                <div class="flex gap-1">
                                    <a href="{{ route('edit_groups', ['group' => $group]) }}">
                                        <button class="btn btn-square" aria-label="Edit user" title="Edit user">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2.5" stroke="currentColor" class="size-[1.2em]">
                                                <path stroke-linecap="round" stroke-linejoin=" round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg> </button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th>0</th>
                            <th>Nothing Here</th>
                            <th>Nothing Here</th>
                            <th>Nothing Here</th>
                            <th>Nothing Here</th>
                            <th>Nothing Here</th>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div>
                {{ $groups_data->links() }}
            </div>
        </div>
    </div>
</x-layout>