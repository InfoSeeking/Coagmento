<?php

use App\Models\Bookmark;
use App\Models\Membership;
use App\Models\Project;
use App\Models\Snippet;
use App\Models\User;

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

    // Some helpers for populating the database.
    // TODO: Consider using factories for data population.
    protected function createProject(User $user) {
        $project = new Project(['title' => 'Project Title', 'creator_id' => $user->id]);
        $project->save();
        return $project;
    }

    protected function createMembership(User $user, Project $project, $level='w') {
        $membership = new Membership();
        $membership->user_id = $user->id;
        $membership->project_id = $project->id;
        $membership->level = $level;
        $membership->save();
        return $membership;
    }

    protected function createBookmark(User $user, Project $project) {
        $bookmark = new Bookmark(['title' => 'Bookmark Title', 'url' => 'http://website.com']);
        $bookmark->project_id = $project->id;
        $bookmark->user_id = $user->id;
        $bookmark->save();
        return $bookmark;
    }

    protected function createSnippet(User $user, Project $project) {
        $snippet = new Snippet(['text' => 'Snippet Title', 'url' => 'http://website.com']);
        $snippet->project_id = $project->id;
        $snippet->user_id = $user->id;
        $snippet->save();
        return $snippet;
    }
}
