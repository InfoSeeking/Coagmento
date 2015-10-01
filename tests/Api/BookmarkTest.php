<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Bookmark;
use App\Models\Membership;
use App\Models\Project;
use App\Models\User;

class BookmarkTest extends TestCase {
	// Using DatabaseTransactions will undo all database modifications
	// after each test case is run.
	use DatabaseTransactions;
	public function testCreate() {
		$user = factory(User::class)->create();
		$project = new Project(['title' => 'Project Title', 'creator_id' => $user->id]);
		$project->save();
		$params = [
			'title' => 'Web page',
			'url' => 'http://website.com',
			'project_id' => $project->id
		];

		$membership = new Membership();
		$membership->user_id = $user->id;
		$membership->project_id = $project->id;
		$membership->level = 'w';
		$membership->save();

		$response = $this->actingAs($user)->call('POST', 'api/v1/bookmarks', $params);
		$this->assertJSONSuccess($response);

		// Check that the bookmark actually exists in the database.
		$this->assertEquals(1, Bookmark::where('user_id', $user->id)->count());

		// Expect an error because malformed url.
		$response = $this->actingAs($user)->call('POST',
			'api/v1/bookmarks',
			array_merge($params, ['url' => 'malformed.url'])
			);
		$this->assertJSONErrors($response);

		$membership->delete();
		// Expect an error because user is not a member of the project.
		$response = $this->actingAs($user)->call('POST', 'api/v1/bookmarks', $params);
		$this->assertJSONErrors($response);

		$membership->level = 'r';
		$membership->save();

		// Still expect an error because the user does not have write permissions.
		$response = $this->actingAs($user)->call('POST', 'api/v1/bookmarks', $params);
		$this->assertJSONErrors($response);

	}
}