<?php

namespace Modules\Repayment\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'loan_id' => $this->loan_id,
            'scheduled_date' => $this->scheduled_date,
            'amount' => $this->amount,
            'week' => $this->week,
            'status' => $this->status_format,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
