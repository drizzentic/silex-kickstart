<?php

namespace Provider\Controllers\Starter;

use Silex\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class StarterController implements ControllerProviderInterface {

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app) {
        $controllers = $app['controllers'];

        //Get List
        $controllers->get('/starter', function()use ($app) {
                    return $app->json('ok');
                });

        //Create
        $controllers->post('/starter', function(Request $request)use ($app) {

                    return $app->json('ok');
                });


        //Get Single
        $controllers->get('/starter/{id}', function($id)use ($app) {

                    return $app->json('ok');
                });


        //Update 
        $controllers->put('/starter/{id}', function(Request $request, $id)use ($app) {

                    return $app->json('ok');
                });


        //Delete
        $controllers->delete('/starter/{id}', function($id)use ($app) {


                    return $app->json('delete client');
                });
        return $controllers;
    }

}
