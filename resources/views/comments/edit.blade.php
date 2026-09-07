<x-layout>
    <x-slot:title>
        Edit Comment
    </x-slot:title>
    <div class="min-h-full w-full">
        <form method="POST" action="/comments/{{ $comment->id }}" class="mx-auto w-full max-w-md"
            enctype="multipart/form-data"
            onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Updating...';">
            @csrf
            @method('PUT')
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-full border p-6 shadow-md">
                <legend class="fieldset-legend">Edit Comment</legend>
                <label class="label">Message</label>
                <textarea class="textarea h-32 w-full max-w-full" placeholder="Message"
                    name="message">{{ old('message', $comment->message) }}</textarea>
                @error('message')
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