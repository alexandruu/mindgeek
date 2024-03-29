<x-app-layout>

    <div class="table-responsive">

        <div class="d-flex justify-content bd-highlight">
            <div class="p-2 bd-highlight">
                <a href="{{ route('actors.index') }}" class="btn btn-outline-danger">Back</a>
            </div>
        </div>

        <table class="table table-striped table-hover align-middle">
            <tr>
                <td>Name:</td>
                <td>{{ $actor->name }}</td>
            </tr>
            <tr>
                <td>Thumbnail:</td>
                <td>
                    @if ($actor->thumbnails !== null && $actor->thumbnails[0]->urls[0]->url_cache)
                        <img src="../{{ $actor->thumbnails[0]->urls[0]->url_cache }}" alt="Poster">
                    @else
                        No image for this actor.
                    @endif
                </td>
            </tr>
            <tr>
                <td>License:</td>
                <td>{{ $actor->license }}</td>
            </tr>
        </table>

    </div>

</x-app-layout>
