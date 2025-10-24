<?php


namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Платежи';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Имя')->required(),
            Forms\Components\TextInput::make('phone')->label('Телефон')->required(),
            Forms\Components\TextInput::make('product')->label('Товар')->required(),
            Forms\Components\TextInput::make('amount')->label('Сумма')->numeric()->required(),
            Forms\Components\Select::make('status')
                ->label('Статус')
                ->options([
                    0 => 'Создан',
                    1 => 'Ожидает оплаты',
                    2 => 'Оплачен',
                    3 => 'Отменён',
                    4 => 'Ошибка',
                ])
                ->default(1),
            Forms\Components\TextInput::make('payment_method')->label('Метод оплаты')->default('robokassa'),
            Forms\Components\Textarea::make('robokassa_signature')->label('Подпись'),
            Forms\Components\Textarea::make('robokassa_response')
                ->label('Ответ Robokassa')
                ->rows(8)
                ->columnSpanFull(),
            Forms\Components\DateTimePicker::make('paid_at')->label('Оплачен в'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Имя')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('Телефон'),
                Tables\Columns\TextColumn::make('product')->label('Товар'),
                Tables\Columns\TextColumn::make('amount')->label('Сумма')->money('rub'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Статус')
                    ->colors([
                        'warning' => fn ($state) => $state == 1,
                        'success' => fn ($state) => $state == 2,
                        'danger' => fn ($state) => $state == 3,
                        'gray' => fn ($state) => $state == 4,
                    ])
                    ->formatStateUsing(fn ($record) => $record->status_label),
                Tables\Columns\TextColumn::make('paid_at')->label('Дата оплаты')->dateTime(),
                Tables\Columns\TextColumn::make('created_at')->label('Создан')->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        1 => 'Ожидает оплаты',
                        2 => 'Оплачен',
                        3 => 'Отменён',
                        4 => 'Ошибка',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
