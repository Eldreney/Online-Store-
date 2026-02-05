<?php

namespace Modules\Category\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Category\App\Models\Category;
use Modules\Category\App\Models\SubCategory;

class SubCategoryController extends Controller
{

 public function index(Request $request)
{
    $search = $request->query('search');
    $categoryId = $request->query('category_id');

    $categories = Category::orderBy('name')->get();

    $subcategories = SubCategory::with('category')
        ->when($categoryId, function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        })
        ->when($search, function ($q) use ($search) {
            $q->where(function($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                   ->orWhere('slug', 'like', "%{$search}%")
                   ->orWhereHas('category', function ($cq) use ($search) {
                       $cq->where('name', 'like', "%{$search}%");
                   });
            });
        })
        ->latest()
        ->paginate(10)
        ->appends([
            'search' => $search,
            'category_id' => $categoryId
        ]);

    return view('category::admin.subcategory.list', compact('subcategories', 'categories', 'search', 'categoryId'));
}

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('category::admin.subcategory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:sub_categories,slug',
            'status' => 'required|integer|in:0,1',
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        SubCategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Sub Category created successfully.'
        ]);
    }

    public function edit(SubCategory $subcategory)
    {
        $categories = Category::orderBy('name')->get();
        return view('category::admin.subcategory.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, SubCategory $subcategory)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:sub_categories,slug,' . $subcategory->id,
            'status' => 'required|integer|in:0,1',
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $subcategory->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Sub Category updated successfully.'
        ]);
    }

    public function destroy(SubCategory $subcategory)
    {
        $subcategory->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sub Category deleted successfully.'
        ]);
    }
}
