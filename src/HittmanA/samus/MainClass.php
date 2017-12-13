<?php

namespace HittmanA\samus;

//Player stuff
use HittmanA\samus\UnAuthedPlayer;
use HittmanA\samus\AuthedPlayer;
use HittmanA\samus\AuthedPlayerData;

//PocketMine
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerMoveEvent;

class MainClass extends PluginBase implements Listener
{

    /** @var Config */
    protected $users;

    /** @var Player */
    protected $unAuthedPlayers = [];

    public function onEnable()
    {

        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        //$this->getServer()->getPluginManager()->registerEvents(new Events($this), $this);
        $this->users = new Config($this->getDataFolder() . "players.json", Config::JSON, []);
        $this->getLogger()->notice("Loaded!");

    }

    public function onDisable()
    {

        $this->getLogger()->info(TextFormat::GREEN . "Unloading!");

    }

    public function onJoin(PlayerJoinEvent $event)
    {

        $player = $event->getPlayer();
        $playerName = strtolower($player->getName());
        $this->unAuthedPlayers[$playerName] = $player;
        if(isset($this->users->get($playerName)["name"])) {
            $player->sendMessage("Welcome back");
        } else {
            $this->users->set($playerName, [
                "name" => $playerName,
                "password" => "IMAPASSWORD"
            ]);
            $this->users->save();
        }

    }

    public function onChatMessage(PlayerChatEvent $event)
    {

        $player = $event->getPlayer();
        $playerName = strtolower($player->getName());
        if(isset($this->unAuthedPlayers[$playerName])) {
            $event->setCancelled();
            $player->sendMessage("You must login or register before you may chat.");
        }

    }

    public function onPlayerMove(PlayerMoveEvent $event)
    {

        $player = $event->getPlayer();
        $playerName = strtolower($player->getName());
        if(isset($this->unAuthedPlayers[$playerName])) {
            $event->setCancelled();
            $player->sendTitle("You must login or register before you may chat.");
        }

    }

}
