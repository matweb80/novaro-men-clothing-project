<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
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

        static::creating(function ($tag) {
            if (empty($tag->slug))
                $tag->slug = self::generateUniqueSlug($tag->name);
            else
                $tag->slug = self::generateUniqueSlug($tag->slug);
        });

        static::updating(function ($tag) {
            if (empty($tag->slug))
                $tag->slug = self::generateUniqueSlug($tag->name, $tag->id);
            else
                $tag->slug = self::generateUniqueSlug($tag->slug, $tag->id);
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

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
