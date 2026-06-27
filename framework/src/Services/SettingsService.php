<?php 

namespace R2Packages\Framework\Services;

use Exception;
use R2Packages\Framework\Repositories\SettingsRepository;
use R2Packages\Framework\Request;

class SettingsService {

    private SettingsRepository $settingsRepository;

    protected $settings = [];


    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Sync settings to the database
     */
    function sync(){
        $this->appendSettings([
            "APP_NAME"=>"My App",
            "APP_URL"=>"http://localhost",
            "APP_EMAIL"=>"info@example.com",
            "APP_PHONE"=>"1234567890",
            "APP_ADDRESS"=>"123 Main St, Anytown, USA",
            "APP_CITY"=>"Anytown",
            "APP_STATE"=>"CA",
            "APP_ZIP"=>"12345",
            "APP_COUNTRY"=>"USA",
            "ENABLE_RUN_MIGRATION"=>"1",
        ]);
        $this->presaveSettings();
    }

    /**
     * Append settings to the service
     * @param array $settings
     */
    protected function appendSettings($settings){
       foreach ($settings as $key => $value){
        $this->settings[$key] = $value;
      } 
    }

    function presaveSettings(){
        foreach ($this->settings as $key => $value){
            $setting = $this->settingsRepository->findByKey($key);
            if ($setting->isEmpty()){
                $this->settingsRepository->save(0, [
                    "setting_key" => $key,
                    "setting_value" => $value,
                ]);
            }else{
                $this->settingsRepository->save($setting->id, [
                    "setting_value" => $value,
                ]);
            }
         }    
    }


    function saveSetting(Request $request){
       // key is required
       $settings = $request->get('settings');
       // is array
       if (!is_array($settings)){
        throw new Exception("Settings must be an array!");
       }
       // foreach settings
       foreach ($settings as $key => $value){
        $this->settings[$key] = $value;
       }
       $this->presaveSettings();
    }


}