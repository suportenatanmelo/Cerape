<x-filament-panels::page>
    <style>
        .profile-shell {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: minmax(19rem, 24rem) minmax(0, 1fr);
        }

        .profile-panel,
        .profile-form {
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.95));
            border: 1px solid rgba(148, 163, 184, 0.22);
            border-radius: 1.5rem;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        .profile-panel {
            overflow: hidden;
        }

        .profile-brand {
            align-items: center;
            background: linear-gradient(135deg, #0f766e, #115e59);
            color: #f8fafc;
            display: flex;
            gap: 1rem;
            padding: 1.25rem;
        }

        .profile-brand-logo {
            background: rgba(255,255,255,0.14);
            border-radius: 1rem;
            max-height: 64px;
            max-width: 128px;
            padding: 0.5rem;
        }

        .profile-brand-title {
            font-size: 1.05rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .profile-brand-subtitle {
            color: rgba(248, 250, 252, 0.86);
            font-size: 0.92rem;
            margin-top: 0.2rem;
        }

        .profile-card {
            padding: 1.25rem;
        }

        .avatar-card {
            align-items: center;
            background: #fff;
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 1.25rem;
            display: flex;
            gap: 1rem;
            padding: 1rem;
        }

        .avatar-image,
        .avatar-placeholder {
            border-radius: 1rem;
            height: 92px;
            object-fit: cover;
            width: 92px;
        }

        .avatar-image {
            border: 3px solid #cbd5e1;
        }

        .avatar-placeholder {
            align-items: center;
            background: #e2e8f0;
            border: 2px dashed #94a3b8;
            color: #334155;
            display: flex;
            font-size: 2rem;
            font-weight: 800;
            justify-content: center;
        }

        .avatar-meta {
            min-width: 0;
        }

        .avatar-name {
            color: #0f172a;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .avatar-email {
            color: #475569;
            font-size: 0.9rem;
            margin-top: 0.25rem;
            word-break: break-word;
        }

        .profile-stats {
            display: grid;
            gap: 0.75rem;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin-top: 1rem;
        }

        .profile-stat {
            background: #fff;
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 1rem;
            padding: 0.9rem;
        }

        .profile-stat-label {
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .profile-stat-value {
            color: #0f172a;
            font-size: 0.92rem;
            font-weight: 700;
            margin-top: 0.35rem;
        }

        .profile-form {
            padding: 1.25rem;
        }

        .profile-form-note {
            color: #475569;
            font-size: 0.92rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 1100px) {
            .profile-shell {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @php
        use App\Support\PdfImage;

        $user = auth()->user();
        $avatarUrl = $user?->getFilamentAvatarUrl();
        $logoUrl = PdfImage::publicUrl('storage/images/logo.png');
    @endphp

    <div class="profile-shell">
        <div class="profile-panel">
            <div class="profile-brand">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo CERAPE" class="profile-brand-logo">
                @else
                    <div class="profile-brand-logo" style="align-items:center;display:flex;justify-content:center;color:#f8fafc;font-weight:800;letter-spacing:0.08em;">CERAPE</div>
                @endif
                <div>
                    <div class="profile-brand-title">Meu perfil</div>
                    <div class="profile-brand-subtitle">Dados de acesso e identificação visual do usuário.</div>
                </div>
            </div>

            <div class="profile-card">
                <div class="avatar-card">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="Foto do usuário" class="avatar-image">
                    @else
                        <div class="avatar-placeholder">{{ str($user?->name ?? '?')->substr(0, 1)->upper() }}</div>
                    @endif

                    <div class="avatar-meta">
                        <div class="avatar-name">{{ $user?->name ?? 'Usuário' }}</div>
                        <div class="avatar-email">{{ $user?->email ?? '-' }}</div>
                    </div>
                </div>

                <div class="profile-stats">
                    <div class="profile-stat">
                        <div class="profile-stat-label">Status</div>
                        <div class="profile-stat-value">{{ ($user?->active_status ?? true) ? 'Ativo' : 'Inativo' }}</div>
                    </div>
                    <div class="profile-stat">
                        <div class="profile-stat-label">Nacionalidade</div>
                        <div class="profile-stat-value">{{ $user?->nacionalidade ?: 'Brasileira' }}</div>
                    </div>
                    <div class="profile-stat">
                        <div class="profile-stat-label">CPF</div>
                        <div class="profile-stat-value">{{ $user?->cpf ?: '-' }}</div>
                    </div>
                    <div class="profile-stat">
                        <div class="profile-stat-label">Nascimento</div>
                        <div class="profile-stat-value">{{ $user?->data_nascimento?->format('d/m/Y') ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-form">
            <div class="profile-form-note">
                Atualize sua foto, dados pessoais e senha em uma tela clara e organizada.
            </div>
            {{ $this->form }}
        </div>
    </div>
</x-filament-panels::page>
