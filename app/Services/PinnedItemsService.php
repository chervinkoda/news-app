<?php
namespace App\Services;

use App\Models\PinnedArticle;

class PinnedItemsService
{
    public function fetchData()
    {
        return PinnedArticle::all();
    }
}
