<?php

/**
 * WC_Gateway_Braintree_AngellEYE class.
 *
 * @extends WC_Payment_Gateway
 */
class WC_Gateway_Braintree_AngellEYE extends WC_Payment_Gateway {

    /**
     * Constuctor
     */
    function __construct() {
        $this->id = 'braintree';
        $this->icon = apply_filters('woocommerce_braintree_icon', plugins_url('/assets/images/cards.png', plugin_basename(dirname(__FILE__))));
        $this->has_fields = true;
        $this->method_title = 'Braintree';
        $this->method_description = __('Credit Card payments Powered by PayPal / Braintree.', 'paypal-for-woocommerce');
        $this->supports = array(
            'products',
            'refunds'
        );
        $this->init_form_fields();
        $this->init_settings();
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->enabled = $this->get_option('enabled');
        $this->sandbox = $this->get_option('sandbox');
        $this->environment = $this->sandbox == 'no' ? 'production' : 'sandbox';
        $this->merchant_id = $this->sandbox == 'no' ? $this->get_option('merchant_id') : $this->get_option('sandbox_merchant_id');
        $this->private_key = $this->sandbox == 'no' ? $this->get_option('private_key') : $this->get_option('sandbox_private_key');
        $this->public_key = $this->sandbox == 'no' ? $this->get_option('public_key') : $this->get_option('sandbox_public_key');
        $this->enable_braintree_drop_in = $this->get_option('enable_braintree_drop_in') === "yes" ? true : false;
        $this->merchant_account_id = $this->sandbox == 'no' ? $this->get_option('merchant_account_id') : $this->get_option('sandbox_merchant_account_id');
        $this->debug = isset($this->settings['debug']) && $this->settings['debug'] == 'yes' ? true : false;
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        $this->response = '';
        if ($this->enable_braintree_drop_in) {
            add_action('wp_enqueue_scripts', array($this, 'payment_scripts'), 0);
        }
        add_action('admin_notices', array($this, 'checks'));
    }

    /**
     * Admin Panel Options
     */
    public function admin_options() {
        ?>
        <h3><?php _e('Braintree', 'paypal-for-woocommerce'); ?></h3>
        <p><?php _e($this->method_description, 'paypal-for-woocommerce'); ?></p>
        <table class="form-table">
            <?php $this->generate_settings_html(); ?>
            <script type="text/javascript">
                jQuery('#woocommerce_braintree_sandbox').change(function () {
                    sandbox = jQuery('#woocommerce_braintree_sandbox_public_key, #woocommerce_braintree_sandbox_private_key, #woocommerce_braintree_sandbox_merchant_id, #woocommerce_braintree_sandbox_merchant_account_id').closest('tr'),
                    production = jQuery('#woocommerce_braintree_public_key, #woocommerce_braintree_private_key, #woocommerce_braintree_merchant_id, #woocommerce_braintree_merchant_account_id').closest('tr');
                    if (jQuery(this).is(':checked')) {
                        sandbox.show();
                        production.hide();
                    } else {
                        sandbox.hide();
                        production.show();
                    }
                }).change();
            </script>
        </table> <?php
    }

    /**
     * Check if SSL is enabled and notify the user
     */
    public function checks() {
        if ($this->enabled == 'no') {
            return;
        }
        if (version_compare(phpversion(), '5.2.1', '<')) {
            echo '<div class="error"><p>' . sprintf(__('Braintree Error: Braintree requires PHP 5.2.1 and above. You are using version %s.', 'paypal-for-woocommerce'), phpversion()) . '</p></div>';
        } 
        if ('no' == get_option('woocommerce_force_ssl_checkout') && !class_exists('WordPressHTTPS') && $this->enable_braintree_drop_in == false && $this->sandbox == 'no') {
            echo '<div class="error"><p>' . sprintf(__('Braintree is enabled, but the <a href="%s">force SSL option</a> is disabled; your checkout may not be secure! Please enable SSL and ensure your server has a valid SSL certificate - Braintree custome credit card UI will only work in sandbox mode.', 'paypal-for-woocommerce'), admin_url('admin.php?page=wc-settings&tab=checkout')) . '</p></div>';
        }
        $this->add_dependencies_admin_notices();
    }

    /**
     * Check if this gateway is enabled
     */
    public function is_available() {
        if ('yes' != $this->enabled) {
            return false;
        }
        if (!$this->merchant_id || !$this->public_key || !$this->private_key) {
            return false;
        }
        return true;
    }

