<?php

namespace HittmanA\samus;

use pocketmine\Player;

use HittmanA\samus\AuthedPlayerData;

class AuthedPlayer
{

    public function __construct(Player $player, AuthedPlayerData $playerData)
    {

        $this->player = $player;
        $this->data = $playerData;

    }

}
