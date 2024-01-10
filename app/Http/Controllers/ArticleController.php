<?php

namespace App\Http\Controllers;

use App\Models\PinnedArticle;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use App\Services\ApiService;

class ArticleController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $values = $request->query->all();
        $pinnedArticles = PinnedArticle::all();
        $search = isset($values["search"]) ? $values["search"] : '';
        $page = $request->query('page', 1);

        $data = $this->apiService->searchGuardianArticle($search, $page);
        $articles = $data['response']['results'];
        $currentPage = $data['response']['currentPage'];
        $pages = $data['response']['pages'];

        return view('welcome', compact('articles', 'page', 'search', 'currentPage', 'pages', 'pinnedArticles'));
    }

    public function pinItem(Request $request)
    {
        try {
            $article = PinnedArticle::where('article_id', $request["article_id"])->first();
            $request->validate([
                'title' => [
                    'required',
                    'string',
                    Rule::unique('pinned_articles')->ignore($article->article_id ?? null),
                ],
                'url' => 'required|string',
                'date_published' => 'required|string',

            ]);

            PinnedArticle::create($request->only(['title', 'url', 'date_published', 'article_id']));
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }

    }

    public function unpinItem($id)
    {
        try {
            $pinnedArticle = PinnedArticle::find($id);
            $pinnedArticle->delete();
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

}
