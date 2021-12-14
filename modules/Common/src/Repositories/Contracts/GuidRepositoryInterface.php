<?php

namespace Common\Repositories\Contract;

interface GuildRepositoriesInterface
{
    public function generate($type, array $arrConfig);

    public function generateCode(array $arrParram);
}