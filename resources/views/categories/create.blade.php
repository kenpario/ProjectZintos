<x-layout>
    <x-slot:title>
        Add Category
    </x-slot:title>
    <div class="min-h-full w-full">
        <form method="POST" action="/categories" class="mx-auto w-full max-w-md" enctype="multipart/form-data"
            onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Adding...';">
            @csrf
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-full border p-6 shadow-md">
                <legend class="fieldset-legend">Add Category</legend>

                <label class="label">Name</label>
                <input type="text" class="input h-12 w-full max-w-full" placeholder="Name" name="name"
                    value="{{ old('name') }}" />
                @error('name')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <label class="label">Description</label>
                <textarea class="textarea h-32 w-full max-w-full" placeholder="Description"
                    name="description">{{ old('description') }}</textarea>
                @error('description')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <fieldset class="fieldset bg-base-100 border-base-300 rounded-box border p-4">
                    <legend class="fieldset-legend">Comment Section</legend>
                    <label class="label">
                        <input type="hidden" name="can_comment" value="0" />
                        <input type="checkbox" checked="checked" class="checkbox" name="can_comment" value="1" />
                        Can members comment?
                    </label>
                </fieldset>
                <button type="submit" class="btn bg-base-300 shadow-md mt-4">Add Category</button>
                <a class="btn" href="{{ route('categories') }}">Back</a>
            </fieldset>
        </form>
    </div>
</x-layout>