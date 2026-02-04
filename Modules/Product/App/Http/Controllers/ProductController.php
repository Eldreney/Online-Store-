<?php

namespace Modules\Product\App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Product\App\Http\Requests\UpdateProductRequest;

use Modules\Product\App\Http\Requests\StoreProductRequest;
use Modules\Product\Repositories\ProductRepositoryInterface;


class ProductController extends Controller
{
  public function __construct(private ProductRepositoryInterface $products) {}

    public function index(Request $request)
    {
        $search = $request->query('search');
        $products = $this->products->paginate($search, 10);

        return view('product::admin.products.index', compact('products', 'search'));
    }

    public function create()
    {

        $categories = \App\Models\Category::where('status',1)->orderBy('name')->get();
        $subCategories = \App\Models\SubCategory::where('status',1)->orderBy('name')->get();
        $brands = \App\Models\Brand::where('status',1)->orderBy('name')->get();

        return view('product::admin.products.create', compact('categories','subCategories','brands'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        if ($request->has('track_qty')) {
            $data['track_qty'] = 'Yes';
        } else {
            $data['track_qty'] = 'No';
            $data['qty'] = null;
        }

        // optional: auto slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $product = $this->products->create($data);

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully.',
            'id' => $product->id
        ]);
    }

    public function edit(int $product)
    {
        $product = $this->products->findOrFail($product);

        $categories = \App\Models\Category::where('status',1)->orderBy('name')->get();
        $subCategories = \App\Models\SubCategory::where('status',1)->orderBy('name')->get();
        $brands = \App\Models\Brand::where('status',1)->orderBy('name')->get();

        return view('product::admin.products.edit', compact('product','categories','subCategories','brands'));
    }

    public function update(UpdateProductRequest $request, int $product)
    {
        $product = $this->products->findOrFail($product);

        $data = $request->validated();

        if ($request->has('track_qty')) {
            $data['track_qty'] = 'Yes';
        } else {
            $data['track_qty'] = 'No';
            $data['qty'] = null;
        }

        $this->products->update($product, $data);

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully.',
        ]);
    }

    public function destroy(int $product)
    {
        $product = $this->products->findOrFail($product);
        $this->products->delete($product);

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully.'
        ]);
    }


    public function uploadMedia(Request $request, int $product)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:4096'
        ]);

        $product = $this->products->findOrFail($product);

        $media = $product
            ->addMediaFromRequest('image')
            ->toMediaCollection('images');

        return response()->json([
            'status' => true,
            'media_id' => $media->id,
            'url' => $media->getUrl(),
            'thumb' => $media->getUrl('thumb'),
        ]);
    }

    public function deleteMedia(Request $request, int $product, int $media)
    {
        $product = $this->products->findOrFail($product);

        $m = $product->media()->where('id', $media)->firstOrFail();
        $m->delete();

        return response()->json(['status' => true]);
    }
}
