<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display all Categories
     */
    public function showAllCategory()
    {
        try {
            $category = Category::all()->orderBy('name', 'asc')->get();
            return $this->sendSuccessResponse($category, "Fetch all category", 200);
        } catch (\Throwable $e) {
            return $this->sendErrorResponse("An error fetching list of category", $e->getMessage(), 500);
        }
    }

    /**
     * Creating Category to store
     */
    public function createCategory(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string'
        ]);

        if ($validate->fails()) {
            return $this->sendErrorResponse('Validation failed.', $validate->errors()->first(), 422);
        }

        try {
            $category = Category::create([
                'name' => $request->name,
                'slug' => $request->slug
            ]);
            return $this->sendSuccessResponse($category, 'Category Created Successfully', 201);
        } catch (\Throwable $e) {
            return $this->sendErrorResponse('An error occurred while creating the category.', $e->getMessage(), 500);
        }
    }

    /**
     * Update resource in storage.
     */
    public function updateCategory(Request $request, $id)
    {

        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string'
        ]);

        if ($validate->fails()) {
            return $this->sendErrorResponse('Validation failed.', $validate->errors()->first(), 422);
        }

        try {
            // Find the ad and check ownership
            $category = Category::where('id', $id)->first();

            if (!$category) {
                return  $this->sendErrorResponse('Category not found', [], 404);
            }

            // Update the ca$category
            $category->update($request->all());

            return $this->sendSuccessResponse($category, 'Category Updated Successfully', 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('An error occurred while updating the category.', $e->getMessage(), 500);
        }
    }
}
