<?php

namespace Modules\Repayment\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RepaymentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
