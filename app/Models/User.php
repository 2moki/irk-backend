<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Auth\RoleType;
use App\Enums\Gender;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
class User extends Authenticatable implements HasName, FilamentUser // Dodano FilamentUser
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    /**
     * Sprawdza, czy użytkownik może wejść do panelu admina.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole([RoleType::ADMIN->value, RoleType::EMPLOYEE->value]);
    }

    /**
     * Zwraca nazwę wyświetlaną w interfejsie Filamentu.
     */
    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Relacja do głównego adresu.
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    /**
     * Relacja do adresu korespondencyjnego.
     */
    public function mailingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'mailing_address_id');
    }

    /**
     * Akcesor dla czytelnego imienia i nazwiska.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn() => mb_trim("{$this->first_name} {$this->last_name}"),
        );
    }

    /**
     * Castingi pól.
     */
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
