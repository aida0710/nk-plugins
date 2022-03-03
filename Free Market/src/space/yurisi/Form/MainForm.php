<?php

declare(strict_types=1);
namespace space\yurisi\Form;

use pocketmine\form\Form;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;
use space\yurisi\Config\YamlConfig;
use space\yurisi\Form\Buy\BuyForm;
use space\yurisi\Form\Sell\ConfirmMyMarketForm;
use space\yurisi\Form\Sell\SellRegisterForm;

class MainForm implements Form {

    /**
     * Handles a form response from a player.
     *
     * @param Player $player
     * @param mixed $data
     *
     */
    public function handleResponse(Player $player, $data): void {
        if (!is_numeric($data)) return;
        switch ($data) {
            case 0:
                $player->sendForm(new BuyForm());
                break;
            case 1:
                $player->sendForm(new SellRegisterForm($player));
                break;
            case 2:
                $cls = new YamlConfig();
                if ($cls->getMarketPlayer($player->getName()) === null) {
                    $player->sendMessage("§bMarket §7>> §c現在出品されているアイテムはありません");
                    break;
                }
                $player->sendForm(new ConfirmMyMarketForm($cls->getMarketPlayer($player->getName())));
                break;
            case 3:
                if ($player->hasPermission(DefaultPermissions::ROOT_OPERATOR)) {
                    $cls = new YamlConfig();
                    if ($cls->getPrivateAllMarket() == null) {
                        $player->sendMessage("§bMarket §7>> §c現在出品されているアイテムはありません");
                        break;
                    }
                    foreach ($cls->getPrivateAllMarket() as $id) {
                        $buttons[] = $cls->getMarketData($id);
                    }
                    $player->sendForm(new ConfirmMyMarketForm($buttons));
                    break;
                }
                $player->sendMessage("§bMarket §7>> §c権限が不足しています");
                break;
        }
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize() {
        $buttons[] = ['text' => "アイテムを購入する"];
        $buttons[] = ['text' => "アイテムを出品する"];
        $buttons[] = ['text' => "自分の出品リスト"];
        $buttons[] = ['text' => "全プレイヤーの出品リスト"];
        return [
            "type" => 'form',
            "title" => 'Free Market',
            "content" => "選択してください",
            "buttons" => $buttons,
        ];
    }
}