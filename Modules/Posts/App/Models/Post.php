<?php

namespace Modules\Posts\App\Models;

use App\Models\User;
//use Laravel\Scout\Searchable;
//use Abbasudo\Purity\Traits\Sortable;
//use Abbasudo\Purity\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Posts\Enums\PostPrivacyEnum;
use Cviebrock\EloquentSluggable\Sluggable;
use Modules\Posts\Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasFactory, Sluggable ,HasTranslations;
//        Filterable, Sortable , Searchable;



    protected $table = 'posts';
    protected $casts = [
        'privacy' => PostPrivacyEnum::class,
    ];
    protected $factory = PostFactory::class;

    protected $fillable = ['user_id', 'title', 'content', 'privacy', 'slug', 'is_promoted', 'event_name', 'event_date_time', 'event_description', 'repost_id', 'repost_text'];

    protected $translatable = [
        'title',
        'content',
    ];
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function repost(): BelongsTo
    {
        return $this->belongsTo(self::class, 'repost_id');
    }
}
