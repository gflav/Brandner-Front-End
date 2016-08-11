<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

  foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
    
    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
  
    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
      
      // TODO: should it?
      $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
      $product_permalink = FALSE;
      
      ?>
      <div class="row <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
  
        <div class="cart-col cart-col-first">
          
          <div class="product-thumbnail">
            <?php
            
              $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
    
              if ( ! $product_permalink ) {
                echo $thumbnail;
              } else {
                printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
              }
              
            ?>
          </div>
          
        </div>
  
        <div class="cart-col cart-col-second">
          
          <div class="product-description">
          
            <?php
    
              // product title
              $product_title = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
              echo '<div class="detail-line product-title">';
              echo $product_title;
              echo '</div>';
    
              // product meta
              $product_meta = WC()->cart->get_item_data( $cart_item );
              if(!empty($product_meta)) {
                echo '<div class="detail-line">' . $product_meta . '</div>';
              }
    
              // qty
              $qty = $cart_item['quantity'];
              echo '<div class="detail-line">Quantity: ' . $qty . '</div>';
              
              // price              
              $price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
              echo '<div class="detail-line">Price: ' . $price . '</div>';
              
            ?>
          </div>
          
        </div>
        
        <div class="cart-col cart-col-third" style="display:none;">
          
          <div class="product-remove">
            <?php
              echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                '<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
                __( 'Remove this item', 'woocommerce' ),
                esc_attr( $product_id ),
                esc_attr( $_product->get_sku() )
              ), $cart_item_key );
            ?>
          </div>
          
        </div>
    
      </div>
      
      <?php
      
        $subtotal = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
      
    } // if
    
  } // foreach
  