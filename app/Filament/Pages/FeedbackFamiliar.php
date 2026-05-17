<?php

namespace App\Filament\Pages;

use App\Models\Acolhido;
use App\Models\FeedbackFamiliarMessage;
use App\Models\User;
use App\Support\AcolhidoAccess;
use App\Support\FilamentDatabaseNotifications;
use App\Support\PortalContext;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class FeedbackFamiliar extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Feedback familiar';

    protected static ?string $title = 'Feedback familiar';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.feedback-familiar';

    public ?int $selectedAcolhidoId = null;

    public string $message = '';

    public function mount(): void
    {
        $this->selectedAcolhidoId = $this->resolveInitialAcolhidoId();

        $this->markCurrentConversationAsRead();
    }

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return PortalContext::portalNavigationGroup();
    }

    public static function getNavigationBadge(): ?string
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return null;
        }

        $count = static::unreadMessagesQuery($user)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Mensagens de feedback aguardando leitura';
    }

    public function selectAcolhido(int $acolhidoId): void
    {
        if (! $this->canAccessAcolhido($acolhidoId)) {
            return;
        }

        $this->selectedAcolhidoId = $acolhidoId;
        $this->markCurrentConversationAsRead();
    }

    public function sendMessage(): void
    {
        $user = auth()->user();

        if (! $user instanceof User || $this->selectedAcolhidoId === null || ! $this->canAccessAcolhido($this->selectedAcolhidoId)) {
            return;
        }

        $this->validate([
            'message' => ['required', 'string', 'min:3', 'max:4000'],
        ], [], [
            'message' => 'mensagem',
        ]);

        $message = FeedbackFamiliarMessage::create([
            'acolhido_id' => $this->selectedAcolhidoId,
            'sender_id' => $user->getKey(),
            'mensagem' => trim($this->message),
            'delivered_at' => now(),
            'read_by_family_at' => PortalContext::isFamilyUser($user) ? now() : null,
            'read_by_institution_at' => PortalContext::isFamilyUser($user) ? null : now(),
        ]);

        $message->loadMissing(['acolhido', 'sender']);

        $this->notifyRecipients($message, $user);
        $this->message = '';
        $this->markCurrentConversationAsRead();

        Notification::make()
            ->title('Mensagem enviada')
            ->body('Seu feedback foi entregue e entrou no historico da conversa.')
            ->success()
            ->send();
    }

    public function refreshMessages(): void
    {
        $this->markCurrentConversationAsRead();
    }

    public function getConversations(): Collection
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return collect();
        }

        $query = Acolhido::query()
            ->where('ativo', true)
            ->where(function (Builder $query): void {
                $query
                    ->whereHas('familyUsers')
                    ->orWhereHas('feedbackMessages');
            })
            ->with(['familyUsers:id,name,acolhido_id'])
            ->withCount([
                'feedbackMessages as unread_feedback_count' => function (Builder $query) use ($user): void {
                    if (PortalContext::isFamilyUser($user)) {
                        $query->whereNull('read_by_family_at');
                    } else {
                        $query->whereNull('read_by_institution_at');
                    }
                },
            ])
            ->addSelect([
                'last_feedback_body' => FeedbackFamiliarMessage::query()
                    ->select('mensagem')
                    ->whereColumn('acolhido_id', 'acolhidos.id')
                    ->latest('created_at')
                    ->limit(1),
                'last_feedback_at' => FeedbackFamiliarMessage::query()
                    ->select('created_at')
                    ->whereColumn('acolhido_id', 'acolhidos.id')
                    ->latest('created_at')
                    ->limit(1),
            ])
            ->orderByDesc('last_feedback_at')
            ->orderBy('nome_completo_paciente');

        if (PortalContext::isFamilyUser($user)) {
            $query->whereKey($user->linkedAcolhidoId());
        }

        return $query
            ->get()
            ->map(function (Acolhido $acolhido) use ($user): array {
                $familyNames = $acolhido->familyUsers
                    ->pluck('name')
                    ->filter()
                    ->values();

                return [
                    'id' => (int) $acolhido->getKey(),
                    'nome' => $acolhido->nome_completo_paciente,
                    'familia' => $familyNames->join(', '),
                    'ultimo_feedback' => $acolhido->last_feedback_body,
                    'ultima_data' => $acolhido->last_feedback_at,
                    'nao_lidas' => (int) ($acolhido->unread_feedback_count ?? 0),
                    'restrito' => PortalContext::isFamilyUser($user),
                ];
            });
    }

    public function getMessages(): Collection
    {
        if ($this->selectedAcolhidoId === null) {
            return collect();
        }

        return FeedbackFamiliarMessage::query()
            ->with(['sender:id,name,acolhido_id'])
            ->where('acolhido_id', $this->selectedAcolhidoId)
            ->orderBy('created_at')
            ->get();
    }

    public function getCurrentAcolhido(): ?Acolhido
    {
        if ($this->selectedAcolhidoId === null) {
            return null;
        }

        return Acolhido::query()
            ->with(['familyUsers:id,name,acolhido_id'])
            ->find($this->selectedAcolhidoId);
    }

    public function isMessageFromCurrentUser(FeedbackFamiliarMessage $message): bool
    {
        return (int) $message->sender_id === (int) auth()->id();
    }

    public function isFamilySender(FeedbackFamiliarMessage $message): bool
    {
        return filled($message->sender?->acolhido_id);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Atualizar conversa')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action('refreshMessages'),
        ];
    }

    private function resolveInitialAcolhidoId(): ?int
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return null;
        }

        if (PortalContext::isFamilyUser($user)) {
            return $user->linkedAcolhidoId();
        }

        $requestedId = request()->integer('acolhido');

        if ($requestedId !== 0 && $this->canAccessAcolhido($requestedId)) {
            return $requestedId;
        }

        return $this->getConversations()->first()['id'] ?? null;
    }

    private function canAccessAcolhido(int $acolhidoId): bool
    {
        return AcolhidoAccess::canAccessAcolhido(auth()->user(), $acolhidoId);
    }

    private function markCurrentConversationAsRead(): void
    {
        $user = auth()->user();

        if (! $user instanceof User || $this->selectedAcolhidoId === null) {
            return;
        }

        FeedbackFamiliarMessage::query()
            ->where('acolhido_id', $this->selectedAcolhidoId)
            ->when(
                PortalContext::isFamilyUser($user),
                fn (Builder $query): Builder => $query->whereNull('read_by_family_at'),
                fn (Builder $query): Builder => $query->whereNull('read_by_institution_at'),
            )
            ->update([
                PortalContext::isFamilyUser($user) ? 'read_by_family_at' : 'read_by_institution_at' => now(),
            ]);
    }

    private function notifyRecipients(FeedbackFamiliarMessage $message, User $sender): void
    {
        $recipients = AcolhidoAccess::notificationRecipientsForAcolhido((int) $message->acolhido_id)
            ->reject(fn (User $user): bool => (int) $user->getKey() === (int) $sender->getKey())
            ->values();

        if ($recipients->isEmpty()) {
            return;
        }

        $acolhidoNome = $message->acolhido?->nome_completo_paciente ?? 'acolhido';
        $body = mb_strimwidth($message->mensagem, 0, 120, '...');

        FilamentDatabaseNotifications::send(
            Notification::make()
                ->title('Novo feedback familiar')
                ->body($sender->name . ' enviou uma mensagem sobre ' . $acolhidoNome . ': ' . $body)
                ->icon('heroicon-o-chat-bubble-left-right')
                ->info()
                ->actions([
                    Action::make('openFeedbackFamiliar')
                        ->label('Abrir conversa')
                        ->button()
                        ->markAsRead()
                        ->url(static::getUrl(['acolhido' => $message->acolhido_id])),
                ])
                ->viewData([
                    'key' => 'feedback_familiar_' . $message->getKey(),
                ]),
            $recipients,
        );
    }

    private static function unreadMessagesQuery(User $user): Builder
    {
        $query = FeedbackFamiliarMessage::query();

        if (PortalContext::isFamilyUser($user)) {
            $query->where('acolhido_id', $user->linkedAcolhidoId())
                ->whereNull('read_by_family_at');
        } else {
            $query->whereNull('read_by_institution_at');
        }

        return $query;
    }
}
