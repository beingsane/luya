<?php

namespace luya\base;

use yii;
use Exception;
use luya\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\base\BootstrapInterface;

/**
 * All Luya modules must extend on this base module class.
 * 
 * @author nadar
 */
abstract class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @var array Contains the apis for each module to provided them in the admin module. They represents 
     *            the name of the api and the value represents the class. Example value:
     * 
     * ```php
     * [
     *     'api-admin-user' => 'admin\apis\UserController',
     *     'api-cms-navcontainer' => 'admin\apis\NavContainerController'
     * ]
     * ```
     */
    public $apis = [];

    /**
     * @var array Contains all urlRules for this module. Can't provided in key value pairing for pattern<=>route
     *            must be array containing class name or array with pattern, route informations.
     */
    public $urlRules = [];

    /**
     * @var bool Determines if a Module is an admin Module or not. This way we can easely change the boot behavior for each Module.
     */
    public $isAdmin = false;

    /**
     * @var array An array containing all components which should be registered for the current module. If 
     *            the component does not exists an Exception will be thrown.
     */
    public $requiredComponents = [];

    /**
     * @var bool Enable or Disable where the PATH to the layout file should be inside the @app namespace or inside your module.
     * 
     * - true = looking for layout file in `@app/views/<ID>/layouts`.
     * - false = looking for layout file in @module/views/layouts/`.
     * 
     * This variable is only available if your not in a context call. A context call would be if the cms renders the module.
     */
    public $useAppLayoutPath = true;

    /**
     * @var bool This variable can enable the view path defintion for all controllers inside this module.
     * 
     * - true = the view path inside this module will be used
     * - false = the view path of the projects app view will be used.
     */
    public $controllerUseModuleViewPath = null;

    /**
     * @var array Each module can have assets, all module controllers will register those assets in the view.. Valid class name to the asset e.g. 
     * 
     * ```php
     * public $assets = ['\app\assets\TestAsset'];
     * ```
     */
    public $assets = [];

    /**
     * @var string if this/the module is included via another module (parent module), the parent module will write its 
     *             name inside the child modules $context variable. For example the cms includes the news module, the context variable
     *             of news would have the value "cms".
     */
    public $context = null;

    /**
     * @var string The default name of the moduleLayout
     */
    public $moduleLayout = 'layout';

    /**
     * @var bool If this property is enabled, the module will be hidden in selections where use can choose a module,
     *           example in wizzard commands where they can create classes inside the modules. (e.g block/create, crud/create).
     *           In the method `Yii::$app->getLuyaModules()` the modules will not be listed.
     */
    public $isCoreModule = true;
    
    /**
     * @var array Add translations for your module, all translation array must have the keys "prefix", "basePath" and "fileMap"
     * For example:
     * 
     * ```php
     * $this->translations = [
     *     ['prefix' => 'luya*', 'basePath' => '@luya/messages', 'fileMap' => ['luya/admin' => 'admin.php']],
     * ],
     * ```
     * 
     * To use this translation run or createa a static helper method in your module.php
     * 
     * ```php
     * Yii::t('luya/admin', 'MyVariableInAdminPhp');
     * ```
     * @since 1.0.0-beta3
     */
    public $translations = [];

    public function bootstrap($app)
    {
    	$app->getUrlManager()->addRules($this->urlRules);
    	Yii::setAlias('@'.$this->id, $this->getBasePath());
    	foreach ($this->registerComponents() as $componentId => $definition) {
    		if (!$app->has($componentId)) {
    			Yii::trace('Register component ' . $componentId, __METHOD__);
    			$app->set($componentId, $definition);
    		}
    	}
    	
    	$this->luyaBootstrap($app);
    }
    
    public function luyaBootstrap($app)
    {
    	
    }
    
    /**
     * The Luya-Module initializer is looking for defined requiredComponents.
     * 
     * @throws \Exception
     *
     * @see \yii\base\Module::init()
     */
    public function init()
    {
        parent::init();
        // verify all the components
        foreach ($this->requiredComponents as $component) {
            if (!Yii::$app->has($component)) {
                throw new Exception(sprintf('The required component "%s" is not registered in the configuration file', $component));
            }
        }
        $this->registerTranslations();
    }
    
    /**
     * register the translation service for luya
     */
    private function registerTranslations()
    {
        foreach ($this->translations as $translation) {
            Yii::$app->i18n->translations[$translation['prefix']] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => $translation['basePath'],
                'fileMap' => $translation['fileMap'],
            ];
        }
    }

    /**
     * Override the default implementation of Yii's getLayoutPath(). If the property `$useAppLayoutPath` is true,.
     *
     * the *@app* namespace views will be looked up for view files
     * 
     * @return string;
     *
     * @see \yii\base\Module::getLayoutPath()
     */
    public function getLayoutPath()
    {
        if ($this->useAppLayoutPath) {
            $this->setLayoutPath('@app/views/'.$this->id.'/layouts');
        }

        return parent::getLayoutPath();
    }

    /**
     * Extract the current module from the route and return the new resolved route.
     * 
     * @param string $route Route to resolve, e.g. `admin/default/index`
     *
     * @return string
     */
    public function resolveRoute($route)
    {
        $routeParts = explode('/', $route);
        foreach ($routeParts as $k => $v) {
            if (($k == 0 && $v == $this->id) || (empty($v))) {
                unset($routeParts[$k]);
            }
        }
        if (count($routeParts) == 0) {
            return $this->defaultRoute;
        }

        return implode('/', $routeParts);
    }

    /**
     * register a component to the application. id => definition. All components will be registered during bootstrap process.
     *
     * @return array:
     */
    public function registerComponents()
    {
        return [];
    }

    /**
     * The import method will be called from exec/import command.
     *
     * @return void|string
     */
    public function import(\luya\console\interfaces\ImportController $exec)
    {
        return false;
    }
    
    /**
     * returns "luya\base" for example.
     *
     * @return string
     */
    public function getNamespace()
    {
        return implode('\\', array_slice(explode('\\', get_class($this)), 0, -1));
    }
    
    /**
     * Returns all controller files of this module from the `getControllerPath()` folder, where the key is the reusable 
     * id of this controller and value the file on the server.
     * 
     * @return array Returns an array where the key is the controller id and value the original file.
     * @since 1.0.0-beta5
     */
    public function getControllerFiles()
    {
        $files = [];
        foreach (FileHelper::findFiles($this->controllerPath) as $file) {
            $value = ltrim(str_replace([$this->controllerPath, 'Controller.php'], '', $file), '/');
            $files[Inflector::camel2id($value)] = $file;
        }
        return $files;
    }
}
