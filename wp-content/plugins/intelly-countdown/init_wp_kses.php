<?php

if ( ! defined('WPINC') ) { 
	die;
}

global $icp_allowed_html_tags;
$icp_allowed_atts                  = array(
	'align'          => array(),
	'class'          => array(),
	'type'           => array(),
	'id'             => array(),
	'dir'            => array(),
	'lang'           => array(),
	'style'          => array(),
	'xml:lang'       => array(),
	'src'            => array(),
	'alt'            => array(),
	'href'           => array(),
	'rel'            => array(),
	'rev'            => array(),
	'target'         => array(),
	'novalidate'     => array(),
	'type'           => array(),
	'value'          => array(),
	'name'           => array(),
	'tabindex'       => array(),
	'action'         => array(),
	'method'         => array(),
	'for'            => array(),
	'width'          => array(),
	'height'         => array(),
	'data'           => array(),
	'title'          => array(),
	'async'          => array(),
	'loading'        => array(),
	'referrerpolicy' => array(),
	'sandbox'        => array(),
	'crossorigin'    => array(),
	'defer'          => array(),
	'integrity'      => array(),
	'nomodule'       => array(),
	'onload'         => array(),
);
$icp_allowed_html_tags['form']     = $icp_allowed_atts;
$icp_allowed_html_tags['label']    = $icp_allowed_atts;
$icp_allowed_html_tags['input']    = $icp_allowed_atts;
$icp_allowed_html_tags['textarea'] = $icp_allowed_atts;
$icp_allowed_html_tags['iframe']   = $icp_allowed_atts;
$icp_allowed_html_tags['script']   = $icp_allowed_atts;
$icp_allowed_html_tags['noscript'] = $icp_allowed_atts;
$icp_allowed_html_tags['style']    = $icp_allowed_atts;
$icp_allowed_html_tags['strong']   = $icp_allowed_atts;
$icp_allowed_html_tags['small']    = $icp_allowed_atts;
$icp_allowed_html_tags['table']    = $icp_allowed_atts;
$icp_allowed_html_tags['span']     = $icp_allowed_atts;
$icp_allowed_html_tags['abbr']     = $icp_allowed_atts;
$icp_allowed_html_tags['code']     = $icp_allowed_atts;
$icp_allowed_html_tags['pre']      = $icp_allowed_atts;
$icp_allowed_html_tags['div']      = $icp_allowed_atts;
$icp_allowed_html_tags['img']      = $icp_allowed_atts;
$icp_allowed_html_tags['h1']       = $icp_allowed_atts;
$icp_allowed_html_tags['h2']       = $icp_allowed_atts;
$icp_allowed_html_tags['h3']       = $icp_allowed_atts;
$icp_allowed_html_tags['h4']       = $icp_allowed_atts;
$icp_allowed_html_tags['h5']       = $icp_allowed_atts;
$icp_allowed_html_tags['h6']       = $icp_allowed_atts;
$icp_allowed_html_tags['ol']       = $icp_allowed_atts;
$icp_allowed_html_tags['ul']       = $icp_allowed_atts;
$icp_allowed_html_tags['li']       = $icp_allowed_atts;
$icp_allowed_html_tags['em']       = $icp_allowed_atts;
$icp_allowed_html_tags['hr']       = $icp_allowed_atts;
$icp_allowed_html_tags['br']       = $icp_allowed_atts;
$icp_allowed_html_tags['tr']       = $icp_allowed_atts;
$icp_allowed_html_tags['td']       = $icp_allowed_atts;
$icp_allowed_html_tags['p']        = $icp_allowed_atts;
$icp_allowed_html_tags['a']        = $icp_allowed_atts;
$icp_allowed_html_tags['b']        = $icp_allowed_atts;
$icp_allowed_html_tags['i']        = $icp_allowed_atts;
$icp_allowed_html_tags['body']     = $icp_allowed_atts;
