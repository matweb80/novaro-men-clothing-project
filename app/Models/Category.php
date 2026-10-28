<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'parent'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Mutator برای ساخت slug از name
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug))
                $category->slug = self::generateUniqueSlug($category->name);
            else
                $category->slug = self::generateUniqueSlug($category->slug);
        });

        static::updating(function ($category) {
            if (empty($category->slug))
                $category->slug = self::generateUniqueSlug($category->name, $category->id);
            else
                $category->slug = self::generateUniqueSlug($category->slug, $category->id);
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

    public function child()
    {
        return $this->hasMany(Category::class, 'parent', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
