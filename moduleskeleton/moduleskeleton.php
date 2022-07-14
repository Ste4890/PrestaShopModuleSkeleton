<?php

if (!defined('_PS_VERSION_')) {
    exit;
}
/**
 * prestashop is not happy during install if autoloader is not included. Move in install method?
 */
include_once _PS_MODULE_DIR_ . '/moduleskeleton/vendor/autoload.php';

use StefanoPelagotti\Module\Installer;
use StefanoPelagotti\Module\Settings;

/**
 * This class is a basic skeleton for a PS module.
 * The aim is to make this class, in the end, just the entry point used to talk
 * with prestashop and delegating all the logic elsewhere,
 * mainly in the src folder with its own classes and stuff.
 */
class ModuleSkeleton extends PaymentModule {
    /**
     * A list of hooks to be installed and uninstalled for this module.
     * Maybe in the future reflection could be used instead?
     *
     * @var array $hooks
     */
    public $hooks = [
        'header', // js & css
        'displayBackofficeHeader', // js & css for back
    ];
    public $config_form_name = Settings::CONFIG_FORM_NAME;

    public function __construct() {
        $this->name = 'moduleskeleton';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Stefano Pelagotti';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_,
        ];
        $this->bootstrap = true;
        $this->is_configurable = 1;


        parent::__construct();

        $this->displayName = $this->l('Module Skeleton');
        $this->description = $this->l('Questo è un modulo da usare come template');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');


    }

    public function install() {

        $installer = new Installer($this);
        // fixme: move into installer
        $this->installTab();

        return parent::install() &&
            $installer->install();
    }

    public function uninstall() {
        $installer = new Installer($this);

        return parent::uninstall() &&
            $installer->uninstall(true);
    }


    public function getContent(): string {
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            $this->parseConfigurationForm();
        }

        return $output . $this->displayForm();
    }

    /**
     * This is the method used to generate a form.
     *
     * @return mixed
     * @todo refactor into something better, maybe in a completely separated class
     */
    public function displayForm() {

        // Get default language
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');
        // Init Fields form array
        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Inserisci i dati di configurazione'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Input di esempio'),
                    'name' => $this->config_form_name . '[random_key_name]',
                    'size' => 50,
                    'required' => true,
                    'class' => 'col-lg-2',
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
            ],
        ];

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = false;        // false -> remove toolbar
        $helper->toolbar_scroll = false;      // true - > sticky toolbar
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                    '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list'),
            ],
        ];

        // Load current values
        $settings = Settings::getInstance();
        $helper->fields_value = $settings->getAllForForm();


        return $helper->generateForm($fieldsForm);
    }

    private function parseConfigurationForm(): string {

        $output = null;
        $settings = Settings::getInstance();
        $submittedValues = Tools::getValue($this->config_form_name);

        if (empty($submittedValues['password'])) {
            // this is due in order to not have to re insert the password every time
            //PrestaShop itself uses this pattern somewhere
            unset($submittedValues['password']);

        }

        $settings->setFromArray($submittedValues);
        $settings->save();


        $output .= $this->displayConfirmation($this->l('Settings updated'));
        return $output;


    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     * Adds them only when needed
     */
    public function hookHeader(): void {

        $permitted_controllers = [
            "authentication",
        ];
        // todo: check if php_self is set. It seems that it is not always the case?
        if (in_array($this->context->controller->php_self, $permitted_controllers)) {

            $this->context->controller->addJS($this->_path . '/views/js/front.js');
            $this->context->controller->addCSS($this->_path . '/views/css/front.css');
        }
    }

    public function hookDisplayBackofficeHeader() {
        $this->context->controller->addJS($this->_path . '/views/js/back.js');
        $this->context->controller->addCSS($this->_path . '/views/css/back.css');
    }
    //fixme: move logic into its own class
    public function hookPaymentOptions($params) {
        if (!$this->active) {
            return [];
        }

        $option = new PaymentOption();
        $option->setModuleName($this->name)
            ->setAction($this->context->link->getModuleLink($this->name, 'validation', [], true))
            // fixme: improve consistency with use of $this->l()
            ->setCallToActionText($this->trans($this->name, [], 'Shop.Childtheme.Base'));

        $payment_options[] = $option;
        return $payment_options;
    }

    public const TAB_TO_INSTALL = [
        [
            'controller' => 'ResellersController',
            'label' => 'Label per il link',
            'parent_classname' => 'IMPROVE',

        ]
    ];

    private function installTab() {
        /**
         * in case of a symfony controller, legacy option must be declared in config/routes.yml
         */
        $ok = true;
        foreach (self::TAB_TO_INSTALL as $tab_data) {
            $tab = new \Tab();
            $tab->active = 1;
            $tab->class_name = $tab_data['controller'];
            $tab->name = [];
            foreach (Language::getLanguages(true) as $lang) {
                $tab->name[$lang['id_lang']] = $tab_data['label'];
            }
            $tab->id_parent = (int)Tab::getIdFromClassName($tab_data['parent_classname']);
            $tab->module = $this->name;
            $ok = $ok && $tab->save();
        }


        return $ok;
    }

}