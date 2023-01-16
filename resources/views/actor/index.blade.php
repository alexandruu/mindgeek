<x-app-layout>

    <div class="table-responsive">

        <div class="d-flex justify-content-end  bd-highlight">
            <div class="p-2 bd-highlight">
                <form action="{{ route('actor.store') }}" method="post" style="display: inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Import actors from Pornhub</button>
                </form>
            </div>
        </div>

        @if (!empty($error))
            <div class="alert alert-primary" role="alert">
                {{ $error }}
            </div>
        @endif

        <table class="table table-striped table-hover align-middle">
            <thead>
                <th>Name</th>
                <th>Thumbnail</th>
                <th>License</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @forelse ($actors as $actor)
                    <tr>
                        <td>{{ $actor->name }}</td>
                        <td>
                            @if (!empty($actor->thumbnails) && !empty($actor->thumbnails[0]->urls))
                                <img src="{{ $actor->thumbnails[0]->urls[0]->url }}" alt="Poster">
                            @else
                                No image for this actor.
                            @endif
                        </td>
                        <td>{{ $actor->license }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle btn-sm"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item fs-6"
                                            href="{{ route('actor.show', ['actor' => $actor->id]) }}">View details</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No actors.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</x-app-layout>
