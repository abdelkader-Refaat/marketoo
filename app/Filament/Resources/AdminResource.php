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
    protected static ?string $modelLabel = 'Admin'; // Singular
    protected static ?string $pluralModelLabel = 'Admins'; // Plural
    protected static ?string $navigationLabel = 'Admins'; // Sidebar
    protected static ?string $model = Admin::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('admin.sections.profile'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('admin.fields.name'))
                            ->required(),

                        Forms\Components\FileUpload::make('avatar')
                            ->label(__('admin.fields.avatar'))
                            ->image()
                            ->directory('admin-avatars')
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make(__('admin.sections.credentials'))
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label(__('admin.fields.email'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('password')
                            ->label(__('admin.fields.password'))
                            ->password()
                            ->required(fn($operation) => $operation === 'create')
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state)),
                    ]),

                Forms\Components\Section::make(__('admin.sections.settings'))
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label(__('admin.fields.type'))
                            ->options(__('admin.types'))
                            ->required(),

                        Forms\Components\Select::make('role_id')
                            ->label(__('admin.fields.role'))
                            ->relationship('role', 'name')
                            ->nullable(),

                        Forms\Components\Toggle::make('is_blocked')
                            ->label(__('admin.fields.is_blocked')),

                        Forms\Components\Toggle::make('is_notify')
                            ->label(__('admin.fields.is_notify')),
                    ])->columns(2),

                Forms\Components\Section::make(__('admin.sections.contact'))
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label(__('admin.fields.phone'))
                            ->tel()
                            ->nullable(),

                        Forms\Components\TextInput::make('country_code')
                            ->label(__('admin.fields.country_code'))
                            ->maxLength(5)
                            ->nullable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label(__('admin.fields.avatar'))
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('admin.fields.name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('admin.fields.email'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('admin.fields.type'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        default => 'primary',
                    }),

                Tables\Columns\IconColumn::make('is_blocked')
                    ->label(__('admin.fields.is_blocked'))
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('admin.fields.type'))
                    ->options(__('admin.types')),

                Tables\Filters\TernaryFilter::make('is_blocked')
                    ->label(__('admin.filters.is_blocked')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->tooltip(__('filament.actions.edit.tooltip')),

                Tables\Actions\DeleteAction::make()
                    ->tooltip(__('filament.actions.delete.tooltip')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('filament.actions.bulk_delete.label')),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('admin.actions.create')),
            ])
            ->emptyStateHeading(__('filament.table.empty.heading'))
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // Add relation managers here
        ];
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
