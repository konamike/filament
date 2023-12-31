<?php

namespace App\Filament\Resources\LetterResource\Pages;

use App\Filament\Resources\LetterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLetters extends ListRecords
{
    protected static string $resource = LetterResource::class;
    protected static ?string $title = 'All Letters';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-envelope')
                ->color('warning')
                ->label('Create Letter'),
        ];
    }
}
