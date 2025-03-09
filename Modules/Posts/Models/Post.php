<?php

namespace Modules\Posts\Models;

use App\Models\User;
use Laravel\Scout\Searchable;
use Abbasudo\Purity\Traits\Sortable;
use Abbasudo\Purity\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Modules\Posts\Enums\PostPrivacyEnum;
use Cviebrock\EloquentSluggable\Sluggable;
use Modules\Posts\Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, Sluggable, Filterable, Sortable , Searchable;



    protected $table = 'posts';
    protected $casts = [
        'privacy' => PostPrivacyEnum::class,
    ];
    protected $factory = PostFactory::class;

    protected $fillable = ['user_id', 'title', 'content', 'privacy', 'slug', 'is_promoted', 'event_name', 'event_date_time', 'event_description', 'repost_id', 'repost_text'];

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
}
