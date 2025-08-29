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

class qa_qlassy_ads_admin
{
	public function init_queries($tableslc)
	{
		return null;
	}

	public function option_default($option)
	{
		switch ($option) {
			case 'qlassy_ads_enabled':
				return '1';
			case 'qlassy_ads_header_enabled':
				return '1';
			case 'qlassy_ads_header_code':
				return '';
			case 'qlassy_ads_footer_enabled':
				return '1';
			case 'qlassy_ads_footer_code':
				return '';
			case 'qlassy_ads_sidebar_top_enabled':
				return '1';
			case 'qlassy_ads_sidebar_top_code':
				return '';
			case 'qlassy_ads_sidebar_bottom_enabled':
				return '1';
			case 'qlassy_ads_sidebar_bottom_code':
				return '';


			case 'qlassy_ads_question_top_enabled':
				return '1';
			case 'qlassy_ads_question_top_code':
				return '';
			case 'qlassy_ads_question_bottom_enabled':
				return '1';
			case 'qlassy_ads_question_bottom_code':
				return '';
			case 'qlassy_ads_answer_top_enabled':
				return '1';
			case 'qlassy_ads_answer_top_code':
				return '';
			case 'qlassy_ads_answer_bottom_enabled':
				return '1';
			case 'qlassy_ads_answer_bottom_code':
				return '';
			case 'qlassy_ads_between_answers_enabled':
				return '1';
			case 'qlassy_ads_between_answers_code':
				return '';
			case 'qlassy_ads_between_answers_frequency':
				return '2';
			case 'qlassy_ads_homepage_top_enabled':
				return '1';
			case 'qlassy_ads_homepage_top_code':
				return '';
			case 'qlassy_ads_homepage_bottom_enabled':
				return '1';
			case 'qlassy_ads_homepage_bottom_code':
				return '';
			case 'qlassy_ads_category_top_enabled':
				return '1';
			case 'qlassy_ads_category_top_code':
				return '';
			case 'qlassy_ads_category_bottom_enabled':
				return '1';
			case 'qlassy_ads_category_bottom_code':
				return '';
			case 'qlassy_ads_tag_top_enabled':
				return '1';
			case 'qlassy_ads_tag_top_code':
				return '';
			case 'qlassy_ads_tag_bottom_enabled':
				return '1';
			case 'qlassy_ads_tag_bottom_code':
				return '';
			case 'qlassy_ads_user_profile_enabled':
				return '1';
			case 'qlassy_ads_user_profile_code':
				return '';
			case 'qlassy_ads_search_results_enabled':
				return '1';
			case 'qlassy_ads_search_results_code':
				return '';
			case 'qlassy_ads_between_questions_enabled':
				return '1';
			case 'qlassy_ads_between_questions_code':
				return '';
			case 'qlassy_ads_between_questions_frequency':
				return '2';
			case 'qlassy_ads_left_rail_enabled':
				return '1';
			case 'qlassy_ads_left_rail_code':
				return '';
			case 'qlassy_ads_right_rail_enabled':
				return '1';
			case 'qlassy_ads_right_rail_code':
				return '';
			case 'qlassy_ads_show_advertisement_label':
				return '1';
			case 'qlassy_ads_page_exclude_mode':
				return 'exclude'; // 'exclude' or 'include'
			case 'qlassy_ads_page_exclude_list':
				return 'admin,login,register,account,confirm,forgot,reset,feedback';
			case 'qlassy_ads_top_sticky_enabled':
				return '0';
			case 'qlassy_ads_top_sticky_code':
				return '';
			case 'qlassy_ads_bottom_sticky_enabled':
				return '0';
			case 'qlassy_ads_bottom_sticky_code':
				return '';
			case 'qlassy_ads_adsense_header_enabled':
				return '0';
			case 'qlassy_ads_adsense_header_code':
				return '';
			case 'qlassy_ads_rotation_enabled':
				return '1';
			case 'qlassy_ads_guest_only_enabled':
				return '0';
			case 'qlassy_ads_device_targeting_enabled':
				return '1';
			case 'qlassy_ads_hide_from_roles_enabled':
				return '0';
			case 'qlassy_ads_hide_from_roles_list':
				return 'admin,moderator';
			case 'qlassy_ads_adblock_detection_enabled':
				return '0';

			default:
				return null;
		}
	}

