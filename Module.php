<?php
/**
 */

namespace execut\alias;


use execut\dependencies\PluginBehavior;
use yii\i18n\PhpMessageSource;
use yii\web\Application;

class Module extends \yii\base\Module implements Plugin
{
    public function behaviors()
    {
        return [
            [
                'class' => PluginBehavior::class,
                'pluginInterface' => Plugin::class,
            ],
        ];
    }

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->initI18n();
    }

    public function initI18n() {
        $this->module->i18n->translations['execut/alias'] = [
            'class' => PhpMessageSource::class,
            'basePath' => 'vendor/execut/alias/messages',
        ];
    }

    public function getModels() {
        return $this->getPluginsResults(__FUNCTION__);
    }
}