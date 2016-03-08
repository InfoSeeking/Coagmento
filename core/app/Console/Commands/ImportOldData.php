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

use App\Models\Bookmark;
use App\Models\Membership;
use App\Models\Project;
use App\Models\User;

use App\Models\Old\OldBookmark;
use App\Models\Old\OldMapping;
use App\Models\Old\OldMembership;
use App\Models\Old\OldProject;
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
            } else {
                printf("Exiting\n");
                return;
            }
        }

        $this->importUsers();
        $this->importProjects();
    }

    private function clear() {
        $this->clearNew(OldUser::class, User::class);
        $this->clearNew(OldMembership::class, Membership::class);
        $this->clearNew(OldProject::class, Project::class);
        $this->clearNew(OldBookmark::class, Bookmark::class);
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
        if (is_null($mapping)) return null;
        return $newModelClass::find($mapping->new_id);
    }

    private function importUsers() {
        printf("Importing users\n");
        $oldUsers = OldUser::all();
        foreach ($oldUsers as $oldUser) {
            $newUser = new User();
            $newUser->email = $oldUser->email;
            $newUser->password = "";
            $newUser->imported_password = $oldUser->password;
            $newUser->name = $oldUser->firstName . " " . $oldUser->lastName;
            $newUser->save();
            $this->addMapping(OldUser::class, $oldUser->userID, $newUser->id);
        }
    }

    private function ifExists($val, $default) {
        if (!is_null($val)) return $val;
        return $default;
    }

    private function importProjects() {
        printf("Importing projects\n");
        $oldProjects = OldProject::all();
        foreach($oldProjects as $oldProject) {
            // Imported project will have arbitrarily chosen owner.
            $creator = null;

            // Get old project creator.
            // User with access=1 is the creator in old Coagmento.
            $oldCreator = OldMembership::where('projectID', $oldProject->projectID)->where('access', 1)->first();
            $newCreator = null;
            if (!is_null($oldCreator)) {
                $newCreator = $this->getNew(OldUser::class, User::class, $oldCreator->userID);
            }
            
            if (is_null($newCreator)) {
                printf("Cannot import project %d because no creator could be found\n", $oldProject->projectID);
                continue;
            }

            $newProject = new Project();
            $newProject->title = $this->ifExists($oldProject->title, "");
            $newProject->description = $oldProject->description;
            $newProject->private = $this->ifExists($oldProject->privacy, 1);
            $newProject->creator_id = $newCreator->id;
            $newProject->save();
            $this->addMapping(OldProject::class, $oldProject->projectID, $newProject->id);

            // Add memberships.
            $oldMemberships = OldMembership::where('projectID', $oldProject->projectID)->get();
            foreach($oldMemberships as $oldMembership) {
                $newMemberUser = $this->getNew(OldUser::class, User::class, $oldMembership->userID);
                if (is_null($newMemberUser)) continue;

                // For now, just import all members as owners.
                $newMembership = new Membership();
                $newMembership->user_id = $newMemberUser->id;
                $newMembership->project_id = $newProject->id;
                $newMembership->level = 'o';
                $newMembership->save();
                $this->addMapping(OldMembership::class, $oldMembership->memberID, $newMembership->id);
            }
        }

    }
    // Must be called after importUsers and importProjects.
    private function importBookmarks() {
        printf("Importing bookmarks\n");
        $oldBookmarks = OldBookmark::all();
        foreach ($oldBookmarks as $oldBookmark) {
            $newBookmark = $this->getOrCreate(OldBookmark::class, Bookmark::class, $oldBookmark->bookmarkID);
            $newProject = $this->getNew(OldProject::class, Project::class, $oldBookmark->projectID);
            if (is_null($newProject)) continue;
            $newUser = $this->getNew(OldUser::class, User::class, $oldBookmark->userID);
            if (is_null($newUser)) continue;

            $newBookmark->url = $oldBookmark->url;
            $newBookmark->title = $oldBookmark->title;
            $newBookmark->notes = $oldBookmark->note;
            $newBookmark->creator_id = $newUser->id;
            $newBookmark->project_id = $newProject->id;
            $newBookmark->save();
            $this->addMapping(OldBookmark::class, $oldBookmark->bookmarkID, $newBookmark->id);
        }
    }

}
