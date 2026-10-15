<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Product extends Model {
 protected $fillable=['category_id','name','slug','sku','short_description','description','price','sale_price','stock','image','colors','sizes','is_active','is_featured'];
 protected $casts=['price'=>'decimal:2','sale_price'=>'decimal:2','colors'=>'array','sizes'=>'array','is_active'=>'boolean','is_featured'=>'boolean'];
 public function category(): BelongsTo { return $this->belongsTo(Category::class); }
 public function getCurrentPriceAttribute(): float { return (float)($this->sale_price ?? $this->price); }
}
