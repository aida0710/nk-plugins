<?php

declare(strict_types = 0);
/*
 * EconomyS, the massive economy plugin with many features for PocketMine-MP
 * Copyright (C) 2013-2017  onebone <jyc00410@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace onebone\economyapi;

use onebone\economyapi\event\account\CreateAccountEvent;
use onebone\economyapi\event\money\AddMoneyEvent;
use onebone\economyapi\event\money\MoneyChangedEvent;
use onebone\economyapi\event\money\ReduceMoneyEvent;
use onebone\economyapi\event\money\SetMoneyEvent;
use onebone\economyapi\provider\MySQLProvider;
use onebone\economyapi\provider\Provider;
use onebone\economyapi\provider\YamlProvider;
use onebone\economyapi\task\SaveTask;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\Internet;
use pocketmine\utils\TextFormat;
use Throwable;
use function count;
use function file_get_contents;
use function file_put_contents;
use function is_file;
use function is_numeric;
use function json_decode;
use function round;
use function serialize;
use function str_replace;
use function strtolower;
use function substr;
use function unserialize;

class EconomyAPI extends PluginBase implements Listener {

    public const API_VERSION = 3;
    public const PACKAGE_VERSION = '5.7';

    public const RET_NO_ACCOUNT = -3;
    public const RET_CANCELLED = -2;
    public const RET_NOT_FOUND = -1;
    public const RET_INVALID = 0;
    public const RET_SUCCESS = 1;

    private static $instance = null;

    /** @var Provider */
    private $provider;

    private $lang = [], $playerLang = [];

    public static function getInstance() : self {
        return self::$instance;
    }

    /**
     * @param string|bool $lang
     */
    public function getCommandMessage(string $command, $lang = false) : array {
        if ($lang === false) {
            $lang = $this->getConfig()->get('default-lang');
        }
        $command = strtolower($command);
        if (isset($this->lang[$lang]['commands'][$command])) {
            return $this->lang[$lang]['commands'][$command];
        } else {
            return $this->lang['def']['commands'][$command];
        }
    }

    public function getMessage(string $key, array $params = [], string $player = 'console') : string {
        $player = strtolower($player);
        if (isset($this->lang[$this->playerLang[$player]][$key])) {
            return $this->replaceParameters($this->lang[$this->playerLang[$player]][$key], $params);
        } elseif (isset($this->lang['def'][$key])) {
            return $this->replaceParameters($this->lang['def'][$key], $params);
        }
        return "Language matching key \"$key\" does not exist.";
    }

    private function replaceParameters($message, $params = []) {
        $search = ['%MONETARY_UNIT%'];
        $replace = [$this->getMonetaryUnit()];
        for ($i = 0; $i < count($params); $i++) {
            $search[] = '%' . ($i + 1);
            $replace[] = $params[$i];
        }
        $colors = [
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'k', 'l', 'm', 'n', 'o', 'r',
        ];
        foreach ($colors as $code) {
            $search[] = '&' . $code;
            $replace[] = TextFormat::ESCAPE . $code;
        }
        return str_replace($search, $replace, $message);
    }

    public function getMonetaryUnit() : string {
        return $this->getConfig()->get('monetary-unit');
    }

    public function setPlayerLanguage(string $player, string $language) : bool {
        $player = strtolower($player);
        $language = strtolower($language);
        if (isset($this->lang[$language])) {
            $this->playerLang[$player] = $language;
            return true;
        }
        return false;
    }

    public function getAllMoney() : array {
        return $this->provider->getAll();
    }

    /**
     * @param Player|string $player
     *
     * @return float|bool
     */
    public function myMoney($player) {
        return $this->provider->getMoney($player);
    }

    /**
     * @param string|Player $player
     * @param float         $amount
     */
    public function setMoney($player, $amount, bool $force = false, string $issuer = 'none') : int {
        if ($amount < 0) {
            return self::RET_INVALID;
        }
        if ($player instanceof Player) {
            $player = $player->getName();
        }
        $player = strtolower($player);
        if ($this->provider->accountExists($player)) {
            $amount = round($amount, 2);
            if ($amount > $this->getConfig()->get('max-money')) {
                return self::RET_INVALID;
            }
            $oldMoney = $this->provider->getMoney($player);
            if (!is_numeric($oldMoney)) $oldMoney = null;
            $ev = new SetMoneyEvent($this, $player, $amount, $issuer);
            $ev->call();
            if (!$ev->isCancelled() || $force === true) {
                $this->provider->setMoney($player, $amount);
                $ev2 = new MoneyChangedEvent($this, $player, $amount, $issuer, $oldMoney);
                $ev2->call();
                return self::RET_SUCCESS;
            }
            return self::RET_CANCELLED;
        }
        return self::RET_NO_ACCOUNT;
    }

    /**
     * @param string|Player $player
     */
    public function accountExists($player) : bool {
        return $this->provider->accountExists($player);
    }

    /**
     * @param string|Player $player
     * @param float         $amount
     * @param string        $issuer
     */
    public function addMoney($player, $amount, bool $force = false, $issuer = 'none') : int {
        if ($amount < 0) {
            return self::RET_INVALID;
        }
        if ($player instanceof Player) {
            $player = $player->getName();
        }
        $player = strtolower($player);
        if (($money = $this->provider->getMoney($player)) !== false) {
            $amount = round($amount, 2);
            if ($money + $amount > $this->getConfig()->get('max-money')) {
                return self::RET_INVALID;
            }
            $ev = new AddMoneyEvent($this, $player, $amount, $issuer);
            $ev->call();
            if (!$ev->isCancelled() || $force === true) {
                $this->provider->addMoney($player, $amount);
                $ev2 = new MoneyChangedEvent($this, $player, $amount + $money, $issuer, $money);
                $ev2->call();
                return self::RET_SUCCESS;
            }
            return self::RET_CANCELLED;
        }
        return self::RET_NO_ACCOUNT;
    }

    /**
     * @param string|Player $player
     * @param float         $amount
     * @param string        $issuer
     */
    public function reduceMoney($player, $amount, bool $force = false, $issuer = 'none') : int {
        if ($amount < 0) {
            return self::RET_INVALID;
        }
        if ($player instanceof Player) {
            $player = $player->getName();
        }
        $player = strtolower($player);
        if (($money = $this->provider->getMoney($player)) !== false) {
            $amount = round($amount, 2);
            if ($money - $amount < 0) {
                return self::RET_INVALID;
            }
            $ev = new ReduceMoneyEvent($this, $player, $amount, $issuer);
            $ev->call();
            if (!$ev->isCancelled() || $force === true) {
                $this->provider->reduceMoney($player, $amount);
                $ev2 = new MoneyChangedEvent($this, $player, $money - $amount, $issuer, $money);
                $ev2->call();
                return self::RET_SUCCESS;
            }
            return self::RET_CANCELLED;
        }
        return self::RET_NO_ACCOUNT;
    }

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        if (!isset($this->playerLang[strtolower($player->getName())])) {
            $this->playerLang[strtolower($player->getName())] = $this->getConfig()->get('default-lang');
        }
        if (!$this->provider->accountExists($player)) {
            $this->getLogger()->debug("Account of '" . $player->getName() . "' is not found. Creating account...");
            $this->createAccount($player, false, true);
        }
    }

    /**
     * @param string|Player $player
     * @param float         $defaultMoney
     */
    public function createAccount($player, $defaultMoney = false, bool $force = false) : bool {
        if ($player instanceof Player) {
            $player = $player->getName();
        }
        $player = strtolower($player);
        if (!$this->provider->accountExists($player)) {
            $defaultMoney = ($defaultMoney === false) ? $this->getConfig()->get('default-money') : $defaultMoney;
            $ev = new CreateAccountEvent($this, $player, $defaultMoney, 'none');
            $ev->call();
            if (!$ev->isCancelled() || $force === true) {
                $this->provider->createAccount($player, $ev->getDefaultMoney());
            }
        }
        return false;
    }

    protected function onDisable(): void {
        $this->saveAll();
        if ($this->provider instanceof Provider) {
            $this->provider->close();
        }
    }

    public function saveAll() {
        if ($this->provider instanceof Provider) {
            $this->provider->save();
        }
        file_put_contents($this->getDataFolder() . 'PlayerLang.dat', serialize($this->playerLang));
    }

    protected function onEnable(): void {
        /*
         * 디폴트 설정 파일을 먼저 생성하게 되면 데이터 폴더 파일이 자동 생성되므로
         * 'Failed to open stream: No such file or directory' 경고 메시지를 없앨 수 있습니다
         * - @64FF00
         *
         * [추가 옵션]
         * if(!file_exists($this->dataFolder))
         *     mkdir($this->dataFolder, 0755, true);
         */
        $this->saveDefaultConfig();
        if (!is_file($this->getDataFolder() . 'PlayerLang.dat')) {
            file_put_contents($this->getDataFolder() . 'PlayerLang.dat', serialize([]));
        }
        $this->playerLang = unserialize(file_get_contents($this->getDataFolder() . 'PlayerLang.dat'));
        if (!isset($this->playerLang['console'])) {
            $this->playerLang['console'] = $this->getConfig()->get('default-lang');
        }
        if (!isset($this->playerLang['rcon'])) {
            $this->playerLang['rcon'] = $this->getConfig()->get('default-lang');
        }
        $this->initialize();
        if ($this->getConfig()->get('auto-save-interval') > 0) {
            $this->getScheduler()->scheduleDelayedRepeatingTask(new SaveTask($this), $this->getConfig()->get('auto-save-interval') * 1200, $this->getConfig()->get('auto-save-interval') * 1200);
        }
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    private function initialize() {
        if ($this->getConfig()->get('check-update')) {
            $this->checkUpdate();
        }
        switch (strtolower($this->getConfig()->get('provider'))) {
            case 'yaml':
                $this->provider = new YamlProvider($this);
                break;
            case 'mysql':
                $this->provider = new MySQLProvider($this);
                break;
            default:
                $this->getLogger()->critical('Invalid database was given.');
                return false;
        }
        $this->provider->open();
        $this->initializeLanguage();
        $this->getLogger()->notice('Database provider was set to: ' . $this->provider->getName());
        $this->registerPermissions();
        $this->registerCommands();
    }

    private function checkUpdate() {
        try {
            $info = json_decode(Internet::simpleCurl($this->getConfig()->get('update-host') . '?version=' . $this->getDescription()->getVersion() . '&package_version=' . self::PACKAGE_VERSION)->getBody(), true);
            if (!isset($info['status']) || $info['status'] !== true) {
                $this->getLogger()->notice('Something went wrong on update server.');
                return false;
            }
            if ($info['update-available'] === true) {
                $this->getLogger()->notice('Server says new version (' . $info['new-version'] . ') of EconomyS is out. Check it out at ' . $info['download-address']);
            }
            $this->getLogger()->notice($info['notice']);
            return true;
        } catch (Throwable $e) {
            $this->getLogger()->logException($e);
            return false;
        }
    }

    private function initializeLanguage() {
        foreach ($this->getResources() as $resource) {
            if ($resource->isFile() && substr(($filename = $resource->getFilename()), 0, 5) === 'lang_') {
                $this->lang[substr($filename, 5, -5)] = json_decode(file_get_contents($resource->getPathname()), true);
            }
        }
        $this->lang['user-define'] = (new Config($this->getDataFolder() . 'messages.yml', Config::YAML, $this->lang['def']))->getAll();
    }

    private function registerPermissions() : void {
        DefaultPermissions::registerPermission(new Permission('economyapi.*', 'Allows to control all of functions in EconomyAPI', [
            'economyapi.command.setmoney',
            'economyapi.command.mymoney',
            'economyapi.command.givemoney',
            'economyapi.command.pay',
            'economyapi.command.seemoney',
            'economyapi.command.setlang',
            'economyapi.command.topmoney',
            'economyapi.command.mystatus',
            'economyapi.command.takemoney',
        ]));
        DefaultPermissions::registerPermission(new Permission('economyapi.*', 'Allows to control all of functions in EconomyAPI', ['economyapi.command.*']));
    }

    private function registerCommands() {
        $map = $this->getServer()->getCommandMap();
        $commands = [
            'mymoney' => "\\onebone\\economyapi\\command\\MyMoneyCommand",
            'topmoney' => "\\onebone\\economyapi\\command\\TopMoneyCommand",
            'setmoney' => "\\onebone\\economyapi\\command\\SetMoneyCommand",
            'seemoney' => "\\onebone\\economyapi\\command\\SeeMoneyCommand",
            'givemoney' => "\\onebone\\economyapi\\command\\GiveMoneyCommand",
            'takemoney' => "\\onebone\\economyapi\\command\\TakeMoneyCommand",
            'pay' => "\\onebone\\economyapi\\command\\PayCommand",
            'setlang' => "\\onebone\\economyapi\\command\\SetLangCommand",
            'mystatus' => "\\onebone\\economyapi\\command\\MyStatusCommand",
        ];
        foreach ($commands as $cmd => $class) {
            $map->register('economyapi', new $class($this));
        }
    }

    public function onLoad() : void {
        self::$instance = $this;
    }

    public function openProvider() {
        if ($this->provider !== null)
            $this->provider->open();
    }
}
