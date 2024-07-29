<?php namespace App\Models;

use App\Enum\UserLevelEnum;
use App\Notifications\ResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'canal_venda_id',
        'pagina_padrao',
        'avatar',
        'phone',
        'birthdate',
        'address',
        'city',
        'state',
        'zip',
        'afiliado_id',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'birthdate'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Retorna se está ativo ou não
     *
     * @return bool
     */
    public function getStatusAttribute()
    {
        return ($this->attributes['deleted_at'] == null);
    }

    /**
     * Nível de acesso do usuário
     *
     * @return mixed
     */
    public function getNivelAcessoAttribute()
    {
        return UserLevelEnum::LEVELS[$this->attributes['level']];
    }

    /**
     * Update birthday user
     *
     * @param $birthdate
     */
    public function setBirthdateAttribute($birthdate)
    {
        $birthdate = Carbon::createFromFormat('d/m/Y', $birthdate);

        $this->attributes['birthdate'] = $birthdate;
    }
}
