<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Usuarios extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'usuarios';

    protected  $primaryKey = 'dni';
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['dni', 'nombre', 'email', 'lu', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function setPasswordAttribute($value)
    {
        if (! empty($value))
        {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function profile()
    {
        return $this->hasOne('App\UserProfile');
    }

    public function scopeName($query, $name)
    {   
        if (trim($name) != "")
        {
            $query->where(\DB::raw("CONCAT(first_name, ' ', last_name)"), "LIKE", "%$name%");
        }
    }

    public function scopeType($query, $type)
    {
        $types = config('options.types');
        if ($type != "" && isset($types[$type]))
        {
            $query->where('type', $type);
        }
    }

    public static function filterAndPaginate($nombre)
    {
        return User::nombre($nombre)->paginate();
    }
}
