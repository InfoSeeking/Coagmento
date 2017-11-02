<?php

namespace App\Services;

use App\Models\Stage;
use App\Models\StageProgress;
use Auth;
use Validator;
use App\Models\Snippet;
use App\Utilities\Status;
use App\Utilities\StatusCodes;

class StageProgressService {
	public function __construct(
		MembershipService $memberService,
		RealtimeService $realtimeService){
		$this->user = Auth::user();
		$this->memberService = $memberService;
		$this->realtimeService = $realtimeService;
	}

	public function getCurrentStage() {
        $stageProgress = StageProgress::all()->where('user_id', $this->user->id)->last();
		if (is_null($stageProgress)) {
            $first_stage_id = Status::fromResult(Stage::all()->first())->getResult()->id;
            StageProgress::create([
                'user_id' => $this->user->id,
                'stage_id' => $first_stage_id
            ])->save();

			return Status::fromResult(Stage::all()->first());
//                StageProgress::fromError('No stage progress found.', StatusCodes::NOT_FOUND);
		}else{
            $stage = Stage::all()->where('id', Status::fromResult($stageProgress)->getResult()->stage_id)->first();
            return Status::fromResult($stage);
        }

	}

	public function moveToNextStage(){
        $stageProgress = StageProgress::all()->where('user_id', $this->user->id)->last();

        if (is_null($stageProgress)) {

            $first_stage_id = Status::fromResult(Stage::all()->first())->getResult()->id;

            StageProgress::create([
                'user_id' => $this->user->id,
                'stage_id' => $first_stage_id
            ])->save();
            return $this->getCurrentStage();

        }else{
            $current_stage_id = Status::fromResult($stageProgress)->getResult()->stage_id;
            $next_stage_result = Stage::where('id',">",$current_stage_id )->first();
//            dd(is_null($next_stage_result) );
            if (is_null($next_stage_result)){
                return Status::fromError('No more stages found.', StatusCodes::NOT_FOUND);
            }else{
                StageProgress::create([
                    'user_id' => $this->user->id,
                    'stage_id' => $next_stage_result->id
                ])->save();
                return $this->getCurrentStage();
            }

        }
    }

//	public function getStageView($id){
//
//    }


//  TODO: Old code from SnippetService
//	public function getMultiple($args, $countOnly=false){
//		$validator = Validator::make($args, [
//			'project_id' => 'sometimes|exists:projects,id'
//			]);
//		if ($validator->fails()) {
//			return Status::fromValidator($validator);
//		}
//
//		if (array_key_exists('project_id', $args)) {
//			$memberStatus = $this->memberService->checkPermission(
//				$args['project_id'], 'r', $this->user);
//			if (!$memberStatus->isOK()) return Status::fromStatus($memberStatus);
//
//			$snippets = Snippet::with('thumbnail')->where('project_id', $args['project_id']);
//			if ($countOnly) return Status::fromResult($snippets->count());
//			return Status::fromResult($snippets->get());
//		}
//
//		// Return all user created snippets.
//		if (!$this->user) return Status::fromError('Log in to see snippets or specify a project_id');
//		$snippets = Snippet::with('thumbnail')->where('user_id', $this->user->id);
//		if ($countOnly) return Status::fromResult($snippets->count());
//		return Status::fromResult($snippets->get());
//	}
//
//	public function create($args) {
//		$validator = Validator::make($args, [
//			'text' => 'required|string',
//			'url' => 'required|string|url',
//			'title' => 'sometimes|string',
//			'project_id' => 'required|integer|exists:projects,id'
//			]);
//		if ($validator->fails()) {
//			return Status::fromValidator($validator);
//		}
//
//		$memberStatus = $this->memberService->checkPermission($args['project_id'], 'r', $this->user);
//		if (!$memberStatus->isOK()) return $memberStatus;
//
//		$snippet = new Snippet($args);
//		$snippet->user_id = $this->user->id;
//		$snippet->title = array_key_exists('title', $args) ? $args['title'] : 'Untitled';
//		$snippet->project_id = $args['project_id'];
//		$snippet->load('thumbnail');
//		$snippet->save();
//
//		$this->realtimeService->withModel($snippet)
//			->onProject($snippet->project_id)
//			->emit('create');
//
//		return Status::fromResult($snippet);
//	}
//
//	public function delete($id) {
//		$snippet = Snippet::find($id);
//		if (is_null($snippet)) {
//			return Status::fromError('Snippet not found', StatusCodes::NOT_FOUND);
//		}
//		$memberStatus = $this->memberService->checkPermission($snippet->project_id, 'w', $this->user);
//		if (!$memberStatus->isOK()) return $memberStatus;
//
//		$this->realtimeService->withModel($snippet)
//			->onProject($snippet->project_id)
//			->emit('delete');
//		$snippet->delete();
//		return Status::OK();
//	}
//
//	public function update($args) {
//		$validator = Validator::make($args, [
//			'text' => 'sometimes|string',
//			'url' => 'sometimes|string|url',
//			'id' => 'required|integer'
//			]);
//		$snippet = Snippet::find($args['id']);
//		if (is_null($snippet)) {
//			return Status::fromError('Snippet not found', StatusCodes::NOT_FOUND);
//		}
//		$memberStatus = $this->memberService->checkPermission($snippet->project_id, 'w', $this->user);
//		if (!$memberStatus->isOK()) return $memberStatus;
//
//		$snippet->update($args);
//		$this->realtimeService->withModel($snippet)
//			->onProject($snippet->project_id)
//			->emit('update');
//		return Status::fromResult($snippet);
//	}
}