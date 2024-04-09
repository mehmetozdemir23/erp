<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Observers\ProductObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy([ProductObserver::class])]
class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'product_category_id'];

    protected $appends = ['thumbnail_url', 'sales_count', 'revenue', 'last_update'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function stock(): HasOne
    {
        return $this->hasOne(ProductStock::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function thumbnail(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_thumbnail', true);
    }

    protected function thumbnailUrl(): Attribute
    {
        return new Attribute(
            get: fn () => $this->getImageUrl($this->thumbnail)
        );
    }

    protected function getImageUrl(ProductImage $image): string
    {
        return "/storage/product_images/{$this->id}/{$image->path}";
    }

    protected function salesCount(): Attribute
    {
        return new Attribute(
            get: fn () => $this->orderItems->filter(fn ($orderItem) => $orderItem->order->status === OrderStatus::COMPLETED->value)->sum('quantity')
        );
    }

    protected function revenue(): Attribute
    {
        return new Attribute(
            get: fn () => number_format($this->price * $this->salesCount, 2)
        );
    }

    protected function lastUpdate(): Attribute
    {
        return new Attribute(
            get: fn () => Carbon::parse($this->updated_at)->format('M. j, Y')
        );
    }

    public static function totalRevenue(): string
    {
        $totalRevenue = self::query()
            ->selectRaw('SUM(price * order_items.quantity) as total_revenue')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', OrderStatus::COMPLETED)
            ->value('total_revenue') ?? 0.0;

        return number_format($totalRevenue, 2);
    }
}
