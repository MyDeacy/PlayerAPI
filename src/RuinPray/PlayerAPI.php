<?php

namespace RuinPray;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\Player;
use pocketmine\command\CommandSender;

class PlayerAPI extends PluginBase implements Listener{

	//戻り値の内容を変えたければここをいじってください(わからない方は推奨しません)
	const OS = array("unknown", "Android", "iOS", "MacOS", "FireOS", "GearVR", "HoloLens", "Windows10", "Windows", "Dedicated", "Orbis", "NX");//実は正しいかは微妙です()
	const UI = array("Classic", "Pocket");
	const CONTROL = array("unknown", "keyboard", "Tap", "Controller");
	const GUI_SIZE = array(
		-2 => "Minimum",
		-1 => "Medium",
		0 => "Maximum"
		);

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
			$this->player[$pk->username] = $data;　//Login時に取ったデータを配列に一時保存
		}
	}

	/*--------- 

	* ここから関数 (SRCによっては動かないものもあるかも)
	* 公式とGenisysProで動作確認済みです。
	* 引数にはPlayerまたはCommandSenderをいれてください。

	 ----------*/


	public function getOSType($player){
		return PlayerAPI::OS[$this->player[$player->getName()]["DeviceOS"]];
	}

	public function getUIType($player){
		return PlayerAPI::UI[$this->player[$player->getName()]["UIProfile"]];
	}

	public function getControlType($player){
		return PlayerAPI::CONTROL[$this->player[$player->getName()]["CurrentInputMode"]];
	}

	public function getGUIsize($player){
		return PlayerAPI::GUI_SIZE[$this->player[$player->getName()]["GuiScale"]];	
	}

	public function getDviceModel($player){
		return $this->player[$player->getName()]["DeviceModel"];
	}

	public static function getInstance(){
		return self::$instance;
	}

}