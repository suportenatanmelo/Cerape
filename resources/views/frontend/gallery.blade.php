@extends('frontend.layout')

@section('content')
    @php
        $totalImages = $categories->sum(fn ($category) => $category->items->count());
    @endphp

    <section class="gallery-hero" style="padding:150px 0 60px;text-align:center;color:#fff;background:linear-gradient(180deg, rgba(20,40,35,.8), rgba(20,40,35,.62)), url('{{ optional($categories->firstWhere('image_path'))->imageUrl() ?: 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?q=80&w=1600&auto=format&fit=crop' }}') center/cover;">
        <div class="wrap">
            <span class="eyebrow" style="color:var(--amber-soft);">Nosso espaço</span>
            <h1 style="color:#fff;margin:14px 0 12px;font-size:clamp(2rem,4vw,2.7rem);">Galeria completa</h1>
            <p style="color:#EFE9DD;max-width:520px;margin:0 auto;">Conheça cada cantinho da casa, organizado por categoria e quantidade de fotos.</p>
        </div>
    </section>

    <div class="wrap" style="padding: 40px 0 70px;">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:28px;">
            <div>
                <span class="eyebrow">Acervo</span>
                <h2 style="margin:10px 0 8px;">{{ $categories->count() }} categorias</h2>
                <p style="margin:0;color:#5b645f;">{{ $totalImages }} fotos organizadas no portal.</p>
            </div>
            <a href="{{ route('home') }}#topo" style="display:inline-flex;align-items:center;padding:12px 18px;border:1px solid #d7d1c5;border-radius:999px;color:#17312d;text-decoration:none;background:#fff;">Voltar para o site</a>
        </div>

        @forelse ($categories as $category)
            @php
                $images = $category->items;
                $galleryId = 'gallery-' . $category->getKey();
            @endphp
            <section class="cat-section" id="{{ $category->slug }}" data-section style="padding:64px 0;border-bottom:1px solid #e5ddd0;">
                <div class="cat-head" style="display:flex;align-items:baseline;justify-content:space-between;gap:10px;flex-wrap:wrap;margin-bottom:28px;">
                    <div>
                        <h2 style="font-size:1.5rem;margin:0;">{{ $category->name }}</h2>
                        <p style="margin:8px 0 0;color:#68716c;">Ordem {{ $category->position ?? '-' }}</p>
                    </div>
                    <span style="font-size:.85rem;color:#68716c;">{{ $images->count() }} fotos</span>
                </div>

                @if ($category->imageUrl())
                    <div style="margin-bottom:22px;max-width:420px;">
                        <img src="{{ $category->imageUrl() }}" alt="{{ $category->name }}" style="width:100%;height:240px;object-fit:cover;border-radius:24px;border:1px solid #e5ddd0;box-shadow:0 18px 42px rgba(15,23,42,.12);">
                    </div>
                @endif

                @if ($images->isNotEmpty())
                    <div class="cat-grid" id="{{ $galleryId }}" style="display:grid;grid-template-columns:repeat(4,1fr);grid-auto-rows:190px;gap:14px;">
                        @foreach ($images as $image)
                            <button type="button" class="g-item" onclick="openLightbox(this)" style="padding:0;border:0;background:transparent;position:relative;overflow:hidden;border-radius:20px;cursor:zoom-in;box-shadow:0 16px 36px rgba(15,23,42,.12);">
                                <img src="{{ $image->imageUrl() }}" alt="{{ $image->caption ?: $image->title }}" style="width:100%;height:100%;object-fit:cover;display:block;">
                                <span class="cap" style="position:absolute;left:0;right:0;bottom:0;padding:12px 14px;color:#fff;background:linear-gradient(180deg,transparent,rgba(0,0,0,.72));text-align:left;">{{ $image->caption ?: $image->title }}</span>
                            </button>
                        @endforeach
                    </div>
                @else
                    <div style="padding:24px;border:1px dashed #d4c8b6;border-radius:18px;color:#6b7280;background:#faf7f1;">Nenhuma imagem cadastrada nesta categoria.</div>
                @endif
            </section>
        @empty
            <div style="padding:24px;border:1px dashed #d4c8b6;border-radius:18px;color:#6b7280;background:#faf7f1;">Nenhuma categoria cadastrada ainda.</div>
        @endforelse
    </div>

    <div class="lightbox" id="lightbox" style="display:none;position:fixed;inset:0;background:rgba(9,12,16,.9);z-index:60;align-items:center;justify-content:center;padding:24px;">
        <button class="lightbox-close" onclick="closeLightbox()" aria-label="Fechar" style="position:absolute;top:22px;right:22px;border:0;background:#fff;color:#111827;font-size:34px;width:48px;height:48px;border-radius:999px;cursor:pointer;">×</button>
        <button class="lightbox-arrow prev" onclick="moveLightbox(-1)" aria-label="Foto anterior" style="position:absolute;left:22px;border:0;background:#fff;color:#111827;font-size:34px;width:48px;height:48px;border-radius:999px;cursor:pointer;">‹</button>
        <div class="lightbox-content" style="max-width:min(1100px,92vw);width:100%;text-align:center;">
            <img id="lightbox-img" src="" alt="" style="max-width:100%;max-height:78vh;border-radius:20px;box-shadow:0 24px 60px rgba(0,0,0,.35);">
            <div class="lightbox-info" style="display:flex;justify-content:space-between;gap:16px;color:#fff;margin-top:14px;">
                <span id="lightbox-cap"></span>
                <span id="lightbox-count"></span>
            </div>
        </div>
        <button class="lightbox-arrow next" onclick="moveLightbox(1)" aria-label="Próxima foto" style="position:absolute;right:22px;border:0;background:#fff;color:#111827;font-size:34px;width:48px;height:48px;border-radius:999px;cursor:pointer;">›</button>
    </div>

    <script>
        let lbIndex = 0;
        let lbItems = [];

        function openLightbox(el) {
            lbItems = Array.from(el.closest('[data-section]').querySelectorAll('.g-item'));
            lbIndex = lbItems.indexOf(el);
            renderLightbox();
            document.getElementById('lightbox').style.display = 'flex';
        }

        function renderLightbox() {
            const item = lbItems[lbIndex];
            const img = item.querySelector('img');
            const cap = item.querySelector('.cap');
            document.getElementById('lightbox-img').src = img.src;
            document.getElementById('lightbox-img').alt = img.alt;
            document.getElementById('lightbox-cap').textContent = cap ? cap.textContent : '';
            document.getElementById('lightbox-count').textContent = (lbIndex + 1) + ' / ' + lbItems.length;
        }

        function moveLightbox(dir) {
            if (!lbItems.length) return;
            lbIndex = (lbIndex + dir + lbItems.length) % lbItems.length;
            renderLightbox();
        }

        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
        }

        document.addEventListener('keydown', (e) => {
            if (document.getElementById('lightbox').style.display !== 'flex') return;
            if (e.key === 'ArrowRight') moveLightbox(1);
            if (e.key === 'ArrowLeft') moveLightbox(-1);
            if (e.key === 'Escape') closeLightbox();
        });
    </script>
@endsection
