<?php

namespace Modules\Brand\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Brand\App\Models\Brand;
use Modules\Brand\App\Http\Requests\StoreBrandRequest;
use Modules\Brand\App\Http\Requests\UpdateBrandRequest;

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

        return view('brand::admin.list', compact('brands', 'search'));
    }

    public function create()
    {
        return view('brand::admin.create');
    }

    public function store(StoreBrandRequest $request)
    {
        Brand::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Brand created successfully.'
        ]);
    }

    public function edit(Brand $brand)
    {
        return view('brand::admin.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand->update($request->validated());

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
