<?php

namespace lazyperson710\core\listener;

use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\JoinItemsSetting;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

class JoinPlayerEvent implements Listener {

    public const NBT_ROOT = "sff";
    public const NBT_ID = "id";

    public const ID_INFORMATION = 1;
    public const ID_COMMAND_EXECUTION = 2;
    public const ID_WARP = 3;
    public const ID_SETTINGS = 4;
    public const ID_TOS = 15;

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        if (!$player->hasPlayedBefore()) {
            $this->setItem($player, VanillaItems::BOOK(), self::ID_TOS, "利用規約本", ["タップすると利用規約を表示します", "利用規約web版 -> https://www.nkserver.net/tos.html"]);
        }
        if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(JoinItemsSetting::getName())?->getValue() === true) {
            $this->sendJoinItem($player);
        } else {
            $player->sendMessage("§bJoinSetting §7>> §a設定されている為アイテムが付与されませんでした。アイテムは/joinitemから取得可能です");
        }
    }

    public static function sendJoinItem(Player $player) {
        (new JoinPlayerEvent)->setItem($player, VanillaItems::PAPER(), self::ID_INFORMATION, "Information", ["お知らせやサーバーの仕様などが確認できます", "公式サイト -> https://www.nkserver.net"]);
        (new JoinPlayerEvent)->setItem($player, VanillaItems::COOKIE(), self::ID_COMMAND_EXECUTION, "CommandExecution", ["サーバーの主なコマンドが簡単に実行できます"]);
        (new JoinPlayerEvent)->setItem($player, VanillaItems::COMPASS(), self::ID_WARP, "WarpCompass", ["サーバー上の他ワールドに移動することができます"]);
        (new JoinPlayerEvent)->setItem($player, VanillaBlocks::OAK_SAPLING()->asItem(), self::ID_SETTINGS, "PlayerSettings", ["プレイヤーの設定を変更することができます"]);
    }

    public function setItem(Player $player, Item $item, int $id, string $customName, array $lore) {
        $item->setCustomName($customName);
        $item->setLore($lore);
        $item->getNamedTag()->setTag(
            self::NBT_ROOT,
            (new CompoundTag())
                ->setInt(self::NBT_ID, $id)
        );
        if (!$player->getInventory()->contains($item) && $player->getInventory()->canAddItem($item)) {
            $player->getInventory()->addItem($item);
        }
    }

}
