<?php

declare(strict_types=1);
namespace nkserver\ranking\event\handler;

use deceitya\miningTools\event\MiningToolsBreakEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Event;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use function get_class;

class EventListener implements Listener {

	/** @var BaseHandler[] */
	protected static array $handlers = [];

	public static function init() : void {
		self::registerHandler(new BlockBreakHandler());
		self::registerHandler(new BlockBreakMiningToolsHandler());
		self::registerHandler(new BlockPlaceHandler());
		self::registerHandler(new PlayerChatHandler());
		self::registerHandler(new PlayerDeathHandler());
		self::registerHandler(new PlayerJoinHandler());
	}

	public static function registerHandler(BaseHandler $handler) : void {
		self::$handlers[$handler->getTarget()] = $handler;
	}

	/**
	 * @priority HIGH
	 */
	public function onBreakBlock(BlockBreakEvent $ev) : void {
		if ($ev->isCancelled()) {
			return;
		}
		self::onListen($ev);
	}

	protected static function onListen(Event $ev) : void {
		if (!isset(self::$handlers[($class = get_class($ev))])) return;
		self::$handlers[$class]->handleEvent($ev);
	}

	/**
	 * @priority HIGH
	 */
	public function onBlockBreakMiningToolsHandler(MiningToolsBreakEvent $ev) : void {
		if ($ev->isCancelled()) {
			return;
		}
		self::onListen($ev);
	}

	/**
	 * @priority HIGH
	 */
	public function onPlaceBlock(BlockPlaceEvent $ev) : void {
		if ($ev->isCancelled()) {
			return;
		}
		self::onListen($ev);
	}

	/**
	 * @priority HIGH
	 */
	public function onChatPlayer(PlayerChatEvent $ev) : void {
		if ($ev->isCancelled()) {
			return;
		}
		self::onListen($ev);
	}

	public function onDeathPlayer(PlayerDeathEvent $ev) : void {
		self::onListen($ev);
	}

	public function onJoinPlayer(PlayerJoinEvent $ev) : void {
		self::onListen($ev);
	}
}
