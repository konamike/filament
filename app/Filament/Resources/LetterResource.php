<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LetterResource\Pages;
use App\Filament\Resources\LetterResource\RelationManagers;
use App\Models\Category;
use App\Models\Letter;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;

class LetterResource extends Resource
{
    protected static ?string $model = Letter::class;
    protected static ?string $navigationIcon = 'heroicon-s-envelope';
    protected static ?string $navigationGroup = 'Incoming Documents';
    protected static ?string $navigationLabel = 'Letter';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('MAIN DETAILS')
                        ->schema([
                            Forms\Components\Textarea::make('description')
                                ->required()
                                ->label('File Description')
                                ->maxLength(65535)
                                ->columnSpanFull(),
                            Forms\Components\Select::make('contractor_id')
                                ->label('Mail Source')
                                ->relationship('contractor', 'name')
                                ->required()
                                ->default(1),
                            Forms\Components\Select::make('category_id')
                                ->label('Category')
                                ->searchable()
                                ->options(Category::where('document_type', 'LETTER')->pluck('name', 'id'))
                                ->preload()
                                ->label('Document Category')
                                ->reactive(),
                            Forms\Components\Select::make('received_by')
                                ->label('Received By')
                                ->options(User::where('is_admin', 0)->pluck('name', 'id'))
                                ->preload()
                                ->searchable(),
                            Forms\Components\DatePicker::make('date_received')
                                ->native(false)
                                ->required(),
                            Forms\Components\TextInput::make('doc_author')
                                ->label('Document Author')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('file_number')
                                ->maxLength(255),
                            ])->columns(3),

                Forms\Components\Fieldset::make('OTHER DETAILS')
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->numeric(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->minLength(11)
                            ->maxLength(11),
                        Forms\Components\Textarea::make('remarks')
                            ->maxLength(65535)
                            ->columnSpanFull(),


                        ]),

                Tabs::make('Label')
                    ->tabs([
                        Tabs\Tab::make('Document Retrieval')
                            ->icon('heroicon-s-wallet')
                            ->schema([
                                Forms\Components\TextInput::make('hand_carried')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('retrieved_by')
                                    ->maxLength(255),
                                Forms\Components\DatePicker::make('date_retrieved')
                                ->native(false),
                                Forms\Components\Toggle::make('treated')
                                    ->offIcon('heroicon-m-no-symbol')
                                    ->offColor('danger')
                                    ->onIcon('heroicon-m-check-badge')
                                    ->inline(false)
                                    ->required(),
                            ])->columns(4),
                        Tabs\Tab::make('Document Review')
                            ->icon('heroicon-s-circle-stack')
                            ->schema([
                                Forms\Components\TextInput::make('treated_by')
                                    ->numeric(),
                                Forms\Components\DatePicker::make('date_treated'),
                            ])
                            ->disabled('edit')
                            ->columns(2),
                        Tabs\Tab::make('Document Dispatch')
                            ->icon('heroicon-s-bell')
                            ->schema([
                                Forms\Components\DatePicker::make('date_dispatched')
                                ->native(false),
                                Forms\Components\TextInput::make('sent_from')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('sent_to')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('dispatch_phone'),
                                Forms\Components\TextInput::make('dispatch_email')
                                    ->email(),
                                Forms\Components\TextInput::make('dispatched_by')
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('dispatched')
                                    ->offIcon('heroicon-m-no-symbol')
                                    ->offColor('danger')
                                    ->onIcon('heroicon-m-check-badge')
                                    ->inline(true),
                            ])
                            ->disabled('edit')
                            ->columns(3),
                    ])->columnSpanFull()
                    ->contained(true)
                    ->visibleOn(['view', 'edit']),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->wrap()
                    ->label('Letter Description')
                    ->sortable(),
                Tables\Columns\TextColumn::make('doc_author')
                    ->label('Document Author')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_received')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('treated')
                    ->boolean(),
                Tables\Columns\TextColumn::make('date_treated')
                    ->date(),
                Tables\Columns\IconColumn::make('dispatched')
                    ->boolean(),
                Tables\Columns\TextColumn::make('date_dispatched')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListLetters::route('/'),
            'create' => Pages\CreateLetter::route('/create'),
            'view' => Pages\ViewLetter::route('/{record}'),
            'edit' => Pages\EditLetter::route('/{record}/edit'),
        ];
    }
}
