前提条件
#ログイン時の処理や何度も入る処理
#プレイヤーが意図しない動作の時には音は鳴らさない

動作の種類と音
#ショップでのエラー
SoundPacket::Send($player, 'dig.chain');

#Command実行時
SoundPacket::Send($player, 'block.false_permissions');

#通常の失敗時
SoundPacket::Send($player, 'note.bass');

#ショップでの成功
SoundPacket::Send($player, 'break.amethyst_block');

#通常の成功時
SoundPacket::Send($player, 'note.harp');

#フォームで戻る時
SoundPacket::Send($player, 'mob.shulker.close');

#ワープ時
SoundPacket::Send($sender, 'mob.endermen.portal');

#何かを知らせたいとき
SoundPacket::Send($player, 'respawn_anchor.deplete');

#プレイヤーが復活したとき
SoundPacket::Send($player, "respawn_anchor.set_spawn");

#落下ダメージが無効化されたとき
SoundPacket::Send($player, 'block.lantern.break');

#アイテムを使用したとき
SoundPacket::Send($player, 'item.trident.return');

#機会音っぽくしたいとき
SoundPacket::Send($player, "item.spyglass.use");