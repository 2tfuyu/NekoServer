<?php

namespace nekoserver\command;

use nekoserver\account\Account;
use nekoserver\money\Nyan;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\Server;

class NyanCommand extends Command
{
    public function __construct()
    {
        parent::__construct("nyan", "Nyan", "/nyan <pay> <player>");
    }

    public function execute(CommandSender $sender, string $label, array $args): bool
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("ゲーム内から実行してください");
            return false;
        }
        $senderAccount = new Account();
        $senderAccount->createAccount($sender);
        $nyan = new Nyan($sender->getName(), $senderAccount);
        $server = Server::getInstance();
        if (empty($args[0])) {
            $sender->sendMessage("Your Nyan: " . $nyan->getNyan());
            return true;
        }

        switch ($args[0]) {
            case "pay":
                if (empty($args[1]) && empty($args[2])) {
                    $sender->sendMessage("フォーマットが間違っています");
                    return false;
                }
                $targetAccount = new Account();
                if (!$targetAccount->createOfflineAccount($args[1])) {
                    $sender->sendMessage("有効な名前を入力してください");
                    return false;
                }
                $targetNyan = new Nyan($args[1], $targetAccount);
                $nyan->payNyan($targetNyan, $args[2]);
                return true;
            case "see":
                if (empty($args[1])) {
                    $sender->sendMessage("フォーマットが間違っています");
                    return false;
                }
                $targetAccount = new Account();
                if (!$targetAccount->createOfflineAccount($args[1])) {
                    $sender->sendMessage("有効な名前を入力してください");
                    return false;
                }
                $targetNyan = new Nyan($args[1], $targetAccount);
                $sender->sendMessage("{$args[0]}`s Nyan: " . $targetNyan->getNyan());
                return true;
            default:
                return false;
        }
    }
}


