<div class="min-h-full w-full mb-4">
    <form method="POST" action="{{ route('comment_posts', $post) }}" class="mx-auto w-full max-w-md"
        enctype="multipart/form-data"
        onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Adding...';">
        @csrf
        <fieldset class="fieldset bg-base-100 border-base-300 rounded-box w-full border p-6 shadow-md">
            <legend class="fieldset-legend">Write a comment</legend>
            <label class="label">Message</label>
            <textarea class="textarea h-32 w-full max-w-full" placeholder="Message"
                name="message">{{ old('message') }}</textarea>
            @error('message')
                <div class="label">
                    <span class="label-text-alt text-error">{{ $message }}</span>
                </div>
            @enderror
            <button type="submit" class="btn bg-base-300 shadow-md mt-4">Add</button>
        </fieldset>
    </form>
</div>