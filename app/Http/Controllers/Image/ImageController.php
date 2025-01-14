<?php

namespace App\Http\Controllers\Image;

use cloudinary;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    /**
     * Upload Image to store
     */
    public function createImage(Request $request)
    {
        $response = cloudinary()->upload($request->file('file')->getRealPath())->getSecurePath();


        $validate = Validator::make($request->all(), [
            'ad_id' => 'required|exists:ads,id',
        ]);
        // dd($response);
        if ($validate->fails()) {
            return $this->sendErrorResponse('Validation failed.', $validate->errors()->first(), 422);
        }

        try {
            $image = Image::create([
                'ad_id' => $request->ad_id,
                'url' => $response
            ]);
            return $this->sendSuccessResponse($image, 'Image uploaded Successfully', 201);
        } catch (\Throwable $e) {
            return $this->sendErrorResponse('An error occurred while creating the category.', $e->getMessage(), 500);
        }
    }

    /**
     * Delete resource in storage.
     */
    public function deleteImage($id)
    {
        try {
            // Find the ad and check ownership
            $image = Image::where('id', $id)->first();

            if (!$image) {
                return  $this->sendErrorResponse('Image not found', [], 404);
            }
            $imageUrl = $image->url;
            // Remove the Cloudinary base URL and version
            $publicIdWithExtension = preg_replace('/^.*\/upload\/v\d+\//', '', $imageUrl);

            // Remove the file extension to get the public ID
            $publicId = pathinfo($publicIdWithExtension, PATHINFO_FILENAME);

            $deleteCloudImage = cloudinary()->uploadApi()->destroy($publicId);
            if ($deleteCloudImage['result'] !== 'ok') {
                return  $this->sendErrorResponse('Error deleting image from cloude', [], 404);
            }

            // Delete the Image$image
            $image->delete();

            return $this->sendSuccessResponse('', 'Image Deleted Successfully', 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('An error occurred while deleting the ad.', $e->getMessage(), 500);
        }
    }
}
