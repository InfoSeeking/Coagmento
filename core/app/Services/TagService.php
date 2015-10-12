<?php
namespace App\Services;

use Auth;
use Validator;

use App\Models\Tag;
use App\Utilities\Status;
use App\Utilities\StatusCodes;

// Tags are exclusive to a project.
class TagService {
	public function __construct(MembershipService $memberService) {
		$this->user = Auth::user();
		$this->memberService = $memberService;
	}

	public function create($args) {
		$validator = Validator::make($args, [
			'project_id' => 'required|integer|exists:projects,id',
			'name' => 'required'
			]);
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}
		$tag = new Tag($args);
		$tag->creator_id = $this->user->id;
		$tag->project_id = $args['project_id'];
		$tag->save();
		return Status::OK();
	}

	public function getMultiple($args) {
		$validator = Validator::make($args, [
			'project_id' => 'required|integer|exists:projects,id',
			]);
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}
		$tags = Tag::where('project_id', $args['project_id']);
		return Status::fromResult($tags->get());
	}

	public function getMultipleOrCreate($args) {
		$validator = Validator::make($args, [
			'name_list' => 'required|array',
			'project_id' => 'required|integer|exists:projects,id',
			]);
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}
		$names = $args['name_list'];
		$projectId = $args['project_id'];
		$tagList = [];
		foreach ($names as $name) {
			$tag = Tag::where('name', $name)->where('project_id', $projectId)->first();
			if (is_null($tag)) {
				$tag = new Tag();
				$tag->name = $name;
				$tag->project_id = $projectId;
				$tag->creator_id = $this->user->id;
				$tag->save();
			}
			array_push($tagList, $tag);
		}
		return Status::fromResult($tagList);
	}

	public function delete($args) {
		$validator = Validator::make($args, [
			'id' => 'required|integer',
			]);
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}
		$tag = Tag::find($args['id']);
		if (is_null($tag)) {
			return Status::fromError('Tag not found', StatusCodes::NOT_FOUND);
		}
		$tag->delete();
		return Status::OK();
	}

	private $user;
	private $memberService;
}