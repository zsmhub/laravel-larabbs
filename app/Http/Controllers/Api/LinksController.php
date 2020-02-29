<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\Link;
use App\Transformers\LinkTransformer;

class LinksController extends Controller
{
    public function index(Link $link)
    {
        $list = $link->getAllCached();

        return $this->response->collection($list, new LinkTransformer());
    }
}
