<?php

namespace Modules\Product\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Product\App\Models\Product;

interface ProductRepositoryInterface
{
    public function paginate(?string $search, int $perPage = 10): LengthAwarePaginator;
    public function findOrFail(int $id): Product;
    public function create(array $data): Product;
    public function update(Product $product, array $data): Product;
    public function delete(Product $product): void;
}