	public function admin_form(&$qa_content)
	{
		$saved = false;

		if (qa_clicked('qlassy_ads_save_button')) {
			// Save main plugin enable/disable setting
			qa_opt('qlassy_ads_enabled', (bool)qa_post_text('qlassy_ads_enabled_field'));
			
			// Save all ad settings
			$ad_locations = array(
				'header', 'footer', 'sidebar_top', 'sidebar_bottom',
				'question_top', 'question_bottom', 'answer_top', 'answer_bottom', 'between_answers',
				'homepage_top', 'category_top', 'category_bottom',
				'tag_top', 'tag_bottom', 'user_profile', 'search_results', 'between_questions',
				'left_rail', 'right_rail'
			);

			foreach ($ad_locations as $location) {
				$enabled_key = 'qlassy_ads_' . $location . '_enabled';
				$code_key = 'qlassy_ads_' . $location . '_code';
				
				qa_opt($enabled_key, (bool)qa_post_text($enabled_key . '_field'));
				qa_opt($code_key, qa_post_text($code_key . '_field'));
			}
			
			// Handle homepage bottom ad separately (only for SnowFlat theme)
			$current_theme = qa_opt('site_theme');
			$is_snowflat = (strtolower($current_theme) === 'snowflat');
			
			if ($is_snowflat) {
				qa_opt('qlassy_ads_homepage_bottom_enabled', (bool)qa_post_text('qlassy_ads_homepage_bottom_enabled_field'));
				qa_opt('qlassy_ads_homepage_bottom_code', qa_post_text('qlassy_ads_homepage_bottom_code_field'));
			} else {
				// Disable homepage bottom ad for non-SnowFlat themes
				qa_opt('qlassy_ads_homepage_bottom_enabled', false);
			}

			// Save between answers frequency
			qa_opt('qlassy_ads_between_answers_frequency', qa_post_text('qlassy_ads_between_answers_frequency_field'));

			// Save between questions frequency
			qa_opt('qlassy_ads_between_questions_frequency', qa_post_text('qlassy_ads_between_questions_frequency_field'));

			// Save advertisement label setting
			qa_opt('qlassy_ads_show_advertisement_label', (bool)qa_post_text('qlassy_ads_show_advertisement_label_field'));

			// Save page exclude/include settings
			qa_opt('qlassy_ads_page_exclude_mode', qa_post_text('qlassy_ads_page_exclude_mode_field'));
			qa_opt('qlassy_ads_page_exclude_list', qa_post_text('qlassy_ads_page_exclude_list_field'));

			// Save sticky ads settings
			qa_opt('qlassy_ads_top_sticky_enabled', (bool)qa_post_text('qlassy_ads_top_sticky_enabled_field'));
			qa_opt('qlassy_ads_top_sticky_code', qa_post_text('qlassy_ads_top_sticky_code_field'));
			qa_opt('qlassy_ads_bottom_sticky_enabled', (bool)qa_post_text('qlassy_ads_bottom_sticky_enabled_field'));
			qa_opt('qlassy_ads_bottom_sticky_code', qa_post_text('qlassy_ads_bottom_sticky_code_field'));

			// Save AdSense header code
			qa_opt('qlassy_ads_adsense_header_enabled', (bool)qa_post_text('qlassy_ads_adsense_header_enabled_field'));
			qa_opt('qlassy_ads_adsense_header_code', qa_post_text('qlassy_ads_adsense_header_code_field'));

			// Save ad rotation setting
			qa_opt('qlassy_ads_rotation_enabled', (bool)qa_post_text('qlassy_ads_rotation_enabled_field'));

			// Save targeting & rules settings
			qa_opt('qlassy_ads_guest_only_enabled', (bool)qa_post_text('qlassy_ads_guest_only_enabled_field'));
			qa_opt('qlassy_ads_device_targeting_enabled', (bool)qa_post_text('qlassy_ads_device_targeting_enabled_field'));
			qa_opt('qlassy_ads_hide_from_roles_enabled', (bool)qa_post_text('qlassy_ads_hide_from_roles_enabled_field'));
			qa_opt('qlassy_ads_hide_from_roles_list', qa_post_text('qlassy_ads_hide_from_roles_list_field'));

			// Save adblock detection setting
			qa_opt('qlassy_ads_adblock_detection_enabled', (bool)qa_post_text('qlassy_ads_adblock_detection_enabled_field'));

			

			$saved = true;
		}

		$fields = array();

		// Main Plugin Enable/Disable
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Plugin Status</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Qlassy Ads Manager Plugin:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_enabled'),
			'tags' => 'name="qlassy_ads_enabled_field"',
			'note' => 'Enable or disable the entire Qlassy Ads Manager plugin. When disabled, no ads will be displayed.',
		);

