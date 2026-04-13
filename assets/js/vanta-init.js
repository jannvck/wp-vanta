document.addEventListener('DOMContentLoaded', function() {
    if (typeof VANTA !== 'undefined' && wpVantaEffect) {
        // Get the effect name (capitalized for VANTA object)
        var effectName = wpVantaEffect.charAt(0).toUpperCase() + wpVantaEffect.slice(1).toUpperCase();
        var VantaEffect = VANTA[effectName];
        
        if (typeof VantaEffect !== 'undefined') {
            // Helper function to safely parse hex color
            function parseHexColor(hexString, defaultValue) {
                if (!hexString || typeof hexString !== 'string') {
                    return defaultValue;
                }
                var parsed = parseInt(hexString.replace('0x', ''), 16);
                return isNaN(parsed) ? defaultValue : parsed;
            }
            
            // Parse colors from hex string to number with safe fallbacks
            var options = {
                el: ".vanta-canvas",
                mouseControls: wpVantaOptions.mouseControls || true,
                touchControls: wpVantaOptions.touchControls || true,
                gyroControls: wpVantaOptions.gyroControls || false,
                minHeight: parseFloat(wpVantaOptions.minHeight) || 200.00,
                minWidth: parseFloat(wpVantaOptions.minWidth) || 200.00,
                skyColor: parseHexColor(wpVantaOptions.skyColor, 0x1ea6e6),
                cloudColor: parseHexColor(wpVantaOptions.cloudColor, 0xa525eb),
                sunColor: parseHexColor(wpVantaOptions.sunColor, 0xff0000),
                sunGlareColor: parseHexColor(wpVantaOptions.sunGlareColor, 0x4830ff),
                sunlightColor: parseHexColor(wpVantaOptions.sunlightColor, 0x25cce3),
                speed: parseFloat(wpVantaOptions.speed) || 0.60
            };

            VantaEffect(options);
        }
    }
});