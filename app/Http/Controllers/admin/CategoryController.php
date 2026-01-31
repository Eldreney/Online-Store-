<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;



class CategoryController extends Controller
{

public function index(Request $request)
{
  $search = $request->query('search');

    $categories = Category::query()
        ->when($search, function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('slug', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(10)
        ->appends(['search' => $search]);

    return view('admin.category.list', compact('categories', 'search'));

}


public function create()
{
        return view('admin.category.create');

}

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:categories,slug',
        'status' => 'required|integer|in:0,1',
        'image_id' => 'nullable|integer|exists:temp_images,id',
    ]);

    if (!$validator->passes()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }

    $category = new Category();
    $category->name = $request->name;
    $category->slug = $request->slug;
    $category->status = $request->status;

    // handle image (temp -> uploads + thumb)
    if ($request->filled('image_id')) {

        $tempImage = TempImage::find($request->image_id);

        if ($tempImage) {
            $sPath = public_path('temp/' . $tempImage->name);

            if (File::exists($sPath)) {

                $ext = pathinfo($tempImage->name, PATHINFO_EXTENSION);
                $newImageName = time() . '-' . uniqid() . '.' . $ext;

                $uploadDir = public_path('uploads/categories');
                $thumbDir  = public_path('uploads/categories/thumb');

                if (!File::exists($uploadDir)) {
                    File::makeDirectory($uploadDir, 0755, true);
                }

                if (!File::exists($thumbDir)) {
                    File::makeDirectory($thumbDir, 0755, true);
                }

                // original
                $dPath = $uploadDir . '/' . $newImageName;
                File::copy($sPath, $dPath);

                // thumbnail
                $thumbPath = $thumbDir . '/' . $newImageName;

                // Image::make($sPath)
                //     ->fit(450, 600, function ($constraint) {
                //         $constraint->upsize();
                //     })
                //     ->save($thumbPath, 80);

                $category->image = $newImageName;

                // cleanup temp
                File::delete($sPath);
                $tempImage->delete();
            }
        }
    }

    $category->save();

    return response()->json([
        'status' => true,
        'message' => 'Category created successfully.'
    ]);
}


public function edit($id)
{
        return view('admin.category.edit');

}

public function update(Request $request, $id)
{
    // $request->validate([
    //     'name' => 'required|string|max:255',
    //     'slug' => 'required|string|max:255|unique:category,slug,' . $id,
    //     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     'status' => 'required|integer|in:0,1'
    // ]);

    // $category = Category::findOrFail($id);
    // $category->name = $request->name;
    // $category->slug = $request->slug;
    // $category->status = $request->status;

    // if ($request->hasFile('image')) {
    //     // Delete old image if exists
    //     if ($category->image) {
    //         Storage::disk('public')->delete($category->image);
    //     }
    //     $imagePath = $request->file('image')->store('categories', 'public');
    //     $category->image = $imagePath;
    // }

    // $category->save();

    // return redirect()->route('admin.category.index')->with('success', 'Category updated successfully.');
}


public function destroy($id)
{
    // $category = Category::findOrFail($id);
    // // Delete image if exists
    // if ($category->image) {
    //     Storage::disk('public')->delete($category->image);
    // }
    // $category->delete();

    // return redirect()->route('admin.category.index')->with('success', 'Category deleted successfully.');

}



}
