<?php

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'GameApi\Controller\Index',
                    ),
                ),
            ),
            'games' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/games[/:id]',
                    'constraints' => array(
                        'id'     => '[a-zA-Z0-9-_\./\+]*',
                    ),
                    'defaults' => array(
                        'controller' => 'GameApi\Controller\Game',
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'GameApi\Controller\Index' => 'GameApi\Controller\IndexController',
            'GameApi\Controller\Game' => 'GameApi\Controller\GameController',
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
