<?php

namespace HittmanA\factionspp\provider;

use pocketmine\IPlayer;
use pocketmine\Player;

interface Provider
{

    /**
     * @param Player $player
     *
     * @return bool
     */
    public function playerIsRegistered(Player $player): bool;

    /**
     * @param Player $sender
     *
     * @param string $password
     *
     * @return bool
     */
    public function createPlayer(Player $player, string $password): bool;

    /**
     * @param Player $player
     *
     * @return bool
     */
    public function removePlayer(Player $player): bool;

    /**
     * @param Player $player
     *
     * @return array
     */
    public function getPlayer(Player $player): array;

    /**
     * @return string
     */
    public function getProvider(): string;

    /**
     * @return int
     */
    public function getNumberOfPlayers(): int;

    public function save();

}
