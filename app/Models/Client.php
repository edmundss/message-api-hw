<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Contracts\Mail\Mailable;
/**
 * @OA\Schema(
 *  description="Client class",
 *  required = {
 *      "firstName",
 *      "lastName",
 *      "email",
 *      "phoneNumber"
 *  },
 *  @OA\Property(
 *      property="id",
 *      type="string",
 *      format="uuid",
 *      readOnly="true",
 *      description="primary key ID for the client"
 *  ),
 *  @OA\Property(
 *      property="firstName",
 *      type="string",
 *      format="alpha",
 *      example="Edmunds",
 *      description="First name of the client"
 *  ),
 *  @OA\Property(
 *      property="lastName",
 *      type="string",
 *      format="alpha",
 *      example="Šuļžanoks",
 *      description="Last name of the client"
 *  ),
 *  @OA\Property(
 *      property="email",
 *      type="string",
 *      format="email",
 *      example="edmunds.sulzanoks@gmail.com",
 *      description="Client's email address"
 *  ),
 *  @OA\Property(
 *      property="phoneNumber",
 *      type="string",
 *      format="E.164",
 *      example="+37126555298",
 *      description="Client's phone number"
 *  )
 * )
 */

class Client extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number'
    ];

    public function notifications(){
        return $this->hasMany(Notification::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Str::uuid());
        });
    }
}
