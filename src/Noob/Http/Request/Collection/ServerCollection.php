<?php
/**
 * Created by PhpStorm.
 * User: willy
 * Date: 3/9/14
 * Time: 7:40 AM
 */

namespace Noob\Http\Request\Collection;


class ServerCollection extends ParameterCollection {
    const basic_authorization = 'Basic';
    const digest_authorization = 'Digest';

    /**
     * Gets the HTTP headers
     *
     * @return array
     */
    public function getHeaders() {
        $headers = [];

        /**
         * Get all headers information
         */
        static $postHeaders = ['CONTENT_TYPE', 'CONTENT_LENGTH'];
        foreach($this as $key => $value) {
            if(strpos($key, 'HTTP_') === 0) {
                $headers[ucwords(strtolower(substr($key, strpos($key, '_')+1)))] = $value;
            } elseif(in_array($key, $postHeaders)) {
                $headers[ucwords(strtolower($key))] = $value;
            }
        }

        /**
         * Get authorization information
         * Basic or Digest authorization
         */
        if (isset($this['PHP_AUTH_USER'])) {
            list($headers['PHP_AUTH_USER'], $headers['PHP_AUTH_PW']) = array($this['PHP_AUTH_USER'], $this['PHP_AUTH_PW']);
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
            $headers['AUTHORIZATION'] = self::basic_authorization.' '.base64_encode($headers['PHP_AUTH_USER'].':'.$headers['PHP_AUTH_PW']);
        } elseif (isset($this['PHP_AUTH_DIGEST'])) {
            $headers['AUTHORIZATION'] = $this['PHP_AUTH_DIGEST'];
        }

        return $headers;
    }

} 