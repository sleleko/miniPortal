<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $fullname
 * @property string $email
 * @property string $phone
 * @property bool $active
 * @property int $role_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read UserRole $role
 * @property-read UserToken $tokens
 */
class User extends Model
{
  public $timestamps = true;
  protected $fillable = ['username', 'password', 'fullname', 'email', 'phone', 'active', 'role_id'];
  protected $casts = [
    'active' => 'boolean',
  ];
  protected $hidden = ['password'];


  /**
   * @param string $key
   * @param mixed $value
   *
   * @return mixed|void
   */
  public function setAttribute($key, $value)
  {
    if (in_array($key, ['password', 'tmp_password'])) {
      $value = password_hash($value, PASSWORD_DEFAULT);
    }
    parent::setAttribute($key, $value);
  }


  /**
   * @param $password
   *
   * @return bool
   */
  public function verifyPassword($password)
  {
    return password_verify($password, $this->getAttribute('password'));
  }


  /**
   * @return BelongsTo
   */
  public function role()
  {
    return $this->belongsTo('App\Model\UserRole');
  }


  /**
   * @return HasMany
   */
  public function tokens()
  {
    return $this->hasMany('App\Model\UserToken');
  }
}