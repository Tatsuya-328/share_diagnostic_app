<?php 
namespace App\Util;

use GuzzleHttp\Client;
use App\Models\Company;
use Google_Client;
use Google_Service_Fusiontables;

/**
 * Googleサービス全般
 */
class GoogleClientHandler
{
    public $client;
    public function __construct()
    {
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/../../config/google_service_auth.json');
        $this->client = new Google_Client();
        $this->client->useApplicationDefaultCredentials();
    }

    // fusionTable
    public function excuteFusionQuery($sql)
    {
        $this->client->addScope('https://www.googleapis.com/auth/fusiontables');
        $service = new Google_Service_Fusiontables($this->client);
        $res = $service->query->sql($sql);
        return $res;
    }

}
