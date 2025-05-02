<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** */
    use HasFactory, Notifiable, HasUuids;

    /**
     * 
     * @var string
     */
    public $primaryKey = 'user_id';

    /**
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image'
    ];

    /**
     * 
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * 
     * @return array{password: string}
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * 
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * 
     * @return array{email: mixed, iss: string, uuid: mixed}
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'iss' => 'jwt-course',
            'uuid' => $this->user_id,
            'email' => $this->email,
        ];
    }

    /**
     * Get user tasks
     * @return HasMany<Task, User>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(related: Task::class, foreignKey: 'user_id');
    }

    /**
     * Set image path and save it 
     * @return Attribute
     */
    public function image(): Attribute
    {
        return Attribute::make(
            set: function ($value, $attributes): string|null {
                if ($value == null) {
                    return null;
                }

                $fixid_name = str_replace(search: ' ', replace: '_', subject: $attributes['name']);
                $image_path = time() . '_' . $fixid_name . '.' . $value->getClientOriginalExtension();
                Storage::disk(name: 'public')->put(path: 'images/users/' . $image_path, contents: file_get_contents(filename: $value));

                return $image_path;
            }
        );
    }

    /**
     * Get profile image full url
     * @return Attribute
     */
    public function profileImage(): Attribute
    {
        return Attribute::make(
            get: fn(): string|null => $this->image == null ? null : env('APP_URL') . Storage::url(path: 'images/users/' . $this->image)
        );
    }

    /**
     * Delete image from storage
     * @return void
     */
    public function deleteProfileImage(): void
    {
        if ($this->image == null) return;

        Storage::disk(name: 'public')->delete(paths: 'images/users/' . $this->image);
        $this->image = null;
        $this->save() ; 
    }
}
