<?php

namespace GameApi\Model;

use Zend\Db\TableGateway\TableGateway;

class GameTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getGame($uuid)
    {
        $rowset = $this->tableGateway->select(array('uuid' => $uuid));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $uuid");
        }
        return $row;
    }

    public function saveGame(Game $game)
    {
        $data = array(
            'uuid' => $game->uuid,
            'board' => $game->board,
            'status'  => $game->status,
        );

        $uuid = $game->uuid;
        $result = $this->tableGateway->insert($data);
        if (!$result) {
            throw new \Exception('Game with uuid already exist');
        }

    }

    public function deleteGame($uuid)
    {
        $this->tableGateway->delete(array('uuid' => $uuid));
    }
}