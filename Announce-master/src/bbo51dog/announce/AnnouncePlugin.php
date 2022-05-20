<?php

namespace bbo51dog\announce;

use bbo51dog\announce\command\AnnounceAdminCommand;
use bbo51dog\announce\command\AnnounceCommand;
use bbo51dog\announce\command\PassCommand;
use bbo51dog\announce\repository\AnnounceRepository;
use bbo51dog\announce\repository\RepositoryPool;
use bbo51dog\announce\repository\SQLiteAnnounceRepository;
use bbo51dog\announce\repository\SQLiteUserRepository;
use bbo51dog\announce\repository\UserRepository;
use bbo51dog\announce\service\AnnounceService;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use SQLite3;

class AnnouncePlugin extends PluginBase {

    private const SQLITE_FILE = "Announce.sqlite";

    private RepositoryPool $repoPool;

    private SQLite3 $db;

    protected function onEnable(): void {
        $this->initRepository();
        AnnounceService::init($this->repoPool);
        Server::getInstance()->getCommandMap()->registerAll("announce", [
            new AnnounceCommand(),
            new AnnounceAdminCommand(),
            new PassCommand(),
        ]);
        Server::getInstance()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

    private function initRepository() {
        $this->db = new SQLite3($this->getDataFolder() . self::SQLITE_FILE);
        $this->repoPool = new RepositoryPool();
        $this->repoPool->register(UserRepository::class, new SQLiteUserRepository($this->db));
        $this->repoPool->register(AnnounceRepository::class, new SQLiteAnnounceRepository($this->db));
    }

    protected function onDisable(): void {
        $this->repoPool->close();
        $this->db->close();
    }
}