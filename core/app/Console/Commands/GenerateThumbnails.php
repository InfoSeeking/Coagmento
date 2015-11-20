<?php

namespace App\Console\Commands;

use App\Models\Bookmark;
use App\Models\Page;
use App\Models\Thumbnail;
use Illuminate\Console\Command;

class GenerateThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a batch of thumbnails for bookmarks and pages.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->active = !!env('THUMBNAIL_SERVER');
        $this->url = env('THUMBNAIL_SERVER');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->active) {
            $this->comment('The thumbnail server is not set.');
            $this->comment('Set the THUMBNAIL_SERVER variable in .env');
            return;
        }

        // Select the oldest bookmarks/pages, at a maximum of 50.
        $bookmarks = Bookmark::where('thumbnail_id', 0)
            ->orderBy('created_at', 'asc')
            ->select('url')
            ->distinct()
            ->limit(50)
            ->get();
        $this->comment('Fetching thumbnails for ' . $bookmarks->count() . ' bookmarks');
        $this->getThumbnails($bookmarks);

        $pages = Page::where('thumbnail_id', 0)
            ->orderBy('created_at', 'asc')
            ->select('url')
            ->distinct()
            ->limit(50)
            ->get();
        $this->comment('Fetching thumbnails for ' . $pages->count() . ' history pages');
        $this->getThumbnails($pages);
    }

    private function attachThumbnails($thumbnails) {
        foreach($thumbnails as $thumbnail) {
            Bookmark::where('url', $thumbnail->url)->update(['thumbnail_id' => $thumbnail->id]);
            Page::where('url', $thumbnail->url)->update(['thumbnail_id' => $thumbnail->id]);
        }
    }

    private function attachMissingThumbnails($urls) {
        // Check for the broken thumbnail and create if necessary.
        $unavailable = Thumbnail::firstOrCreate([
            'image_small' => 'unavailable.png',
            'image_large' => 'unavailable.png'
        ]);

        foreach($urls as $url) {
            Bookmark::where('url', $url)->update(['thumbnail_id' => $unavailable->id]);
            Page::where('url', $url)->update(['thumbnail_id' => $unavailable->id]);
        }
    }

    private function getThumbnails($collection) {
        $allUrls = $collection->pluck('url')->toArray();
        $ungeneratedUrls = [];

        // Check which already have thumbnails generated.
        $existingThumbnails = Thumbnail::whereIn('url', $allUrls)->get();

        // Remove after done debugging.
        if ($existingThumbnails->count() > 0) {
            $this->comment('Existing Thumbnails found, attaching - ' . $existingThumbnails->count());
        }

        $this->attachThumbnails($existingThumbnails);

        $existingUrls = $existingThumbnails->pluck('url')->toArray();
        $ungeneratedUrls = array_diff($allUrls, $existingUrls);

        $json = [
            'data' => []
        ];

        foreach ($ungeneratedUrls as $url) {
            array_push($json['data'], ['url' => $url]);
        }

        $options = [
            'http' => [
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($json),
            ],
        ];

        $context  = stream_context_create($options);
        $rawResponse = file_get_contents($this->url . '/generate', false, $context);
        $response = json_decode($rawResponse, true);
        if (!$response) {
            $this->comment('Invalid response from thumbnail server - ' . $rawResponse);
            return false;
        }
        if ($response['status'] == 'error') {
            $this->comment('Error received - ' . $rawResponse);
            return false;
        }

        $this->downloadAndInsertThumbnails($response);
        return true;
    }

    private function downloadAndInsertThumbnails($response) {
        $newThumbnails = [];
        $missingThumbnailUrls = [];
        $this->comment('Downloading images');
        foreach ($response['results'] as $thumbnailResult) {
            if ($thumbnailResult['thumbnail']['status'] == 'error') {
                // Give it the unavailable thumbnail.
                array_push($missingThumbnailUrls, $thumbnailResult['url']);
                continue;
            }
            $smallFileName = $thumbnailResult['thumbnail']['image_small'];
            $largeFileName = $thumbnailResult['thumbnail']['image_large'];

            $thumbnail = new Thumbnail([
                'image_small' => $smallFileName,
                'image_large' => $largeFileName,
                'url' => $thumbnailResult['url']
                ]);

            $smallImgUrl = $this->url . '/generated/' . $smallFileName;
            $smallImg = file_get_contents($smallImgUrl);
            if (!$smallImg) {
                $this->comment('Could not download image from server - ' . $smallImgUrl);
                continue;
            }

            $largeImgUrl = $this->url . '/generated/' . $largeFileName;
            $largeImg = file_get_contents($largeImgUrl);
            if (!$largeImg) {
                $this->comment('Could not download image from server - ' . $largeImgUrl);
                continue;
            }

            $smallSuccess = file_put_contents('public/images/thumbnails/small/' . $smallFileName, $smallImg);
            $largeSuccess = file_put_contents('public/images/thumbnails/large/' . $largeFileName, $largeImg);
            if (!$smallSuccess || !$largeSuccess) {
                $this->comment('Could write to thumbnails directory. Please ensure write permissions.');
                return;   
            }

            $thumbnail->save();
            array_push($newThumbnails, $thumbnail);
        }

        $this->attachThumbnails($newThumbnails);
        $this->attachMissingThumbnails($missingThumbnailUrls);
    }
}
