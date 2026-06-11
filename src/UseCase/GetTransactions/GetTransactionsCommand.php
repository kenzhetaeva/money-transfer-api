<?php

declare(strict_types=1);

namespace App\UseCase\GetTransactions;

/**
 * @OA\Schema(
 *     schema="GetTransactionsRequest"
 * )
 */
class GetTransactionsCommand
{
    /**
     * @OA\Property(
     *     property="accountId",
     *     type="integer",
     *     description="Id of account",
     *     example=1
     * )
     */
    private $accountId;

    /**
     * @OA\Property(
     *     property="perPage",
     *     type="integer",
     *     description="Per Page",
     *     example=20
     * )
     */
    private $perPage;

    /**
     * @OA\Property(
     *     property="page",
     *     type="integer",
     *     description="Page",
     *     example=1
     * )
     */
    private $page;

    public function __construct(int $accountId, int $perPage, int $page)
    {
        $this->accountId = $accountId;
        $this->perPage = $perPage;
        $this->page = $page;
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}
