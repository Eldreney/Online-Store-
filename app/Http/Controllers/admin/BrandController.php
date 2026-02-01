<?php

namespace App\Http\Controllers\admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
      public function index(Request $request)
    {
        $search = $request->query('search');

        $brands = Brand::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                       ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.brand.list', compact('brands', 'search'));
    }

    public function create()
    {
        return view('admin.brand.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255',
            'slug'   => 'required|string|max:255|unique:brands,slug',
            'status' => 'required|integer|in:0,1',
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        Brand::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Brand created successfully.'
        ]);
    }

    public function edit(Brand $brand)
    {
        return view('admin.brand.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255',
            'slug'   => 'required|string|max:255|unique:brands,slug,' . $brand->id,
            'status' => 'required|integer|in:0,1',
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $brand->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Brand updated successfully.'
        ]);
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully.'
        ]);
    }
}
