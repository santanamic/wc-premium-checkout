<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit75cd69b3aeed0f431a28281e86bf2cd6
{
    public static $classMap = array (
        'WPC\\Abstract_Addon' => __DIR__ . '/../../..' . '/app/classes/wcp-class-abstract-addon.php',
        'WPC\\Control\\Card_Selector' => __DIR__ . '/../../..' . '/app/inc/Controls/wpc-card-selector/class-wpc-control-card-selector.php',
        'WPC\\Control\\Field_Group' => __DIR__ . '/../../..' . '/app/inc/Controls/wpc-field-group/class-wpc-control-field-group.php',
        'WPC\\Control\\Multiple_Select' => __DIR__ . '/../../..' . '/app/inc/Controls/wpc-multiple-select/class-wpc-control-multiple-select.php',
        'WPC\\Extension\\Conditional_Rules' => __DIR__ . '/../../..' . '/app/inc/Addons/wpc-conditional-rules/class-wpc-extension-conditional-rules.php',
        'WPC\\Extension\\Fields_Manager' => __DIR__ . '/../../..' . '/app/inc/Addons/wpc-fields-manager/class-wpc-extension-fields-manager.php',
        'WPC\\Extension\\Theme_Compatibility' => __DIR__ . '/../../..' . '/app/inc/Addons/wpc-theme-compatibility/class-wpc-extension-theme-compatibility.php',
        'WPC\\Extension\\Theme_Selector' => __DIR__ . '/../../..' . '/app/inc/Addons/wpc-theme-selector/class-wpc-extension-theme-selector.php',
        'WPC\\Load_Addons' => __DIR__ . '/../../..' . '/app/classes/wcp-class-load-addons.php',
        'WPC\\Main_Plugin' => __DIR__ . '/../../..' . '/app/classes/wcp-class-main-plugin.php',
        'WPC\\Register_Controls' => __DIR__ . '/../../..' . '/app/classes/wcp-class-register-controls.php',
        'WPC\\Singleton' => __DIR__ . '/../../..' . '/app/classes/traits/wcp-trait-singleton.php',
        'WPC\\Theme\\Default_Theme' => __DIR__ . '/../../..' . '/app/inc/Addons/wpc-default-theme/class-wpc-theme-default-theme.php',
        'WPC\\Theme\\Onepage_Checkout' => __DIR__ . '/../../..' . '/app/inc/Addons/wpc-onepage-checkout/class-wpc-theme-onepage-checkout.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit75cd69b3aeed0f431a28281e86bf2cd6::$classMap;

        }, null, ClassLoader::class);
    }
}
