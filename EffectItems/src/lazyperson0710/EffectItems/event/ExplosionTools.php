<?php

namespace lazyperson0710\EffectItems\event;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Explosion;

class ExplosionTools implements Listener {

    public function onBreak(BlockPlaceEvent $event): void {
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('ExplosionBlock') !== null) {//ExplosionBlock
            $event->cancel();
            $inHand->setCount(1);
            $player->getInventory()->removeItem($inHand);
            $explosion = (new Explosion($event->getBlock()->getPosition()->asPosition(), mt_rand(3, 15)));
            //$explosion->explodeA();//破壊するブロックを計算(オプション、実行しない場合、「explodeB」関数にてエフェクトとダメージの処理のみ実施)
            //$explosion->explodeB();//エフェクト表示、エンティティに対するダメージ、explodeAを実行した場合ブロックの破壊を実施
            $explosion->explodeB();
        }
    }

    public function onDamage(EntityDamageEvent $event) {
        if (!$event->getEntity() instanceof Player) return;
        if ($event->getCause() == EntityDamageEvent::CAUSE_BLOCK_EXPLOSION) {
            /** @var $getEntity Player */
            $getEntity = $event->getEntity();
            $playerName = $getEntity->getName();
            if ($player = Server::getInstance()->getPlayerExact($playerName)) {
                $player->sendTip("§bExplosion §7>> §a爆発ダメージが無効化されました");
                $event->cancel();
            }
        }
    }
}