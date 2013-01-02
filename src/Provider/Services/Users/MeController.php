<?php

namespace Provider\Services\Users;

use Silex\ControllerProviderInterface;
use Silex\Application;
class MeController implements ControllerProviderInterface {

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app) {
        $controllers = $app['controllers'];
        //Get Current user details
        $controllers->get('/users/me', function()use ($app) {
                    $token = $app['security']->getToken();
                    if (null !== $token) {
                        $user = $token->getUser();
                        $userdata = array(
                            'username' => $user->getUsername(),
                            'roles' => implode(',', $user->getRoles())
                        );
                    
                    }
                   
                    return $app->json($userdata);
                });
        return $controllers;
    }

}
