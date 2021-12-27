<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\ReviewResourceCollection;
use App\Models\Review;
use App\Models\Good;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;



class ReviewController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Review::class);
    }

    /**
     * Display a listing of the resource.
     *
    @return JsonResponse
     */
    public function index(Good $good)
    {
        $reviews = $good->reviews()->paginate();
        return $this->success(ReviewResourceCollection::make($reviews));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ReviewRequest $request
     * @param \App\Models\Good $good
     * @return JsonResponse
     */
    public function store(ReviewRequest $request, Good $good )

    {
        $user=auth()->user();
        if($user && $good->reviews()->where('author_id', $user->id )->exists()){
            return $this->error('You can only leave one comment', 422);
        }
        /** @var Review $review */
        $review  = $good->reviews()->create($request->validated());
        $mark_count = $good->reviews()->count();
        $summa =0;
        foreach ($good->reviews as $review) {
            $summa=$summa+$review->mark;
        }
        $sum_mark = $summa;
        $mark_middle = $sum_mark/$mark_count;
        $good->update(['rating' => $mark_middle]);
        return $this->created(ReviewResource::make($review));
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review $review
     * @return JsonResponse
     */
    public function show(Review $review)
    {
        return $this->success(ReviewResource::make($review)->load(['review_good']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Review $review
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ReviewRequest $request, Review $review)
    {
        $review->update($request->validated());
        return $this->success(ReviewResource::make($review));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Review $review
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }
}
