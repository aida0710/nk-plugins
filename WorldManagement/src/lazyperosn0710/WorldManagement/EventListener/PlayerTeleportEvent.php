<?php

namespace lazyperson0710\WorldManagement\EventListener;

use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\WorldManagement\database\WorldManagementAPI;
use lazyperson710\sff\form\WarpForm;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\Server;

class PlayerTeleportEvent implements Listener {

    public function PlayerTeleportEvent(EntityTeleportEvent $event) {
        $player = $event->getEntity();
        if (!$player instanceof Player) return;
        if ($event->getTo()->getWorld()->getDisplayName() === $event->getFrom()->getWorld()->getDisplayName()) {
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= WorldManagementAPI::getInstance()->getMiningLevelLimit($event->getTo()->getWorld()->getFolderName())) {
            switch ($event->getTo()->getWorld()->getDisplayName()) {
                case "nature-1":
                case "nature-2":
                    $player->sendMessage("§bWorld §7>> §a天然資源ワールド\n§7>> §a自由に採掘が可能です\n§7>> §aたくさんの資源を集めてショップで売却してみよう！");
                    break;
                case "nether-1":
                    $player->sendMessage("§bWorld §7>> §aネザーワールド\n§7>> §a自由に採掘が可能です\n§7>> §aたくさんの資源を集めてショップで売却してみよう！");
                    break;
                case "nature-java":
                    $player->sendMessage("§bWorld §7>> §aJava\n§7>> §aこのワールドはマイニングレベルが取得できない等の制限がありますが洞窟などが生成されます");
                    break;
                case "MiningWorld":
                    $player->sendMessage("§bWorld §7>> §aMiningWorld\n§7>> §aこのワールドは一週間ごとにリセットされます");
                    break;
                case "resource":
                    $player->sendMessage("§bWorld §7>> §aResource\n§7>> §aこのワールドは中級者から上級者向けに作成されています\n§7>> §aその為お金を稼ぎたい方は天然資源をおすすめさせていただいております");
                    break;
                case "生物市-c":
                    $player->sendMessage("§bWorld §7>> §a建築ワールド\n§7>> §aこのワールドは建築が可能なワールドになります\n§7>> §a/landを実行して土地を購入してください");
                    break;
                case "船橋市-c":
                    $player->sendMessage("§bWorld §7>> §a建築ワールド\n§7>> §aこのワールドは建築が可能なワールドになります\n§7>> §a/landを実行して土地を購入してください\n§7>> §a船橋市は大きく景観を損なう行為を禁止させていただいております。例:露天掘りや大規模な整地など\n§7>> §aまた、購入範囲が明確にわかるようにしてください");
                    break;
                case "浜松市-f":
                    $player->sendMessage("§bWorld §7>> §a農業ワールド\n§7>> §aこのワールドは農業が可能なワールドになります\n§7>> §a/landを実行して土地を購入してください");
                    break;
                case "event-1":
                    $player->sendMessage("§bWorld §7>> §aイベントワールド\n§7>> §a昔からしようしていたロビーワールド\n§7>> §aせっかくなので何かのイベントに使えたらなと思っています。。。");
                    break;
                case "lobby":
                    $player->sendForm(new WarpForm($player));
                    break;
            }
            return;
        }
        $player->sendMessage("§bWorld §7>> §c移動した先のワールドはまだ解放されていない為ロビーに戻されました");
        Server::getInstance()->dispatchCommand($player, "warp lobby");
        return;
    }

}