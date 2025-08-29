<?php
/*
	Plugin Name: Qlassy Ads Manager
	Plugin URI: https://github.com/Souravpandev/Qlassy-Ads-Manager
	Plugin Description: A comprehensive advertising plugin for Question2Answer that allows you to insert AdSense banner ads or custom HTML code in various locations throughout your Q2A website. Features include 16+ ad insertion locations, dynamic ad insertion between questions/answers, sticky ads, ad rotation, device targeting, guest-only targeting, role-based targeting, page exclude/include, AdSense header integration, advertisement labels, and adblock detection.
	Plugin Version: 1.3
	Plugin Date: 2025-01-28
	Plugin Author: Sourav Pan
	Plugin Author URI: https://github.com/Souravpandev
	Plugin License: GPLv3
	Plugin Minimum Question2Answer Version: 1.8
	Plugin Update Check URI: https://raw.githubusercontent.com/Souravpandev/Qlassy-Ads-Manager/main/qa-plugin.php

	This program is free software: you can redistribute it and/or
	modify it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License along
	with this program. If not, see <http://www.gnu.org/licenses/>.

	Developer: Sourav Pan
	Website: https://wpoptimizelab.com/
	GitHub: https://github.com/Souravpandev
	Repository: https://github.com/Souravpandev/Qlassy-Ads-Manager

	FEATURES:
	- 16+ ad insertion locations (header, footer, sidebar, homepage, questions, answers, categories, tags, profiles, search)
	- Dynamic ad insertion between questions and answers with custom frequency
	- Sticky ads (side rails, top, bottom) with close buttons
	- Ad rotation using [QLASSY ROTATE] separator
	- Device targeting with [DESKTOP] and [MOBILE] tags
	- Guest-only and role-based targeting
	- Page exclude/include functionality
	- AdSense header integration
	- Advertisement labels (globally controllable)
	- Adblock detection with non-closable popup
	- Responsive design with professional styling
	- Professional styling and user experience
*/

class qa_html_theme_layer extends qa_html_theme_base
{
	/**
	 * Theme compatibility helper - improved detection
	 */
	private function get_current_theme()
	{
		// First try to get theme from current instance
		if (isset($this->theme)) {
			return $this->theme;
		}
		
		// Try to get theme from Q2A options
		$current_theme = qa_opt('site_theme');
		if (!empty($current_theme)) {
			return strtolower($current_theme);
		}
		
		// Try to detect theme from class name as fallback
		$class_name = get_class($this);
		if (strpos($class_name, 'snowflat') !== false) {
			return 'snowflat';
		} elseif (strpos($class_name, 'snow') !== false) {
			return 'snow';
		} elseif (strpos($class_name, 'classic') !== false) {
			return 'classic';
		} elseif (strpos($class_name, 'candy') !== false) {
			return 'candy';
		} elseif (strpos($class_name, 'qlassy') !== false) {
			return 'qlassy';
		} elseif (strpos($class_name, 'mars') !== false) {
			return 'mars';
		}
		
		// Default fallback
		return 'default';
	}

	/**
	 * Check if ads should be shown on the current page based on exclude/include settings
	 */
	private function should_show_ads()
	{
		// First check if plugin is enabled
		if (!qa_opt('qlassy_ads_enabled')) {
			return false;
		}

		// Then check user targeting rules
		if (!$this->should_show_ads_to_user()) {
			return false;
		}

		// Then check page exclude/include settings
		$mode = qa_opt('qlassy_ads_page_exclude_mode');
		$page_list = qa_opt('qlassy_ads_page_exclude_list');
		
		if (empty($page_list)) {
			return true; // If no pages specified, show ads everywhere
		}
		
		$pages = array_map('trim', explode(',', strtolower($page_list)));
		$current_template = strtolower($this->template);
		$current_request = strtolower(qa_request());
		
		// Check if current page matches any in the list
		$page_matches = false;
		foreach ($pages as $page) {
			$page = trim($page);
			if (empty($page)) continue;
			
			// Check template name
			if ($current_template === $page) {
				$page_matches = true;
				break;
			}
			
			// Check request path
			if (strpos($current_request, $page) === 0) {
				$page_matches = true;
				break;
			}
		}
		
		// Return based on mode
		if ($mode === 'include') {
			return $page_matches; // Show ads only on listed pages
		} else {
			return !$page_matches; // Show ads everywhere except listed pages
		}
	}

	/**
	 * Get processed ad content with rotation and device targeting
	 */
	private function get_processed_ad($ad_code)
	{
		// Debug: Log the original ad code (uncomment for debugging)
		// error_log('Original ad code: ' . substr($ad_code, 0, 100) . '...');
		
		// First apply device targeting
		$ad_code = $this->get_device_targeted_ad($ad_code);
		
		// Debug: Log after device targeting (uncomment for debugging)
		// error_log('After device targeting: ' . substr($ad_code, 0, 100) . '...');
		
		// Then apply rotation
		$ad_code = $this->get_rotated_ad($ad_code);
		
		// Debug: Log after rotation (uncomment for debugging)
		// error_log('After rotation: ' . substr($ad_code, 0, 100) . '...');
		
		return $ad_code;
	}

	/**
	 * Get processed ad content with lazy loading for below-the-fold ads
	 */
	private function get_processed_ad_with_lazy_loading($ad_code, $ad_position = '')
	{
		$processed_ad = $this->get_processed_ad($ad_code);
		
		// Determine if this ad should be lazy loaded
		if ($this->should_lazy_load_ad($ad_position)) {
			return '<div class="qlassy-lazy-ad" data-ad="' . htmlspecialchars($processed_ad, ENT_QUOTES) . '">Loading advertisement...</div>';
		}
		
		return $processed_ad;
	}

