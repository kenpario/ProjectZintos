<x-layout>
    <x-slot:title>
        Edit {{ $post->title }}
    </x-slot:title>
    <div class="min-h-full w-full">
        <form method="POST" action="/posts/{{ $post->id }}" class="mx-auto w-full max-w-md"
            enctype="multipart/form-data"
            onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Updating...';">
            @csrf
            @method('PUT')
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-full border p-6 shadow-md">
                <legend class="fieldset-legend">New Post</legend>
                <div class="flex justify-center items-center">
                    @if ($post->media)
                        @if ($post->isVideo())
                            <video controls class="w-[250px] h-[250px] object-cover rounded shadow-md">
                                <source src="{{ asset('storage/' . $post->media) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <img src="{{ $post->media ? asset('storage/' . $post->media) : '' }}" alt="Post Media"
                                class="w-[250px] h-[250px] object-cover rounded shadow-md" />
                        @endif
                    @endif
                </div>
                <label class="label" for="media">Media</label>
                <input id="media" type="file" class="file-input w-full" name="media" />
                @error('media')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <label class="label">Category</label>
                <select class="select w-full" name="post_category_id">
                    <option value="">Select a Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('post_category_id', $category->id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('post_category_id')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <label class="label">Title</label>
                <input type="text" class="input h-12 w-full max-w-full" placeholder="Title" name="title"
                    value="{{ old('title', $post->title) }}" />
                @error('title')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <label class="label">Message</label>
                <textarea class="textarea h-32 w-full max-w-full" placeholder="Message"
                    name="message">{{ old('message', $post->message) }}</textarea>
                @error('message')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <button type="submit" class="btn btn-neutral mt-4">Update</button>
                <a class="btn" href="{{ route('categories') }}">Back</a>
            </fieldset>
        </form>
    </div>
</x-layout>