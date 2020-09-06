<?php

namespace Stillat\Meerkat\Statamic\ControlPanel;

use Stillat\Meerkat\Concerns\UsesConfig;
use Stillat\Meerkat\Core\Contracts\Parsing\SanitationManagerContract;
use Stillat\Meerkat\PathProvider;

/**
 * Class SettingsProvider
 *
 * Provides utilities for gathering JavaScript variable names and values.
 *
 * @package Stillat\Meerkat\Statamic\ControlPanel
 * @since 2.0.0
 */
class SettingsProvider
{
    use UsesConfig;

    const JS_NAME_AVATAR_DRIVER = 'avatarDriver';

    /**
     * The SanitationManagerContract implementation instance.
     *
     * @var SanitationManagerContract
     */
    protected $sanitationManager = null;

    public function __construct(SanitationManagerContract $sanitationManager)
    {
        $this->sanitationManager = $sanitationManager;
    }

    /**
     * Creates a JavaScript snippet that can be utilized to provide the Meerkat CoreJS runtime with server-side settings.
     *
     * @return string
     */
    public function emitStatements()
    {
        $javaScriptStub = file_get_contents(PathProvider::getStub('settings.js'));
        $settingAssignments = [];

        foreach ($this->getSettings() as $settingName => $value) {
            $settingAssignments[] = 'window.meerkat.Config.Environment.Settings[\'' . $settingName . '\'] = \'' . $value . '\';';
        }

        $settings = join(';', $settingAssignments);

        $javaScriptStub = str_replace('/*settings*/', $settings, $javaScriptStub);

        return $javaScriptStub;
    }

    /**
     * Returns a mapping of JavaScript variable names and their sanitized values.
     *
     * @return array
     */
    public function getSettings()
    {
        $avatarDriver = $this->sanitationManager->sanitize(
            $this->getConfig('authors.cp_avatar_driver', 'initials')
        );


        return [
            SettingsProvider::JS_NAME_AVATAR_DRIVER => $avatarDriver
        ];
    }


}