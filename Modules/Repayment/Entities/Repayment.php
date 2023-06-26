<?php

namespace Modules\Repayment\Entities;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repayment extends Model
{    
    use SoftDeletes;
    
    const UPDATED_AT = null;

    const PENDING = 0;
    const APPROVED = 1;
    const REJECTED = 2;
    const PAID = 3;

    //0:PENDING, 1: APPROVED, 2: REJECT, 3: PAID
    const STATUS = [
        Repayment::PENDING => 'PENDING',
        Repayment::APPROVED => 'APPROVED',
        Repayment::REJECTED => 'REJECTED',
        Repayment::PAID => 'PAID',
    ];

    const LASTEST_WEEK = 3;

    protected $fillable = [
        'id',
        'loan_id',
        'scheduled_date',
        'amount',
        'status',
        'week',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    /**
     * Get the user's amountFormat.
     */
    protected function amountFormat(): Attribute
    {
        return new Attribute(
            get: fn () => '$'.number_format($this->amount, 2, '.', ',')
        );
    }

    /**
     * Get the user's status.
     */
    protected function statusFormat(): Attribute
    {
        return new Attribute(
            get: fn () => Repayment::STATUS[$this->status]
        );
    }
}
