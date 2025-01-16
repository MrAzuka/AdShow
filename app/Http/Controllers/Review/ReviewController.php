<?php

namespace App\Http\Controllers\Review;

use App\Models\Ads;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateReviewRequest;

class ReviewController extends Controller
{
    /**
     * Display all Reviews on a ad
     */
    public function getAdReview($id)
    {
        try {
            $reviews = Review::where('ad_id', $id)->orderBy('created_at', 'desc')->get();
            return $this->sendSuccessResponse($reviews, "Fetch ad reviews", 200);
        } catch (\Throwable $e) {
            return $this->sendErrorResponse("An error fetching reviews", $e->getMessage(), 500);
        }
    }

    /**
     * Create review for ad
     */
    public function createReview(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'ad_id' => 'required|exists:ads,id',
            'comment' => 'required|string',
            'rating' => 'required|numeric'
        ]);

        if ($validate->fails()) {
            return $this->sendErrorResponse('Validation failed.', $validate->errors()->first(), 422);
        }
        try {
            // Find the ad and check ownership
            $ad = Ads::where('id', $id)->first();

            if (!$ad) {
                return  $this->sendErrorResponse('Ad not found', [], 404);
            }

            $review = Review::create([
                'ad_id' => $id,
                'reviewer_id' => auth()->id(),
                'comment' => $request->comment,
                'rating' => $request->numeric
            ]);

            return $this->sendSuccessResponse($review, 'Review Created Successfully', 201);
        } catch (\Throwable $e) {
            return $this->sendErrorResponse("An error creating review", $e->getMessage(), 500);
        }
    }

    /**
     * Update review for ad
     */
    public function updateReview(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'comment' => 'sometimes|string',
            'rating' => 'sometimes|numeric'
        ]);

        if ($validate->fails()) {
            return $this->sendErrorResponse('Validation failed.', $validate->errors()->first(), 422);
        }
        try {
            // Find the ad and check ownership
            $ad = Ads::where('id', $id)->first();

            if (!$ad) {
                return  $this->sendErrorResponse('Ad not found', [], 404);
            }
            $review = Review::where('ad_id', $id)->where('reviewer_id', auth()->id());
            $review->update($request->all());
            return $this->sendSuccessResponse($review, 'Review Created Successfully', 201);
        } catch (\Throwable $e) {
            return $this->sendErrorResponse("An error creating review", $e->getMessage(), 500);
        }
    }
}
