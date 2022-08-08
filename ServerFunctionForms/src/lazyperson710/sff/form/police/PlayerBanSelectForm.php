<?php

namespace lazyperson710\sff\form\police;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use bbo51dog\mjolnir\service\BanService;
use bbo51dog\pmdiscord\connection\Webhook;
use bbo51dog\pmdiscord\element\Embed;
use bbo51dog\pmdiscord\element\Embeds;
use DateTime;
use DateTimeInterface;
use pocketmine\player\Player;
use pocketmine\Server;

class PlayerBanSelectForm extends CustomForm {

    private Dropdown $dropdown;
    private Input $input;

    public function __construct(Player $player) {
        $names = null;
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            $name = $onlinePlayer->getName();
            if (Server::getInstance()->isOp($name)) {
                continue;
            }
            if ($player->getName() === $name) {
                continue;
            }
            $names[] .= $name;
        }
        if (is_null($names)) {
            $names[] .= "表示可能なプレイヤーが存在しません";
        }
        $this->dropdown = new Dropdown("Banしたいプレイヤーを選択してください", $names);
        $this->input = new Input("banの理由を表記してください\n※このメッセージはbanされた人となまけものに表示されます");
        $this
            ->setTitle("Police System")
            ->addElements(
                $this->dropdown,
                $this->input,
            );
    }

    public function handleSubmit(Player $player): void {
        $playerName = $this->dropdown->getSelectedOption();
        if (Server::getInstance()->getPlayerByPrefix($playerName)) {
            Server::getInstance()->getPlayerByPrefix($playerName)->kick($this->input->getText());
        }
        BanService::banName($playerName, "{$this->input->getText()}");
        $webhook = Webhook::create("https://discord.com/api/webhooks/1006116792915202078/P_8DNsYoGS5msBiylZdbjhjDXWq3Ds9GVL6rnsroLE6i2QlQLd9DQi4HGBF13N1Ee8Cu");
        $embed = (new Embed())
            ->setTitle("{$player->getName()}が{$playerName}をmBanしました")
            ->setColor(255)
            ->setAuthorName("Mode : Player Ban")
            ->setTime((new DateTime())->format(DateTimeInterface::ATOM));
        $embeds = new Embeds();
        $embeds->add($embed);
        $webhook->add($embeds);
        $webhook->send();
    }
}