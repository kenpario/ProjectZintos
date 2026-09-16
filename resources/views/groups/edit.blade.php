<x-layout>
    <x-slot:title>
        Edit {{ $group->name }}
    </x-slot:title>
    <div class="min-h-full w-full">
        <form method="POST" action="/groups/{{ $group->id }}" class="mx-auto w-full max-w-md"
            enctype="multipart/form-data"
            onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Updating...';">
            @csrf
            @method('PUT')
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-full border p-6 shadow-md">
                <legend class="fieldset-legend">Edit {{ $group->name }} Group</legend>

                <label class="label">Name</label>
                <input type="text" class="input h-12 w-full max-w-full" placeholder="Name" name="name"
                    value="{{ old('name', $group->name) }}" />
                @error('name')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <label class="label">Description</label>
                <textarea class="textarea h-32 w-full max-w-full" placeholder="Description"
                    name="description">{{ old('description', $group->description) }}</textarea>
                @error('description')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror<button type="submit" class="btn bg-base-300 shadow-md mt-4">Update</button>
                <a class="btn" href="{{ route('group_administration') }}">Back</a>
            </fieldset>
        </form>
    </div>
</x-layout>