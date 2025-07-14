<?php

namespace Modules\Product\Repositories;

use Modules\Product\Models\Product;

class ProductRepository
{
    public function handle() {}

    public function findByIdForUpdate(int $productId): Product
    {
        return Product::where('id', $productId)
            ->lockForUpdate()
            ->firstOrFail();
    }

    public function decreaseStock(Product $product, int $quantity): void
    {
        $product->decrement('stock', $quantity);
    }

}
