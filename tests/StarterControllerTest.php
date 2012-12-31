<?php

use Silex\WebTestCase;

/**
 *
 */
/**
     * @group starter
     */
class ClientControllerTest extends WebTestCase
{
    /**
     * @return mixed
     */
    public function createApplication()
    {
        // Silex
        $app = new Silex\Application();
        require __DIR__.'/../app/app.php';
        return $this->app = $app;
    }
   //Get List
    public function testGetList()
    {
        $client = $this->createClient();
        $client->request('GET', '/starter',array(),array(),array('PHP_AUTH_USER' => 'fabien', 'PHP_AUTH_PW' => 'foo'));
        
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->isSuccessful());
    }
   //create
   public  function  testCreateClient(){
     $client = $this->createClient();
     $client->request('POST','/starter',array(),array(),array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'foo'));
     $response = $client->getResponse();
     $this->assertEquals(200, $response->getStatusCode());
   $this->assertTrue($response->isSuccessful());
     }
    //Get Single
   public  function  testGetClient(){
        $client = $this->createClient(); 
        $client->request('GET', '/starter/2',array(),array(),array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'foo'));
        $response=$client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->isSuccessful());
    }
    //Update
   public  function  testUpdateClient(){
        $client = $this->createClient();
        $client->request('PUT','/starter/1',array(),array(),array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'foo'));
        $response=$client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->isSuccessful());
    }
    //Delete
    public  function  testDeleteClient(){
        $client = $this->createClient();
        $client->request('DELETE', '/starter/1',array(),array(),array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'foo'));
        $response= $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->isSuccessful());
    }
}
