<?php

namespace HittmanA\samus;

//PocketMine
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerJoinEvent;

class MainClass extends PluginBase implements Listener
{

    /** @var Config */
    protected $users;

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
        if($this->users->get($player->getName()) !== null) {
            $player->sendMessage("Welcome back");
        } else {
            $this->users->set(strtolower($player->getName()), [
                "name" => strtolower($player->getName()),
                "password" => "IMAPASSWORD"
            ]);
            $this->users->save();
        }
    }

}
