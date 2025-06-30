<div id="export" class="shadow">
    <div class="flex justify-between p-4 bg-gray-100">
        <h4>Export/Import Bookmarks</h4>
    </div>

    <div class="p-4">
        <a class="px-3 py-2 bg-orange-500 text-white rounded-md" href="{{ route('bookmarks.export') }}">Export</a>

        @fragment('form')
        <form hx-post="{{ route('bookmarks.import') }}" hx-encoding="multipart/form-data" hx-swap="none">
            <input type="file" name="file" accept=".json">
            <button type="submit">Upload</button>
            @if($errors->has('file'))
                @foreach($errors->get('file') as $error)
                    <span>{{ $error }}</span>
                @endforeach
            @endif
        </form>
        @endfragment
    </div>
</div>
