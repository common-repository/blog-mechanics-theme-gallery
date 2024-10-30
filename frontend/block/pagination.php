<?php
/*  
 * 2J Gallery			http://2joomla.net/wordpress-plugins/2j-gallery
 * Version:           	2.2.6 - 57233
 * Author:            	2J Team (c)
 * Author URI:        	http://2joomla.net
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:              	Thu, 26 Oct 2017 17:09:25 GMT
 */

class twoJGalleryPagination implements twoJGalleryBlockInterface{

	const BLOCK_NAME 		= 'paging';
	const VIEW_NUMBERING 	= 'numbering';
	const VIEW_LOAD_MORE 	= 'load_more';

	/**
	 * @var twoJGallery
	 */
	protected $gallery;

	public function __construct(twoJGallery $gallery){
		$this->gallery = $gallery;
	}

	/**
	 * @param string|null $view
	 * @return string
	 * @throws Exception
	 */
	public function getContent($view = null)
	{
		if (!$view) {
			$view = $this->gallery->getConfig()->get('gallery/block/pagination/view');
		}

		switch ($view) {
			case self::VIEW_NUMBERING:
				return $this->getContentNumbering();
			case self::VIEW_LOAD_MORE:
				return $this->getContentLoadMore();
		}
		throw new Exception(sprintf( 'Wrong Pagination block view: %s', $view));
	}

	/**
	 * @return string
	 */
	public function getContentNumbering()
	{
		$pages = $this->getPageAmount();
		if (1 >= $pages) {
			return '';
		}

		$view = new twoJGalleryView();
		return $view->content(
			'block/pagination/numbering.php',
			array(
				'page' => $this->getCurrentPage(),
				'perPage' => $this->gallery->getConfig()->get('gallery/block/content/per_page'),
				'pages' => $pages,
			)
		);
	}

	/**
	 * @return string
	 */
	public function getContentLoadMore()
	{
		$pages = $this->getPageAmount();
		if (1 >= $pages) {
			return '';
		}
		
		$view = new twoJGalleryView();
		return $view->content(
			'block/pagination/loadmore.php',
			array(
				'page' => $this->getCurrentPage(),
				'perPage' => $this->gallery->getConfig()->get('gallery/block/content/per_page'),
				'pages' => $pages,
				'buttonLoadMore' => __('Load more', 'blog-mechanics-theme-gallery')
			)
		);
	}

	/**
	 * @return int
	 */
	protected function getPageAmount()
	{
		$itemCount = count($this->gallery->getItemIds());
		$itemPerPage = $this->gallery->getConfig()->get('gallery/block/content/per_page');

		return (0 < $itemPerPage) ? ceil($itemCount / $itemPerPage) : 1;
	}

	/**
	 * @return int
	 */
	public function getCurrentPage()
	{
		if (!isset($_GET['gallery_id']) || $_GET['gallery_id'] != $this->gallery->getData('post/ID')) {
			return 1;
		}
		return isset( $_GET[self::BLOCK_NAME]['page'] ) ? absint( $_GET[self::BLOCK_NAME]['page'] ) : 1;
	}
}
