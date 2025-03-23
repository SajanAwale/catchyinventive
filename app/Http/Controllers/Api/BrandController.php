<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorebrandRequest;
use App\Http\Requests\UpdatebrandRequest;
use App\Models\brand;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $brands = Brand::withTrashed()->get();
            return response()->json([
                'message' => 'Brand fetch successfully.',
                'data' => $brands,
                'status' => 200,

            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch brands.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = $request->validate([
                'name' => 'required|string',
                'slug' => 'required|unique:brands,slug',
                'description' => 'required|string',
            ]);
            // $user_id = Auth::user()->id;
            $brand = Brand::get();
            $brandOrder = $brand->count() + 1;

            $brand = Brand::create([
                'name' => $request->name,
                // 'slug' => St::slug($request->name),
                'slug' => $request->slug,
                'description' => $request->description,
                'permalink' => $request->permalink,
                'description' => $request->description,
                'is_featured' => $request->is_featured ?? 1, // 1 is false where 0 is true
                'status' => $request->status ?? 0,
                'sort_order' => $brandOrder,
                'created_at' => now(),
            ]);
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg'
                ]);
                $image_path = $request->file('image');
                $fileName = time() . '_' . $image_path->getClientOriginalName();
                $filePath = 'brand/' . $fileName;
                // Store the file in storage file path
                Storage::disk('public')->putFileAs('brand', $image_path, $fileName);
                // Set image path in the database
                $brand->image = $filePath;
            }
            $brand->save();

            return response()->json([
                'message' => 'Brand created sucessfully.',
                'data'    => $brand,
                'status' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create product.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $brand = Brand::find($id);
            if ($brand) {
                return response()->json([
                    'message' => 'Brand fetch successfully.',
                    'data'    => $brand,
                    'status' => 200,
                ], 200);
            } else {
                return response()->json(['error' => 'Brand not found.'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch product.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
            ]);

            $brand = Brand::findorFail($id);

            // Checking if the request has image file path with update in path 
            // new image is uploaded and path is updated
            if ($request->image != null) {
                $image_path = $request->file('image');
                $fileName = time() . '_' . $image_path->getClientOriginalName();
                $filePath = 'brand/' . $fileName;
                // Store the file in storage file path
                Storage::disk('public')->putFileAs('brand', $image_path, $fileName);
                // Set image path in the database
                $brandImage = $filePath;
            } else {
                $brandImage = $brand->image;
            }

            if ($brand) {
                $brand = Brand::where('id', $id)->update([
                    'name' => $request->name,
                    // 'slug' => Str::slug($request->name),
                    'slug' => $request->slug,
                    'description' => $request->description,
                    'permalink' => $request->permalink,
                    'description' => $request->description,
                    'is_featured' => $request->is_featured ?? 1,
                    'status' => $request->status ?? 0,
                    'sort_order' => $brand->sort_order,
                    'image' => $brandImage,
                    'updated_at' => now(),
                ]);
                return response()->json([
                    'message' => 'Brand updated successfully.',
                    'data'    => $brand,
                    'status' => 200,
                ], 200);
            } else {
                return response()->json(['error' => 'Brand not found.'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update product.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $brand = Brand::find($id)->delete();
            return response()->json([
                'message' => 'Brand deleted successfully.',
                'data'    => $brand,
                'status' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete product.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Recovered the particular Brand Information.
     */
    public function restore($id)
    {
        try {
            Brand::withTrashed()->find($id)->restore();
            return response()->json([
                'message' => 'Brand restored successfully.',
                'status' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => '', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Completely removed the Brand Information.
     */
    public function forceDelete($id)
    {
        try {
            Brand::withTrashed()->find($id)->forceDelete();
            return response()->json([
                'message' => 'Brand force Delete successfully.',
                'status' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => '', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the status of the Brand active for use or not
     */
    public function statusUpdate($id, Request $request)
    {
        try {
            $brand = Brand::find($id)->update([
                'status' => $request->status,
            ]);
            return response()->json([
                'message' => 'Brand status updated successfully.',
                'data' => $brand,
                'status' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
