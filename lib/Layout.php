<?php
/**
 * Utopia PHP Framework
 *
 * @package Framework
 * @subpackage Core
 * 
 * @link https://github.com/eldadfux/Utopia-PHP-Framework
 * @author Eldad Fux <eldad@fuxie.co.il>
 * @version 1.0 RC4
 * @license The MIT License (MIT) <http://www.opensource.org/licenses/mit-license.php>
 */

namespace Utopia;

class Layout extends View {

	/**
	 * @var array
	 */
	private $styles = array();
	
	/**
	 * @var array
	 */
	private $scripts = array();
	
	/**
	 * @var array
	 */
	private $meta = array();
	
	/**
	 * @var array
	 */
	private $links = array();
	
	/**
	 * @var string
	 */
	private $title = '';
	
	/**
	 * @var string
	 */
	private $inlineScript = '';
	
	public function __construct() {
		$this->addMeta('Utopia Framework v.1', 'generator');
	}
	
	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $this->escape($title);
		return $this;
	}
	
	/**
	 * @param string $url
	 * @return Layout
	 */
	public function addStyle($url) {
		if (!array_key_exists($url, $this->styles)) {
			$this->styles[$url] = true;
			$this->addLink('stylesheet', '', $url, '', true);
		}
		return $this;
	}
	
	/**
	 * @param string $path
	 * @return Layout
	 */
	public function addScript($url) {
		if (!array_key_exists($url, $this->scripts)) {
			$this->scripts[$url] = '<script src="' . $url . '"></script>' . "\n\t\t";
		}
		return $this;
	}
	
	/**
	 * @param string $code
	 */
	public function addInlineScript($code) {
		$this->inlineScript .= $code;
		return $this;
	}
		
	/**
	 * @param string $content
	 * @param string $name
	 * @param string $property
	 * @throws \Exception
	 * @return Layout
	 */
	public function addMeta($content, $name = '', $property = '') {
		if (empty($name) && empty($property)) {
			throw new \Exception('Meta tag must have at least $name or $property');
		}
		
		$name 		= (!empty($name)) ? 'name="' . $this->escape($name) . '" ' : '';
		$property	= (!empty($property)) ? 'property="' . $this->escape($property) . '" ' : '';
		
		$this->meta[$name . '_' . $property] = '<meta ' . $name . $property . 'content="' . $this->escape($content) . '" />' . "\n\t\t";
		
		return $this;
	}

	/**
	 * @param string $rel
	 * @param string $type
	 * @param string $href
	 * @param string $title
	 * @return Layout
	 */
	public function addLink($rel, $type = '', $href = '', $title = '', $multiple = false) {
		$href 	= (!empty($href)) ? ' href="' . $href . '"' : '';
		$type 	= (!empty($type)) ? ' type="' . $type . '"' : '';
		$title 	= (!empty($title)) ? ' title="' . $title . '"' : '';
		
		$tag = '<link rel="' . $rel. '"' . $type . $href . $title. ' />' . "\n\t\t";
		
		if ($multiple) {
			$this->links[] = $tag;
		}
		else {
			$this->links[$rel] = $tag; 
		}
		
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Utopia\View::render()
	 */
	public function render() {
		if ($this->rendered) { // Return only content if layout should not be rendered
			return $this->getParam('body');
		}
		
		/* Set HTML head section */
		$head = implode('', $this->meta);
		$head .= implode('', $this->links);
		$head .= implode('', $this->scripts);
		$head .= ($this->inlineScript) ? '<script>' . $this->inlineScript . '</script>' . "\n" : '';
		$head .= '<title>' . $this->title . '</title>' . "\n";
		
		$this->setParam('head', $head);
		
		return parent::render();
	}

}