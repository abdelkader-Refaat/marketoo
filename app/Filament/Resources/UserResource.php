<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('users.field.name'))
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->label(__('users.field.email'))
                    ->email()
                    ->required()
                    ->unique(User::class, 'email'),

                Forms\Components\TextInput::make('password')
                    ->label(__('users.field.password'))
                    ->password()
                    ->required(fn($record) => !$record) // Only required on create
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_admin')
                    ->label(__('users.field.is_admin'))
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\BooleanColumn::make('is_admin')
                    ->label(__('users.field.is_admin')),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_admin')
                    ->query(fn($query) => $query->where('is_admin', true))
                    ->label(__('users.filters.admins')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
