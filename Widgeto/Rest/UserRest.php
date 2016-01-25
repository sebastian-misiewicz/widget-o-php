<?php

namespace Widgeto\Rest;

class UserRest {

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $app->put('/rest/user/:username', function ($username) use ($app) {
            $user = json_decode($app->request->getBody(), true);

            if (!isset($username) || empty($username)) {
                $app->error();
            }
            
            if (!isset($user['newPassword']) || empty($user['newPassword'])) {
                $app->error();
            }
            if (!isset($user['oldPassword']) || empty($user['oldPassword'])) {
                $app->error();
            }
            
            $result = \dibi::query(
                'select username FROM ::user where username = %s and password = %s', 
                $username, md5($user['oldPassword']))
                    ->fetchAll();
            
            if (sizeof($result) != 1) {
                return $app->error();
            }
            
            \dibi::query(
                    'update ::user set password = %s where username = %s', 
                    md5($user['newPassword']), $username);
        });
    }
    
}
