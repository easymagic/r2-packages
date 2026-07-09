<?php

namespace R2Packages\Framework\Settings;

use R2Packages\Framework\Settings\SettingsRepository;
use R2Packages\Framework\Request;
use R2Packages\Framework\Settings\SettingsService;

class SettingsController
{

    private SettingsService $settingsService;
    private Request $request;
    private SettingsRepository $settingsRepository;
    
    public function __construct(
        SettingsService $settingsService,
        Request $request,
        SettingsRepository $settingsRepository
    ) {
        $this->settingsService = $settingsService;
        $this->request = $request;
        $this->settingsRepository = $settingsRepository;
    }

    public function index()
    {
        $settings = $this->settingsRepository->findAll();
        jsonResponse([
            'message' => 'Settings fetched successfully',
            'data' => $settings,
            "success" => true
        ]);
    }

    public function save()
    {
        $this->settingsService->saveSetting($this->request);
        jsonResponse([
            'message' => 'Settings saved successfully',
            "success" => true
        ]);
    }
}
