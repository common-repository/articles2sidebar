<?php

/*
Plugin Name: article2sidebar
Plugin URI: http://www.modulaweb.fr/blog/wp-plugins-en/articles-to-sidebar/
Description: Puts in side bar the excerpt of articles posted in a category called the same as the curent page or posted in the same category than the displayed page. Place en sidebar les articles placés dans une catégorie homonyme avec la page affichée ou de la même catégorie.
Version: 1.1
Author: Jean-François VIAL
Author URI: http://www.modulaweb.fr/
*/
/*  Copyright 2009 Jean-François VIAL  (email : jeff@modulaweb.fr)
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function widget_articles2sidebar_init() {
	if ( !function_exists('register_sidebar_widget') )
		return;

	/* utility function */
	function widget_articles2sidebar_content($a2spost,$options,$before_widget,$before_title,$after_title,$after_widget) {
		if (in_array('articles2sidebar_display_link',get_post_custom_keys($a2spost->ID))) {
			$display_link = get_post_custom_values('articles2sidebar_display_link', $a2spost->ID);
			$display_link = $display_link[0];
		} else {
			$display_link = 1;
		}
		echo $before_widget;
		echo $before_title;
		if (($options['link']>0 || $display_link>0) && (!$display_link == 0))
			echo '<a href="' . $a2spost->post_name . '">';
		echo $a2spost->post_title;
		if (($options['link']>0 || $display_link>0) && (!$display_link == 0))
			echo '</a>';
		echo $after_title;
		if ($a2spost->post_excerpt != '') {
			echo '<p>';
			$imgBeg = strpos($a2spost->post_content, '<img');
			$post = substr($a2spost->post_content, $imgBeg);
			$imgEnd = strpos($post, '>');
			$postOutput = substr($post, 0, $imgEnd+1);
			$result = preg_match('/width="([0-9]*)" height="([0-9]*)"/', $postOutput, $matches);
			if ($result) {
				if ($matches[1]>45 || $matches[2]>45) {
					$pagestring = $matches[0];
					$postOutput = str_replace($pagestring, "", $postOutput);
				}
			}
			if($postOutput != "<p>") { 
				if (($options['link']>0 || $display_link>0) && (!$display_link == 0))
					echo '<a href="' . $a2spost->post_name . '">';
				if ($matches[1]>45 || $matches[2]>45) {
					echo preg_replace('/height="([0-9]*)"/i','',str_replace('<img','<img width="45"',$postOutput));
				} else {
					echo $postOutput;
				}
				if (($options['link']>0 || $display_link>0) && (!$display_link == 0))
					echo '</a>';
			}
			echo $a2spost->post_excerpt;
			if (($options['link']>0 || $display_link>0) && (!$display_link == 0))
				echo '<br /><a href="' . $a2spost->post_name . '">' . $options['lmtext'] . '</a>';
			echo '</p>';
		} else {
			$extended = get_extended($a2spost->post_content);
			if ($extended['extended'] !='') {
				$content = $extended['main'].'[…]';
			} else {
				$tmp = explode(' ',strip_tags(strip_shortcodes($a2spost->post_content)),55);
				$tmp[54] = '[…]';
				$content = implode(' ',$tmp);
			}
			echo '<p>';
			$imgBeg = strpos($a2spost->post_content, '<img');
			$post = substr($a2spost->post_content, $imgBeg);
			$imgEnd = strpos($post, '>');
			$postOutput = substr($post, 0, $imgEnd+1);
			$result = preg_match('/width="([0-9]*)" height="([0-9]*)"/', $postOutput, $matches);
			if ($result) {
				if ($matches[1]>45 || $matches[2]>45) {
					$pagestring = $matches[0];
					$postOutput = str_replace($pagestring, "", $postOutput);
				}
			}
			if($postOutput != "<p>") { 
				if (($options['link']>0 || $display_link>0) && (!$display_link == 0))
					echo '<a href="' . $a2spost->post_name . '">';
				if ($matches[1]>45 || $matches[2]>45) {
					echo preg_replace('/height="([0-9]*)"/i','',str_replace('<img','<img width="45"',$postOutput));
				} else {
					echo $postOutput;
				}
				if (($options['link']>0 || $display_link>0) && (!$display_link == 0))
					echo '</a>';
			}
			echo $content;
			if (($options['link']>0 || $display_link>0) && (!$display_link == 0))
				echo '<br /><a href="' . $a2spost->post_name . '">' . $options['rmtext'] . '</a>';
			echo '</p>';
		}
		echo $after_widget;			
	}

	function widget_articles2sidebar($args) {
	 	if (is_page()) {

			extract($args);

			$options = get_option('widget_articles2sidebar');
			if ( !is_array($options) )
				$options = array('origin'=>'auto', 'cat' => '', 'link' => 1, 'lmtext' => __('Learn more').' »', 'rmtext' => __('Read more').' »');
			$options['link'] = $options['link'];
			$options['lmtext'] = htmlspecialchars($options['lmtext'], ENT_QUOTES);
			$options['rmtext'] = htmlspecialchars($options['lmtext'], ENT_QUOTES);
	 		switch($options['origin']) {
	 			case 'auto':
	 			case 'both':
	 			case 'page':
					// retrieve the category that correspond to the page title
					$cat = get_category_by_slug(the_title('','',false));
					if ($cat) {
						// retrieve that category's posts
						$a2sposts = get_posts('category='.$cat->term_id.'&orderby=ID&order=ASC');
						foreach ($a2sposts as $a2spost) {
							// dislay them
							widget_articles2sidebar_content($a2spost,$options,$before_widget,$before_title,$after_title,$after_widget);
						}
					}
				case 'auto':
					if (count($a2sposts)) break;
	 			case 'both':
	 			case 'cat':
					// retrieve the categories
					$cats = get_categories();
					foreach ($cats as $cat) {
						// retrieve the posts
						if ($cat->cat_name == $options['cat']) {
							$a2sposts = get_posts('category='.$cat->cat_ID.'&orderby=ID&order=ASC');
							foreach ($a2sposts as $a2spost) {
								// dislay them
								widget_articles2sidebar_content($a2spost,$options,$before_widget,$before_title,$after_title,$after_widget);
							}
							break;
						}
					}
			}
		}
	}

	function widget_articles2sidebar_control() {
		$options = get_option('widget_articles2sidebar');
		if ( !is_array($options) )
			$options = array('origin'=>'auto', 'cat' => '', 'link' => 1, 'lmtext' => __('Learn more').' »', 'rmtext' => __('Read more').' »');

		if ( $_POST['articles2sidebar-submit'] ) {
			$options['origin'] = strip_tags(stripslashes($_POST['articles2sidebar-origin']));
			$options['cat']    = strip_tags(stripslashes($_POST['articles2sidebar-cat']));
			(intval($_POST['articles2sidebar-link'])>0) ? $options['link'] = 1 : $options['link'] = 0;
			$options['lmtext'] = strip_tags(stripslashes($_POST['articles2sidebar-lmtext']));
			$options['rmtext'] = strip_tags(stripslashes($_POST['articles2sidebar-rmtext']));
			if (strip_tags(stripslashes($_POST['articles2sidebar-cat2'])) != '') $options['cat'] = strip_tags(stripslashes($_POST['articles2sidebar-cat2']));
			update_option('widget_articles2sidebar', $options);
		}

		$origin = $options['origin'];
		$cat = htmlspecialchars($options['cat'], ENT_QUOTES);
		$link = $options['link'];
		$lmtext = htmlspecialchars($options['lmtext'], ENT_QUOTES);
		$rmtext = htmlspecialchars($options['rmtext'], ENT_QUOTES);
		?>
		<label for="articles2sidebar-origin">
			<?=__('Origin of displayed articles:')?>
			<select style="width: 200px;" id="articles2sidebar-origin" name="articles2sidebar-origin">
				<option value="auto"<?    if ($origin == 'auto') echo ' selected="selected"';?>><?=__('Automatic')?></option>
				<option value="both"<?    if ($origin == 'both') echo ' selected="selected"';?>><?=__('Both')?></option>
				<option value="cat"<?     if ($origin == 'cat')  echo ' selected="selected"';?>><?=__('Category')?></option>
				<option value="page"<?    if ($origin == 'page') echo ' selected="selected"';?>><?=__('Page')?></option>
			</select> <a href="#" onclick="(document.getElementById('article2sidebarhelp').style.display != 'block') ? document.getElementById('article2sidebarhelp').style.display='block' : document.getElementById('article2sidebarhelp').style.display='none'; return false;" onkeypress="(document.getElementById('article2sidebarhelp').style.display != 'block') ? document.getElementById('article2sidebarhelp').style.display='block' : document.getElementById('article2sidebarhelp').style.display='none'; return false;"><?=__('Help')?></a>
		</label>
		<div id="article2sidebarhelp" style="display: none; padding: 3px; font-size: 0.8em; border: sodivd 1px; -moz-border-radius: 3px; -khtml-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px;">
			<div>
				<strong><?=__('Automatic:')?></strong><br />
				<?=__('The widget will look for articles in the specified category, if there are none, the widget will look for articles from a category called as the current page.')?>
			</div>
			<div>
				<strong><?=__('Both:')?></strong><br />
				<?=__('The widget will look for articles in the specified category AND articles from a category called as the current page.')?>
			</div>
			<div>
				<strong><?=__('Category:')?></strong><br />
				<?=__('The widget will look for articles in the specified category.')?>
			</div>
			<div>
				<strong><?=__('Page:')?></strong><br />
				<?=__('The widget will look for articles from a category called as the current page.')?>
			</div>
		</div>
		<hr />
		<label for="articles2sidebar-cat">
			<?=__('Dedicated category name:')?> (<?=__('optional')?>)
			<input style="width: 200px;" id="articles2sidebar-cat" name="articles2sidebar-cat" value="<?=$cat?>" />
		</label>
		<br />
		<label for="articles2sidebar-cat2"><?=__('You can choose a category:')?>
			<select style="width: 200px;" id="articles2sidebar-cat2" name="articles2sidebar-cat2">
				<option value="" selected="selected" disabled="disabled"><?=__('Choose')?></option>
				<?
				$cats = get_categories('hide_empty=0&depth=1');
				foreach ($cats as $c) {
					echo '<option value="' . $c->cat_name . '">' . $c->cat_name . '</option>';
				}
				?>
			</select>
		<hr />
		<label for="articles2sidebar-link">
			<?=__('Put a link to the articles ?')?>
			<select style="width: 200px;" id="articles2sidebar-link" name="articles2sidebar-link">
				<option value="1"<? if ($link == 'yes') echo ' selected="selected"';?>><?=__('Yes')?></option>
				<option value="0"<? if ($link == 'no')  echo ' selected="selected"';?>><?=__('No')?></option>
			</select>
			<div style="padding: 3px; font-size: 0.8em; border: sodivd 1px; -moz-border-radius: 3px; -khtml-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px;">
			<?=__('If set to “no”, nor “learn more” nor “read more“ link nor title link will be added.')?>
			<br />
			<?=__('This behavior can be overriden when adding the “articles2sidebar_display_link” custom var to 1 (yes) ro 0 (no) to the articles.')?>
			</div>
		</label>
		<br />
		<label for="articles2sidebar-lmtext">
			<?=__('“Learn more” link text:')?>
			<input style="width: 200px;" id="articles2sidebar-lmtext" name="articles2sidebar-lmtext" value="<?=$lmtext?>" />
			<div style="padding: 3px; font-size: 0.8em; border: sodivd 1px; -moz-border-radius: 3px; -khtml-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px;">
			<?=__('This text is used to put a link to the article when it got an excerpt.')?>
			</div>
		</label>
		<br />
		<label for="articles2sidebar-rmtext">
			<?=__('“Read more” link text:')?>
			<input style="width: 200px;" id="articles2sidebar-rmtext" name="articles2sidebar-rmtext" value="<?=$rmtext?>" />
			<div style="padding: 3px; font-size: 0.8em; border: sodivd 1px; -moz-border-radius: 3px; -khtml-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px;">
			<?=__('This text is used to put a link to the article when it don\'t have any excerpt.')?>
			</div>
		</label>
		
		<input type="hidden" id="articles2sidebar-submit" name="articles2sidebar-submit" value="1" />
		<?
	} 
	register_sidebar_widget(array('Articles to Sidebar', 'widgets'), 'widget_articles2sidebar');
	register_widget_control(array('Articles to Sidebar', 'widgets'), 'widget_articles2sidebar_control', 400, 100);
}
add_action('widgets_init', 'widget_articles2sidebar_init');

?>
