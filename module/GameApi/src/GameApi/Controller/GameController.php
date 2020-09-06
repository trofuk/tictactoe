<?php
namespace GameApi\Controller;

use GameApi\Controller\AbstractRestfulController;
use GameApi\Model\Game;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;



class GameController extends AbstractRestfulJsonController
{

    protected $gameTable;

    public $victory_conditions = array(
        array('A1', 'B1', 'C1'),
        array('A2', 'B2', 'C2'),
        array('A3', 'B3', 'C3'),
        array('A1', 'A2', 'A3'),
        array('B1', 'B2', 'B3'),
        array('C1', 'C2', 'C3'),
        array('A1', 'B2', 'C3'),
        array('A3', 'B2', 'C1')
    );

    public function getList()
    {
        $data = [];
        $results = $this->getGameTable()->fetchAll();

        foreach ($results as $result) {
            $data[] = $result;
        }
        return new JsonModel(
            array('data' => $data )
        );
    }

    public function get($uuid)
    {   // Action used for GET requests with resource Id
        return new JsonModel(array("game" => $this->getGameTable()->getGame($uuid)));
    }

    public function create($data)
    {   // Action used for POST requests
        $body = $this->request->getContent();
        $data = Json::decode($body);
        $game = new Game();
        $game->uuid = uniqid();
        $board = $data->board;
        $game->board =  $this->nextMove($board);
        $game->status = 'RUNNING';

        $this->getGameTable()->saveGame($game);

        return new JsonModel(array('game' => $game ));
    }

    public function update($uuid, $data)
    {   // Action used for PUT requests
        $body = $this->request->getContent();
        $data = Json::decode($body);
        $game = new Game();
        $game->uuid = $data->uuid;
        $game->board = $data->board;
        $game->status = $data->status;
        $board = $data->board;
        $status = $this->checkGameStatus($board);
        if ($status == 'RUNNING') {
            $game->board =  $this->nextMove($board);
            $status = $this->checkGameStatus($game->board);
        }
        $game->status = $status;
        $this->getGameTable()->saveGame($game);
        return new JsonModel(array('game' => $game ));



    }

    public function delete($uuid)
    {   // Action used for DELETE requests
        $this->getGameTable()->deleteGame($uuid);
        return new JsonModel(array('message' => 'Game successfully deleted' ));

    }

    public function getGameTable()
    {
        if (!$this->gameTable) {
            $sm = $this->getServiceLocator();
            $this->gameTable = $sm->get('GameApi\Model\GameTable');
        }
        return $this->gameTable;
    }

    public function checkGameStatus($board)
    {

        $board_arr = str_split($board);
        $cond_arr = ['A1', 'B1', 'C1', 'A2', 'B2', 'C2', 'A3', 'B3', 'C3'];
        $cond_arr_x = [];
        $cond_arr_o = [];
        $cond_arr_empty = [];

        for ($i = 0; $i < count($board_arr); $i++) {
            if ($board_arr[$i] == 'X') {
                $cond_arr_x[] = $cond_arr[$i];
            } else if ($board_arr[$i] == '0') {
                $cond_arr_o[] = $cond_arr[$i];

            } else {
                $cond_arr_empty[] = $cond_arr[$i];
            }

        }

        foreach($this->victory_conditions as $vc) {
            if ($vc == array_intersect($vc, $cond_arr_x)) {
                $status = 'X_WON';
                break;
            } else if ($vc == array_intersect($vc, $cond_arr_o)) {
                $status = 'O_WON';
                break;
            }
        }

        if (!isset($status)) {
            if (empty($cond_arr_empty)) {
                $status = 'DRAW';
            } else {
                $status = 'RUNNING';
            }
        }

        return $status;
    }

    public function nextMove($board){
        $pos = strpos($board, '-');
        if ($pos !== false) {
            $board = substr_replace($board, '0', $pos, strlen('-'));
        }
        return $board;
    }

}