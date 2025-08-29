/**
 * Qlassy Ads Manager JavaScript
 * Handles ad insertion, adblock detection, and dynamic ad placement
 */

// Global flag to prevent multiple popups
var qlassyAdblockPopupShown = false;

/**
 * Initialize lazy loading for below-the-fold ads
 */
function qlassyInitLazyLoading() {
    const lazyAds = document.querySelectorAll('.qlassy-lazy-ad');
    
    if (lazyAds.length === 0) {
        return;
    }
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const adCode = el.dataset.ad;
                
                if (adCode) {
                    // Clear the placeholder text and content
                    el.innerHTML = '';
                    el.textContent = '';
                    
                    // Insert the actual ad code
                    el.innerHTML = adCode;
                    
                    // Remove the lazy-ad class to prevent re-processing
                    el.classList.remove('qlassy-lazy-ad');
                    
                    // Initialize AdSense ads if present (only for AdSense ads)
                    if (window.adsbygoogle && el.querySelector('ins.adsbygoogle')) {
                        try {
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        } catch (e) {
                            // Ignore AdSense errors for non-AdSense ads
                        }
                    }
                }
                
                observer.unobserve(el);
            }
        });
    }, {
        rootMargin: '50px 0px', // Start loading 50px before the ad comes into view
        threshold: 0.1
    });
    
    lazyAds.forEach(ad => observer.observe(ad));
}

/**
 * Delay sticky ads until after page load to improve LCP
 */
function qlassyDelayStickyAds() {
    // Delay sticky ads until after page load
    window.addEventListener("load", () => {
        const stickyAds = document.querySelectorAll(".qlassy-ads-top-sticky, .qlassy-ads-bottom-sticky");
        
        stickyAds.forEach(el => {
            if (el.style.display === "none") {
                el.style.display = "block";
                
                // Initialize AdSense ads if present
                if (window.adsbygoogle) {
                    (adsbygoogle = window.adsbygoogle || []).push({});
                }
            }
        });
    });
}

/**
 * Insert ads between answers based on frequency
 */
function qlassyInsertBetweenAnswers() {
    var answers = document.querySelectorAll(".qa-a-list-item");
    var adCode = qlassyAdsData.betweenAnswersCode;
    var frequency = qlassyAdsData.betweenAnswersFrequency;
    var showLabel = qlassyAdsData.showAdvertisementLabel;
    
    if (!adCode || !frequency || answers.length === 0) {
        return;
    }
    
    var positions = [];
    if (frequency.includes(",")) {
        // Custom pattern: "2,3,5,7"
        positions = frequency.split(",").map(function(x) { 
            return parseInt(x.trim()) - 1; 
        });
    } else {
        // Regular frequency: "2"
        var freq = parseInt(frequency);
        for (var i = freq - 1; i < answers.length; i += freq) {
            positions.push(i);
        }
    }
    
    positions.forEach(function(pos) {
        if (pos >= 0 && pos < answers.length) {
            var adDiv = document.createElement("div");
            adDiv.className = "qlassy-ads-between-answers";
            if (showLabel) {
                adDiv.setAttribute("data-show-label", "true");
            }
            adDiv.innerHTML = adCode;
            answers[pos].parentNode.insertBefore(adDiv, answers[pos].nextSibling);
        }
    });
}

/**
 * Insert ads between questions based on frequency
 */
function qlassyInsertBetweenQuestions() {
    var questions = document.querySelectorAll(".qa-q-list-item");
    var adCode = qlassyAdsData.betweenQuestionsCode;
    var frequency = qlassyAdsData.betweenQuestionsFrequency;
    var showLabel = qlassyAdsData.showAdvertisementLabel;
    
    if (!adCode || !frequency || questions.length === 0) {
        return;
    }
    
    var positions = [];
    if (frequency.includes(",")) {
        // Custom pattern: "2,3,5,7"
        positions = frequency.split(",").map(function(x) { 
            return parseInt(x.trim()) - 1; 
        });
    } else {
        // Regular frequency: "2"
        var freq = parseInt(frequency);
        for (var i = freq - 1; i < questions.length; i += freq) {
            positions.push(i);
        }
    }
    
    positions.forEach(function(pos) {
        if (pos >= 0 && pos < questions.length) {
            var adDiv = document.createElement("div");
            adDiv.className = "qlassy-ads-between-questions";
            if (showLabel) {
                adDiv.setAttribute("data-show-label", "true");
            }
            adDiv.innerHTML = adCode;
            questions[pos].parentNode.insertBefore(adDiv, questions[pos].nextSibling);
        }
    });
}

