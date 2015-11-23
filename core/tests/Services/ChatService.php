<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;

class ChatTest extends TestCase {
	use DatabaseTransactions;

	public function setUp() {
		parent::setUp();
		$this->user = factory(User::class)->create();
		$this->be($this->user);
		$this->memberService = $this->app->make('App\Services\MembershipService');
		$this->chatService = $this->app->make('App\Services\ChatService');
	}

	public function testCreate() {
		$project = $this->createProject();
		$membership = $this->createMembership($project);
		$params = [
			'project_id' => $project->id,
			'message' => 'Test chat message'
			];
		$chatStatus = $this->chatService->create($params);
		$this->assertTrue($chatStatus->isOK());
	}
}