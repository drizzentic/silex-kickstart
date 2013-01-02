<?php

use Silex\WebTestCase;

/**
 *
 */

/**
 * @group starter
 */
class MeControllerTest extends WebTestCase {

    /**
     * @return mixed
     */
    public function createApplication() {
        // Silex
        $app = new Silex\Application();
        require __DIR__ . '/../app/app.php';
        return $this->app = $app;
    }

    //Get user details
    public function testGetUserDetails() {
        $client = $this->createClient();
        $client->request('GET', '/users/me', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'foo'));
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(200, $response->getStatusCode());
        var_dump($response->getContent());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('admin',$data['username']);
    }

}
