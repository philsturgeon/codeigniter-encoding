<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Encoding Class
 *
 * Encode audio and video using the encoding.com API and service. Supports
 * Windows Media formats, QuickTime, Ogg, all sorts of stuff!
 *
 * http://www.encoding.com/wdocs/ApiDoc
 *
 * This class requires the cURL PHP extension to be enabled.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Multimedia
 * @author		Phil Sturgeon
 * @link		http://bitbucket.org/philsturgeon/codeigniter-encoding
 * @license     http://philsturgeon.co.uk/code/dbad-license
 * @version     1.0.0
 */
class Encoding
{
    private $_ci;                // CodeIgniter instance
	
	private $user_id = 0;
	private $user_key = '';

	private $api_location = 'manage.encoding.com';

	private $request = '';
	private $response = '';

	private $_error_string = '';
	private $_message_string = '';

	// --------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @access    Public
	 * @param     string
	 * @return    none
	 */
	function __construct($params = array())
	{
		$this->_ci =& get_instance();
		
		log_message('debug', 'Encoding Initialized');

		isset($params['id']) || $this->user_id = $params['id'];
		isset($params['key']) || $this->user_key = $params['key'];

	    $this->request = new SimpleXMLElement('<?xml version="1.0"?><query></query>');

	    // Main fields
	    $this->request->addChild('userid', $this->user_id);
	    $this->request->addChild('userkey', $this->user_key);
	}

	// --------------------------------------------------------------------

	/**
	 * Notify URL
	 *
	 * @access    Public
	 * @param     string	$url	URL (or controller) the API should poke when encoding is done
	 * @return    none
	 */
	function notify($url = '')
	{
		// If no a protocol in URL, assume its a CI link
        if (!preg_match('!^\w+://! i', $url))
        {
            $this->_ci->load->helper('url');
            $url = site_url($url);
        }
		
	    $this->request->addChild('notify', $url);
	    return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Media ID
	 *
	 * @access    Public
	 * @param     int	$id		Set the Media ID to something
	 * @return    none
	 */
	function media_id($id)
	{
	    $this->request->addChild('MediaID', $id);
	    return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Encode
	 *
	 * @access    Public
	 * @param     string	$file	Path to the file you wish to encode (including FTP stuff
	 * @return    none
	 */
	function encode($file, $properties = array())
	{
	    // Preparing XML request

	    $this->request->addChild('action', 'AddMedia');
	    $this->request->addChild('source', $file);

	    $format_node = $this->request->addChild('format');

	    // Format fields
	    foreach($properties as $property => $value)
	    {
	        if (!empty($value))
	        {
	        	$format_node->addChild($property, $value);
	        }
	    }

	    // Sending API request
	    $this->response = $this->_request($this->request->asXML());

	    try
	    {
	        // Creating new object from response XML
	        $this->response = new SimpleXMLElement($this->response);

	        // If there are any errors, set error message
	        if(isset($this->response->errors[0]->error[0]))
	        {
	            $this->_error_string = $this->response->errors[0]->error[0];
	        }

	        else if (isset($response->message[0]))
	        {
	            // If message received, set OK message
	            $this->_message_string = (string) $this->response->message[0];
	        }
	    }

	    catch(Exception $e)
	    {
	        // If wrong XML response received
	        $this->_error_string = $e->getMessage();
	    }

	    return empty($this->_error_string);
	}

	public function error_string()
	{
		return $this->_error_string;
	}

	public function message_string()
	{
		return $this->_message_string;
	}

	public function debug()
	{
		echo "=============================================<br/>\n";
		echo "<h2>Encoding.com Debug</h2>\n";
		echo "=============================================<br/>\n";
		echo "<h3>Request</h3>\n";
		echo "<code>".nl2br(htmlentities($this->request->asXML()))."</code><br/>\n\n";
		echo "=============================================<br/>\n";

		if($this->_error_string)
		{
			echo "<h3>Error</h3>";
			echo "<strong>Message:</strong> ".$this->_error_string."<br/>\n";
			echo "=============================================<br/>\n";
		}

		echo "<h3>Response</h3>";
		echo "<pre>";
		print_r($this->response);
		echo "</pre>";
	}

	private function _request($xml)
	{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, 'http://' . $this->api_location . '/');
	    curl_setopt($ch, CURLOPT_POSTFIELDS, "xml=" . urlencode($xml));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    return curl_exec($ch);
	}
}
	
/* End of file Encoding.php */
/* Location: ./system/libraries/Encoding.php */