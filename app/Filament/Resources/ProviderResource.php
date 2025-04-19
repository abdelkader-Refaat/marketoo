<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProviderResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Modules\Providers\App\Models\Provider;

class ProviderResource extends Resource
{
    protected static ?string $model = Provider::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label(__('providers.field.name'))
                ->maxLength(50)
                ->nullable(),

            Forms\Components\FileUpload::make('avatar')
                ->label(__('providers.field.avatar'))
                ->image()
                ->directory('provider-avatars')
                ->nullable(),

            Forms\Components\FileUpload::make('cover')
                ->label(__('providers.field.cover'))
                ->image()
                ->directory('provider-covers')
                ->nullable(),

            Forms\Components\TextInput::make('email')
                ->label(__('providers.field.email'))
                ->email()
                ->maxLength(50)
                ->unique(Provider::class, 'email', ignoreRecord: true)
                ->nullable(),

            Forms\Components\TextInput::make('country_code')
                ->label(__('providers.field.country_code'))
                ->maxLength(5)
                ->default('966'),

            Forms\Components\TextInput::make('phone')
                ->label(__('providers.field.phone'))
                ->maxLength(15)
                ->required(),

            Forms\Components\TextInput::make('password')
                ->label(__('providers.field.password'))
                ->password()
                ->required(fn($record) => !$record)
                ->maxLength(100),

            Forms\Components\Toggle::make('is_active')
                ->label(__('providers.field.is_active')),

            Forms\Components\Toggle::make('is_blocked')
                ->label(__('providers.field.is_blocked')),

            Forms\Components\Toggle::make('is_notify')
                ->label(__('providers.field.is_notify')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('avatar')
                ->label(__('providers.field.avatar'))
                ->circular(),

            Tables\Columns\TextColumn::make('name')
                ->label(__('providers.field.name'))
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('email')
                ->label(__('providers.field.email'))
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('phone')
                ->label(__('providers.field.phone'))
                ->sortable()
                ->searchable(),

            Tables\Columns\BooleanColumn::make('is_active')
                ->label(__('providers.field.is_active')),

            Tables\Columns\BooleanColumn::make('is_blocked')
                ->label(__('providers.field.is_blocked')),

            Tables\Columns\BooleanColumn::make('is_notify')
                ->label(__('providers.field.is_notify')),

            Tables\Columns\TextColumn::make('created_at')
                ->label(__('providers.field.created_at'))
                ->dateTime()
                ->sortable(),
        ])
            ->filters([
                Tables\Filters\Filter::make('is_active')
                    ->label(__('providers.filters.active'))
                    ->query(fn(Builder $query) => $query->where('is_active', true)),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProviders::route('/'),
            'create' => Pages\CreateProvider::route('/create'),
            'edit' => Pages\EditProvider::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }
}
