<?php

namespace App\Filament\Resources\FiledispatchResource\Pages;

use App\Filament\Resources\FiledispatchResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditFiledispatch extends EditRecord
{
    protected static string $resource = FiledispatchResource::class;
    protected static ?string $title = 'Dispatch File';
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    protected static bool $canCreateAnother = false;
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('File Dispatched')
            ->body('The file was scheduled for dispatch successfully')
            ->duration(4000);
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
