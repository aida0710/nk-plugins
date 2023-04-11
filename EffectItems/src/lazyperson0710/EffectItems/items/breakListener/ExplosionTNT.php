<?php

declare(strict_types = 0);

namespace lazyperson0710\EffectItems\items\breakListener;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\player\GameMode;
use pocketmine\world\Explosion;
use function mt_rand;

class ExplosionTNT {

	public static function execution(BlockPlaceEvent $event) : void {
		$event->cancel();
		$inHand = $event->getPlayer()->getInventory()->getItemInHand();
		if ($event->getPlayer()->getGamemode() !== GameMode::CREATIVE()) {
			$event->getPlayer()->getInventory()->removeItem($inHand->setCount(1));
		}
		$explosion = (new Explosion($event->getBlock()->getPosition()->asPosition(), mt_rand(3, 15)));
		//$explosion->explodeA();//破壊するブロックを計算(オプション、実行しない場合、「explodeB」関数にてエフェクトとダメージの処理のみ実施)
		//$explosion->explodeB();//エフェクト表示、エンティティに対するダメージ、explodeAを実行した場合ブロックの破壊を実施
		$explosion->explodeB();
	}
}
