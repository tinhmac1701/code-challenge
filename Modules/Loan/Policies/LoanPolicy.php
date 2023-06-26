<?php

namespace Modules\Loan\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Loan\Entities\Loan;


class LoanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given loan can be updated by the admin or owner and the status is different with PENDDING
     */
    public function update(?User $user, Loan $loan): bool
    {
        return $user->isAdmin() || ($user?->id == $loan->user_id && $loan->status == Loan::PENDING);
    }

    /**
     * Determine if the given loan can be deleted by the admin or owner and the status is different with PENDDING
     */
    public function delete(?User $user, Loan $loan): bool
    {
        return $user->isAdmin() || ($user?->id == $loan->user_id && $loan->status == Loan::PENDING);
    }

    /**
     * Determine if the given loan can be approved by the admin.
     */
    public function approve(?User $user, Loan $loan): bool
    {
        return $user->isAdmin() ? true : false;
    }

    /**
     * Determine if the given loan can be viewd by the admin or owner
     */
    public function view(?User $user, Loan $loan): bool
    {
        return $user->isAdmin() || $user?->id == $loan->user_id;
    }

    /**
     * Determine if the given loans can be viewed by the admin or owner
     */
    public function viewAll(?User $user, Loan $loan): bool
    {
        return $user->isAdmin();
    }

    public function list(User $user)
    {
        return $user->isAdmin();
    }
}
