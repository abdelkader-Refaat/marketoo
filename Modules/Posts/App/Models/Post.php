<?php

namespace Modules\Posts\App\Models;

// use Laravel\Scout\Searchable;
// use Abbasudo\Purity\Traits\Sortable;
// use Abbasudo\Purity\Traits\Filterable;

use App\Enums\PostType;
use App\Models\Core\BaseModel;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Posts\database\factories\PostFactory;
use Modules\Posts\Enums\PostPrivacyEnum;
use Modules\Users\App\Models\User;
use Spatie\Translatable\HasTranslations;

class Post extends BaseModel
{
    use HasFactory, HasTranslations, Sluggable;

    protected $table = 'posts';

    protected $casts = [
        'privacy' => PostPrivacyEnum::class,
        'type' => PostType::class,
        'title' => 'array',
        'content' => 'array',
    ];

    protected $fillable = [
        'user_id', 'title', 'content', 'privacy', 'type', 'slug', 'is_promoted', 'event_name', 'event_date_time',
        'event_description', 'repost_id', 'repost_text',
    ];

    protected $translatable = [
        'title',
        'content',
    ];

    protected static function newFactory()
    {
        return PostFactory::new();
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function repost(): BelongsTo
    {
        return $this->belongsTo(self::class, 'repost_id');
    }
}
