<?php

namespace Modules\Product\Repositories;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Product\App\Models\Product;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function paginate(?string $search, int $perPage = 10): LengthAwarePaginator
    {
        return Product::query()
            ->when($search, function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->appends(['search' => $search]);
    }

    public function findOrFail(int $id): Product
    {
        return Product::findOrFail($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product): void
    {
        $product->clearMediaCollection('images');
        $product->delete();
    }
}
