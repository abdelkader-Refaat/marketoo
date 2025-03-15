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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
                TextInput::make('title.en')
                    ->label(__('posts.field.title'))
                    ->required(),
                TextInput::make('title.ar')
                    ->label(__('resources.pages.posts.label'))
                    ->required(),
                Textarea::make('content.en')
                    ->label('Content (English)')
                    ->required(),
                Textarea::make('content.ar')
                    ->label('Content (Arabic)')
                    ->required(),
                Forms\Components\Select::make('privacy')
                    ->options([
                        PostPrivacyEnum::Public->value => 'Public',
                        PostPrivacyEnum::Private->value => 'Private',
                        PostPrivacyEnum::Unlisted->value => 'Unlisted',
                    ])
                    ->default(PostPrivacyEnum::Public->value)
                    ->required(),

                Forms\Components\Toggle::make('is_promoted')->label('Promoted'),

                Forms\Components\TextInput::make('event_name')->maxLength(50),
                Forms\Components\DateTimePicker::make('event_date_time'),
                Forms\Components\Textarea::make('event_description'),

                Forms\Components\Select::make('repost_id')
                    ->relationship('repost', 'title')
                    ->nullable(),

                Forms\Components\Textarea::make('repost_text')->nullable(),

                Forms\Components\Select::make('user_id')
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
