<?php
// Importing first deletes previously imported data and then rewrites.
// The reason is that if we instead try to "append" data, then deciding
// which data to ignore importing (e.g. do we want to ignore names when appending)
// can be tricky. 
// In the event of needing to import things later without having to
// clearing, we can create separate commands.
//
namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\Old\OldMapping;
use App\Models\Old\OldUser;

class ImportOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all of old Coagmento data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (OldMapping::count() > 0) {
            // Ask and clear first. 
            printf("It looks like you've already imported before.\n");
            printf("To continue we'll need to clear this imported data first.");

            $shouldClear = $this->ask('Is this okay? (Y/N)');
            if ($shouldClear == 'Y') {
                printf("Clearing old data.\n");
                $this->clear();
            }
        }

        $this->importUsers();
    }

    private function clear() {
        $this->clearNew(OldUser::class, User::class);
        OldMapping::query()->delete();
    }

    private function clearNew($oldModelClass, $newModelClass) {
        $mappings = OldMapping::where('table', $oldModelClass::getTableName())->get();
        foreach ($mappings as $mapping) {
            $newModelClass::find($mapping->new_id)->delete();
        }
    }

    private function addMapping($oldModelClass, $oldId, $newId) {
        $mapping = new OldMapping();
        $mapping->table = $oldModelClass::getTableName();
        $mapping->old_id = $oldId;
        $mapping->new_id = $newId;
        $mapping->save();
    }

    private function getNew($oldModelClass, $newModelClass, $oldId) {
        $mapping = OldMapping::where('old_id', $oldId)
            ->where('table', $oldModelClass::getTableName())
            ->first();

        if (!is_null($mapping)) return null;
        return $newModelClass::find($mapping->new_id);
    }

    private function importUsers() {
        printf("Importing users\n");
        $oldUsers = OldUser::all();
        foreach ($oldUsers as $oldUser) {
            printf("Old username %s\n", $oldUser->username);
            $newUser = new User();
            // TODO: set old password in special field.
            $newUser->email = $oldUser->email;
            $newUser->password = "";
            $newUser->imported_password = $oldUser->password;
            $newUser->name = $oldUser->firstName . " " . $oldUser->lastName;
            $newUser->save();
            $this->addMapping(OldUser::class, $oldUser->userID, $newUser->id);
        }

        // printf('Importing bookmarks');
        // $oldBookmarks = OldBookmark::all();
        // foreach ($oldBookmarks as $oldBookmark) {
        //     $newBookmark = $this->getOrCreate(OldBookmark::class, Bookmark::class, $oldBookmark->bookmarkID);
        //     $newBookmark->url = $oldBookmark->url;
        //     $newUser = $this->getNew(OldUser::class, User::class, $oldBookmark->userID);
        //     $newBookmark->creator_id = 
        // }
    }

}
