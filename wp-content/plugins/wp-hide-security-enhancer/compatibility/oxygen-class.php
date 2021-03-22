<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    if(!class_exists('OXYGEN_VSB_Signature'))
        {
            include_once( ABSPATH . 'wp-content/plugins/oxygen/functions.php' );
        }
    
    Class WPH_OXYGEN_VSB_Signature extends OXYGEN_VSB_Signature
        {
            
            private $option_name = 'oxygen_private_key';

            private $shortcode_arg_prefix = 'ct_sign_';

            private $private_key = null;

            private $key_length = 32;

            private $algo = 'sha256';
            
            
            
            
            /**
             * Load the Oxygen private key
             * If it does not exist it will be generated
             *
             * @return string Oxygen private key
             */
            function get_key() {
                if ( $this->private_key !== null ) {
                    // Only query for key once per request
                    return $this->private_key;
                } else {
                    $key = get_option( $this->option_name, false );
                    if ( false === $key ) {
                        // If we don't have an existing key, create one
                        $key = $this->generate_key();
                    }
                    $this->private_key = $key;
                }
                return $key;
            }
        
            /**
            * Generate a hash/signature of the name, args, and content
            *
            * @param null $name
            * @param array $args
            * @param null $content
            *
            * @return false|string
            */
            function generate_signature( $name = null, $args = array(), $content = null ) 
                {
                    $key = $this->get_key();
                    // Extract signature from args
                    $signature_arg = $this->get_shortcode_signature_arg();
                    if ( !empty( $args[ $signature_arg ] ) ) {
                        unset( $args[ $signature_arg ] );
                    }
                    
                    global $wph;
                    
                    $replacement_list   =   $wph->functions->get_replacement_list();
                    $replacement_list   =   array_flip($replacement_list);
                    
                    if ( ! empty ( $content ) )
                        $content    =   $wph->functions->content_urls_replacement( $content,  $replacement_list );
                    
                    if ( isset( $args['ct_options'] )   &&  ! empty ( $args['ct_options'] ) )
                        $args['ct_options'] =   $wph->functions->content_urls_replacement( $args['ct_options'],  $replacement_list );
                    
                    // Generally the hash is checked against data from the DB which will be unslashed so we normalize here.
                    $hash = hash_hmac( $this->algo, serialize( array( $name, wp_unslash( $args ), wp_unslash( $content ) ) ), $key );

                    return $hash;
                }   
            
            
        }


?>