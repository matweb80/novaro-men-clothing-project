<?php

namespace App\Models;

use App\ProductAttributeValues;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'title',
        'description',
        'short_desc',
        'slug',
        'price',
        'is_on_special_sale',
        'sale_price',
        'sale_start_at',
        'sale_end_at',
        'inventory',
        'sku',
        'sales_count',
        'view_count',
        'image'
    ];

    protected $casts = [
        'sale_start_at' => 'datetime',
        'sale_end_at'   => 'datetime',
    ];

    protected $appends = [
        'is_new',
        'is_special',
        'badge',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Mutator برای ساخت slug از name
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug))
                $product->slug = self::generateUniqueSlug($product->title);
            else
                $product->slug = self::generateUniqueSlug($product->slug);
        });

        static::updating(function ($product) {
            if (empty($product->slug))
                $product->slug = self::generateUniqueSlug($product->title, $product->id);
            else
                $product->slug = self::generateUniqueSlug($product->slug, $product->id);
        });
    }

    public static function generateUniqueSlug($title, $ignoreId = null)
    {
        // حذف فاصله‌های اضافه و تبدیل به -
        $slug = trim(preg_replace('/\s+/', '-', $title), '-');

        // حذف کاراکترهای غیرمجاز (غیر از حروف، عدد و خط فاصله)
        $slug = preg_replace('/[^آ-یA-Za-z0-9\-\_]/u', '', $slug);

        $original = $slug;

        $i = 2;
        while (
            static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }

    // sale price
    public function isOnSale(): bool
    {
        $now = now();

        if (!$this->is_on_special_sale || !$this->sale_price || $this->sale_start_at && $now->lt($this->sale_start_at) || $this->sale_end_at && $now->gt($this->sale_end_at)) {
            $this->is_on_special_sale = false;
            return false;
        }

        $this->is_on_special_sale = true;
        return true;
    }

    public function finalPrice(): int
    {
        return $this->isOnSale()
            ? $this->sale_price
            : $this->price;
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->where('parent_id', 0)->with('child.user', 'user');
    }

    public function approvedComments()
    {
        return $this->morphMany(Comment::class, 'commentable')->where('approved', 1)->where('parent_id', 0)->with('child')->latest();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)->using(ProductAttributeValues::class)->withPivot(['value_id']);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_product', 'product_id', 'value_id')->withPivot('attribute_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    public function scopeFilter($query, array $filters = [])
    {
        // search
        if (!empty($filters['search'])) {
            $query->where('title', 'like', "%{$filters['search']}%");
        }

        // price filter
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        //group attributes filter
        if (!empty($filters['attributes']) && is_array($filters['attributes'])) {
            foreach ($filters['attributes'] as $attributeId => $valueIds) {
                if (empty($valueIds)) {
                    continue;
                }
                $query->whereHas(
                    'attributeValues',
                    function ($q) use ($attributeId, $valueIds) {
                        $q->where('attribute_product.attribute_id', $attributeId)->whereIn('attribute_values.id', $valueIds);
                    }
                );
            }
        }

        if (!empty($filters['special_price'])) {
            $query->whereNotNull('sale_price')
                ->where(function ($q) {
                    $now = now();
                    $q->whereNull('sale_start_at')->orWhere('sale_start_at', '<=', $now);
                })
                ->where(function ($q) {
                    $now = now();
                    $q->whereNull('sale_end_at')->orWhere('sale_end_at', '>=', $now);
                });
        }
        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Sorting
    |--------------------------------------------------------------------------
    */

    public function scopeSort($query, $sort)
    {
        return match ($sort) {
            'cheapest' =>
            $query
                ->orderBy('price', 'asc')
                ->orderBy('id', 'desc'),

            'expensive' =>
            $query
                ->orderBy('price', 'desc')
                ->orderBy('id', 'desc'),

            'newest' =>
            $query
                ->latest('id'),

            'bestselling' =>
            $query
                ->orderBy('sales_count', 'desc')
                ->orderBy('id', 'desc'),

            'discount' =>
            $query
                ->whereNotNull('sale_price')
                ->orderByRaw(
                    '(price - sale_price) / NULLIF(price, 0) DESC'
                )
                ->orderByDesc('id'),

            default =>
            $query->latest('id'),
        };
    }

    public function getIsNewAttribute(): bool
    {
        return $this->created_at
            && $this->created_at->gte(
                now()->subHours(config('shop.product_new_hours', 48))
            );
    }

    public function getIsSpecialAttribute(): bool
    {
        if (is_null($this->sale_price)) {
            return false;
        }

        $now = now();

        if ($this->sale_start_at && $this->sale_start_at->isFuture()) {
            return false;
        }

        if ($this->sale_end_at && $this->sale_end_at->isPast()) {
            return false;
        }

        return true;
    }

    public function getBadgeAttribute()
    {
        /*
    |--------------------------------------------------------------------------
    | 1. Special
    |--------------------------------------------------------------------------
    | Special always has the highest priority.
    */
        if ($this->is_special) {
            return [
                'name' => 'ویژه',
                'slug' => 'special',
                'color' => '#C8A96A',
            ];
        }

        /*
    |--------------------------------------------------------------------------
    | 2. New
    |--------------------------------------------------------------------------
    */
        if ($this->is_new) {
            return [
                'name' => 'جدید',
                'slug' => 'new',
                'color' => '#16A34A',
            ];
        }

        /*
    |--------------------------------------------------------------------------
    | 3. Manual badge
    |--------------------------------------------------------------------------
    */
        $badge = $this->badges->first();

        if (!$badge) {
            return null;
        }

        return [
            'name' => $badge->name,
            'slug' => $badge->slug,
            'color' => $badge->color,
        ];
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function gallery()
    {
        return $this->hasMany(ProductGallery::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class)
            ->where('is_active', true)
            ->orderByDesc('priority');
    }
}
