<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Modules\Admins\App\Models\Admin;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('admins.field.name'))
                    ->required(),

                Forms\Components\Select::make('type')
                    ->label(__('admins.field.type'))
                    ->options([
                        'admin' => __('admins.types.admin'),
                        'super_admin' => __('admins.types.super_admin'),
                    ])
                    ->required(),

                Forms\Components\FileUpload::make('avatar')
                    ->label(__('admins.field.avatar'))
                    ->image()
                    ->directory('avatars')
                    ->nullable(),

                Forms\Components\TextInput::make('email')
                    ->label(__('admins.field.email'))
                    ->email()
                    ->required()
                    ->unique(Admin::class, 'email'),

                Forms\Components\TextInput::make('phone')
                    ->label(__('admins.field.phone'))
                    ->tel()
                    ->nullable(),

                Forms\Components\TextInput::make('country_code')
                    ->label(__('admins.field.country_code'))
                    ->maxLength(5)
                    ->nullable(),

                Forms\Components\TextInput::make('password')
                    ->label(__('admins.field.password'))
                    ->password()
                    ->required(fn($record) => !$record) // Required on create
                    ->dehydrateStateUsing(fn($state) => $state ? Hash::make($state) : null)
                    ->maxLength(255),

                Forms\Components\Select::make('role_id')
                    ->label(__('admins.field.role'))
                    ->nullable(),

                Forms\Components\Toggle::make('is_blocked')
                    ->label(__('admins.field.is_blocked'))
                    ->default(false),

                Forms\Components\Toggle::make('is_notify')
                    ->label(__('admins.field.is_notify'))
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('is_blocked')
                    ->label(__('admins.field.is_blocked')),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_blocked')
                    ->label(__('admins.filters.blocked'))
                    ->nullable(),
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
