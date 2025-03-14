<?php

namespace Modules\Posts\Http\Controllers\Api\V1;

use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Modules\Posts\Models\Post;
use App\Http\Controllers\Controller;
use Modules\Posts\Transformers\PostCollection;
use Modules\Posts\Transformers\PostResource;

class PostController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::paginate();
        app()->setLocale('ar');
        return $this->successData(PostCollection::make($posts));
        // return $this->successData(PostResource::collection($posts));
        // return response()->json( Post::filter()->get());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
