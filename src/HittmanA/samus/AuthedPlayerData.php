<?php

namespace HittmanA\samus;

use pocketmine\Player;

class UnAuthedPlayerData
{

    public function __construct(Player $player, AuthedPlayerData $playerData)
    {

        $this->player = $player;
        $this->data = $playerData;

    }

}