/**
 * Manual test function for adblock detection
 */
function qlassyTestAdblockDetection() {
    console.log('=== Manual Adblock Detection Test ===');
    
    let adBlocked = false;
    
    // Test 1: Bait element
    const baitElement = document.getElementById('qlassy-ad-test');
    if (baitElement) {
        const rect = baitElement.getBoundingClientRect();
        const style = window.getComputedStyle(baitElement);
        
        console.log('Test 1 - Bait Element:');
        console.log('  - Width: ' + rect.width + 'px');
        console.log('  - Height: ' + rect.height + 'px');
        console.log('  - Display: ' + style.display);
        console.log('  - Visibility: ' + style.visibility);
        console.log('  - Opacity: ' + style.opacity);
        
        if (rect.width === 0 || rect.height === 0 || 
            style.display === 'none' || 
            style.visibility === 'hidden' || 
            style.opacity === '0') {
            adBlocked = true;
            console.log('  - RESULT: Bait element is hidden - Adblock likely active');
        } else {
            console.log('  - RESULT: Bait element is visible - Adblock likely not active');
        }
    } else {
        console.log('Test 1 - Bait Element: Not found');
    }
    
    // Test 2: Ad elements visibility
    const adElements = document.querySelectorAll('.qlassy-ads-header, .qlassy-ads-footer, .qlassy-ads-sidebar-top, .qlassy-ads-sidebar-bottom');
    console.log('Test 2 - Ad Elements:');
    console.log('  - Total ad elements: ' + adElements.length);
    
    let hiddenAds = 0;
    adElements.forEach((element, index) => {
        const rect = element.getBoundingClientRect();
        const style = window.getComputedStyle(element);
        
        if (rect.width === 0 || rect.height === 0 || 
            style.display === 'none' || 
            style.visibility === 'hidden' || 
            style.opacity === '0') {
            hiddenAds++;
            console.log('  - Ad ' + (index + 1) + ': HIDDEN');
        } else {
            console.log('  - Ad ' + (index + 1) + ': VISIBLE (' + rect.width + 'x' + rect.height + ')');
        }
    });
    
    if (adElements.length > 0 && (hiddenAds / adElements.length) > 0.5) {
        adBlocked = true;
        console.log('  - RESULT: More than 50% of ads hidden - Adblock likely active');
    } else {
        console.log('  - RESULT: Most ads visible - Adblock likely not active');
    }
    
    // Test 3: Image blocking detection
    const adImages = document.querySelectorAll('.qlassy-ads-header img, .qlassy-ads-footer img, .qlassy-ads-sidebar-top img, .qlassy-ads-sidebar-bottom img');
    console.log('Test 3 - Ad Images:');
    console.log('  - Total ad images: ' + adImages.length);
    
    let blockedImages = 0;
    adImages.forEach((img, index) => {
        if (img.naturalWidth === 0 || img.naturalHeight === 0) {
            blockedImages++;
            console.log('  - Image ' + (index + 1) + ': BLOCKED (0x0)');
        } else {
            console.log('  - Image ' + (index + 1) + ': LOADED (' + img.naturalWidth + 'x' + img.naturalHeight + ')');
        }
    });
    
    if (adImages.length > 0 && (blockedImages / adImages.length) > 0.5) {
        adBlocked = true;
        console.log('  - RESULT: More than 50% of images blocked - Adblock likely active');
    } else {
        console.log('  - RESULT: Most images loaded - Adblock likely not active');
    }
    
    // Final result
    console.log('=== FINAL RESULT ===');
    if (adBlocked) {
        console.log('ADBLOCK DETECTED - Popup should show');
    } else {
        console.log('NO ADBLOCK DETECTED - No popup should show');
    }
    console.log('===================');
}

/**
 * Debug function to help troubleshoot adblock detection
 */
