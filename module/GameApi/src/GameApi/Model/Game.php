<?php

namespace GameApi\Model;

class Game
{
    public $uuid;
    public $board;
    public $status;

    public function exchangeArray($data)
    {
        $this->uuid     = (!empty($data['uuid'])) ? $data['uuid'] : null;
        $this->board = (!empty($data['board'])) ? $data['board'] : null;
        $this->status  = (!empty($data['status'])) ? $data['status'] : null;
    }
}
