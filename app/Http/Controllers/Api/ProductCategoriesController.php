<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Storeproduct_categoriesRequest;
use App\Http\Requests\Updateproduct_categoriesRequest;
use App\Models\ProductCategories;
use App\Models\Brand;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductCategoriesController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        try {
            $categories = ProductCategories::with(
                'children:id,parent_category_id,brand_id,category_name,created_at,deleted_at',
                'brand:id,name,slug,image,status,description,created_at,deleted_at'
            )
                ->withTrashed()
                ->whereNull('parent_category_id')
                ->get();
            return response()->json([
                'message' => 'Category fetch successfully.',
                'data'    => $categories,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a new category.
     */
    public function store(Request $request)
    {
        try {
            // return response()->json($request->all());
            // $request->validate([
            //     'category_name' => 'required|string|max:255|unique',
            //     'parent_category_id' => 'nullable',
            //     'brand_id' => 'required',
            // ]);
            $request->validate([
                'category_name' => 'required|string|max:255|unique:product_categories,category_name',
                'parent_category_id' => 'nullable|exists:product_categories,id',
            ], [
                'category_name.unique' => 'This category name already exists.',
                'brand_id' => 'required',
            ]);

            $category = ProductCategories::create([
                'parent_category_id' => $request->parent_category_id ?? null,
                'category_name' => $request->category_name,
                'brand_id' => $request->brand_id,
                'created_at' => Carbon::now(),
            ]);

            return response()->json([
                'message' => 'Category store successfully.',
                'data'    => $category,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        try {
            $category = ProductCategories::with('children')
                ->findOrFail($id);

            return response()->json([
                'message' => 'Category fetch successfully.',
                'data'    => $category,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the bulk subCategories.
     */
    public function updateSubCategory(Request $request, $id)
    {
        try {
            $request->validate([
                'category_name' => 'required|string|max:255|unique:product_categories,category_name',
                'parent_category_id' => 'nullable|exists:product_categories,id',
            ], [
                'category_name.unique' => 'This category name already exists.',
            ]);
            
            $category = ProductCategories::where('id', $id)->whereNotNull('parent_category_id')->first();

            if ($category == null) {
                return response()->json([
                    'message' => 'Item is not a SubCategory',
                    'data' => null,
                    'status' => 401,
                ], 401);
            }
            $category->update([
                'category_name' => $request->category_name,
                'updated_at' => Carbon::now(),
            ]);

            return response()->json([
                'message' => 'SubCategory updated successfully.',
                'data'    => $category,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the bulk Categories.
     */
    public function updateCategory(Request $request, $id)
    {
        try {
            $request->validate([
                'category_name' => 'required|string|max:255',
                'parent_category_id' => 'nullable|exists:product_categories,id',
            ]);

            $category = ProductCategories::where('id', $id)->whereNull('parent_category_id')->first();
            if ($category == null) {
                return response()->json([
                    'message' => 'Category not found',
                    'data'    => null,
                    'status' => 200,
                ], 200);
            } else {
                $category->update([
                    'category_name' => $request->category_name,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'message' => 'Category updated successfully.',
                    'data'    => $category,
                    'status' => 200,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the sub-category 
     */
    public function destroySubCategory($category_id, $sub_category_id)
    {
        try {
            $productCategory = ProductCategories::where('parent_category_id', $category_id)
                ->where('id', $sub_category_id)
                ->forceDelete();

            return response()->json([
                'message' => 'SubCategory has been deleted.',
                'data'    => $productCategory,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroyCategory($id)
    {
        try {
            // Checking availability of Parent Category in database.
            $productCategory = ProductCategories::with('children')->withTrashed()
                ->where('id', $id)
                ->whereNull('parent_category_id')
                ->first();

            if ($productCategory == null) {
                return response()->json([
                    'message' => 'ProductCategories does not exists or already deleted.',
                    'data'    => $productCategory,
                    'status' => 400,
                ], 400);
            }

            // Checking each sub-categories where if it is deleted or soft deleted.
            $subCategory = [];
            foreach ($productCategory->children as $data) {
                if ($data->deleted_at == null) {
                    $subCategory[] = $data->id;
                }
            }
            // Deleting category if the sub-category is deleted or soft deleted.
            if (count($subCategory) == 0) {
                $productCategory = ProductCategories::where('id', $id)
                    ->whereNull('parent_category_id')
                    ->forceDelete();
            }
            return response()->json([
                'message' => 'Category has been deleted.',
                'data'    => $productCategory,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified category.
     */
    // public function destroy($id)
    // {
    //     try {
    //         $category = ProductCategories::findOrFail($id);
    //         $category->delete();

    //         return response()->json([
    //             'message' => 'Category deleted successfully.',
    //             'data'    => $category,
    //             'status' => 200,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => $e->getMessage()], 500);
    //     }
    // }
}
