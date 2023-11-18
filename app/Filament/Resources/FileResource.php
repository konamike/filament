<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileResource\Pages;
use App\Filament\Resources\FileResource\RelationManagers;
use App\Models\File;
use App\Models\Category;
use App\Models\Contractor;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class FileResource extends Resource
{
    protected static ?string $model = File::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';
    protected static ?string $navigationGroup = 'All Documents';
    protected static ?string $navigationLabel = 'Files In';
    protected static ?int $navigationSort = 1;

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

                Forms\Components\FieldSet::make('Primary Details')
                    ->schema(components: [
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->maxLength(65535)
                            ->label('File Description')
                            ->columnSpanFull(),


                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(Category::where('document_type', 'FILE')->pluck('name', 'id')->toArray())
                            ->preload()
                            ->label('Document Category')
                            ->reactive(),
//                            ->afterStateUpdated(fn (Callable $set) => $set ('category_name', 'name')),
//                        TextInput::make('category_name')
//                            ->password()
//                            ->hidden(fn (Closure $get) => $get('category_id')),
                        Forms\Components\Hidden::make('category_name')
                        ->default('category.name'),


                        Forms\Components\Select::make('contractor_id')
                            ->relationship('contractor', 'name')
                            ->native(false)
                            ->preload()
                            ->searchable()
                            ->default(1),
                        Forms\Components\TextInput::make('file_number')
//                            ->formatStateUsing(fn (string $state): string => strtoupper($state))
                            ->maxLength(100),
                        Forms\Components\TextInput::make('amount')
                            ->numeric(),
                        Forms\Components\Select::make('received_by')
                            ->label('Received By')
                            ->options(User::where('is_admin', 0)->pluck('name', 'id'))
                            ->preload()
                            ->searchable(),
                        Forms\Components\DatePicker::make('date_received')
                            ->native(false)
                            ->required(),
                    ])->columns(3),

                    Forms\Components\Fieldset::make('Other Details')
                        ->schema([
                            Forms\Components\TextInput::make('doc_author')
                                ->label('Document Author')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('doc_sender')
                                ->label('Document Sender')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->email(),
                            Forms\Components\Textarea::make('remarks')
                                ->maxLength(65535)
                                ->columnSpanFull(),
                                    ])->columns(3),
                Forms\Components\Fieldset::make('Document Retrievals')
                        ->schema([
                            Forms\Components\TextInput::make('hand_carried')
                                ->maxLength(255),
//                                ->visibleOn(['view', 'edit']),
                            Forms\Components\TextInput::make('retrieved_by')
                                ->maxLength(255),
//                                ->visibleOn(['view', 'edit']),
                            Forms\Components\DatePicker::make('date_retrieved')
                                ->native(false),
//                                ->visibleOn(['view', 'edit']),
                        ])->visibleOn(['view', 'edit'])
                          ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->label('Description')
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_received')
                    ->date(),
                Tables\Columns\TextColumn::make('doc_author')
                    ->label('Document Author')
                    ->searchable(),
                Tables\Columns\IconColumn::make('treated')
                    ->boolean(),
                Tables\Columns\TextColumn::make('date_treated')
                    ->label('Date Treated')
                    ->date(),
                Tables\Columns\IconColumn::make('dispatched')
                    ->boolean(),
                Tables\Columns\TextColumn::make('date_dispatched')
                    ->label('Date Dispatched')
                    ->date(),
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
            'index' => Pages\ListFiles::route('/'),
            'create' => Pages\CreateFile::route('/create'),
            'view' => Pages\ViewFile::route('/{record}'),
            'edit' => Pages\EditFile::route('/{record}/edit'),
        ];
    }
}
