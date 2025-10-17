<?php

namespace App\Filament\Resources;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;


    // 🔹 Название в боковом меню
    protected static ?string $navigationLabel = 'Лендинг';

    // 🔹 Название в верхнем заголовке страницы
    protected static ?string $modelLabel = 'лендинг';

    // 🔹 Множественное число (например, в хлебных крошках)
    protected static ?string $pluralModelLabel = 'Лендинги';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('title')
                    ->label('Название1')
                    ->required()
                    ->live(true)
                    ->maxLength(255)
                    ->afterStateUpdated(function (Set $set, Get $get, ?string $state,
                                                  string $operation) {
                        if ($operation === 'edit' && $get('slug')) {
                            return;
                        }
                        $set('slug', Str::slug($state));
                    })
                ,
                TextInput::make('slug')
                    ->label('URL')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->hint('Генерируется автоматически')
                ,

                Select::make('category_id')
                    ->options(function () {
                        return Category::getCategoriesTree(Category::all());
                    })
                    ->required()
                    ->exists(table: Category::class, column: 'id')
                    ->placeholder('Выберите категорию'),

                TextInput::make('price')
                    ->label('Цена')
                    ->required()
                    ->numeric()
                    ->prefix('Р'),


                Repeater::make('content_blocks')
                    ->label('Контентные блоки')
                    ->orderable()
                    ->schema([
                        Select::make('type')
                            ->label('Тип блока')
                            ->options([
                                'text' => 'Текст',
                                'image' => 'Фото',
                                'button' => 'Кнопка',
                            ])
                            ->reactive()
                            ->required(),

                        TinyEditor::make('description')
                            ->label('Текст блока или фото')
                            ->profile('full')
                            ->fileAttachmentsDisk('public') // Явно указываем диск
                            ->fileAttachmentsDirectory('images/editor') // Папка для загрузок редактора
                            ->columnSpanFull()
                            ->visible(fn ($get) => $get('type') === 'text'),

                        FileUpload::make('image')
                            ->label('Фото блока')
                            ->disk('public')
                            ->directory('images/blocks')
                            ->visibility('public')
                            ->visible(fn ($get) => $get('type') === 'image'),

                        TextInput::make('button_text')
                            ->label('Текст кнопки')
                            ->maxLength(50)
                            ->visible(fn ($get) => $get('type') === 'button'),

                        TextInput::make('button_url')
                            ->label('Ссылка кнопки')
                            ->url()
                            ->visible(fn ($get) => $get('type') === 'button'),
                    ])
                    ->createItemButtonLabel('Добавить блок')
                    ->columnSpanFull()
                ,

                Toggle::make('is_visible')
                    ->label('Опубликовано')
                    ->default(true)
                    ->required(),

                Toggle::make('is_featured')
                    ->label('В топе')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('URL')
                    ->searchable(),
                TextColumn::make('price')
                    ->money()
                    ->sortable(),
                IconColumn::make('is_visible')
                    ->label('Опубликовано')
                    ->boolean(),


                ImageColumn::make('photo') // 👈
                ->label('Фото')
                    ->square() // квадратная форма
                    ->size(80) // размер превью
                    ->disk('public') // используем диск storage/app/public
                    ->defaultImageUrl('/images/no-image.png') // если нет фото
                    ->visibility('public'),


                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
