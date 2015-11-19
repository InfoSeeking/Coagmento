<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;

class UserTest extends TestCase {
	use DatabaseTransactions;

	public function setUp() {
		parent::setUp();
	}

	public function testCreate() {
		$testEmail = 'coagmento_test@test.test';
		$params = [
			'name' => 'Coagmento Test',
			'email' => $testEmail,
			'password' => 'test_test'
		];
		$response = $this->call('POST', 'api/v1/users', $params);
		$this->assertJSONSuccess($response);

		// Check that the bookmark actually exists in the database.
		$this->assertEquals(1, User::where('email', $testEmail)->count());

		// Expect an error because user already exists.
		$response = $this->call('POST', 'api/v1/users', $params);
		$this->assertJSONErrors($response);

		User::where('email', $testEmail)->first()->delete();

		// Expect an error because of invalid password.
		$response = $this->call('POST',
			'api/v1/users',
			['email' => $testEmail, 'password' => '123']
			);
		$this->assertJSONErrors($response);
	}

	public function testGet() {
		$user = factory(User::class)->create();
		$response = $this->call('GET', 'api/v1/users', ['email' => $user->email]);
		$this->assertJSONSuccess($response);
	}

	public function testGetCurrent() {
		$user = factory(User::class)->create();
		$response = $this->call('GET', 'api/v1/users/current');
		$this->assertJSONErrors($response);
		$this->be($user);
		$response = $this->call('GET', 'api/v1/users/current');
		$this->assertJSONSuccess($response);
	}

}