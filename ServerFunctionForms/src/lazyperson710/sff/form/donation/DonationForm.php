<?php

declare(strict_types = 0);

namespace lazyperson710\sff\form\donation;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\element\DonationsButton;

class DonationForm extends SimpleForm {

	public const DonationAmount = 5000;
	public const DonationInfo = [
		'1500' => [
			'donor' => 'toppo0118',
			'enable' => true,
			'item' => true,
		],
		'3000' => [
			'donor' => 'toppo0118',
			'enable' => true,
			'item' => false,
		],
		'5000' => [
			'donor' => 'toppo0118',
			'enable' => true,
			'item' => false,
		],
		'8000' => [
			'donor' => 'ReimariDarkness',
			'enable' => true,
			'item' => false,
		],
		'10000' => [
			'donor' => 'ReimariDarkness',
			'enable' => true,
			'item' => true,
		],
		'15000' => [
			'donor' => null,
			'enable' => false,
			'item' => false,
		],
	];

	public function __construct(?string $message = null) {
		$msg1 = "なま鯖では寄付を募っています。\n寄付方法はamazonギフトカードでDiscordにてなまけもののDMでのみ受け付けさせて頂いています。" . PHP_EOL;
		$msg2 = "また、寄付して頂いた額に応じて全員が特典を得ることができます\n特典は寄付額に応じて以下のボタンから寄付を受け取り可能です。" . PHP_EOL;
		$msg3 = '特典に関しては寄付していただいた際に寄付してくださった方の要望をある程度反映したいと考えております。' . PHP_EOL . PHP_EOL;
		$msg4 = '総寄付金額 11000円' . PHP_EOL;
		$this
			->setTitle('Donation Form')
			->setText($msg1 . $msg2 . $msg3 . $msg4 . $message);
		foreach (self::DonationInfo as $key => $value) {
			$key = (int) $key;
			if ($value['enable']) {
				if ($value['item']) {
					$this->addElement(new DonationsButton("{$key}円寄付 | §d特典: アイテム§r\n寄付者: {$value['donor']}", $key, $value['donor']));
				} else {
					$this->addElement(new DonationsButton("{$key}円寄付 | §d特典: 機能実装§r\n寄付者: {$value['donor']}", $key, $value['donor']));
				}
			} else {
				$this->addElement(new DonationsButton("{$key}円寄付 | §c現在未開放§r\n寄付者: None", $key));
			}
		}
	}
}
