<?php

declare(strict_types=1);

namespace bbo51dog\discordlog;

use bbo51dog\discordlog\listener\DeathListener;
use bbo51dog\discordlog\listener\JoinListener;
use bbo51dog\discordlog\listener\MessageListener;
use bbo51dog\discordlog\listener\QuitListener;
use bbo51dog\discordlog\listener\WhiteListListener;
use bbo51dog\pmdiscord\connection\Webhook;
use bbo51dog\pmdiscord\element\Content;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use function date;
use function date_default_timezone_set;
use function str_replace;

class Main extends PluginBase {

	/** @var string */
	private const TIME = "[Y/m/d | H:i:s]";
	public const MESSAGE = "%time <%player> %message";
	public const JOIN = "%time %playerさんがログインしました";
	public const QUIT = "%time %playerさんがログアウトしました";
	public const DEATH = "%time %playerさんが死亡しました";
	public const START = "%time サーバーを起動しました";
	public const STOP = "%time サーバーを停止しました";
	public const WHITELIST_ON = "%time %playerさんがホワイトリストを有効にしました";
	public const WHITELIST_OFF = "%time %playerホワイトリストを無効にしました";
	private bool $stop;
	private string $url;

	protected function onEnable() : void {
		date_default_timezone_set('Asia/Tokyo');
		$config = new Config($this->getDataFolder() . "Config.yml", Config::YAML, [
			"url" => "Your webhook url",
			"message" => false,
			"join" => false,
			"quit" => false,
			"death" => false,
			"start" => false,
			"stop" => false,
			"whitelist" => false,
		]);
		$setting = $config->getAll();
		if ($setting["message"]) {
			$this->getServer()->getPluginManager()->registerEvents(new MessageListener($setting["url"]), $this);
		}
		if ($setting["join"]) {
			$this->getServer()->getPluginManager()->registerEvents(new JoinListener($setting["url"]), $this);
		}
		if ($setting["quit"]) {
			$this->getServer()->getPluginManager()->registerEvents(new QuitListener($setting["url"]), $this);
		}
		if ($setting["death"]) {
			$this->getServer()->getPluginManager()->registerEvents(new DeathListener($setting["url"]), $this);
		}
		if ($setting["whitelist"]) {
			$this->getServer()->getPluginManager()->registerEvents(new WhiteListListener($setting["url"]), $this);
		}
		if ($setting["start"]) {
			$time = self::getTime();
			$s = str_replace("%time", $time, self::START);
			$webhook = Webhook::create($setting["url"]);
			$content = new Content();
			$content->setText($s);
			$webhook->add($content);
			$webhook->send();
		}
		$this->stop = $setting["stop"];
		$this->url = $setting["url"];
	}

	public static function getTime() : string {
		return date(self::TIME);
	}

	protected function onDisable() : void {
		if (!$this->stop) {
			return;
		}
		$time = self::getTime();
		$s = str_replace("%time", $time, self::STOP);
		$webhook = Webhook::create($this->url);
		$content = new Content();
		$content->setText($s);
		$webhook->add($content);
		$webhook->send();
	}
}
