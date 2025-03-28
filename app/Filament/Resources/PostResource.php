<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Modules\Posts\Enums\PostPrivacyEnum;
use Modules\Posts\Models\Post;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Translatable\HasTranslations;

class PostResource extends Resource
{
    use HasTranslations;
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label(__('posts::posts.field.title'))
                    ->required(),
                    Textarea::make('content')
                        ->label(__('posts::posts.field.content'))
                        ->required(),
                Forms\Components\Select::make('privacy')
                    ->label(__('posts::posts.post_privacy'))
                    ->options([
                        PostPrivacyEnum::Public->value => __('posts::posts.privacy.Public'),
                        PostPrivacyEnum::Private->value => __('posts::posts.privacy.Private'),
                        PostPrivacyEnum::Unlisted->value => __('posts::posts.privacy.Unlisted'),
                    ])
                    ->default(PostPrivacyEnum::Public->value)
                    ->required(),
                Forms\Components\Toggle::make('is_promoted')
                    ->label(__('posts::posts.Is_promoted')),
                Forms\Components\TextInput::make('event_name')
                    ->label(__('posts::posts.Event.name'))
                    ->maxLength(50),
                Forms\Components\DateTimePicker::make('event_date_time')
                ->label(__('posts::posts.Event.date')),
                Forms\Components\Textarea::make('event_description')
                    ->label(__('posts::posts.Event.description')),
                Forms\Components\Select::make('repost_id')
                    ->label(__('posts::posts.Repost'))
                    ->relationship('repost', 'title')
                    ->nullable(),
                Forms\Components\Textarea::make('repost_text')
                    ->label(__('posts::posts.Event.description'))
                    ->nullable(),

                Forms\Components\Select::make('user_id')
                    ->label(__('posts::posts.User'))
                    ->relationship('user', 'name')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('content')->limit(50),
                Tables\Columns\TextColumn::make('privacy')->sortable(),
                Tables\Columns\BooleanColumn::make('is_promoted'),
                Tables\Columns\TextColumn::make('event_name')->limit(30),
                Tables\Columns\TextColumn::make('event_date_time')->dateTime(),
                Tables\Columns\TextColumn::make('repost_id')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->sortable()->label('Author'),
                Tables\Columns\TextColumn::make('created_at')->sortable()->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')->sortable()->dateTime(),
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