    public function validate_fields() {
        if (!$this->enable_braintree_drop_in) {
            try {
                $card = $this->get_posted_card();
                if (empty($card->exp_month) || empty($card->exp_year)) {
                    throw new Exception(__('Card expiration date is invalid', 'paypal-for-woocommerce'));
                }
                if (!ctype_digit($card->cvc)) {
                    throw new Exception(__('Card security code is invalid (only digits are allowed)', 'paypal-for-woocommerce'));
                }
                if (!ctype_digit($card->exp_month) || !ctype_digit($card->exp_year) || $card->exp_month > 12 || $card->exp_month < 1 || $card->exp_year < date('y')) {
                    throw new Exception(__('Card expiration date is invalid', 'paypal-for-woocommerce'));
                }
                if (empty($card->number) || !ctype_digit($card->number)) {
                    throw new Exception(__('Card number is invalid', 'paypal-for-woocommerce'));
                }
                return true;
            } catch (Exception $e) {
                wc_add_notice($e->getMessage(), 'error');
                return false;
            }
            return true;
        } else {
            try {
                if (isset($_POST['braintree_token']) && !empty($_POST['braintree_token'])) {
                    return true;
                } else {
                    throw new Exception(__('Braintree payment method nonce is empty', 'paypal-for-woocommerce'));
                }
            } catch (Exception $e) {
                wc_add_notice($e->getMessage(), 'error');
                return false;
            }
            return true;
        }
    }

