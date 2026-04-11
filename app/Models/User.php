<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Gender;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'first_name',
    'middle_name',
    'last_name',
    'email',
    'password',
    'phone_prefix',
    'phone_number',
    'pesel',
    'document_number',
    'date_of_birth',
    'gender',
    'address_id',
    'mailing_address_id',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasName
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(
            related: Address::class,
            foreignKey: 'address_id',
        );
    }

    public function mailingAddress(): BelongsTo
    {
        return $this->belongsTo(
            related: Address::class,
            foreignKey: 'mailing_address_id',
        );
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'immutable_date',
            'email_verified_at' => 'immutable_datetime',
            'password' => 'hashed',
            'gender' => Gender::class,
        ];
    }
}
