<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = ['name', 'slug'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Mutator برای ساخت slug از name
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attribute) {
            if (empty($attribute->slug))
                $attribute->slug = self::generateUniqueSlug($attribute->name);
            else
                $attribute->slug = self::generateUniqueSlug($attribute->slug);
        });

        static::updating(function ($attribute) {
            if (empty($attribute->slug))
                $attribute->slug = self::generateUniqueSlug($attribute->name, $attribute->id);
            else
                $attribute->slug = self::generateUniqueSlug($attribute->slug, $attribute->id);
        });
    }

    public static function generateUniqueSlug($name, $ignoreId = null)
    {
        // حذف فاصله‌های اضافه و تبدیل به -
        $slug = trim(preg_replace('/\s+/', '-', $name), '-');

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

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
