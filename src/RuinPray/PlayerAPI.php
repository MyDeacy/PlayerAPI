<?php

namespace RuinPray;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\Player;

class PlayerAPI extends PluginBase implements Listener{

	//戻り値の内容を変えたければ定数をいじってください(わからない方は推奨しません)
	const OS = ["unknown", "Android", "iOS", "OSX", "FireOS", "GearVR", "HoloLens", "Windows10", "Windows", "Dedicated", "Orbis", "NX", "Switch"];
	const UI = ["Classic", "Pocket"];
	const CONTROL = ["unknown", "Keyboard", "Tap", "Controller"];
	const GUI_SIZE = [
		-2 => "Minimum",
		-1 => "Medium",
		0 => "Maximum"
		];

	private $player = [];
	
	private static $instance = null;


	public function onLoad(){
		self::$instance = $this;
	}

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onPacketReceived(DataPacketReceiveEvent $event){
		if($event->getPacket() instanceof LoginPacket){
			$pk = $event->getPacket();
			$data = $pk->clientData;
			$this->player[$pk->username] = $data;//Login時に取ったデータを配列に一時保存
		}
	}


	public static function getInstance(){
		return self::$instance;
	}

	//使用OS
	public function getOSType(Player $player): String{
		return self::OS[$this->player[$player->getName()]["DeviceOS"]];
	}

	//UIのステータス(Pocket or Classic)
	public function getUIType(Player $player): String{
		return self::UI[$this->player[$player->getName()]["UIProfile"]];
	}

	//ゲームの操作 (Tap, Keyboard, Conttoller) まれに誤判定有り(?)
	public function getControlType(Player $player): String{
		return self::CONTROL[$this->player[$player->getName()]["CurrentInputMode"]];
	}

	//GUIのスケール (Maximum > Medium > Minimum)
	public function getGUIsize(Player $player): String{
		return self::GUI_SIZE[$this->player[$player->getName()]["GuiScale"]];	
	}

	//使用機種
	public function getDeviceModel(Player $player): String{
		return $this->player[$player->getName()]["DeviceModel"];
	}

	//Minecraftのバージョン
	public function getGameVersion(Player $player): String{
		return $this->player[$player->getName()]["GameVersion"];
	}

	//使用言語
	public function getLanguageCode(Player $player): String{
		$this->player[$player->getName()]["LanguageCode"];
	}

}