    /**
     * Initialise Gateway Settings Form Fields
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'paypal-for-woocommerce'),
                'label' => __('Enable Braintree Payment Gateway', 'paypal-for-woocommerce'),
                'type' => 'checkbox',
                'description' => '',
                'default' => 'no'
            ),
            'title' => array(
                'title' => __('Title', 'paypal-for-woocommerce'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'paypal-for-woocommerce'),
                'default' => __('Credit Card', 'paypal-for-woocommerce'),
                'desc_tip' => true
            ),
            'description' => array(
                'title' => __('Description', 'paypal-for-woocommerce'),
                'type' => 'textarea',
                'description' => __('This controls the description which the user sees during checkout.', 'paypal-for-woocommerce'),
                'default' => 'Pay securely with your credit card.',
                'desc_tip' => true
            ),
            'enable_braintree_drop_in' => array(
                'title' => __('Enable Drop-in Payment UI', 'paypal-for-woocommerce'),
                'label' => __('Enable Drop-in Payment UI', 'paypal-for-woocommerce'),
                'type' => 'checkbox',
                'description' => __('Rather than showing a credit card form on your checkout, this shows the form on it\'s own page, thus making the process more secure and more PCI friendly.', 'paypal-for-woocommerce'),
                'default' => 'yes'
            ),
            'sandbox' => array(
                'title' => __('Sandbox', 'paypal-for-woocommerce'),
                'label' => __('Enable Sandbox Mode', 'paypal-for-woocommerce'),
                'type' => 'checkbox',
                'description' => __('Place the payment gateway in sandbox mode using sandbox API keys (real payments will not be taken).', 'paypal-for-woocommerce'),
                'default' => 'yes'
            ),
            'sandbox_public_key' => array(
                'title' => __('Sandbox Public Key', 'paypal-for-woocommerce'),
                'type' => 'password',
                'description' => __('Get your API keys from your Braintree account.', 'paypal-for-woocommerce'),
                'default' => '',
                'desc_tip' => true
            ),
            'sandbox_private_key' => array(
                'title' => __('Sandbox Private Key', 'paypal-for-woocommerce'),
                'type' => 'password',
                'description' => __('Get your API keys from your Braintree account.', 'paypal-for-woocommerce'),
                'default' => '',
                'desc_tip' => true
            ),
            'sandbox_merchant_id' => array(
                'title' => __('Sandbox Merchant ID', 'paypal-for-woocommerce'),
                'type' => 'password',
                'description' => __('Get your API keys from your Braintree account.', 'paypal-for-woocommerce'),
                'default' => '',
                'desc_tip' => true
            ),
            'sandbox_merchant_account_id' => array (
                 'type' => 'text',
                 'default' => '',
                 'title' => __ ( 'Sandbox Merchant Account Id', 'paypal-for-woocommerce' ),
                 'tool_tip' => true,
                 'description' => __ ( 'NOTE: Not to be confused with the API key Merchant ID. The Merchant Account ID determines the currency that the payment is settled in. You can find your Merchant Account Id by logging into Braintree,
                                 and clicking Settings > Processing and scrolling to the bottom of the page.', 'paypal-for-woocommerce' ) 
             ),
            'public_key' => array(
                'title' => __('Live Public Key', 'paypal-for-woocommerce'),
                'type' => 'password',
                'description' => __('Get your API keys from your Braintree account.', 'paypal-for-woocommerce'),
                'default' => '',
                'desc_tip' => true
            ),
            'private_key' => array(
                'title' => __('Live Private Key', 'paypal-for-woocommerce'),
                'type' => 'password',
                'description' => __('Get your API keys from your Braintree account.', 'paypal-for-woocommerce'),
                'default' => '',
                'desc_tip' => true
            ),
            'merchant_id' => array(
                'title' => __('Live Merchant ID', 'paypal-for-woocommerce'),
                'type' => 'password',
                'description' => __('Get your API keys from your Braintree account.', 'paypal-for-woocommerce'),
                'default' => '',
                'desc_tip' => true
            ),
            'merchant_account_id' => array (
                 'type' => 'text',
                 'default' => '',
                 'title' => __ ( 'Live Merchant Account Id', 'paypal-for-woocommerce' ),
                 'tool_tip' => true,
                 'description' => __ ( 'NOTE: Not to be confused with the API key Merchant ID. The Merchant Account ID determines the currency that the payment is settled in. You can find your Merchant Account Id by logging into Braintree,
                                 and clicking Settings > Processing and scrolling to the bottom of the page.', 'paypal-for-woocommerce' ) 
             ),
            'debug' => array(
                'title' => __('Debug Log', 'paypal-for-woocommerce'),
                'type' => 'checkbox',
                'label' => __('Enable logging', 'paypal-for-woocommerce'),
                'default' => 'no',
                'description' => sprintf( __( 'Log PayPal/Braintree events, inside <code>%s</code>', 'paypal-for-woocommerce' ), wc_get_log_file_path( 'braintree' ) )
            )
        );
    }

    public function payment_fields() {
        
        global $woocommerce;
        $this->angelleye_braintree_lib();
        $this->add_log('Begin Braintree_ClientToken::generate Request');
        if ($this->enable_braintree_drop_in) {
            try {
                $clientToken = Braintree_ClientToken::generate();
            } catch (Braintree_Exception_Authentication $e ) {
                wc_add_notice(__("Error processing checkout. Please try again. ", 'paypal-for-woocommerce'), 'error');
                $this->add_log("Braintree_ClientToken::generate Exception: API keys are incorrect, Please double-check that you haven't accidentally tried to use your sandbox keys in production or vice-versa.");
                wp_redirect($woocommerce->cart->get_cart_url());
                exit;
            } catch (Braintree_Exception_Authorization $e) {
                wc_add_notice(__("Error processing checkout. Please try again. ", 'paypal-for-woocommerce'), 'error');
                $this->add_log("Braintree_ClientToken::generate Exception: The API key that you're using is not authorized to perform the attempted action according to the role assigned to the user who owns the API key.");
                wp_redirect($woocommerce->cart->get_cart_url());
                exit;
            } catch (Braintree_Exception_DownForMaintenance $e) {
                wc_add_notice(__("Error processing checkout. Please try again. ", 'paypal-for-woocommerce'), 'error');
                $this->add_log("Braintree_ClientToken::generate Exception: Request times out.");
                wp_redirect($woocommerce->cart->get_cart_url());
                exit;
            } catch (Braintree_Exception_ServerError $e) {
                wc_add_notice(__("Error processing checkout. Please try again. ", 'paypal-for-woocommerce'), 'error');
                $this->add_log("Braintree_ClientToken::generate Braintree_Exception_ServerError" . $e->getMessage());
                wp_redirect($woocommerce->cart->get_cart_url());
                exit;
            } catch (Braintree_Exception_SSLCertificate $e) {
                wc_add_notice(__("Error processing checkout. Please try again. ", 'paypal-for-woocommerce'), 'error');
                $this->add_log("Braintree_ClientToken::generate Braintree_Exception_SSLCertificate" . $e->getMessage());
                wp_redirect($woocommerce->cart->get_cart_url());
                exit;
            } catch (Exception $ex) {
                $this->add_log("Braintree_ClientToken::generate Exception:" . $ex->getMessage());
                wp_redirect($woocommerce->cart->get_cart_url());
                exit;
            }
            
        }
        if ($this->description) {
            echo wpautop(wptexturize($this->description));
        }
        if ($this->enable_braintree_drop_in) {
            ?>
            <div id="braintree-cc-form">
                <fieldset>
                    <div id="braintree-payment-form"></div>
                </fieldset>
            </div>
            <script>
                var $form = jQuery('form.checkout, #order_review');
                var ccForm = jQuery('form.checkout');
                var clientToken = "<?php echo $clientToken; ?>";
                braintree.setup(clientToken, "dropin", {
                    container: "braintree-payment-form",
                    onReady : function() {
                        jQuery.each(jQuery('#braintree-payment-form').children('iFrame'),
                        function(index) {
                                if (index > 0) {
                                        jQuery(this).remove();
                                }
                        });
                    },
                    onError: function (a) {
                        if ("VALIDATION" === a.type) {
                            if (is_angelleye_braintree_selected()) {
                                console.log("configuration error " + a.message);
                                jQuery( '.woocommerce-error, .braintree-token', ccForm ).remove();
                                ccForm.prepend('<ul class="woocommerce-error"><li>' + a.message + '</li></ul>');
                                return $form.unblock();
                            }
                        } else {
                            jQuery( '.woocommerce-error, .braintree-token', ccForm ).remove();
                            ccForm.prepend('<ul class="woocommerce-error"><li>' + a.message + '</li></ul>');
                            console.log("configuration error " + a.message);
                            return $form.unblock();
                        }
                    },
                    onPaymentMethodReceived: function (obj) {
                        braintreeResponseHandler(obj);
                    }
                });

                function is_angelleye_braintree_selected() {
                    if (jQuery('#payment_method_braintree').is(':checked')) {
                        return true;
                    } else {
                        return false;
                    }
                }
                function braintreeResponseHandler(obj) {
                    var $form = jQuery('form.checkout, #order_review'),
                            ccForm = jQuery('#braintree-cc-form');
                    if (obj.nonce) {
                        ccForm.append('<input type="hidden" class="braintree-token" name="braintree_token" value="' + obj.nonce + '"/>');
                        $form.submit();
                    }
                }
                jQuery('form.checkout').on('checkout_place_order_braintree', function () {
                    return braintreeFormHandler();
                });
                function braintreeFormHandler() {
                    if (jQuery('#payment_method_braintree').is(':checked')) {
                        if (0 === jQuery('input.braintree-token').size()) {
                            return false;
                        }
                    }
                    return true;
                }
            </script>
            <?php
        } else {
            if(class_exists('WC_Payment_Gateway_CC')) {
                $cc_form = new WC_Payment_Gateway_CC;
                $cc_form->id       = $this->id;
                $cc_form->supports = $this->supports;
                $cc_form->form();
            } else {
                $this->credit_card_form();
            }
        }
    }

    private function get_posted_card() {
        $card_number = isset($_POST['braintree-card-number']) ? wc_clean($_POST['braintree-card-number']) : '';
        $card_cvc = isset($_POST['braintree-card-cvc']) ? wc_clean($_POST['braintree-card-cvc']) : '';
        $card_expiry = isset($_POST['braintree-card-expiry']) ? wc_clean($_POST['braintree-card-expiry']) : '';
        $card_number = str_replace(array(' ', '-'), '', $card_number);
        $card_expiry = array_map('trim', explode('/', $card_expiry));
        $card_exp_month = str_pad($card_expiry[0], 2, "0", STR_PAD_LEFT);
        $card_exp_year = isset($card_expiry[1]) ? $card_expiry[1] : '';
        if (strlen($card_exp_year) == 2) {
            $card_exp_year += 2000;
        }
        return (object) array(
                    'number' => $card_number,
                    'type' => '',
                    'cvc' => $card_cvc,
                    'exp_month' => $card_exp_month,
                    'exp_year' => $card_exp_year,
        );
    }

    /**
     * Process the payment
     */
    public function process_payment($order_id) {
        $order = new WC_Order($order_id);
        $success = $this->angelleye_do_payment($order);
        if($success == true) {
            return array(
                'result' => 'success',
                'redirect' => $this->get_return_url($order)
            );
        } else {
            WC()->session->reload_checkout = true;
            return array(
                'result'   => 'fail',
                'redirect' => ''
            );
        }
    }

