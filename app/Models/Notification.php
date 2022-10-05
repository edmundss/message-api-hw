<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Jobs\ProcessNotification;

/**
 * @OA\Schema(
 *  description="Notification class",
 *  required = {
 *      "clientId",
 *      "channel",
 *      "message"
 *  },
 *  @OA\Property(
 *      property="id",
 *      type="string",
 *      format="uuid",
 *      readOnly="true",
 *      description="primary key ID for the message"
 *  ),
 *  @OA\Property(
 *      property="clientId",
 *      type="string",
 *      format="uuid",
 *      description="Id of the client"
 *  ),
 *  @OA\Property(
 *      property="channel",
 *      type="string",
 *      enum={"sms", "email"},
 *      example="sms",
 *      description="Message delivery channel"
 *  ),
 *  @OA\Property(
 *      property="message",
 *      type="text",
 *      example="Molestiae veniam quia ut. Dolores ea quod in. Nam et aut corrupti dolorum. Id et et odit beatae laborum nesciunt.",
 *      description="Mewssage for client"
 *  ),
 *  @OA\Property(
 *      property="status",
 *      type="text",
 *      readOnly="true",
 *      example="sent",
 *      description="Delivery status of the message: pending, processing, sent, failed"
 *  )
 * )
 */

class Notification extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'client_id',
        'channel',
        'message',
        'status'
    ];

    public function client(){
        return $this->belongsTo(Client::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Str::uuid());
        });

        static::created(function (Model $model) {
            ProcessNotification::dispatch($model);
        });
    }
}
