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

if ( ! defined( 'ABSPATH' ) ) exit;

class Gallery_2J_Ordering{
   
    protected $postType;

    protected $assetsUri;

    protected $actionName;

    protected $tag;

    protected $currentPostOrder;

    public function __construct($postType){ 
    
        $this->postType = $postType;

        $this->assetsUri = plugin_dir_url(__FILE__);

        $this->actionName = "{$this->postType}_ordering_save";

        $this->tag = "{$this->postType}_ordering_tag";

        add_action("wp_ajax_".$this->actionName, 	array($this, 'ajaxSave'));

		add_action( 'init', array($this, 'initMenu') );
    }

    public function showSorting(){
    	$this->enqueueScripts();
	    $this->ajaxDialog();
    }

    public function addMenuItem(){ 
    	add_submenu_page( 'edit.php?post_type='.$this->postType, 'Galleries Ordering', 'Galleries Ordering', 'manage_options', '2j-gallery-ordering', array($this, 'showSorting' ) );
    }

    public function initMenu(){ 
    	add_action('admin_menu', array($this, 'addMenuItem'), 10);
    }

    
    public function enqueueScripts(){ 
        $screen = get_current_screen();

        if ($this->postType !== $screen->post_type) {
            return;
        }

        wp_enqueue_style(
            $this->tag.'-style',
            $this->assetsUri . 'css/style.css',
            array()
        );
        wp_enqueue_style('wp-jquery-ui-dialog');

        wp_enqueue_script('jquery-ui-dialog');

        wp_enqueue_script(
            $this->tag.'-nestable-js',
            $this->assetsUri . 'js/jquery.nestable.js',
            array(),
            false,
            true
        );
        wp_enqueue_script(
            $this->tag.'-js',
            $this->assetsUri . 'js/script.js',
            array($this->tag.'-nestable-js'),
            false,
            true
        );

        $postTypeObject = get_post_type_object($this->postType);

       wp_localize_script(
            $this->tag.'-js',
            'gallery2JOrderingAttributes',
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),

                'action' => array(
                    'save' => $this->actionName,
                ),
            
                'status' => array(
                	'saved'		=> __('saved', 		'blog-mechanics-theme-gallery'),
                	'modified'	=> __('edited', 	'blog-mechanics-theme-gallery'),
                	'saving'	=> __('save...', 	'blog-mechanics-theme-gallery'),
                	'error'		=> __('error', 		'blog-mechanics-theme-gallery'),
                	'ok'		=> __('ok', 		'blog-mechanics-theme-gallery'),
                )
            )
        );
    }



    public function ajaxDialog() {
        $this->checkPermission();
        $postTree = $this->getPostTree( $this->postType);
        echo '<div class="wrap">';
        	echo '<h1>'.__('2J Gallery Sorting', 'blog-mechanics-theme-gallery').'</h1> ';
        	echo '<div class="notice inline notice-warning notice-alt">';
        		echo '<p id="gallery_label_status">'.__('No changes', 'blog-mechanics-theme-gallery').'</p>';
        	echo '</div>';
	        echo '<div id="wrapper-nestable-list" class="">';
	        	echo '<button class="buttonSave button button-primary">Save</button> <br/> <br/>';
	        	echo '<div class="nestable-list dd"> '.$this->theNestableList($postTree).' </div> ';
	        	echo '<div class="nestable-list-spinner"> <img src="'.admin_url('/images/spinner-2x.gif').'" /> </div> ';
	        	echo '<br/> <button class="buttonSave button button-primary">Save</button>';
	        echo '</div>';
        echo '</div>';

    }


    public function ajaxSave() {
        $this->checkPermission();

        if (!isset($_POST['hierarchy_posts'])) {
            header('HTTP/1.0 403 Forbidden');
            echo 'Error #100  Please post ticket with this Error ID into support section.';
            die();
        }
        if (!is_array($_POST['hierarchy_posts'])) {
            header('HTTP/1.0 403 Forbidden');
            echo 'Error #101  Please post ticket with this Error ID into support section.';
            die();
        }

        $hierarchyPosts = $_POST['hierarchy_posts'];
        $this->currentPostOrder = 0;
        foreach ($hierarchyPosts as $order => $postData) {
            $this->updatePostHierarchy($postData);
        }
    }


    protected function getPostTree($postType){
        $args = array(
            'post_type' 		=> $postType,
            'post_status' 		=> 'publish',
            'posts_per_page' 	=> -1,
            'orderby' 			=> 'menu_order',
            'order' 			=> 'ASC'
        );
        $postMap = array();
        $postTree = array();

        foreach (get_posts($args) as $post) {
            if (isset($postMap[$post->ID])) {
                $postMap[$post->ID]['post'] = $post;
                $postData = &$postMap[$post->ID];
            } else {
                $postData = array('post' => $post, 'children' => array());
                $postMap[$post->ID] = &$postData;
            }
            if (0 == $post->post_parent) {
                $postTree["{$post->menu_order}-{$post->ID}"] = &$postData;
            } else {
                $postMap[$post->post_parent]['children'][$post->ID] = &$postData;
            }
            unset($postData);
        }
        

        foreach ($postMap as &$postData) {
            if (!isset($postData['post']) && is_array($postData['children'])) {
                foreach ($postData['children'] as &$childPostData) {
                    $childPost = $childPostData['post'];
                    $postTree["{$childPost->menu_order}-{$childPost->ID}"] = &$childPostData;
                }
            }
        }
        asort($postTree);

        return $postTree;
    }


    protected function theNestableList(array $tree){
		$returnHtml = '<ol class="dd-list">';
	        foreach ($tree as $item):
	            $returnHtml .= '<li class="dd-item" data-id="'.$item['post']->ID.'">';
	            	$returnHtml .= '<div class="dd-handle">';
	                        $title = esc_attr($item['post']->post_title);
	                        $returnHtml .=  "{$title} [{$item['post']->ID}: {$item['post']->post_name}]" ;
	                $returnHtml .= '</div>';
	                if (!empty($item['children'])):
	                        $returnHtml .= $this->theNestableList($item['children']);
	                endif;
	            $returnHtml .= '</li>';
        	endforeach;
    	$returnHtml .= '</ol>';
        return $returnHtml ;
    }


    protected function checkPermission(){
        $postTypeObject = get_post_type_object($this->postType);
        if (!current_user_can($postTypeObject->cap->edit_posts)) {
            header('HTTP/1.0 403 Forbidden');
            echo sprintf("You don't have permission for editing this %s", $postTypeObject->labels->name);
            die();
        }
    }


    protected function updatePostHierarchy($postData, $parentId = 0){
        $this->currentPostOrder++;
        wp_update_post(array(
            'ID' => absint($postData['id']),
            'post_parent' => absint($parentId),
            'menu_order' => $this->currentPostOrder
        ));

        if (!empty($postData['children'])) {
            foreach ($postData['children'] as $childPostData) {
                $this->updatePostHierarchy($childPostData, $postData['id']);
            }
        }
    }
}
