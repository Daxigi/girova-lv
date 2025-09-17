<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    public function all(): Collection
    {
        return Category::all();
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Category::find($id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Category::destroy($id);
    }
}