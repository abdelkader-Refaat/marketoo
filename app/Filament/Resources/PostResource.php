<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Modules\Posts\App\Models\Post;
use Modules\Posts\Enums\PostPrivacyEnum;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $availableLocales = languages();

        $tabs = [];

        foreach ($availableLocales as $locale) {
            $tabs[] = Tabs\Tab::make($locale)
                ->schema([
                    TextInput::make("title.$locale")
                        ->label(__('posts::posts.field.title'))
                        ->required(fn () => $locale === lang()),

                    Textarea::make("content.$locale")
                        ->label(__('posts::posts.field.content'))
                        ->required(fn () => $locale === lang()),
                ]);
        }

        return $form->schema([
            Section::make()
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Toggle::make('is_promoted')
                                ->label(__('posts::posts.Is_promoted')),

                            Select::make('privacy')
                                ->label(__('posts::posts.post_privacy'))
                                ->options([
                                    PostPrivacyEnum::Public->value => __('posts::posts.privacy.Public'),
                                    PostPrivacyEnum::Private->value => __('posts::posts.privacy.Private'),
                                    PostPrivacyEnum::Unlisted->value => __('posts::posts.privacy.Unlisted'),
                                ])
                                ->default(PostPrivacyEnum::Public->value)
                                ->required(),
                        ]),

                    Tabs::make('Locales')
                        ->tabs($tabs),

                    Grid::make(2)
                        ->schema([
                            TextInput::make('event_name')
                                ->label(__('posts::posts.Event.name'))
                                ->maxLength(50),

                            DateTimePicker::make('event_date_time')
                                ->label(__('posts::posts.Event.date')),
                        ]),

                    Textarea::make('event_description')
                        ->label(__('posts::posts.Event.description')),

                    Select::make('repost_id')
                        ->label(__('posts::posts.Repost'))
                        ->relationship('repost', 'title')
                        ->nullable(),

                    Textarea::make('repost_text')
                        ->label(__('posts::posts.Event.description'))
                        ->nullable(),

                    Select::make('user_id')
                        ->label(__('posts::posts.User'))
                        ->relationship('user', 'name')
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->formatStateUsing(fn ($state) => $state[lang()] ?? '')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('content')
                    ->formatStateUsing(fn ($state) => $state[lang()] ?? '')
                    ->limit(50),
                TextColumn::make('privacy')->sortable(),
                ToggleColumn::make('is_promoted'),
                TextColumn::make('event_name')->limit(30),
                TextColumn::make('event_date_time')->dateTime(),
                TextColumn::make('repost_id')->sortable(),
                TextColumn::make('user.name')->sortable()->label('Author'),
                TextColumn::make('created_at')->sortable()->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
