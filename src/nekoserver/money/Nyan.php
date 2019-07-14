<?php

namespace nekoserver\money;

use nekoserver\account\Account;
use pocketmine\Player;

class Nyan
{
    private $nyan;
    private $account;

    public function __construct(string $name, Account $account)
    {
        $this->nyan = $account->getConfig()->getNested($name.".nyan");
        $this->account = $account;
    }

    /**
     * @param self $target
     * @param int $amount
     * @return bool
     */
    public function payNyan(self $target, int $amount): bool
    {
        if ($this->removeNyan($amount)) {
            $target->addNyan($amount);
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getNyan(): int{
        return $this->nyan;
    }

    /**
     * @param int $nyan
     */
    public function setNyan(int $nyan): void{
        $this->nyan = $nyan;
        $this->save();
    }

    /**
     * @param int $amount
     */
    public function addNyan(int $amount): void{
        $this->nyan += $amount;
        $this->save();
    }

    /**
     * @param int $amount
     * @return bool
     */
    public function removeNyan(int $amount): bool{
        if ($this->nyan < $amount) {
            return false;
        }
        $this->nyan -= $amount;
        $this->save();
        return true;
    }

    private function save(): void{
        $this->account->getConfig()->save();
    }
}
