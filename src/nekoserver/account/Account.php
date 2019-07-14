<?php

namespace nekoserver\account;

use nekoserver\NekoServerPlugin as Main;
use pocketmine\Player;
use pocketmine\utils\Config;

class Account
{
    private $config;

    public function createAccount(Player $player): void
    {
        $this->config = new Config(Main::getPath() . "players.yml", Config::YAML, [
            $player->getName() => [
                "xuid" => $player->getXuid(),
                "played" => false,
                "first.played" => date(DATE_RSS, $player->getFirstPlayed()),
                "last.played" => date(DATE_RSS, $player->getFirstPlayed()),
                "address" => $player->getAddress(),
                "nyan" => 0,
                "grade" => "member"
            ]
        ]);
    }

    public function createOfflineAccount(string $name): bool
    {
        $this->config = new Config(Main::getPath() . "players.yml", Config::YAML);
        return $this->config->exists($name);
    }

    public function getConfig(): Config
    {
        return $this->config;
    }
}
