<?php
/*
Plugin Name: Ajax Scroll
Description: Turns standard next and previous post links into animated AJAX scrolling.
Author: Nicholas Crawford
Version: 0.42
Author URI: http://www.bankofcanada.ca/
Plugin URI: http://bankofcanada.wordpress.com/
*/

/*
         _._._                       _._._
        _|   |_                     _|   |_
        | ... |_._._._._._._._._._._| ... |
        | ||| |  o BANK OF CANADAo  | ||| |
        | """ |  """    """    """  | """ |
   ())  |[-|-]| [-|-]  [-|-]  [-|-] |[-|-]|  ())
  (())) |     |---------------------|     | (()))
 (())())| """ |  """    """    """  | """ |(())())
 (()))()|[-|-]|  :::   .-"-.   :::  |[-|-]|(()))()
 ()))(()|     | |~|~|  |_|_|  |~|~| |     |()))(()
    ||  |_____|_|_|_|__|_|_|__|_|_|_|_____|  ||
 ~ ~^^ @@@@@@@@@@@@@@/=======\@@@@@@@@@@@@@@ ^^~ ~
      ^~^~                                ~^~^

Should we force the user to specify the ID?  If we use this, it has a default.
But that means if they foget to specify the ID on a certain theme page, the link won't work.

global $as_elem_id;
if (empty($as_elem_id))
	$as_elem_id = 'content';
*/

add_filter('previous_post_link', array('AjaxScroll', 'set_link'));
add_filter('next_post_link', array('AjaxScroll', 'set_link'));
add_action('get_pagenum_link', array('AjaxScroll', 'replace_link'), 10000); // should be called as the very last action
add_action('clean_url', array('AjaxScroll', 'fix_link'), 10000); // should be called as the very last action

wp_enqueue_script('jquery-ui-slide', plugins_url('ajax-scroll/js/jquery-ui-1.7.2.effects.slide.min.js'), array('jquery'));
wp_enqueue_script('ajax-scroll', plugins_url('ajax-scroll/js/ajax-scroll-js.php'), array('jquery', 'jquery-ui-slide'));

class AjaxScroll
{
	public function set_link($link)
	{
		preg_match('/href="(.*?)"/', $link, $match);
		echo str_replace($match[1], AjaxScroll::fix_link(AjaxScroll::replace_link($match[1])), $link);
	}

	public function replace_link($link)
	{ 
		global $as_elem_id, $post;
		if ( empty($as_elem_id) )
			return $link;
	
		// link url
		$page = url_to_postid($link);  // if we are on a single post, this will get post ID from permalink
		if ( $page == 0 )
		{
			parse_str( parse_url ($link, PHP_URL_QUERY), $query );
			if ( isset($query['paged']) && is_numeric($query['paged']) )
			{
				$page = $query['paged'];
			}
			elseif ( strcasecmp(rtrim($link, '/'), WP_HOME) == 0 )
			{
				$page = 1;
			}
			else
			{
				$check_link = rtrim(parse_url ($link, PHP_URL_PATH), '/');
				$method1 = substr($check_link, strrpos($check_link, '/') + 1);
				if ( is_numeric($method1) )
					$page = $method1;
				else
					$page = 1;
			}
		}

		// current url
		$current_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$current_page = url_to_postid($current_url); // if we are on a single post, this will get post ID from permalink
		if ( $current_page == 0 )
		{
			parse_str( parse_url ( $current_url, PHP_URL_QUERY ), $current_query );

			if ( isset($current_query['paged']) && is_numeric($current_query['paged']) )
			{
				$current_page = $current_query['paged'];
			}
			elseif ( strcasecmp(rtrim($current_url, '/'), WP_HOME) == 0 )
			{
				$current_page = 1;
			}
			else
			{
				$check_link = rtrim(parse_url ($current_url, PHP_URL_PATH), '/');
				$current_method1 = substr($check_link, strrpos($check_link, '/') + 1);
				if ( is_numeric($current_method1) )
					$current_page = $current_method1;
				else
					$current_page = 1;
			}
		}
 
 		/*
		 * If it is a singe post, the next/prev link take you to the next/prev post.
		 *    In this case, a higher post number is newer.
		 *    If this is not the case, then it's paging, where a higher page number is usually older.
		 */
		if ( is_single() )
		{
			if ( $current_page > $page )
				$direction = 'older';
			else
				$direction = 'newer';
		}
		else
		{
			if ( $current_page < $page )
				$direction = 'older';
			else
				$direction = 'newer';
		}

		return 'http://ajax-scroll175926233/javascript:ajax_scroll(\'' . $direction . '\', \'' . urlencode($link) . '\', \'' . $as_elem_id . '\');';
	}
	
	public function fix_link($link)
	{
		if ( substr($link, 0, 39) == 'http://ajax-scroll175926233/javascript:' )
			return substr_replace($link, '', 0, 28);
		return $link;
	}
	
	/* 
	 *	$url 		- URI to pull contents from
	 * $direction	- "newer" or "older"
	 * $text		- text to link
	 * $container	- id of the element that will be transitioned
	 */
	static public function generateLink($url, $direction, $text, $container='')
	{
		if ( empty($container) )
		{
			global $as_elem_id;
			$container = $as_elem_id;
		}
		echo '<a href="" onclick="', get_generateLink($url, $direction, $container), 'return false;" title="', htmlspecialchars($text, ENT_COMPAT, 'UTF-8', false), '">', $text, '</a>';
	}
	
	static public function get_generateLink($url, $direction, $container='')
	{
		if ( empty($container) )
		{
			global $as_elem_id;
			$container = $as_elem_id;
		}
		return 'ajax_scroll(\'' . $direction . '\', \'' . urlencode($url) . '\', \'' . $container . '\');';
	}
}