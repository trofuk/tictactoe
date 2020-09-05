<?php
namespace GameApi\Controller;

use GameApi\Controller\AbstractRestfulController;
use GameApi\Model\Game;
use Zend\View\Model\JsonModel;




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
        return new JsonModel(array("data" => $this->getGameTable()->getGame($uuid)));
    }

    public function create($data)
    {   // Action used for POST requests
        $game = new Game();
        $game->uuid = uniqid();
        $game->board = '---------';
        $game->status = 'RUNNING';

        $this->getGameTable()->saveGame($game);

        $currentUrl = $this->getRequest()->getUriString();
        return new JsonModel(array('url' => $currentUrl . '/' . $game->uuid ));
    }

    public function update($uuid, $data)
    {   // Action used for PUT requests

        $board = $data['board'];
        $status = $this->checkGameStatus($board);


        return new JsonModel(array('uuid' => $uuid, 'status' => $status ));



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
}