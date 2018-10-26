<?php
/**
 * Created by PhpStorm.
 * User: yudhawillys
 * Date: 10/14/2016
 * Time: 2:48 PM
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Assets URL
 *
 * Create a local URL based on your assets.
 * Segments can be passed in as a string or an array, same as base_url
 * or a URL to a file can be passed in, e.g. to an image file.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('assets_url'))
{
    function assets_url($uri = '')
    {
        return base_url().'assets/';
    }
}