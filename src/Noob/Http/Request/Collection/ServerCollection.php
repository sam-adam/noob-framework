<?php
/**
 * Created by PhpStorm.
 * User: willy
 * Date: 3/9/14
 * Time: 7:40 AM
 */

namespace Noob\Http\Request\Collection;


class ServerCollection extends ParameterCollection {
    const basic_authorization = 'basic';
    const digest_authorization = 'digest';

    /**
     * Gets the HTTP headers
     *
     * @return array
     */
    public function getHeaders() {
        parent::exchangeArray($headers = array());

        /**
         * Check if the user is authorized
         * will populate from HTTP_AUTHORIZATION
         *
         */
        if (isset($this['PHP_AUTH_USER'])) {
            $headers['PHP_AUTH_USER'] = $this['PHP_AUTH_USER'];
            $headers['PHP_AUTH_PW'] = $this['PHP_AUTH_PW'];
        } else {
            $authorizationHeader = null;
            if (isset($this['HTTP_AUTHORIZATION'])) {
                $authorizationHeader = $this['HTTP_AUTHORIZATION'];
            } elseif (isset($this['REDIRECT_HTTP_AUTHORIZATION'])) {
                $authorizationHeader = $this['REDIRECT_HTTP_AUTHORIZATION'];
            }

            if (!is_null($authorizationHeader)) {
                if (0 === stripos($authorizationHeader, static::basic_authorization)) {
                    $exploded = explode(':', base64_decode($authorizationHeader, 6));
                    if (count($exploded) === 2) {
                        list($headers['PHP_AUTH_USER'], $headers['PHP_AUTH_PW']) = $exploded;
                    }
                } elseif (0 === stripos($authorizationHeader, static::digest_authorization)) {
                    if (empty($this['PHP_AUTH_DIGEST'])) $headers['PHP_AUTH_DIGEST'] = $this['PHP_AUTH_DIGEST'] = $authorizationHeader;
                }
            }
        }

        if (isset($headers['PHP_AUTH_USER'])) {
            $headers['AUTHORIZATION'] = 'Basic '.base64_encode($headers['PHP_AUTH_USER'].':'.$headers['PHP_AUTH_PW']);
        } else {
            $headers['AUTHORIZATION'] = $headers['PHP_AUTH_DIGEST'];
        }

        /**
         * Merge all headers with authorization information
         */
        return getallheaders() + $headers;
    }

} 