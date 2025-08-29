# Adblock Detection Test Guide

## 🧪 **Testing Adblock Detection**

### **How to Test**

1. **Enable Adblock Detection** in the plugin settings
2. **Add this URL parameter** to your site: `?debug=adblock`
3. **Open browser console** (F12 → Console tab)
4. **Test with and without adblock** enabled

### **Expected Behavior**

#### **With Adblock DISABLED:**
- No popup should appear
- Console should show debug info
- All ad elements should be visible
- `adsbygoogle` should be defined

#### **With Adblock ENABLED:**
- Popup should appear after 2-3 seconds
- Console should show debug info
- Some ad elements might be hidden
- `adsbygoogle` might be undefined

### **Debug Information**

When you add `?debug=adblock` to your URL, the console will show:

```
=== Qlassy Adblock Detection Debug ===
Bait element status: {
  exists: true,
  width: 1,
  height: 1,
  display: "block",
  visibility: "visible",
  opacity: "1",
  offsetParent: [object]
}
AdSense status: {
  adsbygoogle: "function",
  adsbygoogleType: "function"
}
Ad elements found: 3
Ad element 1: {
  class: "qlassy-ads-header",
  width: 728,
  height: 90,
  display: "block",
  visibility: "visible",
  opacity: "1"
}
=== End Debug ===
```

### **Troubleshooting**

#### **If popup shows when adblock is disabled:**
1. Check if bait element is being hidden by CSS
2. Check if AdSense is loading properly
3. Check if ad elements are being hidden by theme CSS

#### **If popup doesn't show when adblock is enabled:**
1. Check if adblock is actually blocking the elements
2. Check if the bait element is being detected
3. Check if AdSense is being blocked

### **Manual Testing Steps**

1. **Disable adblock** → Visit site with `?debug=adblock` → Check console
2. **Enable adblock** → Visit site with `?debug=adblock` → Check console
3. **Compare the debug output** between both scenarios

### **Common Issues**

#### **False Positives (Popup shows when adblock is off):**
- Theme CSS hiding ad elements
- AdSense not loading properly
- Bait element being hidden by other CSS

#### **False Negatives (No popup when adblock is on):**
- Adblock not actually blocking the elements
- Detection timing issues
- Adblock using different hiding methods

### **Advanced Testing**

You can also test individual detection methods:

```javascript
// Test bait element detection
const bait = document.getElementById('qlassy-ad-test');
console.log('Bait element:', bait);
console.log('Bait dimensions:', bait.getBoundingClientRect());

// Test AdSense detection
console.log('AdSense available:', typeof window.adsbygoogle);

// Test ad element detection
const ads = document.querySelectorAll('.qlassy-ads-header, .qlassy-ads-footer');
console.log('Ad elements:', ads.length);
```

### **Expected Console Output**

#### **Without Adblock:**
```
Bait element status: { exists: true, width: 1, height: 1, display: "block", visibility: "visible", opacity: "1" }
AdSense status: { adsbygoogle: "function" }
Ad elements found: 3
```

#### **With Adblock:**
```
Bait element status: { exists: true, width: 0, height: 0, display: "none", visibility: "hidden", opacity: "0" }
AdSense status: { adsbygoogle: "undefined" }
Ad elements found: 0
```

### **Contact Support**

If you're still having issues:
1. Share the console debug output
2. Mention which adblock you're using
3. Share your browser and version
4. Include the URL you're testing on
