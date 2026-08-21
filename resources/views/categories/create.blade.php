<x-layout>
    <x-slot:title>
        Add Category
    </x-slot:title>
    <div class="flex min-h-full w-1/2 items-center justify-center">
        <form method="POST" action="/categories" enctype="multipart/form-data">
            @csrf
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
                <legend class="fieldset-legend">Add Category</legend>

                <label class="label">Name</label>
                <input type="text" class="input" placeholder="Name" name="name" value="{{ old('name') }}" />
                @error('name')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <label class="label">Description</label>
                <textarea class="textarea" placeholder="Description"
                    name="description">{{ old('description') }}</textarea>
                @error('description')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <button type="submit" class="btn btn-neutral mt-4">Add Category</button>
                <a class="btn" href="{{ route('dashboard') }}">Back</a>
            </fieldset>
    </div>
    </form>
</x-layout>