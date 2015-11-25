<?php
namespace App\Services;

use Auth;
use Validator;
use App\Models\Chat;
use App\Services\MembershipService;
use App\Services\RealtimeService;
use App\Utilities\Status;

class ChatService {
	public function __construct(
		MembershipService $memberService,
		RealtimeService $realtimeService) {
		$this->memberService = $memberService;
		$this->realtimeService = $realtimeService;
	}

	public function create($args) {
		$validator = Validator::make($args, [
			'message' => 'required|string',
			'project_id' => 'required|exists:projects,id'
			]);

		if ($validator->fails()) return Status::fromValidator($validator);

		$projectId = $args['project_id'];
		$user = Auth::user();

		$memberStatus = $this->memberService->checkPermission($projectId, 'w', $user);
		if (!$memberStatus->isOK()) return $memberStatus;

		$chatMessage = new Chat($args);
		$chatMessage->user_id = $user->id;
		$chatMessage->project_id = $projectId;
		$chatMessage->save();
		$chatMessage->load('user');

		$this->realtimeService
			->onProject($projectId)
			->withModel($chatMessage)
			->emit('create');

		return Status::fromResult($chatMessage);
	}

	public function getMultiple($args) {
		// TODO: implement older_than parameter.
		$validator = Validator::make($args, [
			'project_id' => 'required|exists:projects,id'
			]);
		if ($validator->fails()) return Status::fromValidator($validator);

		$projectId = $args['project_id'];
		$user = Auth::user();

		$memberStatus = $this->memberService->checkPermission($projectId, 'r', $user);
		if (!$memberStatus->isOK()) return $memberStatus;

		$messages = Chat::where('project_id', $projectId)
			->orderBy('created_at', 'DESC')
			->limit(20)
			->with('user')
			->get()
			->reverse();

		return Status::fromResult($messages);
	}
}