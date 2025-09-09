@php
    $baseUrl = rtrim(config('app.url'), '/');
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div>
            <span class="relative z-0 inline-flex rtl:flex-row-reverse shadow-sm rounded-md">
                {{-- Previous Page Link --}}
                @if (!$paginator->onFirstPage())
                    <a href="{{ $baseUrl . parse_url($paginator->toArray()['first_page_url'], PHP_URL_PATH) }}"
                        class="rounded-md mr-1 relative hover:bg-mainGreen hover:text-white inline-flex items-center px-4 py-2 -ml-px text-sm font-medium bg-white border border-gray-300 leading-5 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                        Awal
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span aria-disabled="true">
                            <span
                                class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">{{ $element }}</span>
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @php
                                $fixedUrl = $baseUrl . parse_url($url, PHP_URL_PATH) . (parse_url($url, PHP_URL_QUERY) ? '?' . parse_url($url, PHP_URL_QUERY) : '');
                            @endphp

                            @if ($page == $paginator->currentPage())
                                <span aria-current="page">
                                    <span
                                        class="bg-navy relative inline-flex items-center px-4 py-2 -ml-px text-sm font-semibold text-white border border-gray-300 cursor-default leading-5">{{ $page }}</span>
                                </span>
                            @elseif($page == $paginator->currentPage() - 1 || $page == $paginator->currentPage() + 1)
                                <a href="{{ $fixedUrl }}"
                                    class="relative hover:bg-navy hover:text-white inline-flex items-center px-4 py-2 -ml-px text-sm font-medium bg-white border border-gray-300 leading-5 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $baseUrl . parse_url($paginator->toArray()['last_page_url'], PHP_URL_PATH) }}"
                        rel="next"
                        class="relative ml-1 inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md leading-5 hover:bg-mainGreen hover:text-white focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150">
                        ...
                    </a>
                @endif
            </span>
        </div>
        <div>
            <p class="text-sm text-gray-700 leading-5">
                @if ($paginator->firstItem())
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {!! __('-') !!}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                {!! __('dari') !!}
                <span class="font-medium">{{ $paginator->total() }}</span>
            </p>
        </div>
    </nav>
@endif
