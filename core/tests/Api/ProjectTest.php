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
		$this->memberService = $this->app->make('App\Services\MembershipService');
	}

	public function testCreate() {
		$user = factory(User::class)->create();
		$this->be($user);
		$params = [
			'title' => 'Test Project'
		];
		$response = $this->call('POST', 'api/v1/projects', $params);
		$this->assertJSONSuccess($response);

		// Check that the project actually exists in the database.
		$project = Project::where('creator_id', $user->id)->first();
		$this->assertTrue(!is_null($project));

		// Check that user has owner membership.
		$memberStatus = $this->memberService->checkPermission($project->id, 'o', $user);
		$this->assertTrue($memberStatus->isOK());

		// Expect an error because missing title.
		$response = $this->actingAs($user)->call('POST', 'api/v1/projects', []);
		$this->assertJSONErrors($response);
	}

	public function testDelete() {
		$user = factory(User::class)->create();
		$this->be($user);
		$project = $this->createProject($user);
		$membership = $this->createMembership($user, $project, 'o');
		$response = $this->call('DELETE', 'api/v1/projects/' . $project->id, []);
		$this->assertJSONSuccess($response);
		$this->assertTrue(is_null(Project::find($project->id)));
	}

	public function testPrivate() {
		$user = factory(User::class)->create();
		
		$project = $this->createProject($user);
		$response = $this->call('GET', 'api/v1/projects/'. $project->id, []);
		// Project is public by default, so reading should be permissible without
		// logging in.
		$this->assertJSONSuccess($response);

		$project->private = true;
		$project->save();

		$response = $this->call('GET', 'api/v1/projects/'. $project->id, []);
		$this->assertJSONErrors($response);

		$project->private = false;
		$project->save();

		// The same behavior is expected when the user is logged in, and not a member.
		$this->be($user);
		$response = $this->call('GET', 'api/v1/projects/'. $project->id, []);
		$this->assertJSONSuccess($response);

		$project->private = true;
		$project->save();

		$response = $this->call('GET', 'api/v1/projects/'. $project->id, []);
		$this->assertJSONErrors($response);
	}

	public function testSharing() {
		$userA = factory(User::class)->create();
		$userA->name = 'A';
		$userA->save();

		$project = $this->createProject($userA);
		$project->private = true;
		$project->save();
		$membershipA = $this->createMembership($userA, $project, 'o');

		// Another user should not be able to read.
		$userB = factory(User::class)->create();
		$userB->name = 'B';
		$userB->save();
		$response = $this->actingAs($userB)->call('GET', 'api/v1/projects/' . $project->id, []);
		$this->assertJSONErrors($response);

		$response = $this->actingAs($userA)->call('POST', 'api/v1/projects/' . $project->id . '/share', [
			'permission' => 'r',
			'user_id' => $userB->id
			]);
		$this->assertJSONSuccess($response);

		// Now that it is shared, userB should be able to read.
		$response = $this->actingAs($userB)->call('GET', 'api/v1/projects/' . $project->id, []);
		$this->assertJSONSuccess($response);

		// Unsharing with userB should revert this access.
	}

	public function testUser() {
		// $userA = factory(User::class)->create();
		// $userA->name = "A";
		// $userA->save();

		// $response = $this->actingAs($userA)->call('GET', 'api/v1/users/current', []);
		// echo $response->content();

		// $userB = factory(User::class)->create();
		// $userB->name = "B";
		// $userB->save();

		// $response = $this->actingAs($userB)->call('GET', 'api/v1/users/current', []);
		// echo $response->content();

		// $response = $this->actingAs($userA)->call('GET', 'api/v1/users/current', []);
		// echo $response->content();
		
	}
}