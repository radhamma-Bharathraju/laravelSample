@extends('layouts.app')

@section('content')
    <h1>Gazette Notice</h1>

    @foreach($paginator as $notice)
        @if(is_array($notice))
            @foreach($notice as $item)
        
                <article class="gazette-notice mb-4 p-3 border rounded">
                    <h2 class="h5">
                        <a href="{{ $item['@href'] ?? '#' }}" target="_blank" rel="noopener noreferrer">
                            {{ $item['@title'] ?? 'Untitled' }}
                        </a> -> <<a href="{{ $item['@href'] ?? '#' }}" target="_blank" rel="noopener noreferrer">
                            {{ $item['@href'] ?? 'Untitled' }}
                        </a>
                    </h2>
                    <time class="text-muted d-block mb-2">
                        {{ !empty($item['publishedDate']) ? \Carbon\Carbon::parse($item['publishedDate'])->format('j F Y') : '' }}
                    </time>
                    <!-- <div class="notice-content">
                        {!! $item['content'] ?? 'No content' !!}
                    </div> -->
                </article>

            @endforeach
        @endif
    @endforeach

    <div class="mt-4">
        {{ $paginator->links('pagination::bootstrap-5') }}
    </div>
@endsection