    public function angelleye_do_payment($order) {
        $success = true;
        global $woocommerce;
        try {
            if ($this->enable_braintree_drop_in) {
                $payment_method_nonce = self::get_posted_variable( 'braintree_token' );
		if ( empty( $payment_method_nonce ) ) {
                    $this->add_log("Error: The payment_method_nonce was unexpectedly empty" );
                    wc_add_notice( __( 'Error: PayPal Powered by Braintree did not supply a payment nonce. Please try again later or use another means of payment.', 'paypal-for-woocommerce' ), 'error' );
                    return false;
		}
            }
            $request_data = array();
            $this->angelleye_braintree_lib();
            $card = $this->get_posted_card();
            $request_data['billing'] = array(
                'firstName' => $order->billing_first_name,
                'lastName' => $order->billing_last_name,
                'company' => $order->billing_company,
                'streetAddress' => $order->billing_address_1,
                'extendedAddress' => $order->billing_address_2,
                'locality' => $order->billing_city,
                'region' => $order->billing_state,
                'postalCode' => $order->billing_postcode,
                'countryCodeAlpha2' => $order->billing_country,
            );
            $request_data['shipping'] = array(
                'firstName' => $order->shipping_first_name,
                'lastName' => $order->shipping_last_name,
                'company' => $order->shipping_company,
                'streetAddress' => $order->shipping_address_1,
                'extendedAddress' => $order->shipping_address_2,
                'locality' => $order->shipping_city,
                'region' => $order->shipping_state,
                'postalCode' => $order->shipping_postcode,
                'countryCodeAlpha2' => $order->shipping_country,
            );
            if ($this->enable_braintree_drop_in == false) {
                $request_data['creditCard'] = array(
                    'number' => $card->number,
                    'expirationDate' => $card->exp_month . '/' . $card->exp_year,
                    'cvv' => $card->cvc,
                    'cardholderName' => $order->billing_first_name . ' ' . $order->billing_last_name
                );
            } else {
                $request_data['paymentMethodNonce'] = $payment_method_nonce;
            }
            $request_data['customer'] = array(
                'firstName' => $order->billing_first_name,
                'lastName' => $order->billing_last_name,
                'company' => $order->billing_company,
                'phone' => $order->billing_phone,
                'email' => $order->billing_email,
            );
            $request_data['amount'] = number_format($order->get_total(), 2, '.', '');
            if( isset($this->merchant_account_id) && !empty($this->merchant_account_id)) {
                 $request_data['merchantAccountId'] = $this->merchant_account_id;
             }
            $request_data['orderId'] = $order->get_order_number();
            $request_data['options'] = $this->get_braintree_options();
            $request_data['channel'] = 'AngellEYEPayPalforWoo_BT';
            if ($this->debug) {
                $this->add_log('Begin Braintree_Transaction::sale request');
                $this->add_log('Order: ' . print_r($order->get_order_number(), true));
                $log = $request_data;
                if ($this->enable_braintree_drop_in == false) {
                    $log['creditCard'] = array(
                        'number' => '**** **** **** ****',
                        'expirationDate' => '**' . '/' . '****',
                        'cvv' => '***'
                    );
                } else {
                    $log['paymentMethodNonce'] = '*********************';
                }
                $this->add_log('Braintree_Transaction::sale Reuest Data ' . print_r($log, true));
            }
            
            try {
                $this->response = Braintree_Transaction::sale($request_data);
            } catch (Braintree_Exception_Authentication $e ) {
                wc_add_notice(__("Error processing checkout. Please try again. ", 'paypal-for-woocommerce'), 'error');
                $this->add_log("Braintree_Transaction::sale Braintree_Exception_Authentication: API keys are incorrect, Please double-check that you haven't accidentally tried to use your sandbox keys in production or vice-versa.");
                return $success = false;
            } catch (Braintree_Exception_Authorization $e) {
                wc_add_notice(__("Error processing checkout. Please try again. ", 'paypal-for-woocommerce'), 'error');
                $this->add_log("Braintree_Transaction::sale Braintree_Exception_Authorization: The API key that you're using is not authorized to perform the attempted action according to the role assigned to the user who owns the API key.");
                return $success = false;
            } catch (Braintree_Exception_DownForMaintenance $e) {
                wc_add_notice(__("Error processing checkout. Please try again. ", 'paypal-for-woocommerce'), 'error');
                $this->add_log("Braintree_Transaction::sale Braintree_Exception_DownForMaintenance: Request times out.");
                return $success = false;
            } catch (Braintree_Exception_ServerError $e) {
                wc_add_notice(__("Error processing checkout. Please try again. ", 'paypal-for-woocommerce'), 'error');
                $this->add_log("Braintree_Transaction::sale Braintree_Exception_ServerError " . $e->getMessage());
                return $success = false;
            } catch (Braintree_Exception_SSLCertificate $e) {
                wc_add_notice(__("Error processing checkout. Please try again. ", 'paypal-for-woocommerce'), 'error');
                $this->add_log("Braintree_Transaction::sale Braintree_Exception_SSLCertificate " . $e->getMessage());
                return $success = false;
            } catch (Exception $e) {
                wc_add_notice( __( 'Error: PayPal Powered by Braintree was unable to complete the transaction. Please try again later or use another means of payment.', 'paypal-for-woocommerce' ), 'error' );
                $this->add_log('Error: Unable to complete transaction. Reason: ' . $e->getMessage() );
                return $success = false;
            }
            
            if ( !$this->response->success ) {
                $notice = sprintf( __( 'Error: PayPal Powered by Braintree was unable to complete the transaction. Please try again later or use another means of payment. Reason: %s', 'paypal-for-woocommerce' ), $this->response->message );
                wc_add_notice( $notice, 'error' );
                $this->add_log( "Error: Unable to complete transaction. Reason: {$this->response->message}" );
                return $success = false;
            }
            
            $this->add_log('Braintree_Transaction::sale Response code: ' . print_r($this->get_status_code(), true));
            $this->add_log('Braintree_Transaction::sale Response message: ' . print_r($this->get_status_message(), true));
            
            $maybe_settled_later = array(
			'settling',
			'settlement_pending',
			'submitted_for_settlement',
		);
            
            if (in_array( $this->response->transaction->status, $maybe_settled_later )) {
                $is_sandbox = $this->sandbox == 'no' ? false : true;
                update_post_meta($order->id, 'is_sandbox', $is_sandbox);
                $order->payment_complete($this->response->transaction->id);
                $order->add_order_note(sprintf(__('%s payment approved! Trnsaction ID: %s', 'paypal-for-woocommerce'), $this->title, $this->response->transaction->id));
                WC()->cart->empty_cart();
            } else {
                $this->add_log( sprintf( 'Info: unhandled transaction id = %s, status = %s', $this->response->transaction->id, $this->response->transaction->status ) );
		$order->update_status( 'on-hold', sprintf( __( 'Transaction was submitted to PayPal Braintree but not handled by WooCommerce order, transaction_id: %s, status: %s. Order was put in-hold.', 'paypal-for-woocommerce' ), $this->response->transaction->id, $this->response->transaction->status ) );
            }
        } catch (Exception $ex) {
            wc_add_notice($ex->getMessage(), 'error');
            return $success = false;
        }
        return $success;
    }

