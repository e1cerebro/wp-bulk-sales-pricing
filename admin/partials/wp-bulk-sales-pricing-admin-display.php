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
 

        if(isset($_POST['submit'])):

            $amount = $_POST['amount'];
            $criteria = $_POST['criteria'];
            $form_categories = $_POST['categories'];
            $apply_to = $_POST['apply_to'];
          
            if('All Products' == $apply_to){
                //Get All The Products in the Database that are not in the trash
                $bsp_all_products = get_all_products();
                // echo "all products";
                // print_r($bsp_all_products);

            }elseif('Specific Product' == $apply_to){
                $form_categories = $_POST['specific_products'];
                //Get All The Products in the Database that are not in the trash
                $bsp_all_products = get_all_products($form_categories, 'Yes');
                // echo "specific products";
                // print_r($bsp_all_products);
            }elseif('Specific Category' == $apply_to){
                //Get All The Products in the Database that are not in the trash
                
                $form_categories = $_POST['categories'];
                $bsp_all_products = get_all_products($form_categories);
                // echo "specific Category";
                // print_r($bsp_all_products);
            }

            //loop through the product array
                foreach($bsp_all_products as $product_id):
                    //Get the product object
                    try{
                        $product = wc_get_product( $product_id );
                    }catch(Exception $e){
                        echo 'Message: ' .$e->getMessage();
                    }

                    //Check if product exists:
                    if($product->exists()):
                        //Check if the product is variable or simple product
                        $product_type = $product->get_type();
                        //Get the regular Price
                        $regular_price = $product->get_regular_price();
                        //Get the sales price from the form
                        $sales_price = $amount;

                        //Get the product categories:
                        $product_categories = $product->get_category_ids();

                                //Check if the user supplied categories matches the product category
                        #if(in_array('All', $form_categories) || bsp_check_if_categories_exist($form_categories, $product_categories)):
                                 if( 'simple' == $product_type){
                                    
                                        //Call the set sales price function:
                                        bsp_update_sales_price($product_id, $regular_price, $sales_price, $criteria);
 
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

                                        $variable_product_regular_price = $variable_product->get_regular_price();
                                     
                                        //Call the set sales price function:
                                        bsp_update_sales_price($variable_product_id, $variable_product_regular_price, $sales_price, $criteria);
                                                
                                    endforeach;

                                }

                            endif;

                        #endif;
             
                endforeach;//end the loop
                echo "<div class=\"notice notice-success is-dismissible\"> <p><strong>Sales price was successfully updated.</strong></p></div>";

            endif;
    ?>

</pre>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="container">   
    <div class="col-left">
            
        <section class="sales-form-section">
        <div id="output"></div>
        <section class="bsp-form-header">
                <h1>Bulk Sales Pricing</h1>
            </section>
            <form action="" class="sales-form" method="POST">
                <div class="form-control">
                    <label for="criteria">Adjustment Type:</label>
                    <select required data-placeholder="Choose catgories ..." name="criteria" class="chosen-select">
                        <option value="percentage">Percentage Off</option>
                        <option value="fixed">Fixed Amount</option>
                    </select>
                </div>

                <div class="form-control">
                    <label for="amount">Value:</label>
                    <input type="text" name="amount" id="amount" value="0" placeholder="Discount Amount">
                </div>

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

                <?php submit_button("Update Sales Price(s)"); ?>

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
                    <li>Choose the <strong>Adjustment Type</strong>
                        <ul>
                            <li><strong>Percentage Off:</strong> Percentage OFF from a specific product E.G. 30% OFF</li>
                            <li><strong>Fixed Amount:</strong> A fixed amount of value to subtract from the regukar price E.G. $20 OFF</li>
                        </ul>
                    </li>
                    <li><strong>Value:</strong> If you selected Percentage OFF, the value should a percent. If the you selected fixed amount from (1.) the value should be a fixed amount to subtract from the regular price</li> 
                    <li><strong>Apply To:</strong> Gives you an option to select a product or categories to apply the sales to.
                            <ul>
                                <li><strong>All Products:</strong> This will apply the sales amount to all published products in the shop.</li>
                                <li><strong>Specific Product:</strong> Choose a specific product(s) to apply the sales price to</li>
                                <li><strong>Specific Category:</strong> Choose a specific product(s) category to apply the sales price to</li>
                            </ul>
                    </li> 
                </ol>

            </section>
    
    </div>

 </div>

 

