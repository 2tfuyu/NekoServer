<?php

namespace nekoserver\command;

use nekoserver\account\Account;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class PlayerInfoCommand extends Command
{
    public function __construct()
    {
        parent::__construct("info", "Player Information", "/info <player>");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender->isOp()) {
            $sender->sendMessage("権限がありません");
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage("名前を入力してください");
            return false;
        }
        $targetAccount = new Account();
        $targetAccount->createOfflineAccount($args[0]);
        $sender->sendMessage("=======");
        $sender->sendMessage($args[0] . ":");
        foreach ($targetAccount->getConfig()->get($args[0]) as $key => $value) {
            if ($key === "played") {
                $value = var_export($value, true);
            }
            $sender->sendMessage("{$key}: {$value}");
        }
        $sender->sendMessage("=======");
        return true;
    }
}