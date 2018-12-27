<?php

    /* 
    * This function retrieves all the product categories
    */

    function get_all_product_categories(){
        $taxonomy     = 'product_cat';
        $orderby      = 'name';  
        $show_count   = 0;      // 1 for yes, 0 for no
        $pad_counts   = 0;      // 1 for yes, 0 for no
        $hierarchical = 1;      // 1 for yes, 0 for no  
        $title        = '';  
        $empty        = 0;
    
        $args = array(
                'taxonomy'     => $taxonomy,
                'orderby'      => $orderby,
                'show_count'   => $show_count,
                'pad_counts'   => $pad_counts,
                'hierarchical' => $hierarchical,
                'title_li'     => $title,
                'hide_empty'   => $empty
        );
        $all_categories = get_categories($args);
        return $all_categories;
    }


    /* 
    * This function updates the sales price of a product
    */

    function bsp_update_sales_price($product, $regular_price, $sales_price, $criteria){


        if( 'percentage' == $criteria){

            $new_sales_price = bsp_get_percent_off($sales_price, $regular_price);

            //Check if the regular price is greater than the sales price:
            if($regular_price > 0 && $regular_price > $new_sales_price){
                update_post_meta($product, '_sale_price',$new_sales_price);
                update_post_meta($product, '_price', $new_sales_price);
                wc_delete_product_transients($product);
                
             }

        }elseif('fixed' == $criteria){
            //Check if the regular price is greater than the sales price:
            if($regular_price > 0 && $regular_price > $sales_price){
                 $fixed_sales_price = (int)$regular_price - (int)$sales_price;
 
                update_post_meta($product, '_sale_price',$fixed_sales_price);
                update_post_meta($product, '_price', $fixed_sales_price);
                wc_delete_product_transients($product);

             }

        }
    }
    
    /* Calculate the percentage OFF a product */
    function bsp_get_percent_off($percentage, $original_price){
        $result =  $original_price - (($percentage / 100) * $original_price);
        return round($result, 3);
    }


   

    /* Get all the published products from the wp_post table */
    function get_all_products($form_categories = 'All', $products = 'No'){
        $args = array(
            'numberposts' => -1,
            'post_status' => 'published',
            'orderby' => 'date',
            'order' => 'DESC',
            'return' => 'ids',
        );

        //If the user does not select All Categories
        if(is_array($form_categories)) {
            if(!in_array('All', $form_categories) && 'No' == $products){
                //Add a conditional clause to filter categories
                $query_params = array('category' => $form_categories);
                //Merge the new array with the exists array
                $args = array_merge($args, $query_params);
            }elseif('Yes' == $products){
                 //Add a conditional clause to filter categories
                 $query_params = array('include' => $form_categories);
                 //Merge the new array with the exists array
                 $args = array_merge($args, $query_params);
            }
        }

        $query = new WC_Product_Query($args);
        $products = $query->get_products();
         return $products;
    }


    /* Check if a product category exists */
    function bsp_check_if_categories_exist($form_categories, $product_categories){

        foreach($form_categories as $category):
            $status = in_array($category, $product_categories);

            if($status):
                return true;
                break;
            endif;
            
        endforeach;

    }


    /* Delete the sale price */
    function bsp_delete_sales_price($product,$regular_price){
        update_post_meta($product, '_sale_price','');
        update_post_meta($product, '_price', $regular_price);
        wc_delete_product_transients($product);
    }