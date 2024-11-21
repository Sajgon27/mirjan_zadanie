<?php

class MyModuledataModuleFrontController extends ModuleFrontController {
    public function display() {
      $data = [
        'status' => 'success', 
        'data'=> html_entity_decode(Tools::getValue('MYMODULE_CONFIG', Configuration::get('MYMODULE_CONFIG')))
      ];
        die(Tools::jsonEncode($data));
    } 
}