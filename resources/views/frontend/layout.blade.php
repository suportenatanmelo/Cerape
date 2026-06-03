<!DOCTYPE html>
@php
    use App\Support\PdfImage;
    use Illuminate\Support\Arr;

    $storedImageUrl = function ($path, ?string $fallback = null): ?string {
        if (is_array($path)) {
            $path = Arr::first($path);
        }

        return PdfImage::publicUrl($path) ?? $fallback;
    };

    $homeImageUrl = $storedImageUrl($home?->hero_image, asset('grayscale/assets/img/bg-masthead.jpg'));
    $homeImageAlt = filled($home?->hero_image_alt) ? $home->hero_image_alt : 'CERAPE preview';
    $homeTitle = filled($home?->title) ? $home->title : 'CERAPE';
    $homeSubtitle = filled($home?->subtitle)
        ? $home->subtitle
        : 'Página institucional responsiva do projeto CERAPE, desenvolvida com o tema Grayscale.';
    $homeCtaLabel = filled($home?->cta_label) ? $home->cta_label : 'Começar';
    $homeCtaUrl = filled($home?->cta_url) ? $home->cta_url : '#about';

    $aboutImageUrl = $storedImageUrl($home?->about_image, $homeImageUrl);
    $aboutImageAlt = filled($home?->about_image_alt) ? $home->about_image_alt : 'Imagem da seção sobre o CERAPE';
    $aboutTitle = filled($home?->about_title) ? $home->about_title : 'Sobre o CERAPE';
    $aboutSubtitle = filled($home?->about_subtitle)
        ? $home->about_subtitle
        : 'Edite este bloco pelo painel para apresentar a instituição, serviços, valores e links importantes.';

    $projectsImageUrl = $storedImageUrl($home?->projects_image, asset('grayscale/assets/img/bg-masthead.jpg'));
    $projectsImageAlt = filled($home?->projects_image_alt) ? $home->projects_image_alt : 'Imagem da seção de projetos';
    $projectsTitle = filled($home?->projects_title) ? $home->projects_title : 'Projetos';
    $projectsSubtitle = filled($home?->projects_subtitle)
        ? $home->projects_subtitle
        : 'Use este espaço para destacar projetos, serviços ou conteúdos importantes da página pública.';

    $signupImageUrl = $storedImageUrl($home?->signup_image);
    $signupImageAlt = filled($home?->signup_image_alt) ? $home->signup_image_alt : 'Imagem da seção de contato';
    $signupTitle = filled($home?->signup_title) ? $home->signup_title : 'Entre em contato';
    $signupSubtitle = filled($home?->signup_subtitle)
        ? $home->signup_subtitle
        : 'Preencha seus dados para falar com a equipe CERAPE.';

    $carouselItems = collect($home?->carousel_items ?? [])
        ->map(function (array $item) use ($storedImageUrl): array {
            $item['image_url'] = $storedImageUrl($item['image'] ?? null);

            return $item;
        })
        ->filter(fn (array $item): bool => filled($item['image_url']))
        ->values();

    $showCarousel = (bool) ($home?->enable_carousel ?? false) && $carouselItems->isNotEmpty();
