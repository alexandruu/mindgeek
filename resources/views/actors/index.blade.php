<x-app-layout>

    <div class="table-responsive">

        <div class="d-flex justify-content-end  bd-highlight">
            <div class="p-2 bd-highlight">
                <form action="{{ route('actors.store') }}" method="post" style="display: inline">
                    @csrf
                    <input type="hidden" name="XDEBUG_SESSION_START" value="mindgeek">
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
                            @if (!empty($actor->url_cache))
                                <img src="{{ $actor->url_cache }}" alt="Poster">
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
                                            href="{{ route('actors.show', ['id' => $actor->id]) }}">View details</a>
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

        {{ $actors->links() }}

    </div>

</x-app-layout>
