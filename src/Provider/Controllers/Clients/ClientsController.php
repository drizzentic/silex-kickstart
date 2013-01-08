<?php

// Hi, This file was generated using class_gen Silex Version  
// Written by Moses M Thiga & Emmanuel Nyachoke 
// thigamoses@gmail.com, achachiez@gmail.com

namespace Provider\Controllers\Clients;

use Silex\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class ClientsController implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controllers = $app['controllers'];
        //Get all records from table clients  
        $controllers->get('/clients', function()use ($app) {
                    $sql = " SELECT * FROM users ";
                    $clients = $app['db']->fetchAll($sql);
                    return $app->json($clients);
                });
        //Insert a record into the table clients  
        $controllers->post('/clients', function(Request $request)use ($app) {
                    $clients = array(
                        'first_name' => $request->request->get('first_name'),
                        'last_name' => $request->request->get('last_name'),
                        'phone' => $request->request->get('phone'),
                    );
                    $app['db']->beginTransaction();
                    try {
                        $app['db']->insert('clients', $clients);
                        $app['db']->commit();
                    } catch (Exception $e) {
                        $app['db']->rollback();
                        return $app->json('Internal error', 500);
                    }
                    return $app->json($clients);
                });
        //Get a single record from the table clients  
        $controllers->get('/clients/{id}', function($id)use ($app) {
                    $clients = $app['db']->fetchAssoc('SELECT * FROM clients WHERE id = ?', array($id));
                    if (empty($clients)) {
                        return $app->json('not found', 404);
                    }
                    return $app->json($clients);
                });
        //Update a record in the table clients  
        $controllers->put('/clients/{id}', function(Request $request, $id)use ($app) {
                    $clients = array(
                        'first_name' => $request->request->get('first_name'),
                        'last_name' => $request->request->get('last_name'),
                        'phone' => $request->request->get('phone'),
                    );
                    $rows = $app['db']->update('clients', $clients, array('id' => $id));
                    if ($rows == 0) {
                        return $app->json('no change', 304);
                    }
                    return $app->json($clients);
                });
        $controllers->delete('/clients/{id}', function($id)use ($app) {
                    $rows = $app['db']->delete(clients, array('id' => $id));
                    if ($rows == 0) {
                        return $app->json('no change', 304);
                    }
                    return $app->json('Record Deleted');
                });
        return $controllers;
    }

    protected function _usernameExists($app, $username) {
        $user = $app["db"]->fetchAssoc("SELECT * FROM users WHERE username = ?", array($username));
        $app["logger"]->info("_usernameExists : " . json_encode($user));
        return $user;
    }

    protected function _emailExists($app, $email) {
        $user = $app["db"]->fetchAssoc("SELECT * FROM users WHERE email = ? ", array($email));
        $app["logger"]->info("_emailExists : " . json_encode($user));
        return $user;
    }

}

?>