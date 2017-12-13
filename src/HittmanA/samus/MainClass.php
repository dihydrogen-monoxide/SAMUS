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

    $users = new Config($this->getDataFolder() . "players.json", Config::JSON, []);

    public function onEnable()
    {
        //Make the faction config
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new Events($this), $this);

        $this->getLogger()->notice("Loaded!");
    }

    public function onDisable()
    {
        $this->getLogger()->info(TextFormat::GREEN . "Unloading!");
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        if(isset($users->get($player->getName()))) {
            $player->sendMessage("Welcome back");
        } else {
            $users->set(strtolower($player->getName()), [
                "name" => strtolower($player->getName()),
                "password" => "IMAPASSWORD"
            ]);
            $users->save();
        }
    }

}
