<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListNotifications extends ListRecords
{
    protected static string $resource = NotificationResource::class;

    /**
     * @return string|null
     */
    public function getTitle(): string | Htmlable
    {
       return 'История уведомлений';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
