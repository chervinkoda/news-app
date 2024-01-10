<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body class="antialiased p-10">
    <h1 class="text-[40px] font-semibold">Guardian API Articles</h1>
    <div class="bg-white p-6 rounded shadow-md">
        <label for="input" class="text-gray-700">Search:</label>
        <form id="searchForm" action="/" method="get">
            <div class="flex items-center">
                <input type="text" id="search" name="search"
                    class="border border-blue-500 p-3 w-full rounded-l-md focus:outline-none focus:border-blue-500"
                    placeholder="Type here..." value="{{ $search }}" />
                <button type="submit"
                    class="bg-blue-500 text-white p-3 px-10 rounded-r-md hover:bg-blue-600 focus:outline-none"
                    onclick="return validateSearch()">
                    Search
                </button>
            </div>
        </form>
    </div>
    <div class="bg-white p-6 rounded shadow-md mt-5">
        <label for="input" class="text-gray-700 font-semibold text-lg">Results:</label>
        @if (count($articles) > 0)
            <div id="searchResultsContainer">
                @foreach ($articles as $article)
                    <div>
                        <div class="p-4 flex justify-between items-center">
                            <div>
                                <h2 class="font-bold">{{ $article['webTitle'] }}</h2>
                                <a target="_blank" href="{{ $article['webUrl'] }}"
                                    class="text-blue-700 underline">{{ $article['webUrl'] }}</a>
                                <p>{{ \Carbon\Carbon::parse($article['webPublicationDate'])->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="px-10 h-10 w-10">
                                @php
                                    $articleIds = $pinnedArticles->pluck('article_id')->toArray();
                                @endphp
                                @if (!in_array($article['id'], $articleIds))
                                    <button class="bg-blue-400 rounded-full h-10 w-10 text-lg"
                                        onclick="pinItem('{{ $article['webTitle'] }}', '{{ $article['webUrl'] }}', '{{ $article['webPublicationDate'] }}',  '{{ $article['id'] }}')">📌</button>
                                @endif
                            </div>
                        </div>
                        <div class="border"></div>
                    </div>
                @endforeach
            </div>
            <p class="my-10">Page {{ $currentPage }} of {{ $pages }}</p>
        @else
            <h2 class="font-bold text-center text-lg">No results were shown</h2>
        @endif

        @isset($pinnedArticles)
            <div class="pb-10">
                @if (count($pinnedArticles) > 0)
                    <p class="text-gray-700 font-semibold text-lg">Pinned Items:</p>
                @endif
                @foreach ($pinnedArticles as $pinnedArticle)
                    <div class="flex gap-2 py-1">
                        <button class="bg-red-500 rounded-full hover:bg-red-600 text-white font-bold px-2"
                            onclick="unpinItem('{{ $pinnedArticle->id }}')">
                            -
                        </button>
                        <p>{{ $pinnedArticle->title }}</p>
                    </div>
                @endforeach
            </div>
        @endisset
        <div>
            @if ($currentPage > 1)
                <a href="{{ url('/?search=' . $search . '&page=' . $currentPage - 1) }}"
                    class="w-24 rounded-md bg-blue-500 text-white p-3 hover:bg-blue-600 focus:outline-none">Previous</a>
            @endif

            @if ($currentPage < $pages)
                <a href="{{ url('/?search=' . $search . '&page=' . $currentPage + 1) }}"
                    class="w-24 rounded-md bg-blue-500 text-white p-3 hover:bg-blue-600 focus:outline-none">Next</a>
            @endif
        </div>



    </div>

    <script>
        function validateSearch() {
            var searchInput = document.getElementById('search');
            var searchTerm = searchInput.value.trim();

            if (searchTerm.length < 1) {
                alert('Please fill in the search field.');
                return false; // Prevent form submission
            }

            return true; // Allow form submission
        }

        function pinItem(title, url, publicationDate, articleId) {
            axios.post('/api/pin-item', {
                    title: title,
                    url: url,
                    date_published: publicationDate,
                    article_id: articleId
                }, {
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    console.log(response.data);
                    if (response.data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Axios error:', error);
                });
        }

        function unpinItem(id) {
            axios.delete(`/api/unpin-item/${id}`)
                .then(response => {
                    console.log(response.data);
                    if (response.data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Axios error:', error);
                });
        }
    </script>
</body>

</html>
