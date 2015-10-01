<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function assertJSONErrors($response) {
        $this->assertTrue($response->status() != 200);
        $json = json_decode($response->content(), true);
        $this->assertTrue(array_key_exists('errors', $json));
        $hasErrors = false;
        if (count($json['errors']['input']) > 0) {
            $hasErrors = true;
        } else if (count($json['errors']['general']) > 0) {
            $hasErrors = true;
        }
        $this->assertTrue($hasErrors);
    }

    protected function assertJSONSuccess($response) {
        $this->assertTrue($response->status() == 200);
    }
}
