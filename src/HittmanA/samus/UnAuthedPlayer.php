<?php

namespace HittmanA\samus;

use pocketmine\Player;

class UnAuthedPlayer
{

    public function __construct(Player $player)
    {

        $this->player = $player;

    }

}
