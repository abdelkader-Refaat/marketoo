<?php

namespace App\Models\Core;

use App\Mail\SendCode;
use App\Models\Chat\Room;
use App\Models\Chat\RoomMember;
use App\Models\PublicSections\Complaint;
use App\Models\PublicSections\ComplaintReplay;
use App\Models\PublicSettings\Device;
use App\Models\Wallet\Wallet;
use App\Services\Sms\SmsService;
use App\Traits\SmsTrait;
use App\Traits\UploadTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class AuthBaseModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SmsTrait, SoftDeletes, UploadTrait;

    protected $hidden = ['password'];

    public static function boot()
    {
        parent::boot();
        /* creating, created, updating, updated, deleting, deleted, forceDeleted, restored */
        static::deleted(function ($model) {
            $field = $model->getAvatarField();
            if (! empty($model->attributes[$field])) {
                $model->deleteFile($model->attributes[$field], self::IMAGEPATH);
            }
        });

        // static::created(function ($model) {
        //     if (get_class($model) != Admin::class) {
        //         $model->wallet()->create();
        //     }
        // });
    }

    protected function getAvatarField(): string
    {
        if (Schema::hasColumn($this->getTable(), 'avatar')) {
            return 'avatar';
        }

        return 'image';
    }

    public function scopeSearch($query, $searchArray = [])
    {
        $query->where(function ($query) use ($searchArray) {
            if ($searchArray) {
                foreach ($searchArray as $key => $value) {
                    if (str_contains($key, '_id')) {
                        if ($value != null) {
                            $query->Where($key, $value);
                        }
                    } elseif ($key == 'order') {
                    } elseif ($key == 'created_at_min') {
                        if ($value != null) {
                            $query->WhereDate('created_at', '>=', Carbon::createFromFormat('m-d-Y', $value));
                        }
                    } elseif ($key == 'created_at_max') {
                        if ($value != null) {
                            $query->WhereDate('created_at', '<=', Carbon::createFromFormat('m-d-Y', $value));
                        }
                    } else {
                        if ($value != null) {
                            $query->Where($key, 'like', '%'.$value.'%');
                        }
                    }
                }
            }
        });

        return $query->orderBy('id',
            request()->searchArray && request()->searchArray['order'] ? request()->searchArray['order'] : 'DESC');
    }

    public function setPhoneAttribute($value)
    {
        if (! empty($value)) {
            $this->attributes['phone'] = fixPhone($value);
        }
    }

    public function setCountryCodeAttribute($value)
    {
        if (! empty($value)) {
            $this->attributes['country_code'] = fixPhone($value);
        }
    }

    public function getFullPhoneAttribute(): string
    {
        return Str::of($this->attributes['country_code'])
            ->start('+')
            ->append(' ')
            ->append($this->attributes['phone'])
            ->toString();
    }

    public function getAvatarAttribute()
    {
        $field = $this->getAvatarField();
        $imageName = $this->attributes[$field] ?? null;

        return $this->getImageOrDefault($imageName, static::IMAGEPATH);
    }

    public static function getImageOrDefault($name, $directory)
    {
        $imagePath = storage_path("app/public/images/$directory/".$name);

        if (! empty($name) && file_exists($imagePath)) {
            return asset("storage/images/$directory/".$name);
        }

        return asset('storage/images/default.webp');
    }

    public function setAvatarAttribute($value)
    {
        $field = $this->getAvatarField();
        if ($value != null && is_file($value)) {
            if (isset($this->attributes[$field])) {
                $this->deleteFile($this->attributes[$field], static::IMAGEPATH);
            }
            $this->attributes[$field] = $this->uploadAllTypes($value, static::IMAGEPATH);
        }
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    public function markAsActive()
    {
        $this->update(['code' => null, 'code_expire' => null, 'active' => true]);

        return $this;
    }

    public function sendVerificationCode(): array
    {
        $this->update([
            'code' => $this->activationCode(),
            'code_expire' => Carbon::now()->addMinute(),
        ]);
        $this->sendCodeAtSms($this->code);

        return ['user' => $this];
    }

    private function activationCode(): int
    {
        return 123456;
        //        return mt_rand(111111, 999999);
    }

    public function sendCodeAtSms($code, $full_phone = null): void
    {
        (new SmsService)->sendSms($full_phone ?? $this->full_phone, trans('api.activeCode').$code);
    }

    public function sendCodeAtEmail($code, $email = null): void
    {
        try {
            Mail::to($email ?? $this->email)->send(new SendCode($code, $this->name));
        } catch (\Exception $e) {
            info('Failed to send email: '.$e->getMessage());
        }
    }

    public function login()
    {
        // $this->tokens()->delete();
        $this->updateDevice();
        $this->updateLang();
        if (! $this['parent_id']) {
            $token = $this->createToken(request()->device_type)->plainTextToken;
        } else {
            $token = $this->getAbilitiesAndSetTokenAbility($this, request()->device_type);
        }

        return $token;
    }

    public function updateDevice()
    {
        if (request()->device_id) {
            $this->devices()->updateOrCreate([
                'device_id' => request()->device_id,
                'device_type' => request()->device_type,
            ]);
        }
    }

    public function devices()
    {
        return $this->morphMany(Device::class, 'morph');
    }

    public function updateLang()
    {
        if (
            request()->header('Lang') != null
            && in_array(request()->header('Lang'), languages())
        ) {
            $this->update(['lang' => request()->header('Lang')]);
        } else {
            $this->update(['lang' => defaultLang()]);
        }
    }

    public function getAbilitiesAndSetTokenAbility($provider, $device_type)
    {
        $ProviderAbilities = $provider->abilities()->pluck('value')->toArray();

        return $provider->createToken($device_type, $ProviderAbilities)->plainTextToken;
    }

    public function logout()
    {
        // $this->tokens()->delete();
        $this->currentAccessToken()->delete();
        if (request()->device_id) {
            $this->devices()->where(['device_id' => request()->device_id])->delete();
        }

        return true;
    }

    public function rooms()
    {
        return $this->morphMany(RoomMember::class, 'memberable');
    }

    public function ownRooms()
    {
        return $this->morphMany(Room::class, 'createable');
    }

    public function joinedRooms()
    {
        return $this->morphMany(RoomMember::class, 'memberable')
            ->with('room')
            ->get()
            ->sortByDesc('room.last_message_id')
            ->pluck('room');
    }

    public function replays()
    {
        return $this->morphMany(ComplaintReplay::class, 'replayer');
    }

    public function authUpdates()
    {
        return $this->morphMany(AuthUpdate::class, 'updatable');
    }

    public function wallet()
    {
        return $this->morphOne(Wallet::class, 'walletable')->latest();
    }

    public function complaints(): MorphMany
    {
        return $this->morphMany(Complaint::class, 'complaintable');
    }
}
