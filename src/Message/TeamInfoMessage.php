<?php

namespace App\Message;

final class TeamInfoMessage
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
