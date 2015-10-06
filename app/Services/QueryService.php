<?php
namespace App\Services;

use Auth;
use Validator;

use App\Models\Query;
use App\Utilities\Status;
use App\Services\MembershipService;

class QueryService {
	// Modify this if you want to add custom search engines.
	// url_matches can be any regex.
	// query_param is the url parameter of the search query
	protected $searchEngines = [
		[
			'host_matches' => '/^(www\.)?google\.com/',
			'path_matches' => '/^\/search/',
			'query_param' => 'q',
			'name' => 'google'
		],
		[
			'host_matches' => '/^(www\.)?bing\.com/',
			'path_matches' => '/^\/search/',
			'query_param' => 'q',
			'name' => 'bing'
		],
		[
			'host_matches' => '/^search\.yahoo\.com/',
			'path_matches' => '/^\/search/',
			'query_param' => 'p',
			'name' => 'yahoo'
		],
		[
			'host_matches' => '/^duckduckgo\.com/',
			'query_param' => 'q',
			'name' => 'duckduckgo'
		]
	];

	public function __construct(MembershipService $memberService) {
		$this->user = Auth::user();
		$this->memberService = $memberService;
	}

	/**
	 * Returns an array with relevant query parts if the URL
	 * is a search engine query, otherwise returns error status.
	 * @param {String} $url
	 * @return {Status}
	 */
	public function parseQuery($url) {
		$parts = parse_url($url);
		if (!$parts) {
			return Status::fromError('Malformed URL');
		}

		foreach($this->searchEngines as $searchEngineParams) {
			// Check that host matches.
			if (!array_key_exists('host', $parts)) continue;
			if (!preg_match($searchEngineParams['host_matches'], $parts['host'])) continue;

			// If path has constraint, also check that it matches.
			if (array_key_exists('path_matches', $searchEngineParams)) {
				if (!array_key_exists('path', $parts)) continue;
				if(!preg_match($searchEngineParams['path_matches'], $parts['path'])) continue;
			}

			// Check that the url has the right query parameter.
			if (!array_key_exists('query', $parts)) continue;
			$urlParams = [];
			parse_str($parts['query'], $urlParams);
			if (!array_key_exists($searchEngineParams['query_param'], $urlParams)) continue;

			$queryString = $urlParams[$searchEngineParams['query_param']];
			$queryString = urldecode($queryString);
			return Status::fromResult([
				'text' => $queryString,
				'search_engine' => $searchEngineParams['name'],
				'url' => $url,
				'url_query' => $parts['query'],
				'host' => $parts['host'],
				'path' => $parts['path']
				]);
		}
		return Status::fromError('Does not match any search engine URL criteria');
	}

	public function create($args) {
		$validator = Validator::make($args, [
			'text' => 'required|string',
			'search_engine' => 'required|string',
			'url' => 'sometimes|string',
			'project_id' => 'required|integer'
			]);
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}
		
		$memberStatus = $this->memberService->checkPermission($this->user->id, $args['project_id'], 'w');
		if (!$memberStatus->isOK()) {
			return $memberStatus;
		}

		$query = new Query($args);
		$query->project_id = $args['project_id'];
		$query->user_id = $this->user->id;
		$query->save();

		return Status::fromResult($query);
	}
}