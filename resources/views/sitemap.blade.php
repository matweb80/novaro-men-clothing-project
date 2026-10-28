<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($categories as $cat)
    <url>
        <loc>{{ url('/categories/' . $cat->slug) }}</loc>
        <lastmod>{{ $cat->updated_at->toAtomString() }}</lastmod>
    </url>
    @endforeach

    @foreach($tags as $tag)
    <url>
        <loc>{{ url('/tags/' . $tag->slug) }}</loc>
        <lastmod>{{ $tag->updated_at->toAtomString() }}</lastmod>
    </url>
    @endforeach
</urlset>