@php
    use Illuminate\Support\Arr;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $resolveImage = function ($path, ?string $fallback = null): ?string {
        if (blank($path)) {
            return $fallback;
        }

        if (is_array($path)) {
            $path = Arr::first($path);
        }

        if (Str::startsWith((string) $path, ['http://', 'https://', '//'])) {
            return (string) $path;
        }

        if (Storage::disk('public')->exists((string) $path)) {
            return Storage::disk('public')->url((string) $path);
        }

        return asset((string) $path);
    };

    $coverImage = $resolveImage($post->cover_image, asset('grayscale/assets/img/demo-image-01.jpg'));
    $excerpt = Str::limit(strip_tags((string) $post->excerpt), 160);
    $authorName = data_get($post, 'author.name') ?: $post->author_name ?: 'CERAPE';
@endphp

<article class="group overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0_18px_40px_rgba(15,23,42,0.06)] transition hover:-translate-y-1 hover:border-[color-mix(in_srgb,var(--site-primary)_24%,transparent)] hover:shadow-[0_22px_48px_rgba(15,23,42,0.08)]">
    <a href="{{ route('blog.show', $post) }}" class="block h-full">
        <div class="relative aspect-[16/10] overflow-hidden">
            <img src="{{ $coverImage }}" alt="{{ $post->cover_image_alt ?: $post->title }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/55 via-slate-950/10 to-transparent"></div>
            <div class="absolute left-5 top-5 flex flex-wrap gap-2">
                @if (filled($post->category))
                    <span class="rounded-full bg-white px-3 py-1 text-xs font-bold uppercase tracking-[0.24em] text-slate-700 shadow-sm">{{ $post->category }}</span>
                @endif
                @if ($post->is_featured)
                    <span class="rounded-full bg-amber-400 px-3 py-1 text-xs font-bold uppercase tracking-[0.24em] text-slate-950">Destaque</span>
                @endif
            </div>
        </div>
        <div class="space-y-4 p-6">
            <h3 class="font-display text-2xl font-bold tracking-tight text-slate-900 transition group-hover:text-[var(--site-primary)]">
                {{ $post->title }}
            </h3>
            <p class="text-sm leading-7 text-slate-600">{{ $excerpt }}</p>
            <div class="flex items-center justify-between border-t border-slate-200 pt-4 text-sm text-slate-500">
                <span>{{ $authorName }}</span>
                <span>{{ optional($post->published_at)->format('d/m/Y') ?: 'Em breve' }}</span>
            </div>
        </div>
    </a>
</article>
