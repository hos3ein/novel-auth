<?php

namespace Hos3ein\NovelAuth\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OtpCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * Get the otpable model that the otp code belongs to.
     *
     * @return MorphTo
     */
    public function otpable(): MorphTo
    {
        return $this->morphTo('otpable');
    }
}
