<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Retorna o identificador único do usuário.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retorna as propriedades personalizadas que devem ser incluídas no JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function generate52Weeks()
    {
        $dep = 10;
        $acumulado = 10;

        for ($i = 1; $i <= 52; $i++) {
            if (Evolucao52Semanas::where('semana', $i)->where('user_id', $this->id)->exists()) {
                continue;
            }
            Evolucao52Semanas::create([
                'semana' => $i,
                'status' => 0,
                'user_id' => $this->id,
                'valor_deposito' => $dep,
                'valor_acumulado' => $acumulado
            ]);

            $dep += 10;
            $acumulado += $dep;
        }
    }


}
