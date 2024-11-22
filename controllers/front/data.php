<?php
use PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductPresenter;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Core\Product\ProductPresentationSettings;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use Symfony\Contracts\Translation\TranslatorInterface;


class MyModuledataModuleFrontController extends ModuleFrontController {
    public function display() {
      $id_lang=(int)Context::getContext()->language->id;
      $start=0;
      $limit=10;
      $order_by='id_product';
      $order_way='DESC';
      $id_category = Tools::getValue('MYMODULE_CONFIG', Configuration::get('MYMODULE_CONFIG')); 
      $only_active =true;
      $context = null;

      $all_products = Product::getProducts($id_lang, $start, $limit, $order_by, $order_way, $id_category, $only_active, $context );

      // Set up context and settings
      $context = Context::getContext();
      $imageRetriever = new ImageRetriever($context->link);
      $productColorsRetriever = new ProductColorsRetriever();

      $priceFormatter = new PriceFormatter();
      $productSettings = new ProductPresentationSettings();
      $productSettings->showPrices = true; 

      // Initialize the Product Presenter
      $productPresenter = new ProductPresenter(
          $imageRetriever,
          $context->link,
          $priceFormatter,
          $productColorsRetriever,
          $context->getTranslator()
      );

      $assembler = new ProductAssembler($this->context);
   
      $ready_products = [];
      foreach ($all_products as $rawProduct) {
        if (!isset($rawProduct['id_product_attribute'])) {
          $rawProduct['id_product_attribute'] = 0; 
        }
        $ready_products[] = $productPresenter->present($productSettings, $assembler->assembleProduct($rawProduct), $context->language);
       
      }

      $this->context->smarty->assign([
        'products' => $ready_products
      ]);
      $htmlContent = $this->context->smarty->fetch('module:mymodule/views/templates/front/partials/products.tpl');

      // Creating category object, to get and send category url
      $category = new Category($id_category, $context->language->id);

      header('Content-Type: application/json');
      die(json_encode([
        'html' => $htmlContent, 
        'products' => $ready_products,
        'category_url' => $context->link->getCategoryLink($category->id, $category->link_rewrite)
      ]));


    } 
}