function qlassyDebugAdblockDetection() {
    console.log('=== Qlassy Adblock Detection Debug ===');
    
    // Check bait element
    const baitElement = document.getElementById('qlassy-ad-test');
    if (baitElement) {
        const rect = baitElement.getBoundingClientRect();
        const style = window.getComputedStyle(baitElement);
        console.log('Bait element status:');
        console.log('  - Exists: true');
        console.log('  - Width: ' + rect.width + 'px');
        console.log('  - Height: ' + rect.height + 'px');
        console.log('  - Display: ' + style.display);
        console.log('  - Visibility: ' + style.visibility);
        console.log('  - Opacity: ' + style.opacity);
        console.log('  - OffsetParent: ' + (baitElement.offsetParent ? 'exists' : 'null'));
    } else {
        console.log('Bait element: Not found');
    }
    
    // Check AdSense
    console.log('AdSense status:');
    console.log('  - adsbygoogle type: ' + typeof window.adsbygoogle);
    console.log('  - adsbygoogle available: ' + (typeof window.adsbygoogle !== 'undefined' ? 'yes' : 'no'));
    
    // Check ad elements
    const adElements = document.querySelectorAll('.qlassy-ads-header, .qlassy-ads-footer, .qlassy-ads-sidebar-top, .qlassy-ads-sidebar-bottom');
    console.log('Ad elements found: ' + adElements.length);
    
    adElements.forEach((element, index) => {
        const rect = element.getBoundingClientRect();
        const style = window.getComputedStyle(element);
        console.log('Ad element ' + (index + 1) + ':');
        console.log('  - Class: ' + element.className);
        console.log('  - Width: ' + rect.width + 'px');
        console.log('  - Height: ' + rect.height + 'px');
        console.log('  - Display: ' + style.display);
        console.log('  - Visibility: ' + style.visibility);
        console.log('  - Opacity: ' + style.opacity);
    });
    
    console.log('=== End Debug ===');
}

/**
 * Detect adblock and show popup if enabled
 */
function detectAdBlock() {
    // Prevent multiple popups
    if (qlassyAdblockPopupShown) {
        return;
    }
    
    // Run debug function to help troubleshoot
    if (window.location.search.includes('debug=adblock')) {
        qlassyDebugAdblockDetection();
    }
    
    let adBlocked = false;
    
    // 1. Check if bait element is hidden (indicates adblock is active)
    const baitElement = document.getElementById('qlassy-ad-test');
    if (baitElement) {
        // Check if the element is hidden by adblock
        const rect = baitElement.getBoundingClientRect();
        const style = window.getComputedStyle(baitElement);
        
        // If element is hidden or has zero dimensions, adblock is likely active
        if (rect.width === 0 || rect.height === 0 || 
            style.display === 'none' || 
            style.visibility === 'hidden' || 
            style.opacity === '0') {
            adBlocked = true;
        }
    }
    
    // 2. Check if ad containers are being hidden by adblock
    const adElements = document.querySelectorAll('.qlassy-ads-header, .qlassy-ads-footer, .qlassy-ads-sidebar-top, .qlassy-ads-sidebar-bottom');
    let hiddenAds = 0;
    
    adElements.forEach(element => {
        const rect = element.getBoundingClientRect();
        const style = window.getComputedStyle(element);
        
        // Check if ad element is hidden
        if (rect.width === 0 || rect.height === 0 || 
            style.display === 'none' || 
            style.visibility === 'hidden' || 
            style.opacity === '0') {
            hiddenAds++;
        }
    });
    
    // If more than 50% of ads are hidden, likely adblock is active
    if (adElements.length > 0 && (hiddenAds / adElements.length) > 0.5) {
        adBlocked = true;
    }
    
    // 3. Check for blocked image requests (this is a strong indicator)
    let blockedImages = 0;
    const adImages = document.querySelectorAll('.qlassy-ads-header img, .qlassy-ads-footer img, .qlassy-ads-sidebar-top img, .qlassy-ads-sidebar-bottom img');
    
    adImages.forEach(img => {
        if (img.naturalWidth === 0 || img.naturalHeight === 0) {
            blockedImages++;
        }
    });
    
    // If more than 50% of ad images are blocked, adblock is likely active
    if (adImages.length > 0 && (blockedImages / adImages.length) > 0.5) {
        adBlocked = true;
    }
    
    // 4. Delayed check for dynamic adblock hiding
    setTimeout(() => {
        const currentAdElements = document.querySelectorAll('.qlassy-ads-header, .qlassy-ads-footer, .qlassy-ads-sidebar-top, .qlassy-ads-sidebar-bottom');
        let currentlyHidden = 0;
        
        currentAdElements.forEach(element => {
            const rect = element.getBoundingClientRect();
            const style = window.getComputedStyle(element);
            
            if (rect.width === 0 || rect.height === 0 || 
                style.display === 'none' || 
                style.visibility === 'hidden' || 
                style.opacity === '0') {
                currentlyHidden++;
            }
        });
        
        // If ads are being hidden after initial load, adblock is active
        if (currentAdElements.length > 0 && (currentlyHidden / currentAdElements.length) > 0.5) {
            adBlocked = true;
        }
        
        // Show popup if adblock is detected
        if (adBlocked && !qlassyAdblockPopupShown) {
            showAdblockPopup();
        }
    }, 2000); // Wait 2 seconds to allow adblock to process
    
    // Show popup immediately if obvious adblock is detected
    if (adBlocked && !qlassyAdblockPopupShown) {
        showAdblockPopup();
    }
}

