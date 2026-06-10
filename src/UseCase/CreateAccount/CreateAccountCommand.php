<?php

declare(strict_types=1);

namespace App\UseCase\CreateAccount;

use App\DTO\BaseAccountDTO;
use App\Enum\CurrencyEnum;

/**
 * @OA\Schema(
 *     schema="CreateAccountRequest"
 * )
 */
class CreateAccountCommand extends BaseAccountDTO
{
    public function __construct(int $userId, CurrencyEnum $currency, float $balance)
    {
        parent::__construct($userId, $currency, $balance);
    }
}
