<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\SaleRequest;
use App\Http\Resources\SaleResource;
use App\Http\Resources\SaleResourceCollection;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Sale::class);
    }

    /**
     * Display a listing of the resource.
     *
    @return JsonResponse
     */
    public function index()
    {
        $sales = Sale::with('areas', 'categories', 'goods')->paginate();
        return $this->success(SaleResourcecollection::make($sales));
    }

/**
 * Store a newly created resource in storage.
 *
 * @param SaleRequest $request
 * @return JsonResponse
 */
public function store(SaleRequest $request)

{
    $sale = Sale::create($request->validated());
    return $this->created(SaleResource::make($sale));
}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return JsonResponse
     */
    public function show(Sale $sale)
    {
        return $this->success(SaleResource::make($sale)->load(['areas', 'categories', 'goods']));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SaleRequest $request, Sale $sale)
    {
        $sale->update($request->validated());
        return $this->success(SaleResource::make($sale));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }

}
