<?php

namespace nekoserver;

use nekoserver\command\NyanCommand;
use nekoserver\command\PlayerInfoCommand;
use nekoserver\events\JoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Color;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class NekoServerPlugin extends PluginBase
{
    private static $path;
    private static $config;

    const VERSION = 0.1;

    public function onLoad(): void
    {
        date_default_timezone_set('asia/tokyo');
        self::$path = $this->getDataFolder();
        self::$config = new Config(self::$path . "config.yml", Config::YAML, [
            "depencies" => [],
            "version" => 0.1,
            "chat" => [
                "grade" => [
                    "owner" => "§e＊§f%NAME%",
                    "admin" => "§4＊§f%NAME%",
                    "member" => "§f%NAME%"
                ]
            ]
        ]);
    }

    public function onEnable(): void
    {
        if (!file_exists(self::$path)) {
            @mkdir(self::$path);
        }
        foreach (self::$config->get("depencies") as $depence) {
            if ($this->getServer()->getPluginManager()->isPluginEnabled($depence)) {
                $this->getLogger()->info(TextFormat::AQUA . "{$depence} is Enabled.");
            }
            else {
                $this->getLogger()->alert(TextFormat::RED . "{$depence} is Disabled.");
            }
        }

        $this->getServer()->getPluginManager()->registerEvents(new JoinEvent(), $this);
        $this->getServer()->getCommandMap()->register("nyan", new NyanCommand());
        $this->getServer()->getCommandMap()->register("info", new PlayerInfoCommand());
    }

    public static function getServerConfig(): Config
    {
        return self::$config;
    }

    public static function getPath(): string
    {
        return self::$path;
    }
}
