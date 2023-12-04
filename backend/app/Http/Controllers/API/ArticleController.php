<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $articles = Article::query()
            ->select("id", "title", 'author', 'published_at', 'source', 'from_api', 'category', 'description')
            ->orderBy("created_at", "desc");

        $this->applySearchParams($request, $articles);

        $articles = $articles->paginate(25);
        return response()->json($articles);
    }

    private function applySearchParams(Request $request, Builder $articles)
    {
        if ($request->byFeed === 'true' && $user = auth()->user()) {
            $pref = $user->preferences;
            foreach (['categories', 'authors', 'sources'] as $item) {
                $request->{$item} = !empty($pref[$item]) ? implode(',', $pref[$item]) : '';
            }
        }

        if ($request->search) {
            $articles->where(function ($q) use ($request) {
                $q->where("title", "like", "%" . $request->search . "%")
                    ->orWhereHas('keywords', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }
        if ($request->from) {
            $articles->where("published_at", ">=", now()->parse($request->from));
        }
        if ($request->to) {
            $articles->where("published_at", "<=", now()->parse($request->to));
        }
        if ($request->categories) {
            $categories = explode(',', $request->categories);
            $articles->whereIn("category", $categories);
        }
        if ($request->sources) {
            $sources = explode(',', $request->sources);
            $articles->whereIn("source", $sources);
        }
        if ($request->authors) {
            $authors = explode(',', $request->authors);
            $articles->whereIn("author", $authors);
        }
    }
    /**
     * In case sources got too many depends on the size of them,
     * we can add a new table for it, or we can alter the keywords
     * table also support sources as a new type.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchData(Request $request): JsonResponse
    {
        $data = [];
        $resources = explode(',', $request->resources);
        if (in_array('categories', $resources)) {
            $data['categories'] = Cache::remember('categories', 0, function () {
                return Article::query()->select('category')->groupBy('category')->get()->pluck('category');
            });
        }

        if (in_array('sources', $resources)) {
            $data['sources'] = Cache::remember('sources', 0, function () {
                return Article::query()->select('source')->groupBy('source')->get()->pluck('source');
            });
        }
        if (in_array('authors', $resources)) {
            $data['authors'] = Cache::remember('authors', 0, function () {
                return Article::query()->select('author')->groupBy('author')->get()->pluck('author');
            });
        }
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * @return JsonResponse
     */
    public function show(Article $article)
    {
        // $article->load('keywords');
        $article->keywordss = collect($article->keywords)->pluck('name');

        return response()->json(['article' => $article]);
    }

    /**
     * Show the form for editing the specified resource.
     * @return void
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @return void
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @return void
     */
    public function destroy(Article $article)
    {
        //
    }
}
