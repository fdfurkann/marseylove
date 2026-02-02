<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' ); ?>

<div id="minicart-panel">

	<form id="ajax-minicart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
    <?php wp_nonce_field( 'woocommerce-cart' ); ?>
	</form>

<?php if ( WC()->cart && ! WC()->cart->is_empty() ) : ?>

		<ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr( $args['list_class'] ); ?>">
			<?php
				do_action( 'woocommerce_before_mini_cart_contents' );

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				/**
				 * This filter is documented in woocommerce/templates/cart/cart.php.
				 *
				 * @since 2.1.0
				 */
						$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
						$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						$product_subtotal     = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

						$product_title = $product_name;

						if ( ! empty( $product_permalink ) ) {
					    $product_permalink = esc_url( $product_permalink );
					    $thumbnail = '<a href="' . $product_permalink . '">' . $thumbnail . '</a>';
					    $product_title = '<a href="' . $product_permalink . '">' . $product_name . '</a>';
						}

						?>
						<li id="et-cart-panel-item-<?php echo esc_attr( $cart_item_key ); ?>" class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
							
							<div class="et-cart-panel-item-thumbnail">
								<?php echo '<div class="et-cart-panel-thumbnail-wrap">'. $thumbnail .'<span class="et-loader"></span></div>'; ?>
							</div>
							<div class="et-cart-panel-item-details">
								<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a role="button" href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s" data-success_message="%s">&times;</a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
							/* translators: %s is the product name */
							esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
											esc_attr( $product_id ),
											esc_attr( $cart_item_key ),
							esc_attr( $_product->get_sku() ),
							/* translators: %s is the product name */
							esc_attr( sprintf( __( '&ldquo;%s&rdquo; has been removed from your cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) )
										),
										$cart_item_key
									);
									?>

								<?php echo '<span class="et-cart-panel-product-title">' . wp_kses_post( $product_title ) . '</span>'; ?>
							
								<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

								<div class="et-cart-panel-quantity-pricing">

									<?php if ( $_product->is_sold_individually() ) : ?>
								    <?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . esc_html__( 'Qty', 'woocommerce' ) . ': ' . $cart_item['quantity'] . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php else: ?>
								    <div class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
							        <?php
							        	$min_quantity = 0;
							        	$max_quantity = $_product->get_max_purchase_quantity();

					           		$product_quantity = woocommerce_quantity_input(
													array(
					                'input_name'  => "cart[{$cart_item_key}][qty]",
					                'input_value' => $cart_item['quantity'],
														'max_value'    => $max_quantity,
														'min_value'    => $min_quantity,
					                'product_name' => $product_name,
														'goya_minicart_qty' => true
													),
													$_product,
													false
												);

						            echo apply_filters( 'woocommerce_widget_cart_item_quantity', $product_quantity, $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							        ?>
								    </div>
									<?php endif; ?>

									<?php echo '<div class="et-cart-panel-item-price">' . $product_price . '</div>'; ?>

									<?php echo '<div class="et-cart-panel-item-subtotal">' . $product_subtotal . '</div>'; ?>

								</div>

							</div>
						</li>
						<?php
					}
				}

				do_action( 'woocommerce_mini_cart_contents' );
			?>
		</ul>

	<?php else : ?>

		<div class="et-cart-empty">
			<div class="empty-circle"><?php echo apply_filters( 'goya_minicart_icon', goya_load_template_part('assets/img/svg/shopping-'. get_theme_mod('header_cart_icon', 'bag').'.svg') ); ?></div>
			<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'woocommerce' ); ?></p>
		</div>

	<?php endif; ?>

	<div class="cart-panel-summary<?php if( WC()->cart->is_empty() ) { ?> empty-cart<?php } ?>">
	
		<?php if ( ! WC()->cart->is_empty() ) : ?>

			<p class="woocommerce-mini-cart__total total">
				<?php
				/**
			 * Hook: woocommerce_widget_shopping_cart_total.
				 *
				 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
				 */
				do_action( 'woocommerce_widget_shopping_cart_total' );
				?>
			</p>

			<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

			<p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>

			<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>

<?php else : ?>

			<?php do_action( 'goya_mini_cart_empty' ); ?>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_mini_cart' ); ?>

	</div>

</div>