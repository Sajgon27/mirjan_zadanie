<ul class="glide__slides">
  {foreach $products as $product}    
    <li class="glide__slide product-slider">
      <a href="{$product.url}">
        <span class="stock-status-slider">W magazynie</span>
        <img src="{$product.default_image.large.url}">
        <div class="short-desc hidden">{$product.description_short nofilter}</div>
        <h3 class="slider-product-title">{$product.name}</h3> 
      </a>
      <p class="slider-price">{$product.price}</p> 
    </li>    
  {/foreach}
</ul>
   





   
