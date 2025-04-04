<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Modules\Users\App\Models\User;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('users.field.name')),

                Forms\Components\FileUpload::make('avatar')
                    ->label(__('admin.fields.avatar'))
                    ->image()
                    ->directory('user-avatars')
                    ->nullable(),

                Forms\Components\TextInput::make('email')
                    ->label(__('users.field.email'))
                    ->email()
                    ->unique(User::class, 'email'),

                Forms\Components\TextInput::make('phone')
                    ->label(__('users.field.phone'))
                    ->required(),

                Forms\Components\TextInput::make('password')
                    ->label(__('users.field.password'))
                    ->password()
                    ->required(fn($record) => !$record) // Only required on create
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->maxLength(255),

                Forms\Components\Toggle::make('active')
                    ->label(__('users.field.active')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label(__('users::users.field.avatar'))
                    ->circular(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('full_phone')->sortable()->searchable(),
                Tables\Columns\BooleanColumn::make('active')
                    ->label(__('users.field.active')),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->query(fn($query) => $query->where('active', true))
                    ->label(__('users.filters.active')),
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
