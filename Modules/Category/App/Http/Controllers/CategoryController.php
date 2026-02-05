<?php

namespace Modules\Category\App\Http\Controllers;

use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Modules\Category\App\Models\Category;


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

    return view('category::admin.category.list', compact('categories', 'search'));

}


public function create()
{
        return view('category::admin.category.create');

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


public function edit(Category $category)
{
    return view('admin.category.edit', compact('category'));
}

public function update(Request $request, Category $category)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
        'status' => 'required|integer|in:0,1',
        'image_id' => 'nullable|integer|exists:temp_images,id',
    ]);

    if (!$validator->passes()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }

    $category->name = $request->name;
    $category->slug = $request->slug;
    $category->status = $request->status;


    if ($request->filled('image_id')) {
        $tempImage = TempImage::find($request->image_id);

        if ($tempImage) {
            $sPath = public_path('temp/' . $tempImage->name);

            if (File::exists($sPath)) {


                if (!empty($category->image)) {
                    $oldMain = public_path('uploads/categories/' . $category->image);
                    $oldThumb = public_path('uploads/categories/thumb/' . $category->image);

                    if (File::exists($oldMain)) File::delete($oldMain);
                    if (File::exists($oldThumb)) File::delete($oldThumb);
                }

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


                File::copy($sPath, $uploadDir . '/' . $newImageName);



                $category->image = $newImageName;


                File::delete($sPath);
                $tempImage->delete();
            }
        }
    }

    $category->save();

    return response()->json([
        'status' => true,
        'message' => 'Category updated successfully.'
    ]);
}
public function destroy(Category $category)
{

    if (!empty($category->image)) {
        $main = public_path('uploads/categories/' . $category->image);
        $thumb = public_path('uploads/categories/thumb/' . $category->image);

        if (File::exists($main)) File::delete($main);
        if (File::exists($thumb)) File::delete($thumb);
    }

    $category->delete();

    return response()->json([
        'status' => true,
        'message' => 'Category deleted successfully.'
    ]);
}


}
