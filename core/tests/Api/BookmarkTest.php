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
	}

	public function testCreate() {
		$user = factory(User::class)->create();
		$this->be($user);

		$project = $this->createProject($user);
		$membership = $this->createMembership($user, $project);

		$params = [
			'title' => 'Web page',
			'url' => 'http://website.com',
			'project_id' => $project->id
		];
		$response = $this->call('POST', 'api/v1/bookmarks', $params);
		$this->assertJSONSuccess($response);

		// Check that the bookmark actually exists in the database.
		$this->assertEquals(1, Bookmark::where('user_id', $user->id)->count());

		// Expect an error because malformed url.
		$response = $this->call('POST',
			'api/v1/bookmarks',
			array_merge($params, ['url' => 'malformed.url'])
			);
		$this->assertJSONErrors($response);

		$membership->delete();
		// Expect an error because user is not a member of the project.
		$response = $this->call('POST', 'api/v1/bookmarks', $params);
		$this->assertJSONErrors($response);

		$membership->level = 'r';
		$membership->save();

		// Still expect an error because the user does not have write permissions.
		$response = $this->call('POST', 'api/v1/bookmarks', $params);
		$this->assertJSONErrors($response);

		// Return membership to write permission.
		$membership->level = 'w';
		$membership->save();
	}

	public function testUpdate() {
		$user = factory(User::class)->create();
		$this->be($user);

		$project = $this->createProject($user);
		$membership = $this->createMembership($user, $project);
		$bookmark = $this->createBookmark($user, $project);
		$params = ['title' => 'Updated Title'];
		$response = $this->call('PUT', '/api/v1/bookmarks/' . $bookmark->id, $params);
		$this->assertJSONSuccess($response);

		// Eloquent will not auto update, so we need to refetch.
		$bookmark = Bookmark::find($bookmark->id);
		$this->assertEquals($bookmark->title, 'Updated Title');
	}

	public function testDelete() {
		$user = factory(User::class)->create();
		$this->be($user);

		$project = $this->createProject($user);
		$membership = $this->createMembership($user, $project);
		$bookmark = $this->createBookmark($user, $project);
		$response = $this->call('DELETE', '/api/v1/bookmarks/' . $bookmark->id);
		$this->assertJSONSuccess($response);
		$this->assertNull(Bookmark::find($bookmark->id));
	}

	public function testMove() {
		$user = factory(User::class)->create();
		$this->be($user);
		
		$projectA = $this->createProject($user);
		$membershipA = $this->createMembership($user, $projectA);
		$bookmark = $this->createBookmark($user, $projectA);

		$projectB = $this->createProject($user);
		$membershipB = $this->createMembership($user, $projectB);

		$response = $this->call(
			'PUT',
			'/api/v1/bookmarks/' . $bookmark->id . '/move',
			['project_id' => $projectB->id]);

		$this->assertJSONSuccess($response);
		$bookmark = Bookmark::find($bookmark->id);
		$this->assertEquals($bookmark->project_id, $projectB->id);

		$membershipA->level = 'r';
		$membershipA->save();

		$response = $this->call(
			'PUT',
			'/api/v1/bookmarks/' . $bookmark->id . '/move',
			['project_id' => $projectA->id]);
		// Cannot move without write permission.
		$this->assertJSONErrors($response);
	}
}