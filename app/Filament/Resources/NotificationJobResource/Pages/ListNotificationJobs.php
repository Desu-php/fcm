<?php

namespace App\Filament\Resources\NotificationJobResource\Pages;

use App\Filament\Resources\NotificationJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListNotificationJobs extends ListRecords
{
    protected static string $resource = NotificationJobResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Уведомлений';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
