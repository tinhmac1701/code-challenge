<?php

namespace Modules\Repayment\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Traits\ResponseTrait;
use Modules\Repayment\Http\Requests\RepaymentRequest;
use Modules\Repayment\Repositories\RepaymentRepository;

class RepaymentController extends Controller
{
    public $repaymentRepository;
    use ResponseTrait;

    public function __construct(RepaymentRepository $repaymentRepository)
    {
        $this->repaymentRepository = $repaymentRepository;
    }

    public function store(RepaymentRequest $request): JsonResponse
    {
        try {
            $repaymentData = [
                'loan_id' => $request->loan_id,
                'scheduled_date' => $request->scheduled_date,
                'amount' => $request->amount,
                'week' => $this->repaymentRepository->getNewestWeek($request->loan_id)
            ];

            $repayment = $this->repaymentRepository->store($repaymentData);

            return $this->responseSuccess($repayment, __('repayment::messages.store.success'));
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