    public function get_braintree_options() {
        return array('submitForSettlement' => true, 'storeInVaultOnSuccess' => '');
    }

    public static function multibyte_loaded() {
        return extension_loaded('mbstring');
    }

    public function process_refund($order_id, $amount = null, $reason = '') {
        $order = wc_get_order($order_id);
        if (!$order || !$order->get_transaction_id()) {
            return false;
        }
        
        $this->angelleye_braintree_lib();
        
        try {
            $transaction = Braintree_Transaction::find($order->get_transaction_id());
        } catch (Braintree_Exception_NotFound $e) {
            $this->add_log("Braintree_Transaction::find Braintree_Exception_NotFound" . $e->getMessage());
            return new WP_Error(404, $e->getMessage());
        } catch (Braintree_Exception_Authentication $e ) {
            $this->add_log("Braintree_Transaction::find Braintree_Exception_Authentication: API keys are incorrect, Please double-check that you haven't accidentally tried to use your sandbox keys in production or vice-versa.");
            return new WP_Error(404, $e->getMessage());
        } catch (Braintree_Exception_Authorization $e) {
            $this->add_log("Braintree_Transaction::find Braintree_Exception_Authorization: The API key that you're using is not authorized to perform the attempted action according to the role assigned to the user who owns the API key.");
            return new WP_Error(404, $e->getMessage());
        } catch (Braintree_Exception_DownForMaintenance $e) {
            $this->add_log("Braintree_Transaction::find Braintree_Exception_DownForMaintenance: Request times out.");
            return new WP_Error(404, $e->getMessage());
        } catch (Exception $e) {
            $this->add_log($e->getMessage());
            return new WP_Error(404, $e->getMessage());
        }
    
        if (isset($transaction->status) && $transaction->status == 'submitted_for_settlement') {
            if ($amount == $order->get_total()) {
                try {
                    $result = Braintree_Transaction::void($order->get_transaction_id());
                    if ($result->success) {
                        $order->add_order_note(sprintf(__('Refunded %s - Transaction ID: %s', 'paypal-for-woocommerce'), wc_price(number_format($amount, 2, '.', '')), $result->transaction->id));
                        return true;
                    } else {
                        $error = '';
                        foreach (($result->errors->deepAll()) as $error) {
                            return new WP_Error(404, 'ec_refund-error', $error->message);
                        }
                    }
                } catch (Braintree_Exception_NotFound $e) {
                    $this->add_log("Braintree_Transaction::void Braintree_Exception_NotFound: " . $e->getMessage());
                    return new WP_Error(404, $e->getMessage());
                } catch (Exception $ex) {
                    $this->add_log("Braintree_Transaction::void Exception: " . $e->getMessage());
                    return new WP_Error(404, $e->getMessage());
                }
                
            } else {
                return new WP_Error(404, 'braintree_refund-error', __('Oops, you cannot partially void this order. Please use the full order amount.', 'paypal-for-woocommerce'));
            }
        } elseif (isset($transaction->status) && ($transaction->status == 'settled' || $transaction->status == 'settling')) {
            try {
                $result = Braintree_Transaction::refund($order->get_transaction_id(), $amount);
                if ($result->success) {
                    $order->add_order_note(sprintf(__('Refunded %s - Transaction ID: %s', 'paypal-for-woocommerce'), wc_price(number_format($amount, 2, '.', '')), $result->transaction->id));
                    return true;
                } else {
                    $error = '';
                    foreach (($result->errors->deepAll()) as $error) {
                        return new WP_Error(404, 'ec_refund-error', $error->message);
                    }
                }
            } catch (Braintree_Exception_NotFound $e) {
                $this->add_log("Braintree_Transaction::refund Braintree_Exception_NotFound: " . $e->getMessage());
                return new WP_Error(404, $e->getMessage());
            } catch (Exception $ex) {
                $this->add_log("Braintree_Transaction::refund Exception: " . $e->getMessage());
                return new WP_Error(404, $e->getMessage());
            }
        } else {
            $this->add_log("Error: The transaction cannot be voided nor refunded in its current state: state = {$transaction->status}" );
            return new WP_Error ( 404, "Error: The transaction cannot be voided nor refunded in its current state: state = {$transaction->status}" );
        }
    }

