<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemoResource\Pages;
use App\Filament\Resources\MemoResource\RelationManagers;
use App\Models\Category;
use App\Models\Memo;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class MemoResource extends Resource
{
    protected static ?string $model = Memo::class;

    protected static ?string $navigationGroup = 'All Documents';
    protected static ?string $navigationLabel = 'Memo In';
    protected static ?int $navigationSort = 3;

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
                            ->options(Category::where('document_type', 'MEMO')->pluck('name', 'id')->toArray())
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
                                Forms\Components\DatePicker::make('date_retrieved'),
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
//                                Forms\Components\Textarea::make('treated_notes')
//                                    ->maxLength(65535)
//                                    ->columnSpan(2),
                            ])->disabled('edit')
                            ->columns(2),
                        Tabs\Tab::make('Document Dispatch')
                            ->icon('heroicon-s-bell')
                            ->schema([
                                Forms\Components\DatePicker::make('date_dispatched'),
                                Forms\Components\TextInput::make('sent_from')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('sent_to')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('dispatch_phone')
                                    ->tel()
                                    ->maxLength(11),
                                Forms\Components\TextInput::make('dispatch_email')
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('dispatched_by')
                                    ->maxLength(255),
//                                Forms\Components\Textarea::make('dispatch_note')
//                                    ->maxLength(65535)
//                                    ->columnSpanFull(),
                                Forms\Components\Toggle::make('dispatched')
                                    ->offIcon('heroicon-m-no-symbol')
                                    ->offColor('danger')
                                    ->onIcon('heroicon-m-check-badge')
                                    ->inline(true)
                                    ->required()
                            ])->disabled('edit')
                            ->columns(3),
                    ])->columnSpanFull()
                    ->contained(true)->visibleOn(['view', 'edit']),

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
            'index' => Pages\ListMemos::route('/'),
            'create' => Pages\CreateMemo::route('/create'),
            'view' => Pages\ViewMemo::route('/{record}'),
            'edit' => Pages\EditMemo::route('/{record}/edit'),
        ];
    }
}
