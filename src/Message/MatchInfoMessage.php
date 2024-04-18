<?php

namespace App\Message;

final class MatchInfoMessage
{
     public function __construct(
         private readonly string $league
     ) {
     }

    public function getLeague(): string
    {
        return $this->league;
    }
}
