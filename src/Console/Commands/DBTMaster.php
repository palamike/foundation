<?php

namespace App\Console\Commands\Database\Tools;

use App\Models\Auth\Permission;
use App\Models\Auth\PermissionGroup;
use App\Models\Auth\Role;
use App\Models\System\Setting;
use App\Models\System\SettingGroup;
use App\Support\Facades\SettingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class Master extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbt:master';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->info('=============================');
        $this->info('[Master Table Refresh]');
        $this->info('=============================');

        DB::beginTransaction();
        try{
            $this->refreshPermissionGroup(resource_path('master'.DIRECTORY_SEPARATOR.'tables'.DIRECTORY_SEPARATOR.'groups.yaml'));
            $this->refreshPermission(resource_path('master'.DIRECTORY_SEPARATOR.'tables'.DIRECTORY_SEPARATOR.'permissions.yaml'));
            $this->refreshAdminRole(resource_path('master'.DIRECTORY_SEPARATOR.'tables'.DIRECTORY_SEPARATOR.'roles.yaml'));

            $this->refreshSettingGroup(resource_path('master'.DIRECTORY_SEPARATOR.'tables'.DIRECTORY_SEPARATOR.'setting-groups.yaml'));
            $this->refreshSetting(resource_path('master'.DIRECTORY_SEPARATOR.'tables'.DIRECTORY_SEPARATOR.'settings.yaml'),[
                'th' => resource_path('lang'.DIRECTORY_SEPARATOR.'th'.DIRECTORY_SEPARATOR.'setting.php'),
                'en' => resource_path('lang'.DIRECTORY_SEPARATOR.'en'.DIRECTORY_SEPARATOR.'setting.php'),
            ]);

            DB::commit();

            Cache::forget(SettingService::getCacheKey());
        }//try
        catch(\Exception $e){
            DB::rollBack();
        }//catch

    }

    private function refreshPermissionGroup($file){

        try{
            $yaml = new Parser();
            $value = $yaml->parse(file_get_contents($file));

            $groups = $value['groups'];
            foreach($groups as $group){
                $find = PermissionGroup::byName($group['name'])->first();
                if(empty($find)){
                    $group['created_by'] = 1;
                    PermissionGroup::create($group);
                }//if
                else{
                    $group['updated_by'] = 1;
                    $find->update($group);
                }
            }//foreach
        }
        catch(ParseException $e){
            $this->error($file.' file can not be parse!!');
            throw $e;
        }

        $this->info('Refresh Permission Group : OK');
    }//private function refreshPermissionGroup

    private function refreshPermission($file){

        try{
            $yaml = new Parser();
            $value = $yaml->parse(file_get_contents($file));

            $groupMap = [];

            $permissionGroups = $value['permissions'];
            foreach($permissionGroups as $permissionGroup) {

                foreach($permissionGroup as $group => $permissions){
                    foreach($permissions as $permission){
                        if(!array_key_exists($group,$groupMap)){
                            $result = PermissionGroup::byName($group)->first();

                            if(empty($result)){
                                $error = 'Can not find permission group by name : '.$group;
                                $this->error($error);
                                throw new \Exception($error);
                            }//if

                            $groupMap[$group] = $result;
                        }

                        $permission['group_id'] = $groupMap[$group]->id;

                        $find = Permission::byName($permission['name'])->first();
                        if(empty($find)){
                            $permission['created_by'] = 1;
                            Permission::create($permission);
                        }//if
                        else{
                            $permission['updated_by'] = 1;
                            $find->update($permission);
                        }

                    }//foreach
                }//foreach

            }//foreach
        }
        catch(ParseException $e){
            $this->error($file.' file can not be parse!!');
            throw $e;
        }

        $this->info('Refresh Permission : OK');
    }//private function refreshPermission

    private function refreshAdminRole($file){
        try{
            $yaml = new Parser();
            $value = $yaml->parse(file_get_contents($file));

            $permissionIds = Permission::all()->pluck('id')->all();

            $roles = $value['roles'];
            foreach($roles as $role){
                $find = Role::byName($role['name'])->first();
                if(empty($find)){
                    $role['created_by'] = 1;
                    $find = Role::create($role);
                }//if
                else{
                    $role['updated_by'] = 1;
                    $find->update($role);
                }//else

                $find->permissions()->sync($permissionIds);

            }//foreach
        }
        catch(ParseException $e){
            $this->error($file.' file can not be parse!!');
            throw $e;
        }

        $this->info('Refresh Admin Role : OK');
    }//private function refreshAdminRole

    private function refreshSettingGroup($file){

        try{
            $yaml = new Parser();
            $value = $yaml->parse(file_get_contents($file));

            $groups = $value['groups'];
            foreach($groups as $group){
                $find = SettingGroup::byName($group['name'])->first();
                if(empty($find)){
                    $group['created_by'] = 1;
                    SettingGroup::create($group);
                }//if
                else{
                    $group['updated_by'] = 1;
                    $find->update($group);
                }
            }//foreach
        }
        catch(ParseException $e){
            $this->error($file.' file can not be parse!!');
            throw $e;
        }

        $this->info('Refresh Setting Group : OK');
    }//private function refreshSettingGroup

    private function refreshSetting($file,$langFiles){

        $languages = [];

        foreach($langFiles as $lang => $langFile){
            $languages[$lang] = include $langFile;
        }//foreach

        try{
            $yaml = new Parser();
            $value = $yaml->parse(file_get_contents($file));

            $groupMap = [];

            $settingGroups = $value['settings'];
            foreach($settingGroups as $settingGroup) {

                foreach($settingGroup as $group => $settings){
                    foreach($settings as $setting){
                        if(!array_key_exists($group,$groupMap)){
                            $result = SettingGroup::byName($group)->first();

                            if(empty($result)){
                                $error = 'Can not find setting group by name : '.$group;
                                $this->error($error);
                                throw new \Exception($error);
                            }//if

                            $groupMap[$group] = $result;
                        }

                        $setting['group_id'] = $groupMap[$group]->id;

                        $find = Setting::byName($setting['name'])->first();
                        if(empty($find)){
                            $setting['created_by'] = 1;
                            $setting['value'] = $setting['default'];
                            Setting::create($setting);
                        }//if
                        else{
                            $setting['updated_by'] = 1;
                            $setting['value'] = $setting['default'];
                            $find->update($setting);
                        }

                        $labelKey = str_replace('setting.','',$setting['label']);
                        $descKey = str_replace('setting.','',$setting['description']);

                        foreach($languages as $key => $value){
                            if(!array_key_exists($labelKey,$languages[$key])){
                                $languages[$key][$labelKey] = $labelKey;
                            }//if

                            if(!array_key_exists($descKey,$languages[$key])){
                                $languages[$key][$descKey] = $descKey;
                            }//if
                        }//foreach

                    }//foreach
                }//foreach

            }//foreach

            foreach($langFiles as $lang => $langFile){
                $var = var_export($languages[$lang],true);
                $content = "<?php \n\n return $var; ";
                file_put_contents($langFile,$content);
            }//foreach
        }
        catch(ParseException $e){
            $this->error($file.' file can not be parse!!');
            throw $e;
        }

        $this->info('Refresh Setting : OK');
    }//private function refreshSetting
}
