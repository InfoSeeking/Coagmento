<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Project;
use App\Models\User;

class ProjectTest extends TestCase {
	use DatabaseTransactions;

	public function setUp() {
		parent::setUp();
		$this->user = factory(User::class)->create();
		$this->memberService = $this->app->make('App\Services\MembershipService');
		$this->be($this->user);
	}

	public function testCreate() {
		$params = [
			'title' => 'Test Project'
		];
		$response = $this->call('POST', 'api/v1/projects', $params);
		$this->assertJSONSuccess($response);

		// Check that the project actually exists in the database.
		$project = Project::where('creator_id', $this->user->id)->first();
		$this->assertTrue(!is_null($project));

		// Check that user has owner membership.
		$memberStatus = $this->memberService->checkPermission($this->user->id, $project->id, 'o');
		$this->assertTrue($memberStatus->isOK());

		// Expect an error because missing title.
		$response = $this->actingAs($this->user)->call('POST', 'api/v1/projects', []);
		$this->assertJSONErrors($response);
	}

	public function testDelete() {
		$project = $this->createProject();
		$membership = $this->createMembership($project, 'o');
		$response = $this->call('DELETE', 'api/v1/projects/' . $project->id, []);
		$this->assertJSONSuccess($response);
		$this->assertTrue(is_null(Project::find($project->id)));
	}

}