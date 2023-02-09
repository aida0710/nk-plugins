<?php

declare(strict_types = 1);
namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\ButtonImage;
use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\element\CommandDispatchButton;
use lazyperson710\sff\element\SendFormButton;

class CommandExecutionForm extends SimpleForm {

	public function __construct() {
		$others1 = (new SimpleForm())
			->setTitle('Command Execution')
			->setText('実行したいコマンドを選択してください')
			->addElements(
				new CommandDispatchButton('Inventory Sort', 'inv', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/storageIconColor.png')),
				new CommandDispatchButton('Status Change', 'stinfo', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/icon_book_writable.png')),
				new CommandDispatchButton('NameTag Change', 'tag', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/items/name_tag.png')),
				new SendFormButton($this, '戻る', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/undoArrow.png')),
			);
		$others2 = (new SimpleForm())
			->setTitle('Command Execution')
			->setText('実行したいコマンドを選択してください')
			->addElements(
				new CommandDispatchButton('Login Bonus', 'bonus', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/items/glow_berries.png')),
				new CommandDispatchButton('Add Effect', 'ef', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/speed_effect.png')),
				new CommandDispatchButton('Land Manager', 'land', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/items/armor_stand.png')),
				new CommandDispatchButton('Lock System', 'lock', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/icon_lock.png')),
				new SendFormButton($this, '戻る', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/undoArrow.png')),
			);
		$this
			->setTitle('Command Execution')
			->setText("実行したいコマンドを選択してください\nコマンドの一覧は/cmdlsで確認できます")
			->addElements(
				new CommandDispatchButton('Virtual Storage', 'st', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/inventory_icon.png')),
				new CommandDispatchButton('Level Shop', 'shop', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/icon_balloon.png')),
				new CommandDispatchButton('bank System', 'bank', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/MCoin.png')),
				new CommandDispatchButton('My Warp', 'myw', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/broadcast_glyph_color.png')),
				new CommandDispatchButton('Gacha', 'gacha', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/icon_random.png')),
				new CommandDispatchButton('Statistics & Ranking', 'ranking', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/icon_best3.png')),
				new SendFormButton($others1, 'Others - 1', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/items/book_normal.png')),
				new SendFormButton($others2, 'Others - 2', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/items/book_normal.png')),
				new CommandDispatchButton("Report\nサーバー内から直接メッセージを送ります", 'report', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/mute_off.png')),
			);
	}
}
