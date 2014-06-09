<?php
/**
 * A view helper for displaying html list of flickr images
 *
 * An example of how to use this helper:
 *
 * <code>
 * <?php
 * echo $this->flickrFront()
 * ->setLimit(12)
 * ->setFlickrConfig($flickr)
 * ->setExtras('description,geo');
 * ?>
 * </code>
 *
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_Yql_Oauth
 * @uses OauthTokens
 * @see https://www.flickr.com/services/api/ For api details
 * @example /app/modules/flickr/views/scripts/index/index.phtml
 */
class Pas_View_Helper_FlickrFront extends Zend_View_Helper_Abstract
{
    /** The cache object
     * @access protected
     * @var type
     */
    protected $_cache;

    /** The flickrKey
     * @access protected
     * @var string
     */
    protected $_flickrKey;

    /** The user ID
     * @access protected
     * @var string
     */
    protected $_userID;

    /** The flickr config array
     * Contains api key and userid
     * @access public
     * @var array
     */
    protected $_flickrConfig;

    /* The number of photos to return
     * @access protected
     * @type int
     *
     */
    protected $_limit;

    /** The extras to call from the flickr api
     * @access protected
     * @var string
     */
    protected $_extras = 'description,geo,license,url_sq,url_m';

    /** Get the extras string
     * @access public
     * @return string
     */
    public function getExtras() {
        return $this->_extras;
    }

    /** Set the extras to return
     * @access public
     * @param string $extras
     * @return \Pas_View_Helper_FlickrFront
     */
    public function setExtras($extras) {
        $this->_extras = $extras;
        return $this;
    }

    /** Get the limit of photos to return
     * @access public
     * @return int
     */
    public function getLimit() {
        return $this->_limit;
    }

    /** Set the limit to return
     * @access public
     * @param int $limit
     * @return \Pas_View_Helper_FlickrFront
     */
    public function setLimit( $limit) {
        $this->_limit = $limit;
        return $this;
    }

    /** Get the flickr config array
     * @access public
     * @return array
     */
    public function getFlickrConfig() {
        return $this->_flickrConfig;
    }

    /** Set the flickr config array
     * @access public
     * @param Zend_Config $flickrConfig
     * @return \Pas_View_Helper_FlickrFront
     */
    public function setFlickrConfig( Zend_Config $flickrConfig) {
        $this->_flickrConfig = $flickrConfig;
        return $this;
    }

    /** Get the cache
     * @access public
     * @return object
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Get the flickr api key
     * @access public
     * @return string
     */
    public function getFlickrKey() {
        $this->_flickrKey = $this->getFlickrConfig()->apikey;
        return $this->_flickrKey;
    }

    /** Get the userID to query
     * @access public
     * @return string
     */
    public function getUserID() {
        $this->_userID = $this->getFlickrConfig()->userid;
        return $this->_userID;
    }


    /** Get the access keys for oauth
     * @access public
     * @return array The keys to use
     */
    private function getAccessKeys() {
        $tokens = new OauthTokens();
        $where = array();
        $where[] = $tokens->getAdapter()->quoteInto('service = ?','yahooAccess');
        $validToken = $tokens->fetchRow($where);
        if (!is_null($validToken)) {
            $access = array(
                'access_token' => unserialize($validToken->accessToken),
                'access_token_secret' => unserialize($validToken->tokenSecret),
                'access_token_expiry' => $validToken->expires,
                'handle' => unserialize($validToken->sessionHandle)
        );
        }
        return $access;
    }

    /** Get response from Flickr YQL
     * @uses Pas_YqlOauth
     * @param array $access
     */
    private function getFlickr(array $access) {
        $access = (object) $access;
        $key = 'flickrfontrecent';
        if (!($this->getCache()->test($key))) {
            $oauth = new Pas_Yql_Oauth();
            $q = 'SELECT * FROM flickr.photos.search WHERE';
            $q .= 'tag_mode ="all" AND user_id="';
            $q .= $this->getUserID();
            $q .= '" AND extras="';
            $q .= $this->getExtras();
            $q .= '" and api_key="';
            $q .= $this->getFlickrKey();
            $q .= '" LIMIT';
            $q .= $this->getLimit();
        $data = $oauth->execute(
                $q,
                $access->access_token,
                $access->access_token_secret,
                $access->access_token_expiry,
                $access->handle
                );
        $this->getCache()->save($data);
        } else {
        $data = $this->getCache()->load($key);
        }
        if (is_array((array) $data)) {
        return $this->parseFlickr($data);
        } else {
            return false;
        }
    }

    /** Parse the flickr response to an array and build html
     * @access public
     * @param stdClass $data

     */
    private function parseFlickr(stdClass $data) {
        $recent = array();
        if (!is_null($data)) {
            foreach ($data->query->results->photo as $k) {
                $recent[] = $k;
            }
        }
        return $this->buildHtml($recent);
    }

    /** Build the html from the flickr array
     * @access public
     * @param array $recent
     * @return string The html to return
     */
    public function buildHtml( array $recent) {
        $html = '';
        if(is_array($recent)) {
            foreach($recent as $photo){
                $html .= '<img src="';
                $html .= $photo->url_sq;
                $html .= '" />';
            }
        } else {
            $html .= '<p>No results available';
        }
        return $html;

    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_FlickrFront
     */
    public function flickrFront() {
        return $this;
    }

    /** Render the html string using to String
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->openSesame();
    }


    /** Get the data via Oauth and run the functions
     * @access public
     * @return boolean
     */
    public function openSesame(){
        $openup = $this->getAccessKeys();

        if (!is_null($openup)) {
            return $this->getFlickr($openup);
        } else {
//            return $this->getFlickr($openup);
            return 'There has been a problem accessing the api';
        }
    }
}
