<?php

namespace bbo51dog\mjolnir;

use bbo51dog\mjolnir\command\MjolnirBanCommand;
use bbo51dog\mjolnir\repository\RepositoryFactory;
use bbo51dog\mjolnir\repository\sqlite\SQLiteRepositoryFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class MjolnirPlugin extends PluginBase {

    private static RepositoryFactory $repositoryFactory;

    public static function getRepositoryFactory(): RepositoryFactory {
        return self::$repositoryFactory;
    }

    protected function onLoad(): void {
        $this->initSetting();
        $this->initRepository();
    }

    private function initSetting(): void {
        $config = new Config($this->getDataFolder() . "Config.yml", Config::YAML, [
            "messages" => [
                "kick-message" => "You are banned",
                "default-ban-reason" => "Banned by admin",
            ]
        ]);
        $config->save();
        Setting::getInstance()->setData($config->getAll());
    }

    private function initRepository(): void {
        self::$repositoryFactory = new SQLiteRepositoryFactory($this->getDataFolder() . "MjolnirData.sqlite");
    }

    protected function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getCommandMap()->register("mjolnir", new MjolnirBanCommand());
    }

    protected function onDisable(): void {
        self::$repositoryFactory->close();
    }
}