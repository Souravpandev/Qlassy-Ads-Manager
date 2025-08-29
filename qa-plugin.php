<?php
/*
	Plugin Name: Qlassy Ads Manager
	Plugin URI: https://github.com/Souravpandev/Qlassy-Ads-Manager
	Plugin Description: A comprehensive advertising plugin for Question2Answer that allows you to insert AdSense banner ads or custom HTML code in various locations throughout your Q2A website. Features include 16+ ad insertion locations, dynamic ad insertion between questions/answers, sticky ads, ad rotation, device targeting, guest-only targeting, role-based targeting, page exclude/include, AdSense header integration, advertisement labels, adblock detection, and LCP optimization with responsive design.
	Plugin Version: 1.4
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
	- LCP optimization with lazy loading and ad space reservation
	- Responsive design with professional styling
	- Professional styling and user experience
*/


if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

// Register plugin components
qa_register_plugin_layer('qa-qlassy-ads-layer.php', 'Qlassy Ads Manager Layer');
qa_register_plugin_module('module', 'qa-qlassy-ads-admin.php', 'qa_qlassy_ads_admin', 'Qlassy Ads Manager Settings');

/*
	Omit PHP closing tag to help avoid accidental output
*/
