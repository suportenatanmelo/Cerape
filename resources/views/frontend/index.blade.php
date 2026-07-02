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
        <div class="hero-veil"></div>

        <div class="hero-content">
            @php
                $heroSlide = $slides->first();
            @endphp
            <span class="eyebrow hero-anim" style="--d:.1s">{{ $heroSlide?->subtitle ?? $settings?->hero_subtitle ?? 'Casa de Recuperação · Acolhimento 24h' }}</span>
            <h1 class="hero-title">
                <span class="line-mask"><span class="line-inner" style="--d:.35s">{{ \Illuminate\Support\Str::before($heroSlide?->title ?? $settings?->hero_title ?? 'Um novo amanhecer começa com um passo de coragem.', ' ') }}</span></span>
                <span class="line-mask"><span class="line-inner" style="--d:.5s">{{ \Illuminate\Support\Str::after($heroSlide?->title ?? $settings?->hero_title ?? 'Um novo amanhecer começa com um passo de coragem.', ' ') ?: 'começa com um passo de coragem.' }}</span></span>
            </h1>
            <p class="hero-anim" style="--d:.8s">{{ $heroSlide?->description ?? $settings?->hero_subtitle ?? 'Ambiente seguro, equipe multiprofissional e um plano de tratamento pensado para cada etapa da recuperação — do acolhimento à reinserção.' }}</p>
            @if (($heroSlide?->show_buttons ?? true) || ! blank($heroSlide?->cta_label) || ! blank($heroSlide?->cta_url))
                <div class="hero-actions hero-anim" style="--d:1s">
                    @if ($heroSlide?->show_buttons ?? true)
                        <a href="{{ $heroSlide?->cta_url ?: '#contato' }}" class="btn btn-primary">{{ $heroSlide?->cta_label ?? $settings?->hero_cta_label ?? 'Agendar uma conversa' }}</a>
                        <a href="#jornada" class="btn btn-ghost">{{ $settings?->hero_secondary_cta_label ?? 'Conhecer a jornada' }}</a>
                    @endif
                </div>
            @endif
        </div>

        <a href="#sobre" class="scroll-cue hero-anim" style="--d:1.3s" aria-label="Rolar para conhecer a CERAPE">
            <span class="scroll-cue-mouse"><span></span></span>
            <span class="scroll-cue-label">Role para conhecer</span>
        </a>

        <button class="arrow prev" type="button" onclick="moveSlide(-1)" aria-label="Slide anterior">‹</button>
        <button class="arrow next" type="button" onclick="moveSlide(1)" aria-label="Próximo slide">›</button>
    </section>
    <div class="horizon"></div>

    <section id="sobre" class="section">
        <div class="sobre-grid">
            <div class="sobre-img card reveal">
                <img src="{{ $settings?->about_image_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($settings->about_image_path) : 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?q=80&w=900&auto=format&fit=crop' }}" alt="Casa CERAPE">
            </div>
            <div class="sobre-text reveal" style="--reveal-delay:.15s">
                <span class="eyebrow">{{ $settings?->menu_label_about ?? 'Quem somos' }}</span>
                <h2>{{ $settings?->about_title ?? 'Sobre a CERAPE' }}</h2>
                <p>{!! $settings?->about_paragraph_one ?? 'A CERAPE é uma casa de recuperação dedicada a oferecer acolhimento, tratamento e um novo começo para quem enfrenta a dependência química.' !!}</p>
                <p>{!! $settings?->about_paragraph_two ?? 'Acreditamos que a recuperação acontece em comunidade: por isso trabalhamos junto às famílias, com transparência e respeito ao tempo de cada pessoa, do primeiro dia até a reinserção social.' !!}</p>
                @php
                    $aboutVideoUrl = trim((string) ($settings?->about_video_url ?? ''));
                    $aboutVideoWidth = (int) ($settings?->about_video_width ?? 560);
                    $aboutVideoHeight = (int) ($settings?->about_video_height ?? 315);
                    $aboutVideoEmbedUrl = null;

                    if ($aboutVideoUrl !== '' && preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $aboutVideoUrl, $matches)) {
                        $aboutVideoEmbedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                    }
                @endphp

                @if (!empty($aboutVideoEmbedUrl))
                    <div class="video-card" style="--video-width: {{ $aboutVideoWidth }}px; --video-height: {{ $aboutVideoHeight }}px;">
                        <iframe
                            src="{{ $aboutVideoEmbedUrl }}"
                            title="Vídeo Quem somos"
                            loading="lazy"
                            referrerpolicy="strict-origin-when-cross-origin"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                        ></iframe>
                    </div>
                @endif
                <div class="stats-row">
                    <div class="stat"><strong>12+</strong><span>anos de atuação</span></div>
                    <div class="stat"><strong>500+</strong><span>vidas acolhidas</span></div>
                    <div class="stat"><strong>24h</strong><span>equipe de plantão</span></div>
                </div>
            </div>
        </div>
    </section>

    <section id="jornada" class="section">
        <div class="section-head reveal">
            <span class="eyebrow">{{ $settings?->journey_eyebrow ?? 'Como funciona' }}</span>
            <h2>{{ $settings?->journey_title ?? 'Uma jornada em quatro etapas' }}</h2>
            <p>{{ $settings?->journey_description ?? 'Cada fase tem objetivos claros, sempre com acompanhamento próximo da família e da equipe técnica.' }}</p>
        </div>
        <div class="steps">
            @forelse ($pillars->take(4) as $index => $pillar)
                <article class="step reveal" style="--reveal-delay:{{ $index * 0.12 }}s">
                    <span class="num">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $pillar->title }}</h3>
                    <p>{{ $pillar->summary }}</p>
                </article>
            @empty
                <article class="step"><span class="num">01</span><h3>{{ $settings?->journey_empty_title_one ?? 'Acolhimento' }}</h3><p>{{ $settings?->journey_empty_description ?? 'Cadastre os pilares no painel /frontend.' }}</p></article>
                <article class="step"><span class="num">02</span><h3>{{ $settings?->journey_empty_title_two ?? 'Estabilização' }}</h3><p>{{ $settings?->journey_empty_description ?? 'Cadastre os pilares no painel /frontend.' }}</p></article>
                <article class="step"><span class="num">03</span><h3>{{ $settings?->journey_empty_title_three ?? 'Fortalecimento' }}</h3><p>{{ $settings?->journey_empty_description ?? 'Cadastre os pilares no painel /frontend.' }}</p></article>
                <article class="step"><span class="num">04</span><h3>{{ $settings?->journey_empty_title_four ?? 'Reinserção' }}</h3><p>{{ $settings?->journey_empty_description ?? 'Cadastre os pilares no painel /frontend.' }}</p></article>
            @endforelse
        </div>
    </section>

    <section class="section" id="equipe">
        <div class="section-head reveal">
            <span class="eyebrow">{{ $settings?->team_eyebrow ?? 'Equipe' }}</span>
            <h2>{{ $settings?->team_title ?? 'Quem cuida de você' }}</h2>
            <p>{{ $settings?->team_description ?? 'Uma equipe multiprofissional acompanha cada etapa do tratamento, em conjunto e com plano individual para cada residente.' }}</p>
        </div>
        <div class="team-grid">
            @forelse ($team as $member)
                <article class="team-card reveal">
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
                <article class="team-card"><div class="team-info"><p>{{ $settings?->team_empty_message ?? 'Cadastre a equipe no painel /frontend.' }}</p></div></article>
            @endforelse
        </div>
    </section>

    <section class="section" id="galeria">
        <div class="section-head reveal">
            <span class="eyebrow">{{ $settings?->gallery_eyebrow ?? 'Nosso espaço' }}</span>
            <h2>{{ $settings?->gallery_title ?? 'Galeria' }}</h2>
            <p>{{ $settings?->gallery_description ?? 'Um ambiente pensado para acolher: áreas comuns, quartos, jardim e espaços terapêuticos.' }}</p>
        </div>
        <div class="gallery-filters reveal">
            <button type="button" class="filter-btn active">{{ $settings?->gallery_all_label ?? 'Todos' }}</button>
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
                <article class="g-item reveal">
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
                <article class="g-item"><div class="team-info"><p>{{ $settings?->gallery_empty_message ?? 'Cadastre as categorias da galeria no painel /frontend.' }}</p></div></article>
            @endforelse
        </div>
    </section>

    <section class="section" id="blog">
        <div class="section-head reveal">
            <span class="eyebrow">{{ $settings?->blog_eyebrow ?? 'Conteúdo' }}</span>
            <h2>{{ $settings?->blog_title ?? 'Blog' }}</h2>
            <p>{{ $settings?->blog_description ?? 'Artigos para famílias e pacientes sobre recuperação, saúde mental e reconstrução de vínculos.' }}</p>
        </div>
        <div class="blog-grid">
            @forelse ($posts as $post)
                <article class="post-card reveal">
                    @if ($post->imageUrl())
                        <div class="post-img"><img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}"></div>
                    @endif
                    <div class="post-body">
                        <span class="post-tag">{{ $post->author_name }} | {{ optional($post->published_at)->format('d/m/Y') }}</span>
                        <h3>{{ $post->title }}</h3>
                        <p>{{ $post->excerpt }}</p>
                        <p>Tags: {{ is_array($post->tags) ? implode(', ', $post->tags) : ($post->tags ?? '-') }}</p>
                        @if ($post->slug)
                            <a class="btn btn-line" href="{{ route('blog.show', ['slug' => $post->slug]) }}">Leia mais</a>
                        @endif
                    </div>
                </article>
            @empty
                <article class="post-card"><div class="post-body"><p>{{ $settings?->blog_empty_message ?? 'Cadastre até 5 cards do blog no painel /frontend.' }}</p></div></article>
            @endforelse
        </div>

        <div class="section-actions reveal" style="margin-top: 2rem; text-align: center;">
            <a class="btn btn-primary" href="{{ route('news.index') }}">Ver mais notícias</a>
        </div>
    </section>

    @if (($treatments ?? collect())->isNotEmpty())
        <section class="section" id="tratamentos">
            <div class="section-head reveal">
                <span class="eyebrow">Tratamentos</span>
                <h2>Frentes de cuidado</h2>
                <p>Conteúdos cadastrados no módulo CMS institucional.</p>
            </div>
            <div class="steps">
                @foreach ($treatments as $index => $treatment)
                    <article class="step reveal" style="--reveal-delay:{{ $index * 0.08 }}s">
                        <span class="num">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <h3>{{ $treatment->title }}</h3>
                        <p>{{ $treatment->summary }}</p>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if (($testimonials ?? collect())->isNotEmpty())
        <section class="section" id="depoimentos">
            <div class="section-head reveal">
                <span class="eyebrow">Depoimentos</span>
                <h2>Histórias de confiança</h2>
            </div>
            <div class="team-grid">
                @foreach ($testimonials as $testimonial)
                    <article class="team-card reveal">
                        @if ($testimonial->imageUrl())
                            <div class="team-photo"><img src="{{ $testimonial->imageUrl() }}" alt="{{ $testimonial->title }}"></div>
                        @endif
                        <div class="team-info">
                            <span class="role">{{ $testimonial->category ?: 'Depoimento' }}</span>
                            <h3>{{ $testimonial->title }}</h3>
                            <p>{{ $testimonial->summary }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if (($partners ?? collect())->isNotEmpty())
        <section class="section" id="parceiros">
            <div class="section-head reveal">
                <span class="eyebrow">Parceiros</span>
                <h2>Quem caminha conosco</h2>
            </div>
            <div class="gallery-grid">
                @foreach ($partners as $partner)
                    <article class="g-item reveal">
                        @if ($partner->imageUrl())
                            <div class="team-photo" style="height: 160px;"><img src="{{ $partner->imageUrl() }}" alt="{{ $partner->title }}"></div>
                        @endif
                        <div class="team-info">
                            <h3>{{ $partner->title }}</h3>
                            <p>{{ $partner->summary }}</p>
                            @if ($partner->external_url)
                                <a href="{{ $partner->external_url }}" target="_blank" rel="noopener" class="btn btn-line">Visitar site</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section class="section" id="newsletter">
        <div class="section-head reveal">
            <span class="eyebrow">Newsletter</span>
            <h2>Receba novidades</h2>
            <p>Cadastre seu e-mail para receber conteúdos e comunicados da CERAPE.</p>
        </div>
        <div class="contact-form-card reveal">
            @if (session('newsletter_sent'))
                <div class="contact-success">Cadastro realizado com sucesso.</div>
            @endif
            <form class="contact-form" method="POST" action="{{ route('newsletter.submit') }}">
                @csrf
                <div class="contact-grid">
                    <label class="contact-field">
                        <span>Nome</span>
                        <input type="text" name="name" placeholder="Seu nome" value="{{ old('name') }}">
                    </label>
                    <label class="contact-field">
                        <span>E-mail</span>
                        <input type="email" name="email" placeholder="seu@email.com" value="{{ old('email') }}" required>
                    </label>
                </div>
                <div class="contact-actions">
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </div>
            </form>
        </div>
    </section>

    <section class="section" id="contato">
        @php
            $contactSuccess = session('contact_sent');
        @endphp
        @php
            $whatsappDigits = preg_replace('/\D+/', '', (string) ($settings?->whatsapp_number ?? ''));
            $whatsappDigits = $whatsappDigits ? (str_starts_with($whatsappDigits, '55') ? $whatsappDigits : '55'.$whatsappDigits) : '';
            $whatsappMessage = trim((string) ($settings?->whatsapp_message ?? 'Olá, gostaria de mais informações.'));
            $whatsappWelcome = trim((string) ($settings?->contact_section_description ?? 'Toda mensagem é tratada com sigilo. Nossa equipe responde em até 24h.'));
            $whatsappUrl = $whatsappDigits ? 'https://wa.me/'.$whatsappDigits.'?text='.urlencode($whatsappMessage) : null;
            $whatsappGreeting = trim((string) ($settings?->contact_section_title ?? 'Olá! 👋 Como podemos ajudar?'));
            $whatsappSupportLine = trim((string) ($settings?->contact_whatsapp_title ?? 'Fale com a nossa equipe agora pelo WhatsApp.'));
            $whatsappFooterLine = trim((string) ($settings?->contact_whatsapp_footer ?? 'Atendimento 24h'));
        @endphp
        <div class="contact-wrap">
            <div class="contact-hero reveal">
                <span class="contact-kicker">{{ $settings?->contact_section_eyebrow ?? 'Atendimento confidencial' }}</span>
                <h2>{{ $settings?->contact_section_title ?? 'Vamos conversar' }}</h2>
                <p>{{ $settings?->contact_section_description ?? 'Toda mensagem é tratada com sigilo. Nossa equipe responde em até 24h.' }}</p>
            </div>

            <div class="contact-grid-v2">
                <div class="contact-form-card reveal">
                    <div class="contact-form-header">
                        <h3>{{ $settings?->contact_section_title ?? 'Vamos conversar' }}</h3>
                        <p>Preencha o formulário e fale diretamente com a equipe da CERAPE.</p>
                    </div>

                    @if ($contactSuccess)
                        <div class="contact-success">
                            Mensagem enviada com sucesso. Em breve retornaremos o contato.
                        </div>
                    @endif

                    <form class="contact-form" method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <div class="contact-grid">
                            <label class="contact-field">
                                <span>Nome</span>
                                <input type="text" name="nome" placeholder="{{ $settings?->contact_form_name_placeholder ?? 'Seu nome' }}" value="{{ old('nome') }}" required>
                                @error('nome')<small>{{ $message }}</small>@enderror
                            </label>
                            <label class="contact-field">
                                <span>Telefone / WhatsApp</span>
                                <input type="text" name="telefone" placeholder="{{ $settings?->contact_form_phone_placeholder ?? '(00) 00000-0000' }}" value="{{ old('telefone') }}" required>
                                @error('telefone')<small>{{ $message }}</small>@enderror
                            </label>
                        </div>
                        <label class="contact-field">
                            <span>E-mail</span>
                            <input type="email" name="email" placeholder="{{ $settings?->contact_form_email_placeholder ?? 'seu@email.com' }}" value="{{ old('email') }}">
                        </label>
                        <label class="contact-field">
                            <span>Mensagem</span>
                            <textarea name="mensagem" rows="6" placeholder="{{ $settings?->contact_form_message_placeholder ?? 'Como podemos ajudar?' }}" required>{{ old('mensagem') }}</textarea>
                            @error('mensagem')<small>{{ $message }}</small>@enderror
                        </label>
                        <div class="contact-actions">
                            <button type="submit" class="btn btn-primary">{{ $settings?->contact_whatsapp_cta_label ?? 'Enviar mensagem' }}</button>
                        </div>
                    </form>
                </div>

                <div class="contact-info-card reveal" style="--reveal-delay:.12s">
                    <div class="contact-info-box">
                <span class="contact-info-title">{{ $settings?->clinic_contact_title ?? 'Informações' }}</span>
                        <strong>{{ $settings?->clinic_contact_name ?? 'Clínica CERAPE' }}</strong>
                    </div>
                    <div class="contact-info-box">
                        <span class="contact-info-title">{{ $settings?->clinic_contact_address_label ?? 'Endereço' }}</span>
                        <strong>{{ $settings?->clinic_contact_address_line ?? 'Rua das Acácias, 120 — Bairro Jardim, São Paulo/SP' }}</strong>
                    </div>
                    <div class="contact-info-box">
                        <span class="contact-info-title">{{ $settings?->clinic_contact_city_label ?? 'Cidade' }}</span>
                        <strong>{{ $settings?->clinic_contact_city_line ?? 'São Paulo' }}</strong>
                    </div>
                    <div class="contact-info-box">
                        <span class="contact-info-title">{{ $settings?->clinic_contact_state_label ?? 'Estado' }}</span>
                        <strong>{{ $settings?->clinic_contact_state_line ?? 'SP' }}</strong>
                    </div>
                    <div class="contact-info-box">
                        <span class="contact-info-title">{{ $settings?->clinic_contact_zip_label ?? 'CEP' }}</span>
                        <strong>{{ $settings?->clinic_contact_zip_line ?? '00000-000' }}</strong>
                    </div>
                    <div class="contact-info-box">
                        <span class="contact-info-title">{{ $settings?->clinic_contact_phone_label ?? 'Telefone' }}</span>
                        <strong>{{ $settings?->clinic_contact_phone_line ?? '(11) 0000-0000' }}</strong>
                    </div>
                    <div class="contact-info-box">
                        <span class="contact-info-title">{{ $settings?->clinic_contact_email_label ?? 'E-mail' }}</span>
                        <strong>{{ $settings?->clinic_contact_email_line ?? 'contato@cerape.com' }}</strong>
                    </div>
                </div>
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
                            <p>{{ $settings?->clinic_description ?? 'Preencha o campo de incorporação do Google Maps no painel /frontend.' }}</p>
                        </div>
                    @endif
                </div>
            </article>
            <article class="clinic-card clinic-details">
                <span class="pill">{{ $settings?->clinic_contact_title ?? 'Informações' }}</span>
                <h3>{{ $settings?->clinic_name ?? 'Clínica CERAPE' }}</h3>
                <ul class="clinic-list">
                    <li><strong>Endereço:</strong> {{ $settings?->clinic_address ?? '-' }}</li>
                    <li><strong>Cidade:</strong> {{ $settings?->clinic_city ?? '-' }}</li>
                    <li><strong>Estado:</strong> {{ $settings?->clinic_state ?? '-' }}</li>
                    <li><strong>CEP:</strong> {{ $settings?->clinic_zip_code ?? '-' }}</li>
                </ul>
                @if ($settings?->clinic_maps_link)
                    <a href="{{ $settings->clinic_maps_link }}" target="_blank" rel="noopener" class="btn btn-line">{{ $settings?->clinic_contact_title ?? 'Abrir no Google Maps' }}</a>
                @endif
            </article>
        </div>
    </section>
@endsection
