<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $fillable = ['attribute_id', 'value', 'slug'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Mutator برای ساخت slug از name
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attribute_value) {
            if (empty($attribute_value->slug))
                $attribute_value->slug = self::generateUniqueSlug($attribute_value->value);
            else
                $attribute_value->slug = self::generateUniqueSlug($attribute_value->slug);
        });

        static::updating(function ($attribute_value) {
            if (empty($attribute_value->slug))
                $attribute_value->slug = self::generateUniqueSlug($attribute_value->value, $attribute_value->id);
            else
                $attribute_value->slug = self::generateUniqueSlug($attribute_value->slug, $attribute_value->id);
        });
    }

    public static function generateUniqueSlug($value, $ignoreId = null)
    {
        // حذف فاصله‌های اضافه و تبدیل به -
        $slug = trim(preg_replace('/\s+/', '-', $value), '-');

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

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'attribute_product', 'value_id', 'product_id')->withPivot('attribute_id');
    }
}
