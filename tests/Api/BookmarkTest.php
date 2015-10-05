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

	public function setUp() {
		parent::setUp();
		$this->user = factory(User::class)->create();
	}

	public function testCreate() {
		$project = $this->createProject();
		$membership = $this->createMembership($project);

		$params = [
			'title' => 'Web page',
			'url' => 'http://website.com',
			'project_id' => $project->id
		];
		$response = $this->actingAs($this->user)->call('POST', 'api/v1/bookmarks', $params);
		$this->assertJSONSuccess($response);

		// Check that the bookmark actually exists in the database.
		$this->assertEquals(1, Bookmark::where('user_id', $this->user->id)->count());

		// Expect an error because malformed url.
		$response = $this->actingAs($this->user)->call('POST',
			'api/v1/bookmarks',
			array_merge($params, ['url' => 'malformed.url'])
			);
		$this->assertJSONErrors($response);

		$membership->delete();
		// Expect an error because user is not a member of the project.
		$response = $this->actingAs($this->user)->call('POST', 'api/v1/bookmarks', $params);
		$this->assertJSONErrors($response);

		$membership->level = 'r';
		$membership->save();

		// Still expect an error because the user does not have write permissions.
		$response = $this->actingAs($this->user)->call('POST', 'api/v1/bookmarks', $params);
		$this->assertJSONErrors($response);

		// Return membership to write permission.
		$membership->level = 'w';
		$membership->save();
	}

	public function testUpdate() {
		$project = $this->createProject();
		$membership = $this->createMembership($project);
		$bookmark = $this->createBookmark($project);
		$params = ['title' => 'Updated Title'];
		$response = $this->actingAs($this->user)->call('PUT', '/api/v1/bookmarks/' . $bookmark->id, $params);
		$this->assertJSONSuccess($response);

		// Eloquent will not auto update, so we need to refetch.
		$bookmark = Bookmark::find($bookmark->id);
		$this->assertEquals($bookmark->title, 'Updated Title');
	}

	public function testDelete() {
		$project = $this->createProject();
		$membership = $this->createMembership($project);
		$bookmark = $this->createBookmark($project);
		$response = $this->actingAs($this->user)->call('DELETE', '/api/v1/bookmarks/' . $bookmark->id);
		$this->assertJSONSuccess($response);
		$this->assertNull(Bookmark::find($bookmark->id));
	}

	public function testMove() {
		$projectA = $this->createProject();
		$membershipA = $this->createMembership($projectA);
		$bookmark = $this->createBookmark($projectA);

		$projectB = $this->createProject();
		$membershipB = $this->createMembership($projectB);

		$response = $this->actingAs($this->user)->call(
			'PUT',
			'/api/v1/bookmarks/' . $bookmark->id . '/move',
			['project_id' => $projectB->id]);

		$this->assertJSONSuccess($response);
		$bookmark = Bookmark::find($bookmark->id);
		$this->assertEquals($bookmark->project_id, $projectB->id);

		$membershipA->level = 'r';
		$membershipA->save();

		$response = $this->actingAs($this->user)->call(
			'PUT',
			'/api/v1/bookmarks/' . $bookmark->id . '/move',
			['project_id' => $projectA->id]);
		// Cannot move without write permission.
		$this->assertJSONErrors($response);
	}
}