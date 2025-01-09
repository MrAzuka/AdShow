<?php

namespace App\Http\Controllers\Ads;

use App\Models\Ads;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdsController extends Controller
{

    /**
     * Display a specific listing of the resource.
     */
    public function showAllAd()
    {
        try {
            // Find the ad and load relationships
            $ad = Ads::with(['user', 'category', 'images', 'messages', 'reviews'])
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->sendSuccessResponse($ad, 'Fetched all ads', 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('An error occurred while fetching the ad.', $e->getMessage(), 500);
        }
    }

    /**
     * Display a specific listing of the resource.
     */
    public function showSpecificAd($id)
    {
        try {
            // Find the ad and load relationships
            $ad = Ads::with(['user', 'category', 'images', 'messages', 'reviews'])->find($id);

            // Check if the ad exists
            if (!$ad) {
                return  $this->sendErrorResponse('Ad not found', [], 404);
            }

            return $this->sendSuccessResponse($ad, 'Fetched ad', 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('An error occurred while fetching the ad.', $e->getMessage(), 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function createAds(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string',
            'price' => 'required|numeric',
            'is_active' => 'boolean',
        ]);

        if ($validate->fails()) {
            return $this->sendErrorResponse('Validation failed.', $validate->errors()->first(), 422);
        }

        try {
            // Create the ad with the authenticated user's ID
            $ad = Ads::create([
                'user_id' => auth()->id(), // Automatically assign user ID
                'title' => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'location' => $request->location,
                'price' => $request->price,
                'is_active' => $request->is_active ?? true, // Default to active if not provided
            ]);

            return $this->sendSuccessResponse($ad, 'Ads Created Successfully', 201);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('An error occurred while creating the ad.', $e->getMessage(), 500);
        }
    }

    /**
     * Update resource in storage.
     */
    public function updateAd(Request $request, $id)
    {

        $validate = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'location' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validate->fails()) {
            return $this->sendErrorResponse('Validation failed.', $validate->errors()->first(), 422);
        }

        try {
            // Find the ad and check ownership
            $ad = Ads::where('id', $id)->where('user_id', auth()->id())->first();

            if (!$ad) {
                return  $this->sendErrorResponse('Ad not found', [], 404);
            }

            // Update the ad
            $ad->update($request->all());

            return $this->sendSuccessResponse($ad, 'Ads Updated Successfully', 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('An error occurred while updating the ad.', $e->getMessage(), 500);
        }
    }

    /**
     * Delete resource in storage.
     */
    public function deleteAd($id)
    {
        try {
            // Find the ad and check ownership
            $ad = Ads::where('id', $id)->where('user_id', auth()->id())->first();

            if (!$ad) {
                return  $this->sendErrorResponse('Ad not found', [], 404);
            }

            // Delete the ad
            $ad->delete();

            return $this->sendSuccessResponse('', 'Ads Deleted Successfully', 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('An error occurred while deleting the ad.', $e->getMessage(), 500);
        }
    }
}
