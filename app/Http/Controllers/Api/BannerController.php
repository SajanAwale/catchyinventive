<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorebrandRequest;
use App\Http\Requests\UpdatebrandRequest;
use App\Models\Banner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $banners = Banner::withTrashed()->get();
            return response()->json([
                'message' => 'Banner fetch successfully.',
                'data' => $banners,
                'status' => 200,

            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch banners.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'permalink' => 'required|string',
            ]);
            // $user_id = Auth::user()->id;
            $banner = Banner::get();
            $bannerOrder = $banner->count() + 1;

            $banner = Banner::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description,
                'price' => $request->price,
                'sort_order' => $bannerOrder,
                'created_at' => now(),
            ]);

            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg'
                ]);
                // size validation thumbnail and resize
                $image_path = $request->file('image');
                $fileName = time() . '_' . $image_path->getClientOriginalName();
                $filePath = 'banner/' . $fileName;
                // Store the file in storage file path
                Storage::disk('public')->putFileAs('banner', $image_path, $fileName);
                // Set image path in the database
                $banner->image = $filePath;
            }
            $banner->save();

            return response()->json([
                'message' => 'Banner created sucessfully.',
                'data'    => $banner,
                'status' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create banner.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $banner = Banner::find($id);
            if ($banner) {
                return response()->json([
                    'message' => 'Banner fetch successfully.',
                    'data'    => $banner,
                    'status' => 200,
                ], 200);
            } else {
                return response()->json(['error' => 'Banner not found.'], 404);
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
                'title' => 'required|string',
                'description' => 'required|string',
                'permalink' => 'required|string',
            ]);

            $banner = Banner::findorFail($id);

            // Checking if the request has image file path with update in path 
            // new image is uploaded and path is updated
            if ($request->image != null) {
                $image_path = $request->file('image');
                $fileName = time() . '_' . $image_path->getClientOriginalName();
                $filePath = 'banner/' . $fileName;
                // Store the file in storage file path
                Storage::disk('public')->putFileAs('banner', $image_path, $fileName);
                // Set image path in the database
                $bannerImage = $filePath;
            } else {
                $bannerImage = $banner->image;
            }

            if ($banner) {
                $banner = Banner::where('id', $id)->update([
                    'title' => $request->title,
                    'slug' => Str::slug($request->title),
                    'description' => $request->description,
                    'permalink' => $request->permalink,
                    'price' => $request->price,
                    'status' => $request->status ?? $banner->status,
                    'sort_order' => $banner->sort_order,
                    'image' => $bannerImage,
                    'updated_at' => now(),
                ]);
                return response()->json([
                    'message' => 'Banner updated successfully.',
                    'data'    => $banner,
                    'status' => 200,
                ], 200);
            } else {
                return response()->json(['error' => 'Banner not found.'], 404);
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
            $banner = Banner::find($id)->delete();
            return response()->json([
                'message' => 'Brand deleted successfully.',
                'data'    => $banner,
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