    public function angelleye_braintree_lib() {
        try {
            require_once( 'lib/Braintree/Braintree.php' );
            Braintree_Configuration::environment($this->environment);
            Braintree_Configuration::merchantId($this->merchant_id);
            Braintree_Configuration::publicKey($this->public_key);
            Braintree_Configuration::privateKey($this->private_key);
        } catch (Exception $ex) {
            $this->add_log('Error: Unable to Load Braintree. Reason: ' . $ex->getMessage() );
            WP_Error(404, 'Error: Unable to Load Braintree. Reason: ' . $ex->getMessage());
        }
        
    }

    public function add_dependencies_admin_notices() {
        $missing_extensions = $this->get_missing_dependencies();
        if (count($missing_extensions) > 0) {
            $message = sprintf(
                    _n(
                            '%s requires the %s PHP extension to function.  Contact your host or server administrator to configure and install the missing extension.', '%s requires the following PHP extensions to function: %s.  Contact your host or server administrator to configure and install the missing extensions.', count($missing_extensions), 'paypal-for-woocommerce'
                    ), "PayPal For WooCoomerce - Braintree", '<strong>' . implode(', ', $missing_extensions) . '</strong>'
            );
            echo '<div class="error"><p>' . $message . '</p></div>';
        }
    }

    public function get_missing_dependencies() {
        $missing_extensions = array();
        foreach ($this->get_dependencies() as $ext) {
            if (!extension_loaded($ext)) {
                $missing_extensions[] = $ext;
            }
        }
        return $missing_extensions;
    }

