@php
    $logoUrl = \App\Support\PdfImage::publicUrl(\App\Support\SystemBranding::logoPublicPath()) ?? \App\Support\SystemBranding::logoUrl();
    $heroImage = asset('grayscale/assets/img/bg-masthead.jpg');
    $aboutImage = asset('grayscale/assets/img/demo-image-01.jpg');
    $galleryImages = [
        asset('grayscale/assets/img/bg-masthead.jpg'),
        asset('grayscale/assets/img/demo-image-01.jpg'),
        asset('grayscale/assets/img/demo-image-02.jpg'),
        asset('grayscale/assets/img/bg-signup.jpg'),
    ];

    $highlights = [
        ['icon' => 'heroicon-o-heart', 'title' => 'Acolhimento humanizado', 'text' => 'Tratamento com empatia, respeito e compreensão.'],
        ['icon' => 'heroicon-o-users', 'title' => 'Equipe especializada', 'text' => 'Profissionais preparados para oferecer suporte contínuo.'],
        ['icon' => 'heroicon-o-sparkles', 'title' => 'Planos personalizados', 'text' => 'Acompanhamento ajustado à necessidade de cada pessoa.'],
        ['icon' => 'heroicon-o-shield-check', 'title' => 'Ambiente seguro', 'text' => 'Estrutura preparada para conforto, proteção e cuidado.'],
    ];

    $treatments = [
        ['icon' => 'heroicon-o-beaker', 'title' => 'Desintoxicação', 'text' => 'Acompanhamento seguro para a estabilização do corpo.'],
        ['icon' => 'heroicon-o-user', 'title' => 'Terapia individual', 'text' => 'Atendimento psicológico personalizado.'],
        ['icon' => 'heroicon-o-user-group', 'title' => 'Terapia em grupo', 'text' => 'Compartilhamento de experiências e fortalecimento.'],
        ['icon' => 'heroicon-o-heart', 'title' => 'Atividades terapêuticas', 'text' => 'Ações que promovem bem-estar físico e emocional.'],
        ['icon' => 'heroicon-o-home-modern', 'title' => 'Acompanhamento familiar', 'text' => 'Suporte e orientação para familiares.'],
    ];
@endphp

<x-filament-panels::page>
    <div class="cerape-panel">
        <header class="cerape-sitebar">
            <a class="cerape-brand" href="#home" aria-label="CERAPE">
                <img src="{{ $logoUrl }}" alt="CERAPE">
                <span>
                    <strong>CERAPE</strong>
                    <small>Centro de recuperação e apoio</small>
                </span>
            </a>

            <nav class="cerape-nav" aria-label="Navegação institucional">
                <a href="#home">Home</a>
                <a href="#sobre">Sobre</a>
                <a href="#tratamentos">Tratamentos</a>
                <a href="#galeria">Galeria</a>
                <a href="#contato">Contato</a>
            </nav>

            <a class="cerape-phone" href="tel:+5511999999999">
                <x-filament::icon icon="heroicon-o-phone" />
                <span>(11) 99999-9999</span>
            </a>
        </header>

        <section id="home" class="cerape-hero">
            <img src="{{ $heroImage }}" alt="Área externa do CERAPE">
            <div class="cerape-hero__shade"></div>
            <div class="cerape-hero__content">
                <p>Centro CERAPE</p>
                <h1>Acolhimento que <span>transforma vidas</span></h1>
                <div>Suporte especializado e humanizado para recuperação, bem-estar e recomeço.</div>
                <div class="cerape-actions">
                    <a href="#tratamentos">Conheça os tratamentos</a>
                    <a href="#contato">Fale conosco</a>
                </div>
            </div>
        </section>

        <section class="cerape-highlights" aria-label="Diferenciais">
            @foreach ($highlights as $item)
                <article>
                    <div class="cerape-icon">
                        <x-filament::icon :icon="$item['icon']" />
                    </div>
                    <div>
                        <h2>{{ $item['title'] }}</h2>
                        <p>{{ $item['text'] }}</p>
                    </div>
                </article>
            @endforeach
        </section>

        <section id="sobre" class="cerape-about">
            <div>
                <p class="cerape-eyebrow">Sobre o CERAPE</p>
                <h2>Mais que um tratamento, um recomeço</h2>
                <p>O CERAPE oferece cuidado integrado em uma estrutura acolhedora, com acompanhamento profissional e foco na reconstrução de vínculos, autonomia e qualidade de vida.</p>
                <a href="#contato">Saiba mais sobre nós</a>
            </div>
            <img src="{{ $aboutImage }}" alt="Espaço de acolhimento do CERAPE">
        </section>

        <section id="tratamentos" class="cerape-section">
            <div class="cerape-section__header">
                <div>
                    <p class="cerape-eyebrow">Tratamentos</p>
                    <h2>Nossos tratamentos</h2>
                </div>
                <a href="#contato">Ver todos</a>
            </div>

            <div class="cerape-treatment-grid">
                @foreach ($treatments as $item)
                    <article>
                        <div class="cerape-icon">
                            <x-filament::icon :icon="$item['icon']" />
                        </div>
                        <div>
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['text'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="galeria" class="cerape-section cerape-gallery-section">
            <div class="cerape-section__header">
                <div>
                    <p class="cerape-eyebrow">Galeria</p>
                    <h2>Nosso espaço</h2>
                </div>
                <a href="#contato">Ver mais fotos</a>
            </div>

            <div class="cerape-gallery">
                @foreach ($galleryImages as $index => $image)
                    <img src="{{ $image }}" alt="Ambiente CERAPE {{ $index + 1 }}">
                @endforeach
            </div>
        </section>

        <section id="contato" class="cerape-contact">
            <div>
                <x-filament::icon icon="heroicon-o-hand-raised" />
                <span>
                    <strong>Precisa de ajuda ou mais informações?</strong>
                    <small>Estamos aqui para acolher e orientar você.</small>
                </span>
            </div>
            <a href="tel:+5511999999999">Fale conosco agora</a>
        </section>

        <footer class="cerape-footer">
            <div class="cerape-footer__brand">
                <img src="{{ $logoUrl }}" alt="CERAPE">
                <span>CERAPE</span>
            </div>
            <div>
                <strong>Navegação</strong>
                <a href="#home">Home</a>
                <a href="#sobre">Sobre</a>
                <a href="#tratamentos">Tratamentos</a>
                <a href="#galeria">Galeria</a>
            </div>
            <div>
                <strong>Contato</strong>
                <span>(11) 99999-9999</span>
                <span>contato@cerape.org.br</span>
                <span>São Paulo - SP</span>
            </div>
        </footer>
    </div>
</x-filament-panels::page>
