<?php
/*
	Plugin Name: Qlassy Ads Manager
	Plugin URI: https://github.com/Souravpandev/Qlassy-Ads-Manager
	Plugin Description: A comprehensive advertising plugin for Question2Answer that allows you to insert AdSense banner ads or custom HTML code in various locations throughout your Q2A website. Features include 16+ ad insertion locations, dynamic ad insertion between questions/answers, sticky ads, ad rotation, device targeting, guest-only targeting, role-based targeting, page exclude/include, AdSense header integration, advertisement labels, and adblock detection.
	Plugin Version: 1.0
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
	- Responsive design with dark theme support
	- Professional styling and user experience
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Qlassy Ads Manager Plugin Installation</title>
	<style>
		body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
		.container { max-width: 800px; margin: 0 auto; }
		.step { background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 5px; border-left: 4px solid #007cba; }
		.code { background: #f1f1f1; padding: 10px; border-radius: 3px; font-family: monospace; margin: 10px 0; }
		.note { background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107; }
		.success { background: #d4edda; padding: 15px; border-radius: 5px; border-left: 4px solid #28a745; }
		.warning { background: #f8d7da; padding: 15px; border-radius: 5px; border-left: 4px solid #dc3545; }
		h1 { color: #333; }
		h2 { color: #007cba; }
		ul { padding-left: 20px; }
	</style>
</head>
<body>
	<div class="container">
		<h1>Qlassy Ads Manager Plugin Installation</h1>
		
		<div class="note">
			<strong>Note:</strong> This file is for installation instructions only. You can delete this file after installation.
		</div>

		<h2>Installation Steps</h2>

		<div class="step">
			<h3>Step 1: Upload Plugin Files</h3>
			<p>Upload the entire <code>qlassy-ads-manager</code> folder to your Question2Answer <code>qa-plugin/</code> directory.</p>
			<div class="code">
				your-q2a-site/<br>
				├── qa-plugin/<br>
				│   └── qlassy-ads-manager/<br>
				│       ├── qa-plugin.php<br>
				│       ├── qa-qlassy-ads-admin.php<br>
				│       ├── qa-qlassy-ads-layer.php<br>
				│       ├── qlassy-ads.css<br>
				│       ├── metadata.json<br>
				│       ├── README.md<br>
				│       └── install.php (this file)<br>
				└── ...
			</div>
		</div>

		<div class="step">
			<h3>Step 2: Activate the Plugin</h3>
			<ol>
				<li>Log in to your Question2Answer admin panel</li>
				<li>Go to <strong>Admin → Layout → Plugins</strong></li>
				<li>Find "Qlassy Ads Manager" in the list</li>
				<li>Click "Activate" next to the plugin</li>
			</ol>
		</div>

		<div class="step">
			<h3>Step 3: Configure Your Ads</h3>
			<ol>
				<li>Go to <strong>Admin → Layout → Qlassy Ads Manager Settings</strong></li>
				<li>Enable the ad locations you want to use</li>
				<li>Paste your AdSense or custom HTML ad codes</li>
				<li>Click "Save Changes"</li>
			</ol>
		</div>

		<div class="step">
			<h3>Step 4: Test Your Ads</h3>
			<ol>
				<li>Visit your Question2Answer site</li>
				<li>Check different pages to see your ads</li>
				<li>Test on mobile devices for responsive design</li>
				<li>Clear browser cache if ads don't appear immediately</li>
			</ol>
		</div>

		<h2>Available Ad Locations</h2>
		<ul>
			<li><strong>Header Ad:</strong> Top of every page</li>
			<li><strong>Footer Ad:</strong> Bottom of every page</li>
			<li><strong>Sidebar Ads:</strong> Top and bottom of sidebar</li>
			<li><strong>Main Content Ads:</strong> Top and bottom of main content area</li>
			<li><strong>Question Page Ads:</strong> Top and bottom of question pages</li>
			<li><strong>Answer Ads:</strong> Top and bottom of individual answers</li>
			<li><strong>Between Answers Ads:</strong> Inserted between answers with configurable frequency</li>
			<li><strong>Between Questions Ads:</strong> Inserted between questions in all question lists with configurable frequency</li>
			<li><strong>Sticky Side Rail Ads:</strong> Fixed position ads on left and right sides of the screen</li>
			<li><strong>Homepage Ads:</strong> Top and bottom of homepage</li>
			<li><strong>Category Page Ads:</strong> Top and bottom of category pages</li>
			<li><strong>Tag Page Ads:</strong> Top and bottom of tag pages</li>
			<li><strong>User Profile Ads:</strong> On user profile pages</li>
			<li><strong>Search Results Ads:</strong> On search results pages</li>
		</ul>

		<h2>Example AdSense Code</h2>
		<div class="code">
&lt;script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1234567890123456"
     crossorigin="anonymous"&gt;&lt;/script&gt;
&lt;ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-1234567890123456"
     data-ad-slot="1234567890"
     data-ad-format="auto"
     data-full-width-responsive="true"&gt;&lt;/ins&gt;
&lt;script&gt;
     (adsbygoogle = window.adsbygoogle || []).push({});
&lt;/script&gt;
		</div>

		<h2>Troubleshooting</h2>
		
		<div class="warning">
			<h3>Ads Not Showing?</h3>
			<ul>
				<li>Make sure the plugin is activated</li>
				<li>Check if ads are enabled for the specific location</li>
				<li>Verify your ad code is complete and valid</li>
				<li>Clear browser cache and Q2A cache</li>
				<li>Check browser console for JavaScript errors</li>
			</ul>
		</div>

		<div class="warning">
			<h3>AdSense Not Working?</h3>
			<ul>
				<li>Ensure your site is approved by AdSense</li>
				<li>Check your publisher ID and ad unit ID</li>
				<li>Wait 24-48 hours for ads to start showing</li>
				<li>Disable ad blockers for testing</li>
			</ul>
		</div>

		<div class="success">
			<h3>Installation Complete!</h3>
			<p>Your Qlassy Ads Manager plugin is now installed and ready to use. You can delete this <code>install.php</code> file for security.</p>
		</div>

		<h2>Support</h2>
		<p>For support and questions:</p>
		<ul>
			<li>Check the README.md file for detailed documentation</li>
			<li>Review the troubleshooting section above</li>
			<li>Contact the developer: Sourav Pan</li>
		</ul>

		<div class="note">
			<strong>Plugin Information:</strong><br>
			Version: 1.0.0<br>
			Developer: Sourav Pan<br>
			License: GPLv2<br>
			Minimum Q2A Version: 1.8
		</div>
	</div>
</body>
</html>
