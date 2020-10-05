<?php

namespace NinjaForms\Blocks\Authentication;

/**
 * Creates an encoded public/private key hash and validates it.
 */
class Token {

    /** @var string */
    protected $privateKey;

    /**
     * @param string $privateKey
     */
    public function __construct( $privateKey ) {
        $this->privateKey = $privateKey;
    }

    /**
     * @param string $publicKey
     * 
     * @return string
     */
    public function create( $publicKey ) {
        return base64_encode( $this->hash( $publicKey ) . ':' . $publicKey );
    }

    /**
     * @param string $token
     * 
     * @return bool
     */
    public function validate( $token ) {
        // If the token is malformed, then list() may return an undefined index error.
        // Pad the exploded array to add missing indexes, see https://www.php.net/manual/en/function.list.php#113189.
        list( $hash, $publicKey ) = array_pad( explode( ':', base64_decode( $token ) ), 2, false );

        return hash_equals( $hash, $this->hash( $publicKey ) );
    }

    /**
     * @param string $publicKey
     * 
     * @return string
     */
    protected function hash( $publicKey ) {
        return hash( 'sha256', $this->privateKey.$publicKey );
    }
}