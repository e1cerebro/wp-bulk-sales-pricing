<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wp_Bulk_Sales_Pricing
 * @subpackage Wp_Bulk_Sales_Pricing/admin/partials
 */

 include_once('bsp_custom_function.php');

 ?>


<pre>

    <?php

        if(isset($_POST['remove-sales-price'])):
             
            $apply_to = $_POST['apply_to'];
          
            if('All Products' == $apply_to){
                //Get All The Products in the Database that are not in the trash
                $bsp_all_products = get_all_products();
               
            }elseif('Specific Product' == $apply_to){
                $form_categories = $_POST['specific_products'];
                //Get All The Products in the Database that are not in the trash
                $bsp_all_products = get_all_products($form_categories, 'Yes');
              
            }elseif('Specific Category' == $apply_to){
                //Get All The Products in the Database that are not in the trash
                
                $form_categories = $_POST['categories'];
                $bsp_all_products = get_all_products($form_categories);
                
            }

 
            //Check if the right parameters were supplied to processed with deleting the products sale prices
            if(isset($bsp_all_products)):
               
                //Loop through all the products
                foreach($bsp_all_products as $product_id):
                    //Get the product object
                    try{
                        $product = wc_get_product( $product_id );
                    }catch(Exception $e){
                        echo 'Message: ' .$e->getMessage();
                    }

                    //Get the product categories:
                    $product_categories = $product->get_category_ids();

                    //Check if the product is variable or simple product
                    $product_type = $product->get_type();
                    //Get the regular Price
                    $regular_price = $product->get_regular_price();
                    
                    if( 'simple' == $product_type){
                                
                        //Call the remove sales price function:
                        bsp_delete_sales_price($product_id, $regular_price);

                    }elseif('variable' == $product_type){
                        
                        //get all the product variable IDS:
                        $bsp_available_variation = $product->get_available_variations();
                        
                        //Loop through all the variable products
                        foreach($bsp_available_variation as $product_object):
                            //get the product ID:
                            $variable_product_id = $product_object['variation_id'];
                            try{
                                //Create the product object
                                $variable_product = wc_get_product($variable_product_id);
                            }catch(Exception $e){
                                echo 'Message: ' .$e->getMessage();
                            }

                            //Call the set sales price function:
                            bsp_delete_sales_price($variable_product_id, $regular_price);
                                    
                        endforeach;
                    }
 
                endforeach;

                echo "<div class=\"notice notice-success is-dismissible\"> <p><strong>Sales price was successfully Removed.</strong></p></div>";


            endif;

        endif;

       
    ?>

</pre>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="container">
        <div class="col-left">
        <section class="bsp-form-header">
                <h1>Delete Sales Price</h1>
            </section>
            <section class="sales-form-section">
            <div id="output"></div>

                <form action="" class="sales-form" method="POST">
        
                    <div class="form-control">
                        <label for="apply_to">Apply To:</label>
                        <select required data-placeholder="Choose categories..." id="apply_to" name="apply_to" class="chosen-select">
                            <option value="All Products">All Products</option>
                            <option value="Specific Product">Specific Product</option>
                            <option value="Specific Category" >Specific Category</option>
                        </select>
                    </div>

                    <div class="form-control" id="categories">
                        <label for="categories">Choose Categories:</label>
                        <select  data-placeholder="Choose categories..." name="categories[]" multiple class="chosen-select">
                            <option value="All" selected>All Categories</option>
                            <?php foreach(get_all_product_categories() as $cat): ?>
                                <option value="<?php echo $cat->name; ?>"><?php echo $cat->name; ?></option>
                                <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-control" id="specific-products">
                        <label for="specific_products">Choose Products:</label>
                        <select data-placeholder="Choose products..." name="specific_products[]" multiple class="chosen-select">
                            <?php foreach(get_all_products() as $product_id): ?>
                                <?php $product_obj = wc_get_product( $product_id );?>
                                <option value="<?php echo $product_id; ?>"><?php echo $product_obj->get_title()." (#".$product_id.")"; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php 
                        $other_attributes = array( 'class' => 'remove-btn' );
                        submit_button( 'Remove Sales Price(s)', 'secondary', 'remove-sales-price', true,$other_attributes );
                    ?>

                </form>
            </section>
        </div>


        <div class="col-right">
            <section class="bsp-instruction-header">
                <h1>Instructions</h1>
            </section>
            <section class="bsp-instruction-body">
                <p>Below are some of the cool tips that would help you to maximize this plugin:</p>
                <ol>
                     
                     <li><strong>Apply To:</strong> Gives you an option to select a product or categories to apply the delete the sales price.
                            <ul>
                                <li><strong>All Products:</strong> This will delete the sales price from all published products in the shop.</li>
                                <li><strong>Specific Product:</strong> Choose a specific product(s) to delete the sales price</li>
                                <li><strong>Specific Category:</strong> Choose a specific product(s) category to delete the sales price</li>
                            </ul>
                    </li> 
                </ol>

            </section>
    
    </div>

</div>

 

 

