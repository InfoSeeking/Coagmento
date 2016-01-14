<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Snippet;
use App\Models\User;

class SnippetTest extends TestCase {
	use DatabaseTransactions;

	public function setUp() {
		parent::setUp();
		$this->memberService = $this->app->make('App\Services\MembershipService');
	}

	public function testCreate() {
		$user = factory(User::class)->create();
		$this->be($user);

		$project = $this->createProject($user);
		$membership = $this->createMembership($user, $project);
		$params = [
			'url' => 'http://website.com',
			'project_id' => $project->id,
			'text' => 'Snippet text'
			];
		$response = $this->call('POST', 'api/v1/snippets', $params);
		$this->assertJSONSuccess($response);

		// Check that the snippet actually exists in the database.
		$project = Snippet::where('user_id', $user->id)->first();
		$this->assertTrue(!is_null($project));
	}

	public function testDelete() {
		$user = factory(User::class)->create();
		$this->be($user);

		$project = $this->createProject($user);
		$membership = $this->createMembership($user, $project);
		$snippet = $this->createSnippet($user, $project);
		$response = $this->call('DELETE', 'api/v1/snippets/' . $snippet->id, []);
		$this->assertJSONSuccess($response);
		$this->assertTrue(is_null(Snippet::find($snippet->id)));
	}

}