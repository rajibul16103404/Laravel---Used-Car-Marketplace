<?php

use Illuminate\Support\Facades\Facade;
use Modules\Admin\Inventory_Type\InventoryTypeServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => 'file',
        // 'store'  => 'redis',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        Modules\WhatsappBot\WhatsappBotServiceProvider::class,
        Modules\Auth\AuthServiceProvider::class,
        Modules\Admin\Body_Type\Body_TypeServiceProvider::class,
        Modules\Admin\Users\UserServiceProvider::class,
        Modules\Admin\Cylinders\CylinderServiceProvider::class,
        Modules\Admin\Doors\DoorServiceProvider::class,
        Modules\Admin\Fuel_Type\Fuel_TypeServiceProvider::class,
        Modules\Admin\Make\MakeServiceProvider::class,
        Modules\Admin\CarModel\CarModelServiceProvider::class,
        Modules\Admin\Transmission\TransmissionServiceProvider::class,
        Modules\Admin\CarLists\CarListServiceProvider::class,
        Modules\WhatsappBot\WhatsappBotServiceProvider::class,
        Modules\Admin\Year\YearServiceProvider::class,
        Modules\Admin\Color\ExteriorColor\ExteriorColorServiceProvider::class,
        Modules\Admin\Color\InteriorColor\InteriorColorServiceProvider::class,
        Modules\Admin\Trim\TrimServiceProvider::class,
        Modules\Admin\Version\VersionServiceProvider::class,
        Modules\Admin\Body_Subtype\BodySubTypeServiceProvider::class,
        Modules\Admin\Vehicle_Type\VehicleTypeServiceProvider::class,
        Modules\Admin\DriveTrain\DriveTrainServiceProvider::class,
        Modules\Admin\Engine\EngineServiceProvider::class,
        Modules\Admin\Engine_Size\EngineSizeServiceProvider::class,
        Modules\Admin\Engine_Block\EngineBlockServiceProvider::class,
        Modules\Admin\MadeIn\MadeInServiceProvider::class,
        Modules\Admin\Overall_Height\OverallHeightServiceProvider::class,
        Modules\Admin\Overall_Length\OverallLengthServiceProvider::class,
        Modules\Admin\Overall_Width\OverallWidthServiceProvider::class,
        Modules\Admin\Std_seating\StdSeatingServiceProvider::class,
        Modules\Admin\Highway_Mpg\HighwayMpgServiceProvider::class,
        Modules\Admin\City_Mpg\CityMpgServiceProvider::class,
        Modules\Admin\Powertrain_Type\PowerTrainTypeServiceProvider::class,
        Modules\Admin\Inventory_Type\InventoryTypeServiceProvider::class,
        Modules\Admin\Seller_Type\SellerTypeServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        // 'ExampleClass' => App\Example\ExampleClass::class,
        'Whatsapp' => MissaelAnda\Whatsapp\Facade\Whatsapp::class,
    ])->toArray(),

];