	/**
	 * Determine if an ad should be lazy loaded based on its position
	 */
	private function should_lazy_load_ad($ad_position)
	{
		// Above-the-fold ads should load immediately
		$above_fold_positions = array(
			'header', 'question_top', 'homepage_top'
		);
		
		// Below-the-fold ads should be lazy loaded
		$below_fold_positions = array(
			'footer', 'sidebar_bottom', 'question_bottom', 'homepage_bottom',
			'category_bottom', 'tag_bottom', 'between_answers', 'between_questions',
			'answer_bottom', 'search_results'
		);
		
		if (in_array($ad_position, $above_fold_positions)) {
			return false; // Load immediately
		}
		
		if (in_array($ad_position, $below_fold_positions)) {
			return true; // Lazy load
		}
		
		// Default to lazy loading for unknown positions
		return true;
	}

	/**
	 * Get a randomly selected ad from rotation if enabled
	 */
	private function get_rotated_ad($ad_code)
	{
		// If rotation is disabled, return the original ad code
		if (!qa_opt('qlassy_ads_rotation_enabled')) {
			return $ad_code;
		}

		// Check if the ad code contains rotation separator
		if (strpos($ad_code, '[QLASSY ROTATE]') === false) {
			return $ad_code;
		}

		// Split the ad code by the rotation separator
		$ads = explode('[QLASSY ROTATE]', $ad_code);
		
		// Remove empty ads (trim whitespace)
		$ads = array_map('trim', $ads);
		$ads = array_filter($ads, function($ad) {
			return !empty($ad);
		});

		// If no valid ads found, return original
		if (empty($ads)) {
			return $ad_code;
		}

		// Randomly select one ad
		$random_index = array_rand($ads);
		return $ads[$random_index];
	}

	/**
	 * Get device-targeted ad content
	 */
	private function get_device_targeted_ad($ad_code)
	{
		// If device targeting is disabled, return the original ad code
		if (!qa_opt('qlassy_ads_device_targeting_enabled')) {
			return $ad_code;
		}

		// Check if the ad code contains device targeting tags
		if (strpos($ad_code, '[DESKTOP]') === false && strpos($ad_code, '[MOBILE]') === false) {
			return $ad_code;
		}

		// Detect if user is on mobile device
		$is_mobile = $this->is_mobile_device();

		// Extract desktop and mobile content
		$desktop_content = '';
		$mobile_content = '';

		// Extract desktop content
		if (preg_match('/\[DESKTOP\](.*?)\[\/DESKTOP\]/s', $ad_code, $matches)) {
			$desktop_content = trim($matches[1]);
		}

		// Extract mobile content
		if (preg_match('/\[MOBILE\](.*?)\[\/MOBILE\]/s', $ad_code, $matches)) {
			$mobile_content = trim($matches[1]);
		}

		// Return appropriate content based on device
		if ($is_mobile && !empty($mobile_content)) {
			return $mobile_content;
		} elseif (!$is_mobile && !empty($desktop_content)) {
			return $desktop_content;
		}

		// If device-specific content is empty but tags exist, return empty string
		if (strpos($ad_code, '[DESKTOP]') !== false || strpos($ad_code, '[MOBILE]') !== false) {
			return '';
		}

		// If no device-specific content found, return original ad code
		return $ad_code;
	}

