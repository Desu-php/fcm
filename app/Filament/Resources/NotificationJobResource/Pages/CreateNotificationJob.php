<?php

namespace App\Filament\Resources\NotificationJobResource\Pages;

use App\Enums\NotificationJobStatusEnum;
use App\Filament\Resources\NotificationJobResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNotificationJob extends CreateRecord
{
    protected static string $resource = NotificationJobResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = NotificationJobStatusEnum::Pending;

        return $data;
    }
}
