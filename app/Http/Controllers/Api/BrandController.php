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
            $brands = Brand::all();
            return response()->json($brands, 200);
            // return BrandResource::collection($brands);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch products.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
        ]);
        dd($validator);
        // if ($validator->fails()) {
        //     return response()->json([
        //         'message' => 'all fields are required',
        //         'error' => $validator->messages()
        //     ], 422);
        // }
        try {
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
                'is_featured' => $request->is_featured,
                'status' => $request->status,
                'sort_order' => $brandOrder,
                'created_at' => now(),
                'updated_at' => now(),
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
                'message' => 'Brand created successfully.',
                'data'    => $brand,
            ], 201);
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
                return response()->json($brand, 200);
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
                    'is_featured' => $request->is_featured,
                    'status' => $request->status,
                    'sort_order' => $request->sort_order,
                    'image' => $brandImage,
                    'updated_at' => now(),
                ]);


                return response()->json([
                    'message' => 'Brand updated successfully.',
                    'data'    => $brand,
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
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete product.', 'message' => $e->getMessage()], 500);
        }
    }
}
