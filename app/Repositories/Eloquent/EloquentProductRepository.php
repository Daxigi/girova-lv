<?php
namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function find(int $id): ?Product
    {
        return Product::find($id);
    }

    public function all(): Collection
    {
        return Product::all();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Product::find($id)->update($data) ?? false;
    }

    public function delete(int $id): bool
    {
        return Product::destroy($id);
    }
}