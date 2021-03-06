<?php

namespace Palamike\Foundation\Console\Commands;

use Illuminate\Support\Facades\Hash;
use Palamike\Foundation\Models\Auth\Permission;
use Palamike\Foundation\Models\Auth\PermissionGroup;
use Palamike\Foundation\Models\Auth\Role;
use Palamike\Foundation\Models\Auth\User;
use Palamike\Foundation\Models\System\Setting;
use Palamike\Foundation\Models\System\SettingGroup;
use Palamike\Foundation\Support\Facades\SettingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class DBTMaster extends Command
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

            $admin = User::find(1);
            if(empty($admin)){
                User::create([
                    'name' => 'Administrator',
                    'email' => 'admin@foundation.tld',
                    'password' => Hash::make('password'),
                    'username' => 'admin',
                    'status' => 'active',
                    'created_by' => 1,
                    'created_by_name' => 'command'
                ]);
            }//if

            $foundationLang = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR;
            $foundationMaster = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'master'.DIRECTORY_SEPARATOR;
            
            $foundationGroupFile = $foundationMaster.'tables'.DIRECTORY_SEPARATOR.'groups.yaml';
            $this->refreshPermissionGroup($foundationGroupFile);
            
            $groupFile = resource_path('master'.DIRECTORY_SEPARATOR.'tables'.DIRECTORY_SEPARATOR.'groups.yaml');
            if(file_exists($groupFile)){
                $this->refreshPermissionGroup($groupFile);    
            }//if
            else{
                $this->info('File Not found skipped : '.$groupFile);
            }//else

            $foundationPermissionFile = $foundationMaster.'tables'.DIRECTORY_SEPARATOR.'permissions.yaml';
            $this->refreshPermission($foundationPermissionFile);

            $permissionFile = resource_path('master'.DIRECTORY_SEPARATOR.'tables'.DIRECTORY_SEPARATOR.'permissions.yaml');
            if(file_exists($permissionFile)){
                $this->refreshPermission($permissionFile);
            }//if
            else{
                $this->info('File Not found skipped : '.$permissionFile);
            }//else

            $foundationAdminRoleFile = $foundationMaster.'tables'.DIRECTORY_SEPARATOR.'roles.yaml';
            $this->refreshAdminRole($foundationAdminRoleFile);

            $adminRoleFile = resource_path('master'.DIRECTORY_SEPARATOR.'tables'.DIRECTORY_SEPARATOR.'roles.yaml');
            if(file_exists($adminRoleFile)){
                $this->refreshAdminRole($adminRoleFile);
            }//if
            else{
                $this->info('File Not found skipped : '.$adminRoleFile);
            }//else

            $foundationSettingGroupFile = $foundationMaster.'tables'.DIRECTORY_SEPARATOR.'setting-groups.yaml';
            $this->refreshSettingGroup($foundationSettingGroupFile);

            $settingGroupFile = resource_path('master'.DIRECTORY_SEPARATOR.'tables'.DIRECTORY_SEPARATOR.'setting-groups.yaml');
            if(file_exists($settingGroupFile)){
                $this->refreshSettingGroup($settingGroupFile);
            }//if
            else{
                $this->info('File Not found skipped : '.$settingGroupFile);
            }//else

            $foundationSettingFile = $foundationMaster.'tables'.DIRECTORY_SEPARATOR.'settings.yaml';
            $this->refreshSetting($foundationSettingFile,[
                'th' => $foundationLang.'th'.DIRECTORY_SEPARATOR.'setting.php',
                'en' => $foundationLang.'en'.DIRECTORY_SEPARATOR.'setting.php',
            ]);

            $settingFile = resource_path('master'.DIRECTORY_SEPARATOR.'tables'.DIRECTORY_SEPARATOR.'settings.yaml');
            if(file_exists($settingFile)){
                $this->refreshSetting($settingFile ,[
                    'th' => resource_path('lang'.DIRECTORY_SEPARATOR.'th'.DIRECTORY_SEPARATOR.'setting.php'),
                    'en' => resource_path('lang'.DIRECTORY_SEPARATOR.'en'.DIRECTORY_SEPARATOR.'setting.php'),
                ]);
            }
            else{
                $this->info('File Not found skipped : '.$settingFile);
            }

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
                    $group = PermissionGroup::create($group);
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

        $this->info('Refreshed Permission Group : '.$file);
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

        $this->info('Refreshed Permission : '.$file);
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

        $admin = User::find(1);

        if(count($admin->roles) == 0){
            $admin->roles()->attach(1);
        }//if

        $this->info('Refreshed Admin Role : '.$file);
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

        $this->info('Refreshed Setting Group : '.$file);
    }//private function refreshSettingGroup

    private function refreshSetting($file,$langFiles){
        
        $languages = [];

        foreach($langFiles as $lang => $langFile){
            if(file_exists($langFile)){
                $languages[$lang] = include $langFile;    
            }
            else{
                $languages[$lang] = [];
            }
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

        $this->info('Refreshed Setting : '.$file);
    }//private function refreshSetting
}
