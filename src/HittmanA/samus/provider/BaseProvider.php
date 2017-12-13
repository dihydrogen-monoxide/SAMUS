<?php

namespace HittmanA\samus\provider;

use HittmanA\samus\MainClass;

abstract class BaseProvider implements Provider
{

    /** @var MainClass */
    protected $plugin;

    public function __construct(MainClass $plugin)
    {
        $this->plugin = $plugin;

        $this->initialize();
    }

    /**
     * @return bool
     */
    public function initialize(): bool
    {
        return false;
    }

    /**
     * @return MainClass
     */
    public function getPlugin(): MainClass
    {
        return $this->plugin;
    }
}
