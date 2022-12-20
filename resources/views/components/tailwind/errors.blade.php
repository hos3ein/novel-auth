@if(count($errors) > 0)
    <div class="mb-4 px-2 py-4 bg-red-300 rounded-md text-sm text-red-700 border border-red-400">
        <ul>
            @foreach($errors->getMessages() as $key => $error)
                @foreach($error as $e)
                    <li>{{ $e }}</li>
                @endforeach
            @endforeach
        </ul>
    </div>
@endif
