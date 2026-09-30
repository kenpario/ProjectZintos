<x-layout>
    <x-slot:title>
        Edit Subcategory
    </x-slot:title>
    <div class="min-h-full w-full">
        <form method="POST" action="/subcategories/{{ $subcategory->id }}" class="mx-auto w-full max-w-md"
            enctype="multipart/form-data"
            onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Adding...';">
            @csrf
            @method('PUT')
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-full border p-6 shadow-md">
                <legend class="fieldset-legend">Add Subcategory</legend>
                <label class="label">Category</label>
                <select class="select w-full" name="post_category_id">
                    <option value="">Select a parent category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('post_category_id', $subcategory->post_category_id) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('post_category_id')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <label class="label">Name</label>
                <input type="text" class="input h-12 w-full max-w-full" placeholder="Name" name="name"
                    value="{{ old('name', $subcategory->name) }}" />
                @error('name')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <label class="label">Description</label>
                <textarea class="textarea h-32 w-full max-w-full" placeholder="Description"
                    name="description">{{ old('description', $subcategory->description) }}</textarea>
                @error('description')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <button type="submit" class="btn bg-base-300 shadow-md mt-4">Update Subcategory</button>
                <input type="hidden" name="back_url" value="{{ old('back_url', $backUrl) }}">
                <a class="btn shadow-md" href="{{ old('back_url', $backUrl) }}">Back</a>
            </fieldset>
        </form>
    </div>
</x-layout>