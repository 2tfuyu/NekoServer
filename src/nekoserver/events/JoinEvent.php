<?php

namespace nekoserver\events;

use nekoserver\account\Account;
use nekoserver\NekoServerPlugin as Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;

class JoinEvent implements Listener
{

    public function onJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $account = new Account();
        $account->createAccount($player);
        $config = $account->getConfig();
        if (!$config->getNested($name . ".played")) {
            $player->sendMessage("初めまして！");
            $event->setJoinMessage("{$name}さんが初めて参加しました");
            $config->setNested($name . ".played", true);
            $config->save();
        }
        elseif ($player->isOp()) {
            $event->setJoinMessage("{$name}神が降臨した！");
        }
        else {
            $player->sendMessage("おかえりなさい！");
            $event->setJoinMessage("{$name}さんが参加しました");
        }
        $config->setNested($name . ".last.played", date(DATE_RSS, $player->getFirstPlayed()));

        switch ($config->getNested($name . ".grade")) {
            case "owner":
                $tag = Main::getServerConfig()->getNested("chat.grade.owner");
                break;
            case "admin":
                $tag = Main::getServerConfig()->getNested("chat.grade.admin");
                break;
            case "member":
                $tag = Main::getServerConfig()->getNested("chat.grade.member");
                break;
        }
        $tag = str_replace("%NAME%", $name, $tag);
        $player->setDisplayName($tag);
        $player->setNameTag($tag);
    }
}
