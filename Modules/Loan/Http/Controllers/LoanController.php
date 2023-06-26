<?php

namespace Modules\Loan\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Traits\ResponseTrait;
use Modules\Loan\Http\Requests\LoanRequest;
use Modules\Loan\Repositories\LoanRepository;

class LoanController extends Controller
{
    public $loanRepository;
    use ResponseTrait;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function store(LoanRequest $request): JsonResponse
    {
        try {
            $loanData = [
                'user_id' => auth()->user()->id,
                'amount' => $request->amount,
                'terms' => $request->terms,
            ];

            $loan = $this->loanRepository->store($loanData);

            return $this->responseSuccess($loan, __('loan::messages.store.success'));
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function view(int $id): JsonResponse
    {
        try {
            $loan = $this->loanRepository->getById($id);
            if (empty($loan)) {
                return $this->responseError(null, __('loan::messages.not_found'), Response::HTTP_NOT_FOUND);
            }

             // can not view
             if (auth()->user()->cannot('view', $loan->resource)) {
                return $this->responseError(null, __('loan::messages.policy.can_not_view'), Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($loan, __('loan::messages.view.success'));
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(LoanRequest $request, $id): JsonResponse
    {
        try {
            $loan =  $this->loanRepository->getById($id);
            if (empty($loan)) {
                return $this->responseError(null, __('loan::messages.not_found'), Response::HTTP_NOT_FOUND);
            }

            // can not approve
            if (isset($request->status) && $request->user()->cannot('approve', $loan->resource)) {
                return $this->responseError(null, __('loan::messages.policy.can_not_approve'), Response::HTTP_NOT_FOUND);
            }

            // can not update if the status is different with PENDING
            if ($request->user()->cannot('update', $loan->resource)) {
                return $this->responseError(null, __('loan::messages.policy.can_not_update'), Response::HTTP_NOT_FOUND);
            }

            $loanData = $request->only('amount', 'terms', 'status');
            $loan = $this->loanRepository->update($id, $loanData);
            return $this->responseSuccess($loan, __('loan::messages.update.success'));
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $loan =  $this->loanRepository->getById($id);
            if (empty($loan)) {
                return $this->responseError(null, __('loan::messages.not_found'), Response::HTTP_NOT_FOUND);
            }

            // can not delete if the status is different with PENDING
            if(auth()->user()->cannot('delete', $loan->resource)) 
            {
                return $this->responseError(null, __('loan::messages.policy.can_not_delete'), Response::HTTP_NOT_FOUND);
            }

            $deleted = $this->loanRepository->delete($id);
            if (!$deleted) {
                return $this->responseError(null, 'Failed to delete the loan.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->responseSuccess(null, __('loan::messages.delete.success'));
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function list(): JsonResponse
    {
        try {
            $loans = $this->loanRepository->list();
            if (empty($loans)) {
                return $this->responseError(null, __('loan::messages.not_found'), Response::HTTP_NOT_FOUND);
            }

            // can not view
            if (auth()->user()->cannot('viewAll', $loans->resource->first()->resource)) {
                return $this->responseError(null, __('loan::messages.policy.can_not_view'), Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($loans, __('loan::messages.view.success'));
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
