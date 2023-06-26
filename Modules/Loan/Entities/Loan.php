<?php

namespace Modules\Loan\Entities;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;
    
    const UPDATED_AT = null;

    const PENDING = 0;
    const APPROVED = 1;
    const REJECTED = 2;
    const PAID = 3;

    //0:PENDING, 1: APPROVED, 2: REJECT, 3: PAID
    const STATUS = [
        loan::PENDING => 'PENDING',
        loan::APPROVED => 'APPROVED',
        loan::REJECTED => 'REJECTED',
        loan::PAID => 'PAID',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'amount',
        'terms',
        'status',
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
            get: fn () => Loan::STATUS[$this->status]
        );
    }

    /**
     * Get the user's scheduledAmount.
     */
    protected function scheduledAmount(): Attribute
    {
        return new Attribute(
            get: fn () => number_format($this->amount / $this->terms, 2, '.', '')
        );
    }

    /**
     * Get the user that owns the loan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