		// AdSense Header Code
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>AdSense Header Code (Required for AdSense)</h3>',
		);
		$fields[] = array(
			'label' => 'Enable AdSense Header Code:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_adsense_header_enabled'),
			'tags' => 'name="qlassy_ads_adsense_header_enabled_field"',
		);
		$fields[] = array(
			'label' => 'AdSense Header Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_adsense_header_code')),
			'tags' => 'name="qlassy_ads_adsense_header_code_field"',
			'rows' => 3,
			'note' => 'Paste your AdSense header script here. Example: &lt;script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1234567890123456" crossorigin="anonymous"&gt;&lt;/script&gt;',
		);



		// Ad Rotation Feature
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Ad Rotation Feature</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Ad Rotation:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_rotation_enabled'),
			'tags' => 'name="qlassy_ads_rotation_enabled_field"',
			'note' => 'Enable this to rotate multiple ads in the same location. Separate multiple ad codes with [QLASSY ROTATE] in any ad field.',
		);

		// Targeting & Rules
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Targeting & Rules</h3>',
		);
		$fields[] = array(
			'label' => 'Show ads only to guests:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_guest_only_enabled'),
			'tags' => 'name="qlassy_ads_guest_only_enabled_field"',
			'note' => 'Enable this to hide all ads from logged-in users. Only guests will see ads.',
		);
		$fields[] = array(
			'label' => 'Enable device targeting:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_device_targeting_enabled'),
			'tags' => 'name="qlassy_ads_device_targeting_enabled_field"',
			'note' => 'Enable this to show different ads for desktop and mobile devices. Add [DESKTOP] and [MOBILE] tags in ad fields.',
		);
		$fields[] = array(
			'label' => 'Hide ads from specific user roles:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_hide_from_roles_enabled'),
			'tags' => 'name="qlassy_ads_hide_from_roles_enabled_field"',
			'note' => 'Enable this to hide ads from users with specific roles.',
		);
		$fields[] = array(
			'label' => 'Roles to hide ads from:',
			'type' => 'text',
			'value' => qa_html(qa_opt('qlassy_ads_hide_from_roles_list')),
			'tags' => 'name="qlassy_ads_hide_from_roles_list_field"',
			'note' => 'Comma-separated list of user roles. Available roles: admin, moderator, editor, expert, user. Example: admin,moderator',
		);

		// Header Ad
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Header Ad (Top of every page)</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Header Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_header_enabled'),
			'tags' => 'name="qlassy_ads_header_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Header Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_header_code')),
			'tags' => 'name="qlassy_ads_header_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);

		// Footer Ad
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Footer Ad (Bottom of every page)</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Footer Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_footer_enabled'),
			'tags' => 'name="qlassy_ads_footer_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Footer Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_footer_code')),
			'tags' => 'name="qlassy_ads_footer_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);

		// Sidebar Ads
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Sidebar Ads</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Top Sidebar Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_sidebar_top_enabled'),
			'tags' => 'name="qlassy_ads_sidebar_top_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Top Sidebar Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_sidebar_top_code')),
			'tags' => 'name="qlassy_ads_sidebar_top_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);
		$fields[] = array(
			'label' => 'Enable Bottom Sidebar Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_sidebar_bottom_enabled'),
			'tags' => 'name="qlassy_ads_sidebar_bottom_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Bottom Sidebar Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_sidebar_bottom_code')),
			'tags' => 'name="qlassy_ads_sidebar_bottom_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);

		// Sticky Ads
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Sticky Ads (Fixed Position)</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Top Sticky Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_top_sticky_enabled'),
			'tags' => 'name="qlassy_ads_top_sticky_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Top Sticky Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_top_sticky_code')),
			'tags' => 'name="qlassy_ads_top_sticky_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here. This ad will remain fixed at the top of the screen.',
		);
		$fields[] = array(
			'label' => 'Enable Bottom Sticky Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_bottom_sticky_enabled'),
			'tags' => 'name="qlassy_ads_bottom_sticky_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Bottom Sticky Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_bottom_sticky_code')),
			'tags' => 'name="qlassy_ads_bottom_sticky_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here. This ad will remain fixed at the bottom of the screen.',
		);



		// Question Page Ads
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Question Page Ads</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Question Top Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_question_top_enabled'),
			'tags' => 'name="qlassy_ads_question_top_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Question Top Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_question_top_code')),
			'tags' => 'name="qlassy_ads_question_top_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);
		$fields[] = array(
			'label' => 'Enable Question Bottom Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_question_bottom_enabled'),
			'tags' => 'name="qlassy_ads_question_bottom_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Question Bottom Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_question_bottom_code')),
			'tags' => 'name="qlassy_ads_question_bottom_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);

		// Answer Ads
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Answer Ads</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Answer Top Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_answer_top_enabled'),
			'tags' => 'name="qlassy_ads_answer_top_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Answer Top Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_answer_top_code')),
			'tags' => 'name="qlassy_ads_answer_top_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);
		$fields[] = array(
			'label' => 'Enable Answer Bottom Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_answer_bottom_enabled'),
			'tags' => 'name="qlassy_ads_answer_bottom_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Answer Bottom Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_answer_bottom_code')),
			'tags' => 'name="qlassy_ads_answer_bottom_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);

		// Between Answers Ad
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Between Answers Ad</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Between Answers Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_between_answers_enabled'),
			'tags' => 'name="qlassy_ads_between_answers_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Between Answers Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_between_answers_code')),
			'tags' => 'name="qlassy_ads_between_answers_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);
		$fields[] = array(
			'label' => 'Show ad after answers:',
			'type' => 'text',
			'value' => qa_html(qa_opt('qlassy_ads_between_answers_frequency')),
			'tags' => 'name="qlassy_ads_between_answers_frequency_field"',
			'note' => 'Enter a single number (e.g., "2") for regular frequency, or comma-separated numbers (e.g., "2,3,5,7") for custom pattern',
		);

		// Homepage Ads
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Homepage Ads</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Homepage Top Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_homepage_top_enabled'),
			'tags' => 'name="qlassy_ads_homepage_top_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Homepage Top Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_homepage_top_code')),
			'tags' => 'name="qlassy_ads_homepage_top_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);
		
		// Check if SnowFlat theme is active
		$current_theme = qa_opt('site_theme');
		$is_snowflat = (strtolower($current_theme) === 'snowflat');
		
		if ($is_snowflat) {
			// Show homepage bottom ad settings for SnowFlat theme
			$fields[] = array(
				'label' => 'Enable Homepage Bottom Ad:',
				'type' => 'checkbox',
				'value' => qa_opt('qlassy_ads_homepage_bottom_enabled'),
				'tags' => 'name="qlassy_ads_homepage_bottom_enabled_field"',
			);
			$fields[] = array(
				'label' => 'Homepage Bottom Ad Code:',
				'type' => 'textarea',
				'value' => qa_html(qa_opt('qlassy_ads_homepage_bottom_code')),
				'tags' => 'name="qlassy_ads_homepage_bottom_code_field"',
				'rows' => 4,
				'note' => 'Paste your AdSense or custom HTML ad code here (SnowFlat theme only)',
			);
		} else {
			// Show disabled message for other themes
			$fields[] = array(
				'type' => 'static',
				'label' => '<div style="background:#f8f9fa; padding:10px; border:1px solid #dee2e6; border-radius:4px; color:#6c757d;"><strong>Homepage Bottom Ad:</strong> This feature is only available when using the SnowFlat theme. Current theme: <strong>' . qa_html($current_theme) . '</strong></div>',
			);
		}

		// Category Page Ads
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Category Page Ads</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Category Top Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_category_top_enabled'),
			'tags' => 'name="qlassy_ads_category_top_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Category Top Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_category_top_code')),
			'tags' => 'name="qlassy_ads_category_top_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);
		$fields[] = array(
			'label' => 'Enable Category Bottom Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_category_bottom_enabled'),
			'tags' => 'name="qlassy_ads_category_bottom_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Category Bottom Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_category_bottom_code')),
			'tags' => 'name="qlassy_ads_category_bottom_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);

		// Tag Page Ads
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Tag Page Ads</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Tag Top Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_tag_top_enabled'),
			'tags' => 'name="qlassy_ads_tag_top_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Tag Top Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_tag_top_code')),
			'tags' => 'name="qlassy_ads_tag_top_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);
		$fields[] = array(
			'label' => 'Enable Tag Bottom Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_tag_bottom_enabled'),
			'tags' => 'name="qlassy_ads_tag_bottom_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Tag Bottom Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_tag_bottom_code')),
			'tags' => 'name="qlassy_ads_tag_bottom_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);

		// User Profile Ads
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>User Profile Page Ads</h3>',
		);
		$fields[] = array(
			'label' => 'Enable User Profile Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_user_profile_enabled'),
			'tags' => 'name="qlassy_ads_user_profile_enabled_field"',
		);
		$fields[] = array(
			'label' => 'User Profile Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_user_profile_code')),
			'tags' => 'name="qlassy_ads_user_profile_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);

		// Between Questions Ads
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Between Questions Ads (All Question Lists)</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Between Questions Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_between_questions_enabled'),
			'tags' => 'name="qlassy_ads_between_questions_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Between Questions Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_between_questions_code')),
			'tags' => 'name="qlassy_ads_between_questions_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);
		$fields[] = array(
			'label' => 'Show ad after questions:',
			'type' => 'text',
			'value' => qa_html(qa_opt('qlassy_ads_between_questions_frequency')),
			'tags' => 'name="qlassy_ads_between_questions_frequency_field"',
			'note' => 'Enter a single number (e.g., "2") for regular frequency, or comma-separated numbers (e.g., "2,3,5,7") for custom pattern. This will apply to all question lists.',
		);

		// Sticky Side Rail Ads
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Sticky Side Rail Ads (Fixed Position)</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Left Side Rail Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_left_rail_enabled'),
			'tags' => 'name="qlassy_ads_left_rail_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Left Side Rail Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_left_rail_code')),
			'tags' => 'name="qlassy_ads_left_rail_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here. This ad will be fixed to the left side of the screen.',
		);
		$fields[] = array(
			'label' => 'Enable Right Side Rail Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_right_rail_enabled'),
			'tags' => 'name="qlassy_ads_right_rail_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Right Side Rail Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_right_rail_code')),
			'tags' => 'name="qlassy_ads_right_rail_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here. This ad will be fixed to the right side of the screen.',
		);

		// Advertisement Label Control
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Advertisement Label Settings</h3>',
		);
		$fields[] = array(
			'label' => 'Show "Advertisement" Label:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_show_advertisement_label'),
			'tags' => 'name="qlassy_ads_show_advertisement_label_field"',
			'note' => 'Enable this to show "Advertisement" labels on all ad containers throughout the website',
		);

		// Page Exclude/Include Settings
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Page Exclude/Include Settings</h3>',
		);
		$fields[] = array(
			'label' => 'Page Control Mode:',
			'type' => 'select',
			'value' => qa_opt('qlassy_ads_page_exclude_mode'),
			'tags' => 'name="qlassy_ads_page_exclude_mode_field"',
			'options' => array(
				'exclude' => 'Exclude ads from specific pages',
				'include' => 'Show ads only on specific pages'
			),
			'note' => 'Choose whether to exclude ads from specific pages or show ads only on specific pages',
		);
		$fields[] = array(
			'label' => 'Page List:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_page_exclude_list')),
			'tags' => 'name="qlassy_ads_page_exclude_list_field"',
			'rows' => 4,
			'note' => 'Enter page names separated by commas (e.g., admin,login,register,account). Available pages: account, activity, admin, answers, ask, categories, comments, confirm, favorites, feedback, forgot, hot, ip, login, logout, messages, questions, register, reset, search, tag, tags, unanswered, unsubscribe, updates, user, users',
		);

		// Search Results Ads
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Search Results Page Ads</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Search Results Ad:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_search_results_enabled'),
			'tags' => 'name="qlassy_ads_search_results_enabled_field"',
		);
		$fields[] = array(
			'label' => 'Search Results Ad Code:',
			'type' => 'textarea',
			'value' => qa_html(qa_opt('qlassy_ads_search_results_code')),
			'tags' => 'name="qlassy_ads_search_results_code_field"',
			'rows' => 4,
			'note' => 'Paste your AdSense or custom HTML ad code here',
		);

		// Adblock Detection
		$fields[] = array(
			'type' => 'static',
			'label' => '<h3>Adblock Detection</h3>',
		);
		$fields[] = array(
			'label' => 'Enable Adblock Detection:',
			'type' => 'checkbox',
			'value' => qa_opt('qlassy_ads_adblock_detection_enabled'),
			'tags' => 'name="qlassy_ads_adblock_detection_enabled_field"',
			'note' => 'Enable this to detect if the user\'s browser has adblocker enabled. If adblocker is detected, ads will be hidden.',
		);

		return array(
			'ok' => $saved ? 'Qlassy Ads settings saved successfully!' : null,
			'style' => 'wide',
			'fields' => $fields,
			'buttons' => array(
				array(
					'label' => 'Save Changes',
					'tags' => 'name="qlassy_ads_save_button"',
				),
			),
		);
	}
}
