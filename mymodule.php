<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class MyModule extends Module {

    // Module basic configuration
    public function __construct() {
        $this->name = 'mymodule';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Szymon Mudrak';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => '8.99.99',
        ];
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('My module', [], 'Modules.Mymodule.Admin');
        $this->description = $this->trans('Description of my module.', [], 'Modules.Mymodule.Admin');
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.Mymodule.Admin');

        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->trans('No name provided', [], 'Modules.Mymodule.Admin');
        }
    }

    // Module install function
    public function install() {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
    
        return parent::install() &&
            $this->registerHook('displayLeftColumn') &&
            $this->registerHook('displayHome') &&
            $this->registerHook('actionFrontControllerSetMedia') &&
            Configuration::updateValue('MYMODULE_NAME', 'my friend');
    }

    public function uninstall() {
        return (
            parent::uninstall() 
            && Configuration::deleteByName('MYMODULE_NAME')
        );
    }

    public function getContent() {
        // Output variable shown to the user
        $output = '';

        // Form submiting validation
        if (Tools::isSubmit('submit' . $this->name)) {
            // Value from the form
            $configValue = htmlspecialchars(Tools::getValue('MYMODULE_CONFIG'));

            // CHeck if value is not empty
            if (empty($configValue)) {
                $output = $this->displayError($this->l('Invalid Configuration value'));
            } else {
                // If input is correct, update it and display a confirmation message
                Configuration::updateValue('MYMODULE_CONFIG', $configValue);
                $output = $this->displayConfirmation($this->l('Settings updated'));
            }
        }

        // Displays any message and the form
        return $output . $this->displayForm();
}

public function displayForm() {
    // Form settings on module page
    $form = [
        'form' => [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [
                [
                    'type' => 'textarea',
                    'class' => 'rte',
                    'autoload_rte' => true, 
                    'label' => $this->l('Configuration value'),
                    'name' => 'MYMODULE_CONFIG',
                    'size' => 20,
                    'required' => true,
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
            ],
        ],
    ];
    $helper = new HelperForm();

    // Module, token and currentIndex
    $helper->table = $this->table;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
    $helper->submit_action = 'submit' . $this->name;

    // Default language
    $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');

    // Loads current value into the form
    $helper->fields_value['MYMODULE_CONFIG'] = html_entity_decode(Tools::getValue('MYMODULE_CONFIG', Configuration::get('MYMODULE_CONFIG')));
    return $helper->generateForm([$form]);
}


    public function hookDisplayHome($params) {
        // Assigns value from to the form to the frontend
        $this->context->smarty->assign([
            'text_from_form' => html_entity_decode(Tools::getValue('MYMODULE_CONFIG', Configuration::get('MYMODULE_CONFIG')))
        ]);

        return $this->display(__FILE__, 'displayHome.tpl');
    }

    public function hookActionFrontControllerSetMedia() {
        // Registering css file for the module
        $this->context->controller->addCSS($this->_path.'views/css/style.css', 'all');
    }

}
