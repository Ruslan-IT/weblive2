<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // 🔹 Название в боковом меню
    protected static ?string $navigationLabel = 'Раздел';

    // 🔹 Название в верхнем заголовке страницы
    protected static ?string $modelLabel = 'раздел';

    // 🔹 Множественное число (например, в хлебных крошках)
    protected static ?string $pluralModelLabel = 'Раздел';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make()->schema([

                        TextInput::make('title')
                            ->label('Название')
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

                        RichEditor::make('description')
                            ->label('Описание')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('images/categories')
                        ,
                    ])

                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make()->schema([


                        FileUpload::make('photo')
                            ->label('Фото основное')
                            ->image()
                            ->directory('images/categories/main')
                            ->disk('public')
                            ->visibility('public')
                        ,

                        Select::make('parent_id')
                            ->options(function () {
                                return Category::getCategoriesTree(Category::all());
                            })
                            ->disableOptionWhen(function (Get $get, string $value) {
                                return $value == $get('id');
                            })
                            ->placeholder('Root category'),
                    ])
                ]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Название')
                    ->searchable(),

                TextColumn::make('slug')
                    ->label('URL')
                    ->searchable(),



                ImageColumn::make('photo') // 👈
                ->label('Фото')
                    ->square() // квадратная форма
                    ->size(50) // размер превью
                    ->disk('public') // используем диск storage/app/public
                    ->defaultImageUrl('/images/no-image.png') // если нет фото
                    ->visibility('public'),

                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Обновлено')
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
