<?php 

require('products-array.php');


// Filters
$cat_filter = isset($_GET['cats']) ? $_GET['cats'] : '';
$search_filter = isset($_GET['search']) ? $_GET['search'] : '';

// Default limit
$limit = isset($_GET['per-page']) ? $_GET['per-page'] : 3;

// Default offset
$offset = 0;
$current_page = 1;
if(isset($_GET['page-number'])) {
    $current_page = (int)$_GET['page-number'];
    $offset = ($current_page * $limit) - $limit;
}

// filter products array based on query
if(!empty($cat_filter) || !empty($search_filter)) {
    $filtered_products = array();
    foreach($products as $product) {
        if( !empty($cat_filter) && !empty($search_filter) ) {
            
            if( ( strpos($product['title'], $search_filter) !== false || $product['sku'] == $search_filter ) && $product['category'] == $cat_filter ) {
                $filtered_products[] = $product;
            }

        }
        else if(!empty($cat_filter) && $product['category'] == $cat_filter) {

            $filtered_products[] = $product;
        }
        else if(!empty($search_filter) && ( strpos($product['title'], $search_filter) !== false || $product['sku'] == $search_filter) ) {
            
            $filtered_products[] = $product;
        }
        
    }
    
    $products = $filtered_products;
}

$paged_products = array_slice($products, $offset, $limit);

$total_products = count($products);

// Get the total pages rounded up the nearest whole number
$total_pages = ceil( $total_products / $limit );

$paged = $total_products > count($paged_products) ? true : false;

if (count($paged_products)) {
    foreach ($paged_products as $product) { ?>
       
       <div class="col-md-4 product-wrapper">
           <div class="product">
               <div class="product-image">
                   <img src="<?php echo $product['image']; ?>" />
                </div>
                <div class="product-title">
                    <h3><?php echo $product['title']; ?></h3>
                </div>
                <div class="product-info">
                    <p class="product-sku"><?php echo $product['sku']; ?></p>
                    <p class="product-price">R <?php echo $product['price']; ?></p>
                    <p class="product-category">Listed in <?php echo $product['category']; ?></p>
                </div>
           </div>
           
       </div>
           
     <?php }
}

else {
    echo '<p class="alert alert-warning" >No results found.</p>';
}
 
 if ($paged) {
     require('pagination.php');
 }