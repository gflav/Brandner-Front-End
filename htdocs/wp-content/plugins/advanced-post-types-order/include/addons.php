<?php

    //woocomerce archive fix
    add_action ('apto_object_order_update', 'wooc_apto_order_update_hierarchical', 10);
    function wooc_apto_order_update_hierarchical($data)
        {
            global $wpdb, $blog_id;
           
            $sort_view_id       =   $data['sort_view_id'];
            $sort_view_settings =   APTO_functions::get_sort_view_settings($sort_view_id);
            
            $sort_view_data     =   get_post($sort_view_id);
            $sortID             =   $sort_view_data->post_parent; 
                       
            //return if not woocommerce
            if (APTO_functions::is_woocommerce($sortID) === FALSE )
                return;
                                
            // Clear product specific transients
            $post_transients_to_clear = array(
                                                //old field name
                                                'wc_product_children_ids_',
                                                
                                                //new field name
                                                'wc_product_children_'
                                            );

            foreach( $post_transients_to_clear as $transient ) 
                {
                    delete_transient( $transient . $data['post_id'] );
                    $wpdb->query( $wpdb->prepare( "DELETE FROM `$wpdb->options` WHERE `option_name` = %s OR `option_name` = %s", '_transient_' . $transient . $data['post_id'], '_transient_timeout_' . $transient . $data['post_id'] ) );
                }

            clean_post_cache( $data['post_id'] );
        }
        
    //woocommerce grouped / simple icons
    add_filter ('apto_reorder_item_additional_details', 'wooc_apto_reorder_item_additional_details', 10, 2);
    function wooc_apto_reorder_item_additional_details($additiona_details, $post_data)
        {
            if ($post_data->post_type != "product" || !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
                return $additiona_details;
            
            //to be updated
                            
            return $additiona_details;
        }
        
        
    //ignore the gallery edit images order which is set locally, independent from images archvie order
    add_filter('ajax_query_attachments_args', 'apto_ajax_query_attachments_args', 99);
    function apto_ajax_query_attachments_args($query)
        {
            $query['ignore_custom_sort'] = TRUE;

            return $query;    
        }
        
    //Shopp plugin compatibility    
    add_filter('shopp_collection_query', 'apto_shopp_collection_query');
    function apto_shopp_collection_query($options)
        {
            $orderby = shopp_setting('product_image_orderby');
            if($orderby !=  "sortorder")
                return $options;
            
            //create a csutom query then use the results as order
            $argv =     array(
                                'post_type'         =>  'shopp_product',
                                'posts_per_page'    =>  -1,
                                'fields'            =>  'ids'
                                );
            
            if(isset($options['joins']['wp_term_taxonomy']))
                {
                    preg_match('/.*tt.term_id=([0-9]+)?.*/i', $options['joins']['wp_term_taxonomy'], $matches);
                    if(isset($matches[1]))
                        {
                            $term_id = $matches[1];
                            
                            $argv['tax_query'] = array(
                                                            array(
                                                                'taxonomy' => 'shopp_category',
                                                                'field'    => 'term_id',
                                                                'terms'    => array($term_id),
                                                            ),
                                                        );    
                        }
                }
                
            $custom_query   =   new WP_Query($argv);
            if(!$custom_query->have_posts())
                return $options;    
            
            $posts_list =    $custom_query->posts;
            
            if(count($posts_list) > 0)
                {
                    global $wpdb; 
                    
                    $options['orderby'] =   " FIELD(p.ID, ". implode(",", $posts_list) .") ASC";
                    
                }
            
            return $options;
        }

?>