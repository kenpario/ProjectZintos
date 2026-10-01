<div class="flex justify-end gap-1">
    <a href="{{ route('edit_subcategories', ['subcategory' => $subcategory]) }}">
        <button class="btn btn-square" aria-label="Edit subsubcategory" title="Edit subsubcategory">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                stroke="currentColor" class="size-[1.2em]">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
            </svg>
        </button>
    </a>
    <div onclick="event.stopPropagation()">
        <button type="button"
            onclick="document.getElementById('delete_subcategory_modal_{{ $subcategory->id }}').showModal()"
            class="btn btn-square" aria-label="Delete subcategory" title="Delete subcategory">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                stroke="currentColor" class="size-[1.2em]">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-10.978.562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.5a2.25 2.25 0 0 0-2.25-2.25h-3A2.25 2.25 0 0 0 9.272 4.5v.615m9.968 0a48.667 48.667 0 0 0-9.968 0" />
            </svg>
        </button>

        <dialog id="delete_subcategory_modal_{{ $subcategory->id }}" class="modal">
            <div class="modal-box">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="text-lg font-bold">Delete subcategory?</h3>
                <p class="py-4">This will permanently delete "{{ $subcategory->name }}". This can't be undone.</p>
                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn">Cancel</button>
                    </form>
                    <button type="submit" form="delete_form_{{ $subcategory->id }}"
                        id="delete_confirm_{{ $subcategory->id }}" class="btn btn-error">Delete</button>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>

        <form id="delete_form_{{ $subcategory->id }}" method="POST" action="/subcategories/{{ $subcategory->id }}"
            onsubmit="const button = document.getElementById('delete_confirm_{{ $subcategory->id }}'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Deleting...';">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>