@endphp
<html lang="pt-BR">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Pagina inicial do CERAPE." />
        <meta name="author" content="CERAPE" />
        <title>CERAPE</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('grayscale/assets/favicon.ico') }}" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,800,900" rel="stylesheet" />
        <link href="{{ asset('grayscale/css/styles.css') }}" rel="stylesheet" />
        <style>
            .masthead {
                background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.25), rgba(0, 0, 0, 0.7)), url('{{ $homeImageUrl }}');
            }

            .frontend-rich-text a {
                color: inherit;
                font-weight: 700;
                text-decoration: underline;
                text-underline-offset: 0.2rem;
            }

            .frontend-rich-text p:last-child {
                margin-bottom: 0;
            }

            .home-carousel-image {
                aspect-ratio: 16 / 9;
                object-fit: cover;
            }

            .contact-form-card {
                background: rgba(255, 255, 255, 0.96);
                border: 0;
                border-radius: 0.5rem;
                box-shadow: 0 1rem 2.5rem rgba(0, 0, 0, 0.2);
            }
        </style>
    </head>
    <body id="page-top">
        <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="#page-top">CERAPE</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Alternar navegação">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="#about">Sobre</a></li>
                        <li class="nav-item"><a class="nav-link" href="#projects">Projetos</a></li>
                        @if ($showCarousel)
                            <li class="nav-item"><a class="nav-link" href="#carousel">Carrossel</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="#signup">Contato</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <header class="masthead">
            <div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
                <div class="d-flex justify-content-center">
                    <div class="text-center">
                        <h1 class="mx-auto my-0 text-uppercase">{{ $homeTitle }}</h1>
                        <div class="text-white-50 mx-auto mt-2 mb-5 frontend-rich-text">{!! $homeSubtitle !!}</div>
                        <a class="btn btn-primary" href="{{ $homeCtaUrl }}">{{ $homeCtaLabel }}</a>
                    </div>
                </div>
            </div>
        </header>

        <section class="about-section text-center" id="about">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-lg-8">
                        <h2 class="text-white mb-4">{{ $aboutTitle }}</h2>
                        <div class="text-white-50 frontend-rich-text">{!! $aboutSubtitle !!}</div>
                    </div>
                </div>
                <img class="img-fluid" src="{{ $aboutImageUrl }}" alt="{{ $aboutImageAlt }}" />
             </div>
        </section>

        <section class="projects-section bg-light" id="projects">
            <div class="container px-4 px-lg-5">
                <div class="row gx-0 mb-4 mb-lg-5 align-items-center">
                    <div class="col-xl-8 col-lg-7">
                        <img class="img-fluid mb-3 mb-lg-0" src="{{ $projectsImageUrl }}" alt="{{ $projectsImageAlt }}" />
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="featured-text text-center text-lg-left">
                            <h4>{{ $projectsTitle }}</h4>
                            <div class="text-black-50 mb-0 frontend-rich-text">{!! $projectsSubtitle !!}</div>
                        </div>
                    </div>
                </div>

                @if ($showCarousel)
                    <div id="carousel" class="carousel slide shadow" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach ($carouselItems as $index => $slide)
                                <button type="button" data-bs-target="#carousel" data-bs-slide-to="{{ $index }}" @class(['active' => $index === 0]) aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach ($carouselItems as $index => $slide)
                                <div @class(['carousel-item', 'active' => $index === 0])>
                                    <img class="d-block w-100 home-carousel-image" src="{{ $slide['image_url'] }}" alt="{{ $slide['alt'] ?? $slide['title'] ?? 'Slide do carrossel' }}">
                                    <div class="carousel-caption d-none d-md-block">
                                        @if (filled($slide['title'] ?? null))
                                            <h5>{{ $slide['title'] }}</h5>
                                        @endif
                                        @if (filled($slide['description'] ?? null))
                                            <div class="frontend-rich-text">{!! $slide['description'] !!}</div>
                                        @endif
                                        @if (filled($slide['link_url'] ?? null) && filled($slide['link_label'] ?? null))
                                            <a class="btn btn-primary mt-3" href="{{ $slide['link_url'] }}">{{ $slide['link_label'] }}</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Próximo</span>
                        </button>
                    </div>
                @else
                    <div class="row gx-0 mb-5 mb-lg-0 justify-content-center">
                        <div class="col-lg-6"><img class="img-fluid" src="{{ asset('grayscale/assets/img/demo-image-01.jpg') }}" alt="CERAPE detail" /></div>
                        <div class="col-lg-6">
                            <div class="bg-black text-center h-100 project">
                                <div class="d-flex h-100">
                                    <div class="project-text w-100 my-auto text-center text-lg-left">
                                        <h4 class="text-white">Conteúdo editável</h4>
                                        <p class="mb-0 text-white-50">Ative o carrossel no painel para exibir várias imagens com textos, links e ordem personalizada.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gx-0 justify-content-center">
                        <div class="col-lg-6"><img class="img-fluid" src="{{ asset('grayscale/assets/img/demo-image-02.jpg') }}" alt="CERAPE detail" /></div>
                        <div class="col-lg-6 order-lg-first">
                            <div class="bg-black text-center h-100 project">
                                <div class="d-flex h-100">
                                    <div class="project-text w-100 my-auto text-center text-lg-right">
                                        <h4 class="text-white">Imagens com links</h4>
                                        <p class="mb-0 text-white-50">Cada slide pode ter imagem, título, descrição formatada e botão opcional.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <section class="signup-section" id="signup">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5">
                    <div class="col-md-10 col-lg-8 mx-auto text-center">
                        <i class="far fa-paper-plane fa-2x mb-2 text-white"></i>
                        <h2 class="text-white mb-3">{{ $signupTitle }}</h2>
                        <div class="text-white-50 mb-5 frontend-rich-text">{!! $signupSubtitle !!}</div>
                        @if ($signupImageUrl)
                            <img class="img-fluid rounded mb-4" src="{{ $signupImageUrl }}" alt="{{ $signupImageAlt }}" />
                        @endif
                        @if (session('contact_success'))
                            <div class="alert alert-success text-start" role="alert">
                                {{ session('contact_success') }}
                            </div>
                        @endif
                        <form class="contact-form-card p-4 p-md-5 text-start" method="POST" action="{{ route('contact.store') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="contactName">Nome</label>
                                    <input @class(['form-control', 'is-invalid' => $errors->has('name')]) id="contactName" type="text" name="name" value="{{ old('name') }}" placeholder="Seu nome completo" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="contactEmail">E-mail</label>
                                    <input @class(['form-control', 'is-invalid' => $errors->has('email')]) id="contactEmail" type="email" name="email" value="{{ old('email') }}" placeholder="seuemail@exemplo.com" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="contactPhone">Telefone</label>
                                    <input @class(['form-control', 'is-invalid' => $errors->has('phone')]) id="contactPhone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="(00) 00000-0000">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="contactSubject">Assunto</label>
                                    <input @class(['form-control', 'is-invalid' => $errors->has('subject')]) id="contactSubject" type="text" name="subject" value="{{ old('subject') }}" placeholder="Como podemos ajudar?" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="contactMessage">Mensagem</label>
                                    <textarea @class(['form-control', 'is-invalid' => $errors->has('message')]) id="contactMessage" name="message" rows="5" placeholder="Escreva sua mensagem" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 text-end">
                                    <button class="btn btn-primary" type="submit">Enviar mensagem</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-section bg-black">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="card py-4 h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-map-marked-alt text-primary mb-2"></i>
                                <h4 class="text-uppercase m-0">Endereco</h4>
                                <hr class="my-4 mx-auto" />
                                <div class="small text-black-50">CERAPE</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="card py-4 h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-envelope text-primary mb-2"></i>
                                <h4 class="text-uppercase m-0">E-mail</h4>
                                <hr class="my-4 mx-auto" />
                                <div class="small text-black-50"><a href="mailto:contato@cerape.test">contato@cerape.test</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="card py-4 h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-mobile-alt text-primary mb-2"></i>
                                <h4 class="text-uppercase m-0">Telefone</h4>
                                <hr class="my-4 mx-auto" />
                                <div class="small text-black-50">+55 (00) 0000-0000</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="social d-flex justify-content-center">
                    <a class="mx-2" href="#!"><i class="fab fa-twitter"></i></a>
                    <a class="mx-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                    <a class="mx-2" href="#!"><i class="fab fa-github"></i></a>
                </div>
            </div>
        </section>

        <footer class="footer bg-black small text-center text-white-50">
            <div class="container px-4 px-lg-5">Copyright &copy; CERAPE 2026</div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('grayscale/js/scripts.js') }}"></script>
    </body>
</html>
