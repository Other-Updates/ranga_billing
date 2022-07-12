<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class REST_Controller extends MY_Controller {

   
    private $ci = NULL;
    private $_get_args = array();
    protected $request = NULL;
    private $_supported_formats = array(
        'xml' => 'application/xml',
        'rawxml' => 'application/xml',
        'json' => 'application/json',
        'jsonp' => 'application/javascript',
        'serialize' => 'application/vnd.php.serialized',
        'php' => 'text/plain',
        'html' => 'text/html',
        'csv' => 'application/csv'
    );

    
    public function __construct() {
        parent::__construct();
    }



    private function _detect_format() {
        $pattern = '/\.(' . implode('|', array_keys($this->_supported_formats)) . ')$/';

        // Check if a file extension is used
        if (preg_match($pattern, end($this->_get_args), $matches)) {
            // The key of the last argument
            $last_key = end(array_keys($this->_get_args));

            // Remove the extension from arguments too
            $this->_get_args[$last_key] = preg_replace($pattern, '', $this->_get_args[$last_key]);
            $this->_args[$last_key] = preg_replace($pattern, '', $this->_args[$last_key]);

            return $matches[1];
        }

        // A format has been passed as an argument in the URL and it is supported
        if (isset($this->_args['format']) AND isset($this->_supported_formats)) {
            return $this->_args['format'];
        }

        // Otherwise, check the HTTP_ACCEPT (if it exists and we are allowed)
        if ($this->config->item('rest_ignore_http_accept') === FALSE AND $this->input->server('HTTP_ACCEPT')) {
            // Check all formats against the HTTP_ACCEPT header
            foreach (array_keys($this->_supported_formats) as $format) {
                // Has this format been requested?
                if (strpos($this->input->server('HTTP_ACCEPT'), $format) !== FALSE) {
                    // If not HTML or XML assume its right and send it on its way
                    if ($format != 'html' AND $format != 'xml') {

                        return $format;
                    }

                    // HTML or XML have shown up as a match
                    else {
                        // If it is truely HTML, it wont want any XML
                        if ($format == 'html' AND strpos($this->input->server('HTTP_ACCEPT'), 'xml') === FALSE) {
                            return $format;
                        }

                        // If it is truely XML, it wont want any HTML
                        elseif ($format == 'xml' AND strpos($this->input->server('HTTP_ACCEPT'), 'html') === FALSE) {
                            return $format;
                        }
                    }
                }
            }
        } // End HTTP_ACCEPT checking
        // Well, none of that has worked! Let's see if the controller has a default
        if (!empty($this->rest_format)) {
            return $this->rest_format;
        }

        // Just use the default format
        return config_item('rest_default_format');
    }

    public function response($data = array(), $http_code = null) {
       
           
        // If data is empty and not code provide, error and bail
        if (empty($data) && $http_code === null) {
            $http_code = 404;
        }
        
        // Otherwise (if no data but 200 provided) or some data, carry on camping!
        else {
            is_numeric($http_code) OR $http_code = 200;
            $this->request = $this->_detect_format();
            
            // If the format method exists, call and return the output in that format
            if (method_exists($this, '_format_' . $this->request)) {
                // Set the correct format header
                header('Content-type: ' . $this->_supported_formats[$this->request]);

                $formatted_data = $this->{'_format_' . $this->request}($data);
                $output = $formatted_data;
            }
           
            // Format not supported, output directly
            else {
                $output = $data;
            }
            
        }

        header('HTTP/1.1: ' . $http_code);
        header('Status: ' . $http_code);
        echo json_encode($output);

    }

    public function _get_customer_post_values() {
        $json_input = json_decode(file_get_contents('php://input'), TRUE);
        // if (empty($json_input)) {
            // $get_arr = is_array($this->input->get(null)) ? $this->ci->input->get(null) : array();
            // $post_arr = is_array($this->input->post(null)) ? $this->ci->input->post(null) : array();
            // $json_input = array_merge($get_arr, $post_arr);
        // }

        return $json_input;
    }
}
