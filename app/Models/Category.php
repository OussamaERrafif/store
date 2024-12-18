<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Exception;
class Category extends Model
{
    protected $fillable = ['name'];

    /**
     * Get the products associated with the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    /**
     * Get the total number of products in the category.
     *
     * @return int
     * @throws \Exception
     */
    public function getTotalProductsAttribute()
    {
        try {
            return $this->products()->count();
        } catch (\Exception $e) {
            throw new Exception('Error retrieving total products: ' . $e->getMessage());
        }
    }
}
