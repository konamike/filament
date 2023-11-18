<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CreateFile extends CreateRecord
{
    protected static string $resource = FileResource::class;


    protected static bool $canCreateAnother = false;
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('File Created')
            ->body('The file was created successfully')
            ->duration(4000);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = auth()->id();
        $data['category_name'] = 'category.name';
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


}
