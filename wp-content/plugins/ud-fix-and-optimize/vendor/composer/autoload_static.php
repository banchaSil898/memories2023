<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit552831291cac9b0ff6208062672a6bdc
{
    public static $prefixLengthsPsr4 = array (
        'U' => 
        array (
            'UDFixAndOptimize\\UDOptionFramework\\' => 35,
            'UDFixAndOptimize\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'UDFixAndOptimize\\UDOptionFramework\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib/ud-option-framework/src',
        ),
        'UDFixAndOptimize\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'UDFixAndOptimize\\Admin\\Admin' => __DIR__ . '/../..' . '/src/Admin/Admin.php',
        'UDFixAndOptimize\\Admin\\Setting' => __DIR__ . '/../..' . '/src/Admin/Setting.php',
        'UDFixAndOptimize\\Admin\\SettingPage' => __DIR__ . '/../..' . '/src/Admin/SettingPage.php',
        'UDFixAndOptimize\\CLI\\CLIManager' => __DIR__ . '/../..' . '/src/CLI/CLIManager.php',
        'UDFixAndOptimize\\CLI\\PageSpeedCommand' => __DIR__ . '/../..' . '/src/CLI/PageSpeedCommand.php',
        'UDFixAndOptimize\\CLI\\RevisionCommand' => __DIR__ . '/../..' . '/src/CLI/RevisionCommand.php',
        'UDFixAndOptimize\\Core\\DuracelltomiGTM' => __DIR__ . '/../..' . '/src/Core/DuracelltomiGTM.php',
        'UDFixAndOptimize\\Core\\InstantArticlesForWP' => __DIR__ . '/../..' . '/src/Core/InstantArticlesForWP.php',
        'UDFixAndOptimize\\Core\\MediaLibraryCategories' => __DIR__ . '/../..' . '/src/Core/MediaLibraryCategories.php',
        'UDFixAndOptimize\\Core\\Nobuna' => __DIR__ . '/../..' . '/src/Core/Nobuna.php',
        'UDFixAndOptimize\\Core\\PageSpeed' => __DIR__ . '/../..' . '/src/Core/PageSpeed.php',
        'UDFixAndOptimize\\Core\\WPCore' => __DIR__ . '/../..' . '/src/Core/WPCore.php',
        'UDFixAndOptimize\\Core\\WPRSSAggregator' => __DIR__ . '/../..' . '/src/Core/WPRSSAggregator.php',
        'UDFixAndOptimize\\Core\\YoastSEO' => __DIR__ . '/../..' . '/src/Core/YoastSEO.php',
        'UDFixAndOptimize\\UDFixAndOptimize' => __DIR__ . '/../..' . '/src/UDFixAndOptimize.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Base\\AbstractComponent' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Base/AbstractComponent.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Base\\AbstractField' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Base/AbstractField.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Base\\AbstractOption' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Base/AbstractOption.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Base\\AbstractPage' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Base/AbstractPage.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Base\\AbstractSection' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Base/AbstractSection.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Exception\\InvalidOptionValueException' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Exception/InvalidOptionValueException.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Field\\CategoryChecklistField' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Field/CategoryChecklistField.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Field\\CheckBoxField' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Field/CheckBoxField.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Field\\IntegerWithUnitField' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Field/IntegerWithUnitField.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Field\\SelectField' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Field/SelectField.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Field\\TextAreaField' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Field/TextAreaField.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Field\\TextField' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Field/TextField.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Option\\ArrayOption' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Option/ArrayOption.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Option\\BooleanOption' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Option/BooleanOption.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Option\\ChoiceOption' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Option/ChoiceOption.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Option\\DigitOption' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Option/DigitOption.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Option\\IntegerOption' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Option/IntegerOption.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Option\\IntegerWithUnitOption' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Option/IntegerWithUnitOption.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Option\\RegExStringOption' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Option/RegExStringOption.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Option\\StringOption' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Option/StringOption.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Page\\Page' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Page/Page.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Page\\SubPage' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Page/SubPage.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Section\\Section' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Section/Section.php',
        'UDFixAndOptimize\\UDOptionFramework\\Component\\Section\\TabContainerSection' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Component/Section/TabContainerSection.php',
        'UDFixAndOptimize\\UDOptionFramework\\Core\\Manager' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Core/Manager.php',
        'UDFixAndOptimize\\UDOptionFramework\\Core\\OptionManager' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Core/OptionManager.php',
        'UDFixAndOptimize\\UDOptionFramework\\OptionFramework' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/OptionFramework.php',
        'UDFixAndOptimize\\UDOptionFramework\\Util\\CategoryChecklistWalker' => __DIR__ . '/../..' . '/lib/ud-option-framework/src/Util/CategoryChecklistWalker.php',
        'UDFixAndOptimize\\Util\\ImageEditor' => __DIR__ . '/../..' . '/src/Util/ImageEditor.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit552831291cac9b0ff6208062672a6bdc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit552831291cac9b0ff6208062672a6bdc::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit552831291cac9b0ff6208062672a6bdc::$classMap;

        }, null, ClassLoader::class);
    }
}