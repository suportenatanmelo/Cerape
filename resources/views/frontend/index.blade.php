@extends('frontend.layout')

@section('content')
    <section class="hero hero-cover" id="topo">
        <div class="hero-carousel">
            @forelse ($slides->take(3) as $index => $slide)
                <div class="slide {{ $index === 0 ? 'active' : '' }}" style="background-image:url('{{ $slide->imageUrl() ?: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1600&auto=format&fit=crop' }}')"></div>
            @empty
                <div class="slide active" style="background-image:url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1600&auto=format&fit=crop')"></div>
                <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1521477716071-8623476a3a4e?q=80&w=1600&auto=format&fit=crop')"></div>
                <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1505691938895-1758d7feb511?q=80&w=1600&auto=format&fit=crop')"></div>
            @endforelse
        </div>

        <div class="hero-content">
            @php
                $heroSlide = $slides->first();
            @endphp
            <span class="eyebrow">{{ $heroSlide?->subtitle ?? 'Casa de Recuperação · Acolhimento 24h' }}</span>
            <h1>{{ $heroSlide?->title ?? $settings?->hero_title ?? 'Um novo amanhecer começa com um passo de coragem.' }}</h1>
            <p>{{ $heroSlide?->description ?? $settings?->hero_subtitle ?? 'Ambiente seguro, equipe multiprofissional e um plano de tratamento pensado para cada etapa da recuperação — do acolhimento à reinserção.' }}</p>
            @if (($heroSlide?->show_buttons ?? true) || ! blank($heroSlide?->cta_label) || ! blank($heroSlide?->cta_url))
                <div class="hero-actions">
                    @if ($heroSlide?->show_buttons ?? true)
                        <a href="{{ $heroSlide?->cta_url ?: '#contato' }}" class="btn btn-primary">{{ $heroSlide?->cta_label ?? 'Agendar uma conversa' }}</a>
                        <a href="#jornada" class="btn btn-ghost">Conhecer a jornada</a>
                    @endif
                </div>
            @endif
        </div>

        <button class="arrow prev" type="button" onclick="moveSlide(-1)" aria-label="Slide anterior">‹</button>
        <button class="arrow next" type="button" onclick="moveSlide(1)" aria-label="Próximo slide">›</button>
    </section>

    <section id="sobre" class="section">
        <div class="sobre-grid">
            <div class="sobre-img card">
                <img src="{{ $settings?->about_image_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($settings->about_image_path) : 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?q=80&w=900&auto=format&fit=crop' }}" alt="Casa CERAPE">
            </div>
            <div class="sobre-text">
                <span class="eyebrow">Quem somos</span>
                <h2>{{ $settings?->about_title ?? 'Sobre a CERAPE' }}</h2>
                <p>{{ $settings?->about_paragraph_one ?? 'A CERAPE é uma casa de recuperação dedicada a oferecer acolhimento, tratamento e um novo começo para quem enfrenta a dependência química.' }}</p>
                <p>{{ $settings?->about_paragraph_two ?? 'Acreditamos que a recuperação acontece em comunidade: por isso trabalhamos junto às famílias, com transparência e respeito ao tempo de cada pessoa, do primeiro dia até a reinserção social.' }}</p>
                <div class="stats-row">
                    <div class="stat"><strong>12+</strong><span>anos de atuação</span></div>
                    <div class="stat"><strong>500+</strong><span>vidas acolhidas</span></div>
                    <div class="stat"><strong>24h</strong><span>equipe de plantão</span></div>
                </div>
            </div>
        </div>
    </section>

    <section id="jornada" class="section">
        <div class="section-head">
            <span class="eyebrow">Como funciona</span>
            <h2>Uma jornada em quatro etapas</h2>
            <p>Cada fase tem objetivos claros, sempre com acompanhamento próximo da família e da equipe técnica.</p>
        </div>
        <div class="steps">
            @forelse ($pillars->take(4) as $index => $pillar)
                <article class="step">
                    <span class="num">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $pillar->title }}</h3>
                    <p>{{ $pillar->summary }}</p>
                </article>
            @empty
                <article class="step"><span class="num">01</span><h3>Acolhimento</h3><p>Cadastre os pilares no painel /frontend.</p></article>
                <article class="step"><span class="num">02</span><h3>Estabilização</h3><p>Cadastre os pilares no painel /frontend.</p></article>
                <article class="step"><span class="num">03</span><h3>Fortalecimento</h3><p>Cadastre os pilares no painel /frontend.</p></article>
                <article class="step"><span class="num">04</span><h3>Reinserção</h3><p>Cadastre os pilares no painel /frontend.</p></article>
            @endforelse
        </div>
    </section>

    <section class="section" id="equipe">
        <div class="section-head">
            <span class="eyebrow">Como funciona</span>
            <h2>Quem cuida de você</h2>
            <p>Uma equipe multiprofissional acompanha cada etapa do tratamento, em conjunto e com plano individual para cada residente.</p>
        </div>
        <div class="team-grid">
            @forelse ($team as $member)
                <article class="team-card">
                    <div class="team-photo">
                        @if ($member->photoUrl())
                            <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}">
                        @endif
                    </div>
                    <div class="team-info">
                        <span class="role">{{ $member->role }}</span>
                        <h3>{{ $member->name }}</h3>
                        <p>{{ $member->description }}</p>
                    </div>
                </article>
            @empty
                <article class="team-card"><div class="team-info"><p>Cadastre a equipe no painel /frontend.</p></div></article>
            @endforelse
        </div>
    </section>

    <section class="section" id="galeria">
        <div class="section-head">
            <span class="eyebrow">Nosso espaço</span>
            <h2>Galeria</h2>
            <p>Um ambiente pensado para acolher: áreas comuns, quartos, jardim e espaços terapêuticos.</p>
        </div>
        <div class="gallery-filters">
            <button type="button" class="filter-btn active">Todos</button>
            @forelse ($categories as $category)
                <button type="button" class="filter-btn">{{ $category->name }}</button>
            @empty
                <button type="button" class="filter-btn">Áreas Comuns</button>
                <button type="button" class="filter-btn">Quartos</button>
                <button type="button" class="filter-btn">Jardim</button>
                <button type="button" class="filter-btn">Terapêutico</button>
            @endforelse
        </div>
        <div class="gallery-grid">
            @forelse ($categories->sortBy('position') as $category)
                <article class="g-item">
                    @if ($category->imageUrl())
                        <div class="team-photo" style="height: 220px;">
                            <img src="{{ $category->imageUrl() }}" alt="{{ $category->name }}">
                        </div>
                    @endif
                    <div class="team-info">
                        <span class="role">{{ $category->name }}</span>
                        <h3>Categoria da galeria</h3>
                        <p>Ordem {{ $category->position ?? '-' }} | {{ $category->show_on_home ? 'Visivel na home' : 'Oculta na home' }}</p>
                    </div>
                </article>
            @empty
                <article class="g-item"><div class="team-info"><p>Cadastre as categorias da galeria no painel /frontend.</p></div></article>
            @endforelse
        </div>
    </section>

    <section class="section" id="blog">
        <div class="section-head">
            <span class="eyebrow">Conteúdo</span>
            <h2>Blog</h2>
            <p>Artigos para famílias e pacientes sobre recuperação, saúde mental e reconstrução de vínculos.</p>
        </div>
        <div class="blog-grid">
            @forelse ($posts as $post)
                <article class="post-card">
                    @if ($post->imageUrl())
                        <div class="post-img"><img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}"></div>
                    @endif
                    <div class="post-body">
                        <span class="post-tag">{{ $post->author_name }} | {{ optional($post->published_at)->format('d/m/Y') }}</span>
                        <h3>{{ $post->title }}</h3>
                        <p>{{ $post->excerpt }}</p>
                        <p>Tags: {{ is_array($post->tags) ? implode(', ', $post->tags) : ($post->tags ?? '-') }}</p>
                    </div>
                </article>
            @empty
                <article class="post-card"><div class="post-body"><p>Cadastre até 5 cards do blog no painel /frontend.</p></div></article>
            @endforelse
        </div>
    </section>

    <section class="section" id="contato">
        <div class="section-head">
            <span class="eyebrow">Contato</span>
            <h2>Contato</h2>
            <p>WhatsApp principal: {{ $settings?->whatsapp_number ?? 'defina no painel /frontend' }}</p>
        </div>
        <div class="card">
            <div class="toggle">
                <strong>Site ativo:</strong> {{ ($settings?->site_enabled ?? true) ? 'Sim' : 'Não' }}
                <strong>Home ativa:</strong> {{ ($settings?->home_enabled ?? true) ? 'Sim' : 'Não' }}
            </div>
        </div>
    </section>

    <section class="section" id="temas">
        <div class="section-head">
            <span class="eyebrow">Clínica</span>
            <h2>Onde fica a clínica</h2>
            <p>{{ $settings?->clinic_description ?? 'Veja abaixo onde fica a clínica de recuperação e como chegar.' }}</p>
        </div>
        <div class="clinic-grid">
            <article class="clinic-card">
                <div class="clinic-map">
                    @php
                        $embedValue = trim((string) ($settings?->clinic_google_maps_embed ?? ''));
                        $mapsSrc = null;

                        if ($embedValue !== '') {
                            if (str_contains($embedValue, '<iframe')) {
                                if (preg_match('/src=["\']([^"\']+)["\']/', $embedValue, $matches)) {
                                    $mapsSrc = $matches[1];
                                }
                            } else {
                                $mapsSrc = $embedValue;
                            }
                        }
                    @endphp

                    @if ($mapsSrc)
                        <iframe src="{{ $mapsSrc }}" title="Google Maps da clínica" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                    @else
                        <div class="clinic-map-empty">
                            <strong>Mapa ainda não configurado</strong>
                            <p>Preencha o campo de incorporação do Google Maps no painel /frontend.</p>
                        </div>
                    @endif
                </div>
            </article>
            <article class="clinic-card clinic-details">
                <span class="pill">Informações</span>
                <h3>{{ $settings?->clinic_name ?? 'Clínica CERAPE' }}</h3>
                <ul class="clinic-list">
                    <li><strong>Endereço:</strong> {{ $settings?->clinic_address ?? '-' }}</li>
                    <li><strong>Cidade:</strong> {{ $settings?->clinic_city ?? '-' }}</li>
                    <li><strong>Estado:</strong> {{ $settings?->clinic_state ?? '-' }}</li>
                    <li><strong>CEP:</strong> {{ $settings?->clinic_zip_code ?? '-' }}</li>
                </ul>
                @if ($settings?->clinic_maps_link)
                    <a href="{{ $settings->clinic_maps_link }}" target="_blank" rel="noopener" class="btn btn-line">Abrir no Google Maps</a>
                @endif
            </article>
        </div>
    </section>
@endsection
