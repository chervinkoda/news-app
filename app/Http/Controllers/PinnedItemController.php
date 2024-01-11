<?php

namespace App\Http\Controllers;

use App\Models\PinnedArticle;
use Illuminate\Http\Request;
use App\Http\Requests\StorePinnedItemsRequest;

use Exception;

class PinnedItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PinnedArticle::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePinnedItemsRequest $request)
    {
        try {
            $article = PinnedArticle::where('article_id', $request["article_id"])->first();
            PinnedArticle::create($request->only(['title', 'url', 'date_published', 'article_id']));

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
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
