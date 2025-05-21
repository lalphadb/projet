@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center my-4">
        <div class="flex space-x-2">
            {{-- Flèche précédente --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">
                    &larr;
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    &larr;
                </a>
            @endif

            {{-- Numéros de page --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1 bg-blue-500 text-white rounded-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Flèche suivante --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    &rarr;
                </a>
            @else
                <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">
                    &rarr;
                </span>
            @endif
        </div>
    </nav>
@endif
