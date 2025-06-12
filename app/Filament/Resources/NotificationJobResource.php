<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\NotificationJobStatusEnum;
use App\Filament\Resources\NotificationJobResource\Pages;
use App\Filament\Resources\NotificationJobResource\RelationManagers;
use App\Models\NotificationJob;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotificationJobResource extends Resource
{
    protected static ?string $navigationLabel = 'Уведомлений';

    protected static ?string $model = NotificationJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Заголовок')
                    ->required()
                    ->maxLength(100)
                    ->rule('string'),
                TextInput::make('body')
                    ->label('Сообщение')
                    ->required()
                    ->maxLength(2000)
                    ->rule('string'),
                DateTimePicker::make('scheduled_at')
                    ->required()
                    ->label('Запланировать на')
                    ->after(now()->toDatetimeString())
                    ->displayFormat('Y-m-d H:i')
                    ->closeOnDateSelection()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('body')
                    ->label('Сообщение')
                    ->limit(50)
                    ->toggleable()
                    ->wrap(),
                TextColumn::make('scheduled_at')
                    ->label('Запланировано на')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Создано')
                    ->sortable()
                    ->since(),
                TextColumn::make('updated_at')
                    ->label('Создано')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListNotificationJobs::route('/'),
            'create' => Pages\CreateNotificationJob::route('/create'),
            'edit' => Pages\EditNotificationJob::route('/{record}/edit'),
        ];
    }
}
