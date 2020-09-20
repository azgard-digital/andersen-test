<?php
declare(strict_types=1);

namespace App\DTO;

final class TransactionDTO
{
    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var int
     */
    private $fee;

    /**
     * @var int
     */
    private $result;

    /**
     * @var int
     */
    private $walletId;

    /**
     * @var int
     */
    private $userId;

    public function __construct(
        string $from,
        string $to,
        int $amount,
        int $fee,
        int $result,
        int $walletId,
        int $userId
    ) {
        $this->from = $from;
        $this->to = $to;
        $this->amount = $amount;
        $this->fee = $fee;
        $this->result = $result;
        $this->walletId = $walletId;
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getFee(): int
    {
        return $this->fee;
    }

    /**
     * @return int
     */
    public function getResult(): int
    {
        return $this->result;
    }

    /**
     * @return int
     */
    public function getWalletId(): int
    {
        return $this->walletId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

}
