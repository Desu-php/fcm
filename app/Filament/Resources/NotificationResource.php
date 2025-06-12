<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Filament\Resources\NotificationResource\RelationManagers;
use App\Models\Device;
use App\Models\Notification;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class NotificationResource extends Resource
{
    protected static ?string $navigationLabel = 'История уведомлений';

    protected static ?string $model = Notification::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('notificationJob.title')
                    ->label('Уведомление'),
                TextColumn::make('notificationJob.body')
                    ->label('Сообщение')
                    ->wrap(),
                TextColumn::make('status')
                    ->label('Статус'),
                TextColumn::make('notifiable_id')
                    ->label('ID Устройство'),
                TextColumn::make('created_at')
                    ->label('Дата отправки')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('notification_job_id')
                    ->label('Уведомление')
                    ->relationship('notificationJob', 'title')
                    ->searchable(),

                Filter::make('Устройство')
                    ->form([
                        Select::make('notifiable_id')
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search): array => Device::where('device_token', 'like', "%{$search}%")->limit(50)->pluck('device_token', 'id')->toArray())
                            ->getOptionLabelUsing(fn (int $value): ?string => Device::find($value)?->device_token),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['notifiable_id'],
                                fn (Builder $query, int $id): Builder => $query->where('notifiable_id', $id)->where('notifiable_type', Device::class),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