	/**
	 * Detect if user is on mobile device
	 */
	private function is_mobile_device()
	{
		$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
		
		// Common mobile device patterns
		$mobile_patterns = array(
			'Android', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 'Windows Phone',
			'Mobile', 'Tablet', 'Opera Mini', 'IEMobile'
		);

		// Check for mobile patterns
		foreach ($mobile_patterns as $pattern) {
			if (stripos($user_agent, $pattern) !== false) {
				return true;
			}
		}

		// Additional check for mobile-specific patterns
		if (preg_match('/(android|webos|iphone|ipad|ipod|blackberry|windows phone)/i', $user_agent)) {
			return true;
		}

		// Check for mobile viewport width (if available via JavaScript)
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
			// For AJAX requests, we might have additional mobile detection
			if (isset($_SERVER['HTTP_X_MOBILE']) && $_SERVER['HTTP_X_MOBILE'] === 'true') {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if ads should be shown based on user targeting rules
	 */
	private function should_show_ads_to_user()
	{
		// Check if user is logged in
		$user_id = qa_get_logged_in_userid();
		
		// Guest-only targeting
		if (qa_opt('qlassy_ads_guest_only_enabled') && $user_id !== null) {
			return false;
		}

		// Role-based targeting
		if (qa_opt('qlassy_ads_hide_from_roles_enabled') && $user_id !== null) {
			$hide_roles = qa_opt('qlassy_ads_hide_from_roles_list');
			if (!empty($hide_roles)) {
				$roles = array_map('trim', explode(',', strtolower($hide_roles)));
				$user_level = qa_get_logged_in_level();
				
				// Check user level against roles
				foreach ($roles as $role) {
					$role = trim($role);
					if (empty($role)) continue;
					
					// Map role names to Q2A user levels
					switch ($role) {
						case 'admin':
							if ($user_level >= QA_USER_LEVEL_ADMIN) return false;
							break;
						case 'moderator':
							if ($user_level >= QA_USER_LEVEL_MODERATOR) return false;
							break;
						case 'editor':
							if ($user_level >= QA_USER_LEVEL_EDITOR) return false;
							break;
						case 'expert':
							if ($user_level >= QA_USER_LEVEL_EXPERT) return false;
							break;
						case 'user':
							if ($user_level >= QA_USER_LEVEL_BASIC) return false;
							break;
					}
				}
			}
		}

		return true;
	}
	/**
	 * Override body_content to add sticky ads only
	 * Compatible with all Q2A themes using proper inheritance
	 */
	public function body_content()
	{
		// Check if ads should be shown on this page
		if (!$this->should_show_ads()) {
			// Call parent method using proper inheritance
			parent::body_content();
			return;
		}

		// Call parent body_content using proper inheritance
		parent::body_content();

		// Add sticky side rail ads if enabled
		if (qa_opt('qlassy_ads_left_rail_enabled') && qa_opt('qlassy_ads_left_rail_code')) {
			$this->output('<div class="qlassy-ads-left-rail">');
			$this->output('<button class="qlassy-ads-close-btn" onclick="this.parentElement.style.display=\'none\';">×</button>');
			$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_left_rail_code')));
			$this->output('</div>');
		}

		if (qa_opt('qlassy_ads_right_rail_enabled') && qa_opt('qlassy_ads_right_rail_code')) {
			$this->output('<div class="qlassy-ads-right-rail">');
			$this->output('<button class="qlassy-ads-close-btn" onclick="this.parentElement.style.display=\'none\';">×</button>');
			$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_right_rail_code')));
			$this->output('</div>');
		}

		// Add sticky top and bottom ads if enabled
		if (qa_opt('qlassy_ads_top_sticky_enabled') && qa_opt('qlassy_ads_top_sticky_code')) {
			$this->output('<div class="qlassy-ads-top-sticky">');
			$this->output('<button class="qlassy-ads-close-btn" onclick="this.parentElement.style.display=\'none\';">×</button>');
			$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_top_sticky_code')));
			$this->output('</div>');
		}

		if (qa_opt('qlassy_ads_bottom_sticky_enabled') && qa_opt('qlassy_ads_bottom_sticky_code')) {
			$this->output('<div class="qlassy-ads-bottom-sticky">');
			$this->output('<button class="qlassy-ads-close-btn" onclick="this.parentElement.style.display=\'none\';">×</button>');
			$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_bottom_sticky_code')));
			$this->output('</div>');
		}
	}



	/**
	 * Override sidepanel to add sidebar ads
	 * Compatible with all Q2A themes using proper inheritance
	 */
	public function sidepanel()
	{
		// Check if ads should be shown on this page
		if (!$this->should_show_ads()) {
			parent::sidepanel();
			return;
		}

		// Get current theme name
		$theme_name = $this->get_current_theme();
		
		// For SnowFlat theme, handle its custom sidepanel structure
		if ($theme_name === 'snowflat') {
			$this->handle_snowflat_sidepanel();
		}
		// For Snow theme, it removes sidebar for user profile pages
		elseif ($theme_name === 'snow') {
			if ($this->template != 'user') {
				$this->handle_standard_sidepanel();
			}
		}
		// For Classic and Candy themes, use standard structure
		elseif (in_array($theme_name, ['classic', 'candy', 'qlassy', 'mars'])) {
			$this->handle_standard_sidepanel();
		}
		// Default fallback
		else {
			$this->handle_standard_sidepanel();
		}
	}

	/**
	 * Handle standard sidepanel structure with ads
	 */
	private function handle_standard_sidepanel()
	{
		// Start the sidepanel div
		$this->output('<div class="qa-sidepanel">');
		
		// Add top sidebar ad if enabled
		if (qa_opt('qlassy_ads_sidebar_top_enabled') && qa_opt('qlassy_ads_sidebar_top_code')) {
			$this->output('<div class="qlassy-ads-sidebar-top">');
			$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_sidebar_top_code')));
			$this->output('</div>');
		}

		// Add widgets at top
		$this->widgets('side', 'top');
		
		// Add sidebar content
		$this->sidebar();
		
		// Add widgets at high position
		$this->widgets('side', 'high');
		
		// Add widgets at low position
		$this->widgets('side', 'low');
		
		// Add raw sidepanel content
		$this->output_raw(@$this->content['sidepanel']);
		
		// Add feed
		$this->feed();
		
		// Add bottom sidebar ad if enabled
		if (qa_opt('qlassy_ads_sidebar_bottom_enabled') && qa_opt('qlassy_ads_sidebar_bottom_code')) {
			$this->output('<div class="qlassy-ads-sidebar-bottom">');
			$this->output_raw($this->get_processed_ad_with_lazy_loading(qa_opt('qlassy_ads_sidebar_bottom_code'), 'sidebar_bottom'));
			$this->output('</div>');
		}
		
		// Add widgets at bottom
		$this->widgets('side', 'bottom');
		
		// Close the sidepanel div
		$this->output('</div>');
	}

	/**
	 * Handle SnowFlat theme's custom sidepanel structure
	 */
	private function handle_snowflat_sidepanel()
	{
		try {
			// Remove sidebar for user profile pages (SnowFlat behavior)
			if ($this->template == 'user') {
				return;
			}

			// Add toggle button (SnowFlat specific)
			$this->output('<div id="qam-sidepanel-toggle"><i class="icon-left-open-big"></i></div>');
			$this->output('<div class="qa-sidepanel" id="qam-sidepanel-mobile">');
			
			// Add search (SnowFlat specific) - use parent search method instead of private qam_search
			$this->output('<div class="qam-search">');
			$this->search();
			$this->output('</div>');
			
			// Add top sidebar ad if enabled
			if (qa_opt('qlassy_ads_sidebar_top_enabled') && qa_opt('qlassy_ads_sidebar_top_code')) {
				$this->output('<div class="qlassy-ads-sidebar-top">');
				$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_sidebar_top_code')));
				$this->output('</div>');
			}

			// Add widgets at top
			$this->widgets('side', 'top');
			
			// Add sidebar content (SnowFlat specific)
			$this->sidebar();
			
			// Add widgets at high position
			$this->widgets('side', 'high');
			
			// Add widgets at low position
			$this->widgets('side', 'low');
			
			// Add raw sidepanel content
			if (isset($this->content['sidepanel'])) {
				$this->output_raw($this->content['sidepanel']);
			}
			
			// Add feed
			$this->feed();
			
			// Add bottom sidebar ad if enabled
			if (qa_opt('qlassy_ads_sidebar_bottom_enabled') && qa_opt('qlassy_ads_sidebar_bottom_code')) {
				$this->output('<div class="qlassy-ads-sidebar-bottom">');
				$this->output_raw($this->get_processed_ad_with_lazy_loading(qa_opt('qlassy_ads_sidebar_bottom_code'), 'sidebar_bottom'));
				$this->output('</div>');
			}
			
			// Add widgets at bottom
			$this->widgets('side', 'bottom');
			
			// Close the sidepanel div
			$this->output('</div> <!-- qa-sidepanel -->', '');
		} catch (Exception $e) {
			// Fallback to standard sidepanel if there's an error
			$this->handle_standard_sidepanel();
		}
	}



	/**
	 * Override main to add main content area ads
	 * Compatible with all Q2A themes using proper inheritance
	 */
	public function main()
	{
		// Get current theme name
		$theme_name = $this->get_current_theme();
		
		// Only add top ads if they should be shown
		if ($this->should_show_ads()) {
			// Add homepage top ad if enabled and on homepage
			if (($this->template == 'qa' || empty($this->template) || $this->template == 'questions') && qa_opt('qlassy_ads_homepage_top_enabled') && qa_opt('qlassy_ads_homepage_top_code')) {
				$this->output('<div class="qlassy-ads-homepage-top">');
				$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_homepage_top_code')));
				$this->output('</div>');
			}

			// Add category top ad if enabled and on category page
			if ($this->template == 'questions' && qa_opt('qlassy_ads_category_top_enabled') && qa_opt('qlassy_ads_category_top_code')) {
				$this->output('<div class="qlassy-ads-category-top">');
				$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_category_top_code')));
				$this->output('</div>');
			}

			// Add tag top ad if enabled and on tag page
			if (($this->template == 'tag' || $this->template == 'tags') && qa_opt('qlassy_ads_tag_top_enabled') && qa_opt('qlassy_ads_tag_top_code')) {
				$this->output('<div class="qlassy-ads-tag-top">');
				$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_tag_top_code')));
				$this->output('</div>');
			}
		}

		// For SnowFlat theme, we need to handle the main content differently
		if ($theme_name === 'snowflat') {
			$this->handle_snowflat_main();
		} else {
			// For other themes, use standard approach
			$this->handle_standard_main();
		}
	}

	/**
	 * Handle SnowFlat theme's main content with proper ad placement
	 */
	private function handle_snowflat_main()
	{
		$content = $this->content;
		$hidden = !empty($content['hidden']) ? ' qa-main-hidden' : '';

		$this->output('<div class="qa-main' . $hidden . '">');

		$this->widgets('main', 'top');

		$this->page_title_error();

		$this->widgets('main', 'high');

		$this->main_parts($content);

		$this->widgets('main', 'low');

		$this->page_links();
		$this->suggest_next();

		// Add homepage bottom ad INSIDE qa-main div for SnowFlat theme
		if ($this->should_show_ads() && ($this->template == 'qa' || empty($this->template) || $this->template == 'questions') && qa_opt('qlassy_ads_homepage_bottom_enabled') && qa_opt('qlassy_ads_homepage_bottom_code') && $this->get_current_theme() === 'snowflat') {
			$this->output('<div class="qlassy-ads-homepage-bottom">');
			$this->output_raw($this->get_processed_ad_with_lazy_loading(qa_opt('qlassy_ads_homepage_bottom_code'), 'homepage_bottom'));
			$this->output('</div>');
		}

		// Add category bottom ad INSIDE qa-main div for SnowFlat theme
		if ($this->should_show_ads() && $this->template == 'questions' && qa_opt('qlassy_ads_category_bottom_enabled') && qa_opt('qlassy_ads_category_bottom_code')) {
			$this->output('<div class="qlassy-ads-category-bottom">');
			$this->output_raw($this->get_processed_ad_with_lazy_loading(qa_opt('qlassy_ads_category_bottom_code'), 'category_bottom'));
			$this->output('</div>');
		}

		// Add tag bottom ad INSIDE qa-main div for SnowFlat theme
		if ($this->should_show_ads() && ($this->template == 'tag' || $this->template == 'tags') && qa_opt('qlassy_ads_tag_bottom_enabled') && qa_opt('qlassy_ads_tag_bottom_code')) {
			$this->output('<div class="qlassy-ads-tag-bottom">');
			$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_tag_bottom_code')));
			$this->output('</div>');
		}

		$this->widgets('main', 'bottom');

		$this->output('</div> <!-- END qa-main -->', '');
	}

	/**
	 * Handle standard theme's main content
	 */
	private function handle_standard_main()
	{
		// Always call parent main to ensure proper content rendering
		// This is critical for proper question list rendering
		parent::main();

		// Only add bottom ads if they should be shown
		if ($this->should_show_ads()) {
			// Add category bottom ad if enabled and on category page
			if ($this->template == 'questions' && qa_opt('qlassy_ads_category_bottom_enabled') && qa_opt('qlassy_ads_category_bottom_code')) {
				$this->output('<div class="qlassy-ads-category-bottom">');
				$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_category_bottom_code')));
				$this->output('</div>');
			}

			// Add tag bottom ad if enabled and on tag page
			if (($this->template == 'tag' || $this->template == 'tags') && qa_opt('qlassy_ads_tag_bottom_enabled') && qa_opt('qlassy_ads_tag_bottom_code')) {
				$this->output('<div class="qlassy-ads-tag-bottom">');
				$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_tag_bottom_code')));
				$this->output('</div>');
			}

			// Add homepage bottom ad if enabled and on homepage (SnowFlat theme only)
			if (($this->template == 'qa' || empty($this->template) || $this->template == 'questions') && qa_opt('qlassy_ads_homepage_bottom_enabled') && qa_opt('qlassy_ads_homepage_bottom_code') && $this->get_current_theme() === 'snowflat') {
				$this->output('<div class="qlassy-ads-homepage-bottom">');
				$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_homepage_bottom_code')));
				$this->output('</div>');
			}
		}
	}

	/**
	 * Override q_view to add question page ads
	 */
	public function q_view($q_view)
	{
		// Check if ads should be shown on this page
		if (!$this->should_show_ads()) {
			parent::q_view($q_view);
			return;
		}

		// Add question top ad if enabled and on question page
		if ($this->template == 'question' && qa_opt('qlassy_ads_question_top_enabled') && qa_opt('qlassy_ads_question_top_code')) {
			$this->output('<div class="qlassy-ads-question-top">');
			$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_question_top_code')));
			$this->output('</div>');
		}

		// Call parent q_view using proper inheritance
		parent::q_view($q_view);

		// Add question bottom ad if enabled and on question page
		if ($this->template == 'question' && qa_opt('qlassy_ads_question_bottom_enabled') && qa_opt('qlassy_ads_question_bottom_code')) {
			$this->output('<div class="qlassy-ads-question-bottom">');
			$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_question_bottom_code')));
			$this->output('</div>');
		}
	}

	/**
	 * Override a_list to add answer ads and between answers ads
	 */
	public function a_list($a_list)
	{
		// Check if ads should be shown on this page
		if (!$this->should_show_ads()) {
			parent::a_list($a_list);
			return;
		}

		// Add answer top ad if enabled and on question page
		if ($this->template == 'question' && qa_opt('qlassy_ads_answer_top_enabled') && qa_opt('qlassy_ads_answer_top_code')) {
			$this->output('<div class="qlassy-ads-answer-top">');
			$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_answer_top_code')));
			$this->output('</div>');
		}

		// Call parent a_list using proper inheritance
		parent::a_list($a_list);

		// Add answer bottom ad if enabled and on question page
		if ($this->template == 'question' && qa_opt('qlassy_ads_answer_bottom_enabled') && qa_opt('qlassy_ads_answer_bottom_code')) {
			$this->output('<div class="qlassy-ads-answer-bottom">');
			$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_answer_bottom_code')));
			$this->output('</div>');
		}

		// Between answers ads are now handled by external JavaScript
	}

	/**
	 * Override q_list to add between questions ads
	 */
	public function q_list($q_list)
	{
		// Always call parent q_list first to ensure questions are rendered properly
		// This is critical for proper question list rendering
		parent::q_list($q_list);

		// Between questions ads are now handled by external JavaScript
		// No need to check should_show_ads() here as it's handled in JavaScript
	}

	/**
	 * Override a_item to ensure proper answer rendering
	 */
	public function a_item($a_item)
	{
		// Always call parent a_item to ensure answers are rendered properly
		parent::a_item($a_item);
	}

	/**
	 * Override page_title_error to ensure proper error display
	 */
	public function page_title_error()
	{
		// Always call parent page_title_error to ensure errors are displayed properly
		parent::page_title_error();
	}

	/**
	 * Override main_parts to ensure proper content rendering
	 */
	public function main_parts($content)
	{
		// Always call parent main_parts first to ensure content is rendered properly
		parent::main_parts($content);
	}

	/**
	 * Override profile_header to add user profile ads
	 * Compatible with SnowFlat, Snow, Classic, and Candy themes
	 */
	public function profile_header($useraccount, $userprofile, $userpoints)
	{
		// Check if ads should be shown on this page
		if (!$this->should_show_ads()) {
			parent::profile_header($useraccount, $userprofile, $userpoints);
			return;
		}

		// Add user profile ad if enabled and on user profile page
		if ($this->template == 'user' && qa_opt('qlassy_ads_user_profile_enabled') && qa_opt('qlassy_ads_user_profile_code')) {
			$this->output('<div class="qlassy-ads-user-profile">');
			$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_user_profile_code')));
			$this->output('</div>');
		}

		// Call parent profile_header using proper inheritance
		parent::profile_header($useraccount, $userprofile, $userpoints);
	}

	/**
	 * Override header to add header ads for theme compatibility
	 * This method is called by some themes to customize header
	 */
	public function header()
	{
		// Check if ads should be shown on this page
		if (!$this->should_show_ads()) {
			parent::header();
			return;
		}

		// Add header ad if enabled (before header content)
		if (qa_opt('qlassy_ads_header_enabled') && qa_opt('qlassy_ads_header_code')) {
			$this->output('<div class="qlassy-ads-header">');
			$this->output_raw($this->get_processed_ad_with_lazy_loading(qa_opt('qlassy_ads_header_code'), 'header'));
			$this->output('</div>');
		}

		// Call parent header using proper inheritance
		parent::header();
	}

	/**
	 * Override footer to add footer ads for theme compatibility
	 * This method is called by some themes to customize footer
	 */
	public function footer()
	{
		// Check if ads should be shown on this page
		if (!$this->should_show_ads()) {
			parent::footer();
			return;
		}

		// Call parent footer first using proper inheritance
		parent::footer();

		// Add footer ad if enabled (after footer content)
		if (qa_opt('qlassy_ads_footer_enabled') && qa_opt('qlassy_ads_footer_code')) {
			$this->output('<div class="qlassy-ads-footer">');
			$this->output_raw($this->get_processed_ad_with_lazy_loading(qa_opt('qlassy_ads_footer_code'), 'footer'));
			$this->output('</div>');
		}
	}

	/**
	 * Override head_script to add AdSense header code and optimized JavaScript loading
	 */
	public function head_script()
	{
		// Call parent head_script using proper inheritance
		parent::head_script();

		// Add preconnect links for AdSense domains to improve LCP
		if ($this->should_show_ads()) {
			$this->output('<link rel="preconnect" href="https://pagead2.googlesyndication.com">');
			$this->output('<link rel="preconnect" href="https://tpc.googlesyndication.com">');
			$this->output('<link rel="dns-prefetch" href="https://pagead2.googlesyndication.com">');
		}

		// Add AdSense header code if enabled (critical for AdSense)
		if (qa_opt('qlassy_ads_adsense_header_enabled') && qa_opt('qlassy_ads_adsense_header_code')) {
			$this->output_raw(qa_opt('qlassy_ads_adsense_header_code'));
		}

		// Add external JavaScript file with defer for non-blocking loading (minified)
		$this->output('<script type="text/javascript" defer src="' . qa_html(QA_HTML_THEME_LAYER_URLTOROOT . 'js/qlassy-ads.min.js') . '"></script>');

		// Add JavaScript data for ads (inline for immediate availability)
		if ($this->should_show_ads()) {
			$this->output('<script type="text/javascript">');
			$this->output('var qlassyAdsData = {');
			$this->output('    betweenAnswersEnabled: ' . (qa_opt('qlassy_ads_between_answers_enabled') ? 'true' : 'false') . ',');
			$this->output('    betweenQuestionsEnabled: ' . (qa_opt('qlassy_ads_between_questions_enabled') ? 'true' : 'false') . ',');
			$this->output('    adblockDetectionEnabled: ' . (qa_opt('qlassy_ads_adblock_detection_enabled') ? 'true' : 'false') . ',');
			$this->output('    showAdvertisementLabel: ' . (qa_opt('qlassy_ads_show_advertisement_label') ? 'true' : 'false') . ',');
			
			if (qa_opt('qlassy_ads_between_answers_enabled') && qa_opt('qlassy_ads_between_answers_code')) {
				$this->output('    betweenAnswersCode: \'' . addslashes($this->get_processed_ad(qa_opt('qlassy_ads_between_answers_code'))) . '\',');
				$this->output('    betweenAnswersFrequency: "' . addslashes(qa_opt('qlassy_ads_between_answers_frequency')) . '",');
			}
			
			if (qa_opt('qlassy_ads_between_questions_enabled') && qa_opt('qlassy_ads_between_questions_code')) {
				$this->output('    betweenQuestionsCode: \'' . addslashes($this->get_processed_ad(qa_opt('qlassy_ads_between_questions_code'))) . '\',');
				$this->output('    betweenQuestionsFrequency: "' . addslashes(qa_opt('qlassy_ads_between_questions_frequency')) . '"');
			}
			
			$this->output('};');
			$this->output('</script>');

			// Add adblock detection bait element if enabled
			if (qa_opt('qlassy_ads_adblock_detection_enabled')) {
				// Add CSS-based detection first
				$this->output('<style type="text/css">');
				$this->output('/* Adblock detection using CSS */');
				$this->output('.qlassy-adblock-test {');
				$this->output('    position: absolute;');
				$this->output('    left: -9999px;');
				$this->output('    top: -9999px;');
				$this->output('    width: 1px;');
				$this->output('    height: 1px;');
				$this->output('    background: transparent;');
				$this->output('    z-index: -1;');
				$this->output('}');
				$this->output('</style>');
				
				// Add multiple bait elements with different class names
				$this->output('<div id="qlassy-ad-test" class="ads ad-banner advertisement qlassy-adblock-test"></div>');
				$this->output('<div class="ads ad-banner advertisement qlassy-adblock-test"></div>');
				$this->output('<div class="advertisement ads qlassy-adblock-test"></div>');
				
				// Add inline adblock detection that works even when JS is blocked
				$this->output('<script type="text/javascript">');
				$this->output('(function() {');
				$this->output('    // Inline adblock detection');
				$this->output('    var adBlocked = false;');
				$this->output('    var popupShown = false;');
				$this->output('    ');
				$this->output('    function showAdblockPopup() {');
				$this->output('        if (popupShown) return;');
				$this->output('        popupShown = true;');
				$this->output('        ');
				$this->output('        var overlay = document.createElement("div");');
				$this->output('        overlay.id = "qlassy-adblock-overlay";');
				$this->output('        overlay.style.cssText = "position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 999999; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);";');
				$this->output('        ');
				$this->output('        var popup = document.createElement("div");');
				$this->output('        popup.id = "qlassy-adblock-popup";');
				$this->output('        popup.style.cssText = "background: white; padding: 30px; border-radius: 6px; max-width: 500px; text-align: center; box-shadow: 0 3px 5px rgba(186,186,186,0.3); border: 1px solid #e5e7eb;";');
				$this->output('        ');
				$this->output('        popup.innerHTML = \'<div style="margin-bottom: 20px;"><svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#e74c3c" stroke-width="2" style="margin: 0 auto 15px;"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg><h2 style="color: #e74c3c; margin: 0 0 15px 0; font-size: 24px;">Ad Blocker Detected!</h2><p style="color: #555; line-height: 1.6; margin: 0 0 20px 0;">We noticed you are using an Ad Blocker. Please consider disabling it to support our website.</p></div><div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: left;"><h4 style="margin: 0 0 10px 0; color: #333;">How to disable ad blocker:</h4><ul style="margin: 0; color: #666;"><li>Click the ad blocker icon in your browser toolbar</li><li>Select "Disable for this site" or "Allow ads"</li><li>Refresh this page</li></ul></div><button onclick="location.reload()" style="background: #000000; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 12px; font-weight: bold; line-height: 18px;">Okay, I will Whitelist</button>\';');
				$this->output('        ');
				$this->output('        overlay.appendChild(popup);');
				$this->output('        document.body.appendChild(overlay);');
				$this->output('        ');
				$this->output('        // Prevent closing');
				$this->output('        document.addEventListener("keydown", function(e) { if (e.key === "Escape") { e.preventDefault(); return false; } });');
				$this->output('        overlay.addEventListener("click", function(e) { if (e.target === overlay) { e.preventDefault(); return false; } });');
				$this->output('        overlay.addEventListener("contextmenu", function(e) { e.preventDefault(); return false; });');
				$this->output('    }');
				$this->output('    ');
				$this->output('    function detectAdBlock() {');
				$this->output('        // Check bait elements');
				$this->output('        var baitElements = document.querySelectorAll(".qlassy-adblock-test");');
				$this->output('        ');
				$this->output('        for (var i = 0; i < baitElements.length; i++) {');
				$this->output('            var rect = baitElements[i].getBoundingClientRect();');
				$this->output('            var style = window.getComputedStyle(baitElements[i]);');
				$this->output('            ');
				$this->output('            if (rect.width === 0 || rect.height === 0 || style.display === "none" || style.visibility === "hidden" || style.opacity === "0") {');
				$this->output('                adBlocked = true;');
				$this->output('            }');
				$this->output('        }');
				$this->output('        ');
				$this->output('        // Check ad elements');
				$this->output('        var adElements = document.querySelectorAll(".qlassy-ads-header, .qlassy-ads-footer, .qlassy-ads-sidebar-top, .qlassy-ads-sidebar-bottom");');
				$this->output('        ');
				$this->output('        var hiddenAds = 0;');
				$this->output('        for (var i = 0; i < adElements.length; i++) {');
				$this->output('            var rect = adElements[i].getBoundingClientRect();');
				$this->output('            var style = window.getComputedStyle(adElements[i]);');
				$this->output('            ');
				$this->output('            if (rect.width === 0 || rect.height === 0 || style.display === "none" || style.visibility === "hidden" || style.opacity === "0") {');
				$this->output('                hiddenAds++;');
				$this->output('            }');
				$this->output('        }');
				$this->output('        ');
				$this->output('        if (adElements.length > 0 && (hiddenAds / adElements.length) >= 0.5) {');
				$this->output('            adBlocked = true;');
				$this->output('        }');
				$this->output('        ');
				$this->output('        // Check ad images');
				$this->output('        var adImages = document.querySelectorAll(".qlassy-ads-header img, .qlassy-ads-footer img, .qlassy-ads-sidebar-top img, .qlassy-ads-sidebar-bottom img");');
				$this->output('        ');
				$this->output('        var blockedImages = 0;');
				$this->output('        for (var i = 0; i < adImages.length; i++) {');
				$this->output('            if (adImages[i].naturalWidth === 0 || adImages[i].naturalHeight === 0) {');
				$this->output('                blockedImages++;');
				$this->output('            }');
				$this->output('        }');
				$this->output('        ');
				$this->output('        if (adImages.length > 0 && (blockedImages / adImages.length) >= 0.5) {');
				$this->output('            adBlocked = true;');
				$this->output('        }');
				$this->output('        ');
				$this->output('        // Show popup if adblock detected');
				$this->output('        if (adBlocked) {');
				$this->output('            showAdblockPopup();');
				$this->output('        }');
				$this->output('    }');
				$this->output('    ');
				$this->output('    // Run detection multiple times');
				$this->output('    setTimeout(detectAdBlock, 1000);');
				$this->output('    setTimeout(detectAdBlock, 2000);');
				$this->output('    setTimeout(detectAdBlock, 3000);');
				$this->output('    setTimeout(detectAdBlock, 5000);');
				$this->output('    ');
				$this->output('    // Also run when DOM is ready');
				$this->output('    if (document.readyState === "loading") {');
				$this->output('        document.addEventListener("DOMContentLoaded", function() {');
				$this->output('            setTimeout(detectAdBlock, 500);');
				$this->output('        });');
				$this->output('    } else {');
				$this->output('        setTimeout(detectAdBlock, 500);');
				$this->output('    }');
				$this->output('})();');
				$this->output('</script>');
			}
		}
	}

	/**
	 * Override head_css to add custom CSS for ads
	 * Compatible with SnowFlat, Snow, Classic, and Candy themes
	 */
	public function head_css()
	{
		// Call parent head_css
		qa_html_theme_base::head_css();

		// Add critical CSS inline for immediate rendering
		$this->output('<style type="text/css">');
		$this->output('/* Critical ad container styles for immediate rendering */');
		$this->output('.qlassy-ads-header, .qlassy-ads-footer, .qlassy-ads-sidebar-top, .qlassy-ads-sidebar-bottom,');
		$this->output('.qlassy-ads-main-top, .qlassy-ads-question-top, .qlassy-ads-question-bottom,');
		$this->output('.qlassy-ads-answer-top, .qlassy-ads-answer-bottom, .qlassy-ads-between-answers,');
		$this->output('.qlassy-ads-between-questions, .qlassy-ads-homepage-top, .qlassy-ads-homepage-bottom,');
		$this->output('.qlassy-ads-category-top, .qlassy-ads-category-bottom, .qlassy-ads-tag-top, .qlassy-ads-tag-bottom,');
		$this->output('.qlassy-ads-user-profile, .qlassy-ads-search-results {');
		$this->output('margin: 15px 0; padding: 10px; text-align: center; border-radius: 4px;');
		$this->output('background: #ffffff; border: 1px solid #e9ecef; position: relative; z-index: 1;');
		$this->output('}');
		$this->output('.qlassy-ads-homepage-bottom { margin: 15px 0; padding: 10px; text-align: center;');
		$this->output('border-radius: 4px; background: #ffffff; border: 1px solid #e9ecef; }');
		
		// Add ad space reservation to prevent layout shifts
		$this->output('/* Ad space reservation for LCP optimization */');
		$this->output('.qlassy-ads-question-top, .qlassy-ads-question-bottom,');
		$this->output('.qlassy-ads-homepage-top, .qlassy-ads-homepage-bottom {');
		$this->output('    display: block;');
		$this->output('    min-height: 250px; /* Reserve space for standard ad slots */');
		$this->output('}');
		$this->output('.qlassy-ads-header, .qlassy-ads-footer {');
		$this->output('    display: block;');
		$this->output('    min-height: 90px; /* Reserve space for header/footer ads */');
		$this->output('}');
		$this->output('.qlassy-ads-sidebar-top, .qlassy-ads-sidebar-bottom {');
		$this->output('    display: block;');
		$this->output('    min-height: 250px; /* Reserve space for sidebar ads */');
		$this->output('}');
		$this->output('.qlassy-ads-between-answers, .qlassy-ads-between-questions {');
		$this->output('    display: block;');
		$this->output('    min-height: 250px; /* Reserve space for between content ads */');
		$this->output('}');
		$this->output('.qlassy-lazy-ad {');
		$this->output('    display: block;');
		$this->output('    min-height: 250px; /* Reserve space for lazy-loaded ads */');
		$this->output('    background: #f8f9fa; /* Placeholder background */');
		$this->output('    border: 1px dashed #dee2e6; /* Placeholder border */');
		$this->output('    border-radius: 4px;');
		$this->output('    margin: 15px 0;');
		$this->output('    text-align: center;');
		$this->output('    line-height: 250px; /* Center placeholder text */');
		$this->output('    color: #6c757d;');
		$this->output('    font-size: 14px;');
		$this->output('    position: relative;');
		$this->output('}');
		$this->output('.qlassy-lazy-ad::before {');
		$this->output('    content: "Loading advertisement...";');
		$this->output('    position: absolute;');
		$this->output('    top: 50%;');
		$this->output('    left: 50%;');
		$this->output('    transform: translate(-50%, -50%);');
		$this->output('    color: #6c757d;');
		$this->output('    font-size: 14px;');
		$this->output('    z-index: 1;');
		$this->output('}');
		$this->output('.qlassy-lazy-ad:not(:empty)::before {');
		$this->output('    display: none;');
		$this->output('}');
		$this->output('.qlassy-lazy-ad img, .qlassy-lazy-ad iframe, .qlassy-lazy-ad ins {');
		$this->output('    display: block;');
		$this->output('    max-width: 100%;');
		$this->output('    height: auto;');
		$this->output('}');
		$this->output('</style>');

		// Add external CSS file with media="print" and onload for non-blocking loading (minified)
		$this->output('<link rel="stylesheet" href="' . qa_html(QA_HTML_THEME_LAYER_URLTOROOT . 'css/qlassy-ads.min.css') . '" media="print" onload="this.media=\'all\'"/>');
		$this->output('<noscript><link rel="stylesheet" href="' . qa_html(QA_HTML_THEME_LAYER_URLTOROOT . 'css/qlassy-ads.min.css') . '"/></noscript>');

		// Add theme-specific CSS adjustments inline
		$this->add_theme_specific_css();

		// Add dynamic CSS for advertisement labels if enabled
		if (qa_opt('qlassy_ads_show_advertisement_label')) {
			$this->output('<style type="text/css">');
			$this->output('.qlassy-ads-header, .qlassy-ads-footer, .qlassy-ads-sidebar-top, .qlassy-ads-sidebar-bottom,');
			$this->output('.qlassy-ads-question-top, .qlassy-ads-question-bottom,');
			$this->output('.qlassy-ads-answer-top, .qlassy-ads-answer-bottom, .qlassy-ads-homepage-top, .qlassy-ads-homepage-bottom,');
			$this->output('.qlassy-ads-category-top, .qlassy-ads-category-bottom, .qlassy-ads-tag-top, .qlassy-ads-tag-bottom,');
			$this->output('.qlassy-ads-user-profile, .qlassy-ads-search-results, .qlassy-ads-left-rail, .qlassy-ads-right-rail,');
			$this->output('.qlassy-ads-top-sticky, .qlassy-ads-bottom-sticky {');
			$this->output('position: relative;');
			$this->output('}');
			$this->output('.qlassy-ads-header::before, .qlassy-ads-footer::before, .qlassy-ads-sidebar-top::before, .qlassy-ads-sidebar-bottom::before,');
			$this->output('.qlassy-ads-question-top::before, .qlassy-ads-question-bottom::before,');
			$this->output('.qlassy-ads-answer-top::before, .qlassy-ads-answer-bottom::before, .qlassy-ads-homepage-top::before, .qlassy-ads-homepage-bottom::before,');
			$this->output('.qlassy-ads-category-top::before, .qlassy-ads-category-bottom::before, .qlassy-ads-tag-top::before, .qlassy-ads-tag-bottom::before,');
			$this->output('.qlassy-ads-user-profile::before, .qlassy-ads-search-results::before, .qlassy-ads-left-rail::before, .qlassy-ads-right-rail::before,');
			$this->output('.qlassy-ads-top-sticky::before, .qlassy-ads-bottom-sticky::before {');
			$this->output('content: "Advertisement";');
			$this->output('position: absolute;');
			$this->output('top: -10px;');
			$this->output('left: 50%;');
			$this->output('transform: translateX(-50%);');
			$this->output('color: #6d6d6d;');
			$this->output('font-size: 11px;');
			$this->output('z-index: 10;');
			$this->output('}');
			$this->output('</style>');
		}
	}

	/**
	 * Add theme-specific CSS adjustments
	 */
	private function add_theme_specific_css()
	{
		$theme_name = $this->get_current_theme();
		
		$this->output('<style type="text/css">');
		
		// SnowFlat theme adjustments
		if ($theme_name === 'snowflat') {
			$this->output('/* SnowFlat theme compatibility */');
			$this->output('.qlassy-ads-sidebar-top, .qlassy-ads-sidebar-bottom {');
			$this->output('    margin: 10px 0;');
			$this->output('    padding: 8px;');
			$this->output('    border-radius: 4px;');
			$this->output('    background: #ffffff;');
			$this->output('    border: 1px solid #e9ecef;');
			$this->output('}');
			$this->output('.qlassy-ads-left-rail, .qlassy-ads-right-rail {');
			$this->output('    z-index: 9998; /* Below SnowFlat topbar */');
			$this->output('}');
		}
		// Snow theme adjustments
		elseif ($theme_name === 'snow') {
			$this->output('/* Snow theme compatibility */');
			$this->output('.qlassy-ads-header, .qlassy-ads-footer {');
			$this->output('    margin: 15px 0;');
			$this->output('    padding: 10px;');
			$this->output('    background: #ffffff;');
			$this->output('    border: 1px solid #e9ecef;');
			$this->output('}');
		}
		// Classic theme adjustments
		elseif ($theme_name === 'classic') {
			$this->output('/* Classic theme compatibility */');
			$this->output('.qlassy-ads-header, .qlassy-ads-footer, .qlassy-ads-sidebar-top, .qlassy-ads-sidebar-bottom {');
			$this->output('    margin: 10px 0;');
			$this->output('    padding: 8px;');
			$this->output('    background: #f8f9fa;');
			$this->output('    border: 1px solid #dee2e6;');
			$this->output('}');
		}
		// Candy theme adjustments
		elseif ($theme_name === 'candy') {
			$this->output('/* Candy theme compatibility */');
			$this->output('.qlassy-ads-header, .qlassy-ads-footer, .qlassy-ads-sidebar-top, .qlassy-ads-sidebar-bottom {');
			$this->output('    margin: 12px 0;');
			$this->output('    padding: 10px;');
			$this->output('    background: #ffffff;');
			$this->output('    border: 1px solid #e0e0e0;');
			$this->output('    border-radius: 3px;');
			$this->output('}');
		}
		
		$this->output('</style>');
	}

	/**
	 * Override main_part to add search results ads
	 */
	public function main_part($key, $part)
	{
		// Always call parent main_part first to ensure content is rendered properly
		parent::main_part($key, $part);

		// Add search results ad if enabled and on search results page
		if ($this->should_show_ads() && $this->template == 'search' && $key == 'q_list' && qa_opt('qlassy_ads_search_results_enabled') && qa_opt('qlassy_ads_search_results_code')) {
			$this->output('<div class="qlassy-ads-search-results">');
			$this->output_raw($this->get_processed_ad(qa_opt('qlassy_ads_search_results_code')));
			$this->output('</div>');
		}
	}
}
