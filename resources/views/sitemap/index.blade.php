@php $xmlDeclaration = '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
{!! $xmlDeclaration !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 @foreach($staticUrls as $loc)
 <url>
 <loc>{{ $loc }}</loc>
 </url>
 @endforeach
 @foreach($news as $n)
 <url>
 <loc>{{ url('/news/'.$n->slug) }}</loc>
 <lastmod>{{ optional($n->updated_at)->toAtomString() }}</lastmod>
 </url>
 @endforeach
 @foreach($galleries as $g)
 <url>
 <loc>{{ url('/gallery/'.$g->id) }}</loc>
 <lastmod>{{ optional($g->updated_at)->toAtomString() }}</lastmod>
 </url>
 @endforeach
</urlset>