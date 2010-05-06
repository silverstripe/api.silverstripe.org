<?php
require_once(dirname(__FILE__) . '/../../HTML/Smarty/HTMLSmartyConverter.inc');

/**
 * Copied from HTMLSmartyConverter to generate a simple
 * gotapi.com XML document.
 * 
 * @see http://www.gotapi.com/contribute/index.html
 * @author Ingo Schommer, SilverStripe Ltd.
 *
 * Copyright (c) 2000-2006 Joshua Eichorn, Gregory Beaver
 * 
 * LICENSE:
 * 
 * This library is free software; you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General
 * Public License as published by the Free Software Foundation;
 * either version 2.1 of the License, or (at your option) any
 * later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package		Converters
 * @subpackage HTMLframes
 * @author		 Joshua Eichorn <jeichorn@phpdoc.org>
 * @author		 Greg Beaver <cellog@php.net>
 * @copyright	2000-2006 Joshua Eichorn, Gregory Beaver
 * @license		http://www.opensource.org/licenses/lgpl-license.php LGPL
 */
class XMLGotAPIConverter extends HTMLSmartyConverter
{
	
	var $outputformat = 'XML';

	var $name = 'GotAPI';
	
	var $base_domain = 'http://api.silverstripe.org/';

	function XMLGotAPIConverter(&$allp, &$packp, &$classes, &$procpages, $po, $pp, $qm, $targetDir, $templateDir, $title) {

		// Custom: See HTMLSMartyConvert::setTargetDir() for details
		global $_phpDocumentor_setting;
		if(isset($_phpDocumentor_setting['templatebase'])) {
			$this->setTemplateBase(trim($_phpDocumentor_setting['templatebase']), 'default/');
		}
		
		HTMLSmartyConverter::HTMLSmartyConverter($allp, $packp, $classes, $procpages,$po, $pp, $qm, $targetDir, $templateDir, $title);
	}
	
	function writeSource($filepath, $source)
	{
			return;
	}

	function writeExample($title, $path, $source)
	{
			return;
	}
	
	function endPage() {
		return;
	}
	
	function endClass() {
		return;
	}
	
	function formatPkgIndex() {
		return;
	}
	
	function formatIndex() {
		return;
	}
	
	function formatLeftIndex() {
		return;
	}

	function Output($title) {
		$templ = &$this->newSmarty();
		$this->setTargetDir($this->base_dir);
		$templ->template_dir = realpath($this->smarty_dir . PATH_DELIMITER . 'templates');
		$classes = array();
		foreach($this->class_contents as $packageName => $package) {
			foreach($package as $subpackageName => $subpackage) {
				foreach($subpackage as $className => $class) {
					// create link (similar to convertClass())
					$url = $this->base_domain . $this->base_dir;
					if($packageName) $url .= '/' . $packageName;
					if($subpackageName) $url .= '/' . $subpackageName;
					$url .= '/' . $className . '.html';
					
					$classes[$className] = array(
						'url' => $url,
						'desc' => $class['###main'],
						'properties' => $class
					);
				}
			}
		}
		
		asort($classes);

		$templ->assign('baseurl', $this->base_domain . $this->base_dir);
		$templ->assign('classes',$classes);
		// var_dump($this->class_contents);
		$this->writefile('index.xml',$templ->fetch('index.tpl'));
	}
	
	function setTemplateDir($dir)
	{
		// set through setTemplateBase() in the constructor (to avoid it being overwritten with default /opt/local path)
		if(!$this->templateDir) {
			HTMLSmartyConverter::setTemplateDir($dir);
		}
	}

	function setTargetDir($dir)
	{
		Converter::setTargetDir($dir);
		if ($this->_wrote_tdir) return;

		// CUSTOM ischommer, SilverStripe Ltd.
		// Necessary because of how the smarty converter hooks into IntermediaParser->Output():
		// It copies assets on setTargetDir(), which is in turn called by the Converter constructor.
		// The actual setTemplateBase() call happens *after* the constructor.
		// This code emulates phpDocumentor_setup->readCommandLineSettings().
		global $_phpDocumentor_setting;
		if(isset($_phpDocumentor_setting['templatebase'])) {
			// TODO Second parameter is hardcoded, no idea where to get that from
			$this->setTemplateBase(trim($_phpDocumentor_setting['templatebase']), 'default/');
		}
		// CUSTOM END

		$this->_wrote_tdir = true;
		$template_images = array();
		$stylesheets = array();
		$tdir = $dir;
		$dir = $this->templateDir;
		$this->templateDir = $this->templateDir.'templates/';

		// CUSTOM Removed media copying, not necessary here
	}
	
}