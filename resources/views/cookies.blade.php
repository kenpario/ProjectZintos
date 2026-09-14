<x-layout>
    <x-slot:title>
        Cookies Policy
    </x-slot:title>
    <div class="rounded-box shadow-md m-2">
        <label class="text-sm font-bold">How do we use cookies?</label>
        <div class="overflow-x-auto">
            @foreach(Cookies::getCategories() as $category)
                <table class="table table-zebra">
                    <caption>{{ $category->title }}</caption>
                    <div class="divider p-2"></div>
                    <thead>
                        <tr>
                            <th>Cookie</th>
                            <th>Description</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category->getCookies() as $cookie)
                            <tr>
                                <td>{{ $cookie->name }}</td>
                                <td>{{ $cookie->description }}</td>
                                <td>{{ \Carbon\Carbon::now()->diffForHumans(\Carbon\Carbon::now()->addMinutes($cookie->duration), true) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
    <div class="flex justify-end m-2">
        @cookieconsentbutton(action: 'reset', label: 'Reset cookies', attributes: ['id' => 'reset-button', 'class' => 'btn btn-neutral'])
    </div>
</x-layout>