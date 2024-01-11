<?php

namespace App\Http\Controllers;

use App\Models\PinnedArticle;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use App\Services\ApiService;
use App\Services\PinnedItemsService;

class ArticleController extends Controller
{
    protected $apiService;
    protected $pinnedItemsService;

    public function __construct(ApiService $apiService, PinnedItemsService $pinnedItemsService)
    {
        $this->apiService = $apiService;
        $this->pinnedItemsService = $pinnedItemsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $values = $request->query->all();
        $search = isset($values["search"]) ? $values["search"] : '';
        $page = $request->query('page', 1);

        $pinnedArticles =  $this->pinnedItemsService->fetchData();
        $data = $this->apiService->searchGuardianArticle($search, $page);
        $articles = $data['response']['results'];
        $currentPage = $data['response']['currentPage'];
        $pages = $data['response']['pages'];

        return view('welcome', compact('articles', 'page', 'search', 'currentPage', 'pages', 'pinnedArticles'));
    }
}
