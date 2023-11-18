<?php

namespace App\Filament\Resources\MemoResource\Pages;

use App\Filament\Resources\MemoResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateMemo extends CreateRecord
{
    protected static string $resource = MemoResource::class;

    protected static bool $canCreateAnother = false;
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Memo Created')
            ->body('The memo was created successfully')
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
