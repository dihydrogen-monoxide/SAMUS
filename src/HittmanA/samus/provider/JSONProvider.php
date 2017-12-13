<?php

namespace HittmanA\samus\provider;

use HittmanA\samus\MainClass;
use pocketmine\IPlayer;
use pocketmine\Player;
use pocketmine\utils\Config;

class JSONProvider extends BaseProvider implements Provider
{

    /** @var Config */
    protected $users;

    protected $passwordOptions = [
        'cost' => 10,
    ];

    public function __construct(MainClass $plugin, int $passwordSecurity)
    {
        parent::__construct($plugin);
        if($passwordSecurity < 1)
        {
            $this->passwordOptions['cost'] = 1;
        } else {
            $this->passwordOptions['cost'] = $passwordSecurity;
        }
        $this->plugin = $plugin;
        $this->users = new Config($this->plugin->getDataFolder() . "players.json", Config::JSON, []);
    }

    public function getProvider(): string
    {
        return "json";
    }

    public function getNumberOfPlayers(): int
    {
        return count($this->users->getAll());
    }

    public function createPlayer(Player $sender, string $password): bool
    {
        //And make a new player profile in the player config.
        $this->users->set(strtolower($sender->getName()), [
            "username" => strtolower($sender->getName()),
            "password" => password_hash($password, PASSWORD_BCRYPT, $options)//,
            //"role" => Member::MEMBER_LEADER
        ]);
        $this->save();

        return true;
    }

    public function save()
    {
        $this->users->save();
    }

    public function removePlayer(Player $player): bool
    {
        $this->users->remove($player);
        //Save the faction config.
        $this->save();

        return true;
    }

    public function getPlayer(Player $player): array
    {
        $playerName = strtolower($player->getName());
        if($this->users->get($playerName) == false)
        {
            return array();
        }
        else
        {
            return $this->users->get($playerName);
        }
    }

    public function playerIsRegistered(Player $player): bool
    {
        if(isset($this->getPlayer($player)["username"]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}
