<x-layout>
    <x-slot:title>
        Edit Category
    </x-slot:title>
    <div class="min-h-full w-full">
        <form method="POST" action="/categories/{{ $category->id }}" class="mx-auto w-full max-w-md"
            enctype="multipart/form-data"
            onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Updating...';">
            @csrf
            @method('PUT')
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-full border p-6 shadow-md">
                <legend class="fieldset-legend">Edit {{ $category->name }} Category</legend>

                <label class="label">Name</label>
                <input type="text" class="input h-12 w-full max-w-full" placeholder="Name" name="name"
                    value="{{ old('name', $category->name) }}" />
                @error('name')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <label class="label">Description</label>
                <textarea class="textarea h-32 w-full max-w-full" placeholder="Description"
                    name="description">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <fieldset class="fieldset bg-base-100 border-base-300 rounded-box w-64 border p-4">
                    <legend class="fieldset-legend">Comment Section</legend>
                    <label class="label">
                        <input type="hidden" name="can_comment" value="0" />
                        <input type="checkbox" @checked(old('can_comment', $category->can_comment) == 1) class="checkbox"
                            name="can_comment" value="1" />
                        Can members comment?
                    </label>
                </fieldset>
                <button type="submit" class="btn btn-neutral mt-4">Update</button>
                <a class="btn" href="{{ route('categories') }}">Back</a>
            </fieldset>
        </form>
    </div>
</x-layout>