    public function get_dependencies() {
        return array('curl', 'dom', 'hash', 'openssl', 'SimpleXML', 'xmlwriter');
    }

    public function add_log($message) {
        if ($this->debug == 'yes') {
            if (empty($this->log))
                $this->log = new WC_Logger();
            $this->log->add('braintree', $message);
        }
    }

    public function get_status_code() {
        if ($this->response->success) {
            return $this->get_success_status_info('code');
        } else {
            return $this->get_failure_status_info('code');
        }
    }

    public function get_status_message() {
        if ($this->response->success) {
            return $this->get_success_status_info('message');
        } else {
            return $this->get_failure_status_info('message');
        }
    }

    public function get_success_status_info($type) {
        $transaction = !empty($this->response->transaction) ? $this->response->transaction : $this->response->creditCardVerification;
        if (isset($transaction->processorSettlementResponseCode) && !empty($transaction->processorSettlementResponseCode)) {
            $status = array(
                'code' => $transaction->processorSettlementResponseCode,
                'message' => $transaction->processorSettlementResponseText,
            );
        } else {
            $status = array(
                'code' => $transaction->processorResponseCode,
                'message' => $transaction->processorResponseText,
            );
        }
        return isset($status[$type]) ? $status[$type] : null;
    }

    public function get_failure_status_info($type) {
        if ($this->has_validation_errors()) {
            $errors = $this->get_validation_errors();
            return implode(', ', ( 'code' === $type ? array_keys($errors) : array_values($errors)));
        }
        $transaction = !empty($this->response->transaction) ? $this->response->transaction : $this->response->creditCardVerification;
        switch ($transaction->status) {
            case 'gateway_rejected':
                $status = array(
                    'code' => $transaction->gatewayRejectionReason,
                    'message' => $this->response->message,
                );
                break;
            case 'processor_declined':
                $status = array(
                    'code' => $transaction->processorResponseCode,
                    'message' => $transaction->processorResponseText . (!empty($transaction->additionalProcessorResponse) ? ' (' . $transaction->additionalProcessorResponse . ')' : '' ),
                );
                break;
            case 'settlement_declined':
                $status = array(
                    'code' => $transaction->processorSettlementResponseCode,
                    'message' => $transaction->processorSettlementResponseText,
                );
                break;
            default:
                $status = array(
                    'code' => $transaction->status,
                    'message' => $this->response->message,
                );
        }
        return isset($status[$type]) ? $status[$type] : null;
    }

    public function has_validation_errors() {
        return isset($this->response->errors) && $this->response->errors->deepSize();
    }

    public function get_validation_errors() {
        $errors = array();
        if ($this->has_validation_errors()) {
            foreach ($this->response->errors->deepAll() as $error) {
                $errors[$error->code] = $error->message;
            }
        }
        return $errors;
    }

