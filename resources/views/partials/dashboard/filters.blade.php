@props(['queryTags', 'querySites'])

<form
    class="mb-2 flex flex-wrap gap-2"
    id="filters"
>
    <input
        class="hidden"
        id="title"
        name="title"
        type="text"
        value="{{ request('title') }}"
    >
    @if (!empty($queryTags))
        @foreach ($queryTags as $qtag)
            @include('partials.dashboard.filters.tag', ['tag' => $qtag])
        @endforeach
    @endif
    @if (!empty($querySites))
        @foreach ($querySites as $qsite)
            @include('partials.dashboard.filters.site', ['site' => $qsite])
        @endforeach
    @endif
</form>