/**
 * Show adblock detection popup
 */
function showAdblockPopup() {
    // Prevent multiple popups
    if (qlassyAdblockPopupShown) {
        return;
    }
    
    // Set flag to prevent multiple popups
    qlassyAdblockPopupShown = true;
    
    // Create popup overlay
    var overlay = document.createElement("div");
    overlay.id = "qlassy-adblock-overlay";
    overlay.style.cssText = "position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgb(255 255 255 / 80%); z-index: 999999; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);";
    
    // Create popup content
    var popup = document.createElement("div");
    popup.id = "qlassy-adblock-popup";
    popup.style.cssText = "background: white; padding: 30px; border-radius: 6px; max-width: 500px; text-align: center; box-shadow: rgb(186 186 186 / 30%) 0px 3px 5px; border: 1px solid #e5e7eb;";
    
    // Popup content
    var popupContent = "";
    popupContent += "<div style=\"margin-bottom: 20px;\">";
    popupContent += "<svg width=\"64\" height=\"64\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"#e74c3c\" stroke-width=\"2\" style=\"margin: 0 auto 15px;\">";
    popupContent += "<circle cx=\"12\" cy=\"12\" r=\"10\"></circle>";
    popupContent += "<line x1=\"15\" y1=\"9\" x2=\"9\" y2=\"15\"></line>";
    popupContent += "<line x1=\"9\" y1=\"9\" x2=\"15\" y2=\"15\"></line>";
    popupContent += "</svg>";
    popupContent += "<h2 style=\"color: #e74c3c; margin: 0 0 15px 0; font-size: 24px;\">Ad Blocker Detected!</h2>";
    popupContent += "<p style=\"color: #555; line-height: 1.6; margin: 0 0 20px 0;\">We noticed you are using an Ad Blocker. Please consider disabling it to support our website.</p>";
    popupContent += "</div>";
    popupContent += "<div style=\"background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: left;\">";
    popupContent += "<h4 style=\"margin: 0 0 10px 0; color: #333;\">How to disable ad blocker:</h4>";
    popupContent += "<ul style=\"margin: 0; color: #666;\">";
    popupContent += "<li>Click the ad blocker icon in your browser toolbar</li>";
    popupContent += "<li>Select \"Disable for this site\" or \"Allow ads\"</li>";
    popupContent += "<li>Refresh this page</li>";
    popupContent += "</ul>";
    popupContent += "</div>";
    popupContent += "<button onclick=\"location.reload()\" style=\"background: #000000; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 12px; font-weight: bold;line-height: 18px;\">Okay, I will Whitelist</button>";
    popup.innerHTML = popupContent;
    
    // Add popup to overlay
    overlay.appendChild(popup);
    
    // Add overlay to page
    document.body.appendChild(overlay);
    
    // Prevent closing with escape key
    document.addEventListener("keydown", function(e) {
        if (e.key === "Escape") {
            e.preventDefault();
            return false;
        }
    });
    
    // Prevent closing with clicks outside popup
    overlay.addEventListener("click", function(e) {
        if (e.target === overlay) {
            e.preventDefault();
            return false;
        }
    });
    
    // Prevent right-click context menu
    overlay.addEventListener("contextmenu", function(e) {
        e.preventDefault();
        return false;
    });
}

/**
 * Initialize ads when DOM is ready
 */
function qlassyInitAds() {
    // Insert between answers ads
    if (qlassyAdsData.betweenAnswersEnabled) {
        qlassyInsertBetweenAnswers();
    }
    
    // Insert between questions ads
    if (qlassyAdsData.betweenQuestionsEnabled) {
        qlassyInsertBetweenQuestions();
    }
    
    // Detect adblock if enabled
    if (qlassyAdsData.adblockDetectionEnabled) {
        setTimeout(detectAdBlock, 1000);
    }

    // Initialize lazy loading for below-the-fold ads
    qlassyInitLazyLoading();

    // Delay sticky ads until after page load
    qlassyDelayStickyAds();
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", qlassyInitAds);
