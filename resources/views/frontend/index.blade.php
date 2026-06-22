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
        @php
            $contactSuccess = session('contact_sent');
        @endphp
        @php
            $whatsappDigits = preg_replace('/\D+/', '', (string) ($settings?->whatsapp_number ?? ''));
            $whatsappDigits = $whatsappDigits ? (str_starts_with($whatsappDigits, '55') ? $whatsappDigits : '55'.$whatsappDigits) : '';
            $whatsappMessage = trim((string) ($settings?->whatsapp_message ?? 'Olá, gostaria de mais informações.'));
            $whatsappWelcome = trim((string) ($settings?->contact_description ?? 'Toda mensagem é tratada com sigilo. Nossa equipe responde em até 24h.'));
            $whatsappUrl = $whatsappDigits ? 'https://wa.me/'.$whatsappDigits.'?text='.urlencode($whatsappMessage) : null;
            $whatsappGreeting = trim((string) ($settings?->contact_whatsapp_greeting ?? 'Olá! 👋 Como podemos ajudar?'));
            $whatsappSupportLine = trim((string) ($settings?->contact_whatsapp_support_line ?? 'Fale com a nossa equipe agora pelo WhatsApp.'));
            $whatsappFooterLine = trim((string) ($settings?->contact_whatsapp_footer_line ?? 'Atendimento 24h'));
        @endphp
        <div class="contact-wrap">
            <div class="contact-hero">
                <span class="contact-kicker">{{ $settings?->contact_eyebrow ?? 'Atendimento confidencial' }}</span>
                <h2>{{ $settings?->contact_title ?? 'Vamos conversar' }}</h2>
                <p>{{ $settings?->contact_description ?? 'Toda mensagem é tratada com sigilo. Nossa equipe responde em até 24h.' }}</p>
            </div>

            <div class="contact-grid-v2">
                <div class="contact-form-card">
                    <div class="contact-form-header">
                        <h3>{{ $settings?->contact_title ?? 'Vamos conversar' }}</h3>
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
                            <button type="submit" class="btn btn-primary">Enviar mensagem</button>
                        </div>
                    </form>
                </div>

                <div class="contact-info-card">
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
