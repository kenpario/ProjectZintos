<x-layout>
    <x-slot:title>
        Edit {{ $user->name }}'s Profile
    </x-slot:title>
    <div class="min-h-full w-full">
        <form method="POST" action="/users/{{ $user->id }}" class="mx-auto w-full max-w-md"
            enctype="multipart/form-data"
            onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Updating...';">
            @csrf
            @method('PUT')
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-full border p-6 shadow-md">
                <legend class="fieldset-legend">Edit {{ $user->name }}'s Profile</legend>

                <label class="label" for="avatar">Avatar</label>
                @if ($user->avatar)
                    <div class="mb-2 flex justify-center">
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}'s avatar"
                            class="w-[150px] h-[150px] object-cover rounded-full shadow-md m-2" />
                    </div>
                @else
                    <div class="mb-2 flex justify-center">
                        <div class="w-20 rounded-full shadow">
                            <img src="https://img.daisyui.com/images/profile/demo/superperson@192.webp"
                                alt="{{ $user->name }}'s avatar"
                                class="w-[150px] h-[150px] object-cover rounded-full shadow-md m-2" />
                        </div>
                    </div>
                @endif
                <input id="avatar" type="file" class="file-input w-full" name="avatar"
                    accept="image/jpeg,image/png,image/gif, image/jpg" />
                @error('avatar')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror

                <label class="label">Name</label>
                <input type="text" class="input h-12 w-full max-w-full" placeholder="Name" name="name"
                    value="{{ old('name', $user->name) }}" />
                @error('name')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <label class="label">Email</label>
                <input type="text" placeholder="Email" class="input" disabled value="{{ $user->email }}" />
                <label class="label">Biography</label>
                <textarea class="textarea h-32 w-full max-w-full" placeholder="Biography"
                    name="bio">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <button type="submit" class="btn btn-neutral mt-4">Update</button>
                <a class="btn" href="{{ route('dashboard') }}">Back</a>
            </fieldset>
        </form>
    </div>
</x-layout>