<?php

namespace App\Filament\Resources\LetterResource\Pages;

use App\Filament\Resources\LetterResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateLetter extends CreateRecord
{
    protected static string $resource = LetterResource::class;


    protected static bool $canCreateAnother = false;
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Letter Created')
            ->body('The letter was created successfully')
            ->duration(4000);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = auth()->id();
//        $data['category_name'] = 'category.name';
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
