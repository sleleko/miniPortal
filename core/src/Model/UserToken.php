<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $token
 * @property int $user_id
 * @property bool $active
 * @property string $valid_till
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 */
class UserToken extends Model
{
  public $incrementing = false;
  protected $primaryKey = 'token';
  protected $fillable = ['token', 'user_id', 'active', 'valid_till'];
  protected $casts = [
    'active' => 'boolean',
  ];


  /**
   * @return BelongsTo
   */
  public function user()
  {
    return $this->belongsTo('App\Model\User');
  }
}