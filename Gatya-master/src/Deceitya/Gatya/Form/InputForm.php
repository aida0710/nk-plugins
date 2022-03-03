<?php

namespace Deceitya\Gatya\Form;

use Deceitya\Gatya\Series\Series;
use Deceitya\Gatya\Series\SeriesFactory;
use Deceitya\Gatya\Utils\MessageContainer;
use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\Server;

class InputForm implements Form {

    private Series $series;

    public function __construct(string $name) {
        $this->series = SeriesFactory::getSeries($name);
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        if (!preg_match('/\d+$/', $data[2])) {
            $player->sendMessage(MessageContainer::get('form.input.input.invalid'));
            return;
        }
        Server::getInstance()->dispatchCommand($player, "gatya {$this->series->getId()} {$data[2]}");
    }

    public function jsonSerialize() {
        return [
            'type' => 'custom_form',
            'title' => MessageContainer::get('form.input.title'),
            'content' => [
                [
                    'type' => 'label',
                    'text' => MessageContainer::get(
                        'form.input.label1',
                        $this->series->getName(),
                        $this->series->getId()
                    )
                ],
                [
                    'type' => 'label',
                    'text' => $this->series->isTicket() ?
                        MessageContainer::get('form.input.label_ticket', $this->series->getCost()) :
                        MessageContainer::get('form.input.label_money', EconomyAPI::getInstance()->getMonetaryUnit(), $this->series->getCost())
                ],
                [
                    'type' => 'input',
                    'text' => MessageContainer::get('form.input.input.text'),
                    'placeholder' => MessageContainer::get('form.input.input.placeholder'),
                    'default' => MessageContainer::get('form.input.input.default')
                ]
            ]
        ];
    }
}