    public function get_user_message($message_id) {
        $message = null;
        switch ($message_id) {
            case 'error': $message = __('An error occurred, please try again or try an alternate form of payment', 'paypal-for-woocommerce');
                break;
            case 'decline': $message = __('We cannot process your order with the payment information that you provided. Please use a different payment account or an alternate payment method.', 'paypal-for-woocommerce');
                break;
            case 'held_for_review': $message = __('This order is being placed on hold for review. Please contact us to complete the transaction.', 'paypal-for-woocommerce');
                break;
            case 'held_for_incorrect_csc': $message = __('This order is being placed on hold for review due to an incorrect card verification number.  You may contact the store to complete the transaction.', 'paypal-for-woocommerce');
                break;
            case 'csc_invalid': $message = __('The card verification number is invalid, please try again.', 'paypal-for-woocommerce');
                break;
            case 'csc_missing': $message = __('Please enter your card verification number and try again.', 'paypal-for-woocommerce');
                break;
            case 'card_type_not_accepted': $message = __('That card type is not accepted, please use an alternate card or other form of payment.', 'paypal-for-woocommerce');
                break;
            case 'card_type_invalid': $message = __('The card type is invalid or does not correlate with the credit card number.  Please try again or use an alternate card or other form of payment.', 'paypal-for-woocommerce');
                break;
            case 'card_type_missing': $message = __('Please select the card type and try again.', 'paypal-for-woocommerce');
                break;
            case 'card_number_type_invalid': $message = __('The card type is invalid or does not correlate with the credit card number.  Please try again or use an alternate card or other form of payment.', 'paypal-for-woocommerce');
                break;
            case 'card_number_invalid': $message = __('The card number is invalid, please re-enter and try again.', 'paypal-for-woocommerce');
                break;
            case 'card_number_missing': $message = __('Please enter your card number and try again.', 'paypal-for-woocommerce');
                break;
            case 'card_expiry_invalid': $message = __('The card expiration date is invalid, please re-enter and try again.', 'paypal-for-woocommerce');
                break;
            case 'card_expiry_month_invalid': $message = __('The card expiration month is invalid, please re-enter and try again.', 'paypal-for-woocommerce');
                break;
            case 'card_expiry_year_invalid': $message = __('The card expiration year is invalid, please re-enter and try again.', 'paypal-for-woocommerce');
                break;
            case 'card_expiry_missing': $message = __('Please enter your card expiration date and try again.', 'paypal-for-woocommerce');
                break;
            case 'bank_aba_invalid': $message_id = __('The bank routing number is invalid, please re-enter and try again.', 'paypal-for-woocommerce');
                break;
            case 'bank_account_number_invalid': $message_id = __('The bank account number is invalid, please re-enter and try again.', 'paypal-for-woocommerce');
                break;
            case 'card_expired': $message = __('The provided card is expired, please use an alternate card or other form of payment.', 'paypal-for-woocommerce');
                break;
            case 'card_declined': $message = __('The provided card was declined, please use an alternate card or other form of payment.', 'paypal-for-woocommerce');
                break;
            case 'insufficient_funds': $message = __('Insufficient funds in account, please use an alternate card or other form of payment.', 'paypal-for-woocommerce');
                break;
            case 'card_inactive': $message = __('The card is inactivate or not authorized for card-not-present transactions, please use an alternate card or other form of payment.', 'paypal-for-woocommerce');
                break;
            case 'credit_limit_reached': $message = __('The credit limit for the card has been reached, please use an alternate card or other form of payment.', 'paypal-for-woocommerce');
                break;
            case 'csc_mismatch': $message = __('The card verification number does not match. Please re-enter and try again.', 'paypal-for-woocommerce');
                break;
            case 'avs_mismatch': $message = __('The provided address does not match the billing address for cardholder. Please verify the address and try again.', 'paypal-for-woocommerce');
                break;
        }
        return apply_filters('wc_payment_gateway_transaction_response_user_message', $message, $message_id, $this);
    }

    public function get_message() {
        $messages = array();
        $message_id = array();
        $decline_codes = array(
            'cvv' => 'csc_mismatch',
            'avs' => 'avs_mismatch',
            '2000' => 'card_declined',
            '2001' => 'insufficient_funds',
            '2002' => 'credit_limit_reached',
            '2003' => 'card_declined',
            '2004' => 'card_expired',
            '2005' => 'card_number_invalid',
            '2006' => 'card_expiry_invalid',
            '2007' => 'card_type_invalid',
            '2008' => 'card_number_invalid',
            '2010' => 'csc_mismatch',
            '2012' => 'card_declined',
            '2013' => 'card_declined',
            '2014' => 'card_declined',
            '2016' => 'error',
            '2017' => 'card_declined',
            '2018' => 'card_declined',
            '2023' => 'card_type_not_accepted',
            '2024' => 'card_type_not_accepted',
            '2038' => 'card_declined',
            '2046' => 'card_declined',
            '2056' => 'credit_limit_reached',
            '2059' => 'avs_mismatch',
            '2060' => 'avs_mismatch',
            '2075' => 'paypal_closed',
        );
        $response_codes = $this->get_validation_errors();
        if (isset($response_codes) && !empty($response_codes) && is_array($response_codes)) {
            foreach ($response_codes as $key => $value) {
                $messages[] = isset($decline_codes[$key]) ? $this->get_user_message($key) : $value;
            }
        }
        return implode(' ', $messages);
    }

    public function payment_scripts() {
        if (!is_checkout() || !$this->is_available()) {
            return;
        }
        wp_enqueue_script('braintree-gateway', 'https://js.braintreegateway.com/js/braintree-2.27.0.min.js', array(), WC_VERSION, false);
    }
    
    public static function get_posted_variable( $variable, $default = '' ) {
        return ( isset( $_POST[$variable] ) ? $_POST[$variable] : $default );
    }
    
    function get_transaction_url( $order ) {
        $transaction_id = $order->get_transaction_id();
        if ( empty( $transaction_id ) ) {
                return false;
        }
        $is_sandbox = get_post_meta($order->id, 'is_sandbox', true);
        if ( $is_sandbox  == true ) {
            $server = "sandbox.braintreegateway.com";
        } else {
            if ( empty( $is_sandbox ) ) {
                if (  $this->sandbox == 'yes' ) {
                   $server = "sandbox.braintreegateway.com";
                } else {
                    $server = "braintreegateway.com";
                }
            } else {
                $server = "braintreegateway.com";
            }
        }
        return "https://" . $server . "/merchants/" . urlencode( $this->merchant_id ). "/transactions/" . urlencode( $transaction_id );
    }
}