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
        $rowset = $this->tableGateway->select(array('uuid' => $uuid));
        $row = $rowset->current();
        if (!$row) {
            $result = $this->tableGateway->insert($data);
        } else {
            $result = $this->tableGateway->update($data, array('uuid' => $uuid));
        }
        if (!$result) {
            throw new \Exception('Bad request');
        }

    }

    public function deleteGame($uuid)
    {
        $this->tableGateway->delete(array('uuid' => $uuid));
    }
}