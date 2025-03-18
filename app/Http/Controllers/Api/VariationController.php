<?php

namespace App\Http\Controllers\Api;

use App\Models\Variation;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorevariationRequest;
use App\Http\Requests\UpdatevariationRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class VariationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $variations = Variation::get();
            return response()->json($variations, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch variations.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // dd($request);
            $validation = $request->validate([
                'category_id' => 'required',
                'name' => 'required|string',
            ]);
            dd(34);
            // $variation = Variation::create([
            //     'category_id' => $request->category_id,
            //     'name' => $request->name,
            //     'created_at' => Carbon::now(),
            // ]);
            // return response()->json($variation, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch variations.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $variation = Variation::find($id);
            if ($variation) {
                $variations = Variation::where('id', $id)->get();

                return response()->json($variation, 200);
            } else {
                return response()->json(['error' => 'Variation not found.', 404]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch variations.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatevariationRequest $request, variation $variation)
    {
        try {
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch variations.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(variation $variation)
    {
        try {
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch variations.', 'message' => $e->getMessage()], 500);
        }
    }
}
