<?php

/**
 *
 */
class UserInfo
{
    private $browserInfo;
    private $geoInfo;

    /*
     * Auto load information form external services
     */
    public function __construct()
    {
        try {
            $this->browserInfo = get_browser($_SERVER['HTTP_USER_AGENT'], true);

        }
        catch (Exception $e) {
            $this->browserInfo = array();
        }

        try {
            $this->geoInfo = $this->getGeoInfo();

            if (!is_array()) {
                throw new Exception('Did not get a valid JSON respose',1);
            }
        }
        catch(Exception $e) {
            $this->geoInfo = array();
        }
    }

    public function getIP() {
        return $_SERVER['REMOTE_ADDR'];
    }


    public function getReverseDNS() {
        return gethostbyaddr($_SERVER['REMORE_ADDR']);
    }


    public function getCurrentURL() {
        return 'http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 's':'')
            .'://'.$_SERVER["SERVER_NAME"]
            .($_SERVER['SERVER_PORT'] !='80' ? $_SERVER['SERVER_PORT'] : '')
            .$_SERVER["REQUEST_URL"];
    }


    public function getRefererURL() {
        return (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
    }


    public function getLanguage() {
        return strtoupper(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
    }


    /**
     * Get user OS info
     *  @return string
     */
    public function getDevice() {
        $result = '';

        if (is_array($this->browserInfo) && isset($this->browserInfo['device_name'])) {
            $result = $this->browserInfo['device_name'];
        }
        return $result;
    }


    public function getOS() {
        $result = '';

        if (is_array($this->browserInfo) && isset($this->browserInfo['platform'])) {
            $result = $this->browserInfo['platform'];
 
        }

        return $result;
     }   


    public function getBrowser() {
        $result = '';

        if (is_array($this->browserInfo) && isset($this->browserInfo['browser'])) {
            $result = $this->browserInfo['browser'] . (isset($this->browserInfo['version']) 
                ? ' v.' . $this->browserInfo['version'] : '');
        }

        return $result;
    }


    public function getCountryName() {
        $result = '';

        if (is_array($this->geoInfo) && isset($this->geoInfo['country_name'])) {
            $result = $this->geoInfo['country_name'];
        }

        return $result;
    }


        /**
     * Get user Region Code
     * @return string
     */
    public function getRegionCode() {
        $result = '';

        if (is_array($this->geoInfo) && isset($this->geoInfo['region_code'])) {
            $result = $this->geoInfo['region_code'];
        }

        return $result;
    }

    /**
     * Get user Region Name
     * @return string
     */
    public function getRegionName() {
        $result = '';

        if (is_array($this->geoInfo) && isset($this->geoInfo['region_name'])) {
            $result = $this->geoInfo['region_name'];
        }

        return $result;
    }

    /**
     * Get user City
     * @return string
     */
    public function getCity() {
        $result = '';

        if (is_array($this->geoInfo) && isset($this->geoInfo['city'])) {
            $result = $this->geoInfo['city'];
        }

        return $result;
    }

    /**
     * Get user Zipcode
     * @return string
     */
    public function getZipcode() {
        $result = '';

        if (is_array($this->geoInfo) && isset($this->geoInfo['zipcode'])) {
            $result = $this->geoInfo['zipcode'];
        }

        return $result;
    }

    /**
     * Get user Latitude
     * @return string
     */
    public function getLatitude() {
        $result = '';

        if (is_array($this->geoInfo) && isset($this->geoInfo['latitude'])) {
            $result = $this->geoInfo['latitude'];
        }

        return $result;
    }

    /**
     * Get user Longitude
     * @return string
     */
    public function getLongitude() {
        $result = '';

        if (is_array($this->geoInfo) && isset($this->geoInfo['longitude'])) {
            $result = $this->geoInfo['longitude'];
        }

        return $result;
    }

    /**
     * Get geo information about user. For this we use user IP and external service
     * Freegeoip (http://freegeoip.net)
     */
    private function getGeoInfo() {
        $url = 'http://freegeoip.net/json/' . self::getIP();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        return $result;
    }
}

?>
