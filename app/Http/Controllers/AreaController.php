<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Good;
use App\Models\User;
use App\Models\Area;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AreaRequest;
use App\Http\Resources\AreaResource;
use App\Http\Resources\AreaResourceCollection;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Area::class);
    }
    /**
     * Display a listing of the resource.
     *
    @return JsonResponse
     */
    public function index()
    {
        $areas = Area::all();
        return AreaResource::collection($areas->load(['areaCategoies.sales', 'sales']));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param AreaRequest $request
     * @return JsonResponse
     */
    public function store(AreaRequest $request)

    {
        $area = Area::create($request->validated());
        return $this->created(AreaResource::make($area));
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Area  $area
     * @return JsonResponse
     */
    public function show(Area $area)
    {
        return $this->success(AreaResource::make($area)->load(['areaCategoies','sales']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Area $area
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AreaRequest $request, Area $area)
    {
        $area->update($request->validated());
        return $this->success(AreaResource::make($area));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Area $area
     * @param  \App\Models\Good  $good
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Area $area)
    {
         $area->areaCategoies()->each(function (Category $category) use ($area) {$category->categoryGoods()->each(function (Good $good){$good->delete();});

        $area->areaCategoies()->each(function (Category $category){$category->delete();});
        $area->delete();
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    });


}
}
