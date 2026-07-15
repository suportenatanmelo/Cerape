<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class UserReportController extends Controller
{
    public function __invoke(User $user): Response
    {
        abort_unless(auth()->id() === $user->id || canAccess(User::PERMISSION_USER_VIEW), 403);

        $user->loadMissing(['roles', 'permissions']);

        $filename = Str::of($user->name ?: 'usuario')
            ->slug()
            ->prepend('perfil-usuario-')
            ->append('.pdf')
            ->toString();

        return Pdf::loadView('pdf.user-report', [
            'user' => $user,
            'sections' => $this->getReportSections($user),
        ])
            ->setPaper('a4')
            ->download($filename);
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function getReportSections(User $user): array
    {
        return [
            'Identificação' => [
                'Nome' => $this->formatValue($user->name),
                'E-mail' => $this->formatValue($user->email),
                'Telefone / WhatsApp' => $this->formatValue($user->phone_whatsapp),
                'CPF' => $this->formatValue($user->cpf),
                'Endereço' => $this->formatValue($user->address),
            ],
            'Situação de acesso' => [
                'Acesso ao painel' => $user->access_status_label,
                'Status de permissão' => $user->permission_status_label,
                'Papéis' => $this->formatValue(
                    $user->roles
                        ->pluck('name')
                        ->map(fn (string $role): string => $this->roleLabel($role))
                        ->all()
                ),
                'Permissões diretas' => $this->formatValue(
                    $user->permissions
                        ->pluck('name')
                        ->map(fn (string $permission): string => User::permissionLabel($permission))
                        ->all()
                ),
            ],
            'Controle do cadastro' => [
                'E-mail verificado em' => $user->email_verified_at?->format('d/m/Y H:i') ?? '-',
                'Criado em' => $user->created_at?->format('d/m/Y H:i') ?? '-',
                'Atualizado em' => $user->updated_at?->format('d/m/Y H:i') ?? '-',
            ],
        ];
    }

    private function roleLabel(string $role): string
    {
        return match ($role) {
            User::ROLE_SUPER_ADMIN => 'Super administrador',
            User::ROLE_ADMIN => 'Administrador',
            User::ROLE_CADASTROS => 'Cadastros',
            User::ROLE_LEITURA => 'Leitura',
            default => str($role)->replace(['_', '-'], ' ')->title()->toString(),
        };
    }

    private function formatValue(mixed $value): string
    {
        if (is_array($value)) {
            $value = collect($value)
                ->filter(fn (mixed $item): bool => filled($item))
                ->implode(', ');
        }

        if (is_string($value)) {
            $value = trim(strip_tags($value));
        }

        return filled($value) ? (string) $value : '-';
    }
}
