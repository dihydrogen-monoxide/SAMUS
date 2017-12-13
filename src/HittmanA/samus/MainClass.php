<?php

namespace HittmanA\samus;

//Player stuff
use HittmanA\samus\UnAuthedPlayer;
use HittmanA\samus\AuthedPlayer;
use HittmanA\samus\AuthedPlayerData;

//Providers
use HittmanA\samus\provider\BaseProvider;
use HittmanA\samus\provider\JSONProvider;

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

    /** @var BaseProvider */
    private $provider = null;

    public function onEnable()
    {

        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        //$this->getServer()->getPluginManager()->registerEvents(new Events($this), $this);
        $this->users = new Config($this->getDataFolder() . "players.json", Config::JSON, []);
        $this->getLogger()->notice("Loaded!");

        switch(strtolower($this->getConfig()->get("provider")))
        {
            case "json":
                $this->provider = new JSONProvider($this, $this->getConfig()->get("security"));
                break;
            default:
                $this->getLogger()->error("Invalid database was given. Selecting JSON data provider as default.");
                $this->provider = new JSONProvider($this, $this->getConfig()->get("security"));
                break;
        }

        $this->getLogger()->notice("Database provider set to " . TextFormat::YELLOW . $this->provider->getProvider());
        if($this->provider->getNumberOfPlayers() == 1)
        {
            $this->getLogger()->notice($this->provider->getNumberOfPlayers() . " player profile has been loaded.");
        }
        else
        {
            $this->getLogger()->notice($this->provider->getNumberOfPlayers() . " player profiles have been loaded.");
        }

    }

    public function onDisable()
    {

        $this->getLogger()->info(TextFormat::GREEN . "Unloading!");

    }

    public function onJoin(PlayerJoinEvent $event)
    {

        $player = $event->getPlayer();
        $playerName = $player->getName();
        $this->unAuthedPlayers[$playerName] = $player;
        if($this->provider->playerIsRegistered($player)) {
            $player->addTitle("Welcome Back!", "Please login by typing your password directly into chat (No one will see it).");
        } else {
            $player->addTitle("Welcome!", "Please register by typing your desired password directly into chat (No one will see it).");
        }

    }

    public function onChatMessage(PlayerChatEvent $event)
    {

        $player = $event->getPlayer();
        $playerName = strtolower($player->getName());
        if(isset($this->unAuthedPlayers[$playerName])) {
            $password = $event->getMessage();
            $this->provider->createPlayer($player, $password);
            $player->addTitle("Success!", "You have been successfully registered.");
            if (($key = array_search($playerName, $this->unAuthedPlayers)) !== false) {
                unset($unAuthedPlayers[$key]);
            }
            $event->setCancelled();
        }

    }

    public function onPlayerMove(PlayerMoveEvent $event)
    {

        $player = $event->getPlayer();
        $playerName = $player->getName();
        if(isset($this->unAuthedPlayers[$playerName])) {
            $event->setCancelled();
            $player->addTitle("Unauthorized!", "You must login or register.");
        }

    }

}
