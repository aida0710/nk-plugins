<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\gacha;

use InvalidArgumentException;
use LogicException;
use matsuyuki\worldinv\api as WorldInvAPI;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use rarkhopper\gacha\Gacha;
use rarkhopper\gacha\GachaMessages;
use rarkhopper\gacha\IGachaItem;
use rarkhopper\gacha\IRarity;
use rarkhopper\gacha\ItemTable;
use rarkhopper\gacha\ITicket;
use ymggacha\src\yomogi_server\ymggacha\gacha\effect\NormalEffect;
use ymggacha\src\yomogi_server\ymggacha\gacha\effect\RareEffect;
use ymggacha\src\yomogi_server\ymggacha\gacha\effect\SuperRareEffect;
use ymggacha\src\yomogi_server\ymggacha\gacha\effect\SwitchToSuperRareEffect;
use ymggacha\src\yomogi_server\ymggacha\gacha\text\EffectableGachaMessage;
use ymggacha\src\yomogi_server\ymggacha\YmgGachaPlugin;
use function count;
use function mt_rand;

class EffectableRollableGacha extends Gacha implements IInFormRollableGacha {

	use OneLineDescriptionTrait;
	use EmmitListTrait;

	/** @var EffectableGachaMessage */
	protected GachaMessages $messages;

	public function __construct(
		string $name,
		string $oneLineDescription,
		string $description,
		string $emmitList,
		EffectableGachaMessage $messages,
		ItemTable $table,
		ITicket $ticket,
	) {
		parent::__construct($name, $description, $messages, $table, $ticket);
		$this->oneLineDescription = $oneLineDescription;
		$this->emmitList = $emmitList;
	}

	/**
	 * @throws LogicException
	 */
	public function roll(Player $player, int $count) : void {
		if ($count < 1 || $count > 10) throw new LogicException('invalid count ' . $count);
		if (!WorldInvAPI::getInstance()->isLife($player->getWorld()->getFolderName())) {
			$player->sendMessage($this->messages->invalidWorld);
			return;
		}
		if (!$this->canRoll($player, $count)) {
			$player->sendMessage($this->messages->rollFailed);
			return;
		}
		if (!$this->canAddItem($player, $count)) {
			$player->sendMessage($this->messages->cantAddItem);
			return;
		}
		$playersMap = GachaRollingPlayersMap::getInstance();
		if ($playersMap->exists($player)) {
			$player->sendMessage($this->messages->alreadyRolling);
			return;
		}
		$playersMap->register($player);
		$items = $this->table->pop($count);
		$giveItemFn = fn () => $this->giveItem($player, $items);
		$effect = match ($this->getHighestRarity($items)->getName()) {
			RarityMap::NAME_SSR => mt_rand(0, 1) === 1 ? new RareEffect() : new NormalEffect(),
			RarityMap::NAME_UR => mt_rand(0, 1) === 1 ? new SuperRareEffect() : new SwitchToSuperRareEffect(),
			default => new NormalEffect()
		};
		YmgGachaPlugin::getTaskScheduler()?->scheduleDelayedTask(new ClosureTask(fn () => $effect->play($player, $items, $giveItemFn)), 15);
	}

	/**
	 * @param array<IGachaItem> $items
	 */
	private function giveItem(Player $player, array $items) : void {
		GachaRollingPlayersMap::getInstance()->unregister($player);
		if (!$player->isOnline()) return;
		if (!$this->ticket->has($player, count($items))) {
			$player->sendMessage($this->messages->glitchTicket);
			return;
		}
		$giveCount = 0;
		foreach ($items as $item) {
			if ($item->giveItem($player)) {
				++$giveCount;
			} else {
				$player->sendMessage($this->messages->giveItemFailed);
			}
		}
		$this->ticket->consume($player, $giveCount);
	}

	/**
	 * @param array<IGachaItem> $items
	 * @throws InvalidArgumentException
	 */
	private function getHighestRarity(array $items) : IRarity {
		$highestRarity = null;
		if (count($items) < 1) throw new InvalidArgumentException('items must be count of 1 then more');
		foreach ($items as $item) {
			if ($highestRarity === null) {
				$highestRarity = $item->getRarity();
				continue;
			}
			if ($highestRarity->getEmissionPercent() > $item->getRarity()->getEmissionPercent()) {
				$highestRarity = $item->getRarity();
			}
		}
		return $highestRarity;
	}

	/**
	 * @throws InvalidArgumentException
	 */
	private function canAddItem(Player $player, int $count) : bool {
		if ($count < 1) throw new InvalidArgumentException('count of 1 then more');
		$dummyItem = VanillaItems::WOODEN_SWORD()->setCount($count);
		return $player->getInventory()->canAddItem($dummyItem);
	}
}
