<?php

namespace App\Filament\Frontend\Resources\NewsletterSubscriberResource\Pages;

use App\Filament\Frontend\Resources\NewsletterSubscriberResource;
use App\Models\NewsletterSubscriber;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ManageNewsletterSubscribers extends ManageRecords
{
    protected static string $resource = NewsletterSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportar')
                ->label('Exportar CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn (): StreamedResponse => response()->streamDownload(function (): void {
                    $output = fopen('php://output', 'w');
                    fputcsv($output, ['nome', 'email', 'telefone', 'origem', 'inscrito_em', 'ativo']);

                    NewsletterSubscriber::query()
                        ->orderBy('email')
                        ->each(function (NewsletterSubscriber $subscriber) use ($output): void {
                            fputcsv($output, [
                                $subscriber->name,
                                $subscriber->email,
                                $subscriber->phone,
                                $subscriber->source,
                                optional($subscriber->subscribed_at)->format('Y-m-d H:i:s'),
                                $subscriber->is_active ? 'sim' : 'não',
                            ]);
                        });

                    fclose($output);
                }, 'newsletter.csv')),
            CreateAction::make()->label('Cadastrar inscrito'),
        ];
    }
}
