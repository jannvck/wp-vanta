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
            
            // Helper function to safely parse numbers
            function parseNumber(value, defaultValue) {
                if (value === '' || value === null || value === undefined) {
                    return defaultValue;
                }
                var parsed = parseFloat(value);
                return isNaN(parsed) ? defaultValue : parsed;
            }
            
            // Helper function to safely parse booleans
            function parseBoolean(value) {
                return value === 1 || value === '1' || value === true;
            }
            
            // Build options object dynamically
            var options = {
                el: ".vanta-canvas",
                mouseControls: parseBoolean(wpVantaOptions.mouseControls),
                touchControls: parseBoolean(wpVantaOptions.touchControls),
                gyroControls: parseBoolean(wpVantaOptions.gyroControls),
                minHeight: parseNumber(wpVantaOptions.minHeight, 200),
                minWidth: parseNumber(wpVantaOptions.minWidth, 200)
            };
            
            // Add color options
            if (wpVantaOptions.skyColor) options.skyColor = parseHexColor(wpVantaOptions.skyColor, 0x1ea6e6);
            if (wpVantaOptions.cloudColor) options.cloudColor = parseHexColor(wpVantaOptions.cloudColor, 0xa525eb);
            if (wpVantaOptions.sunColor) options.sunColor = parseHexColor(wpVantaOptions.sunColor, 0xff0000);
            if (wpVantaOptions.sunGlareColor) options.sunGlareColor = parseHexColor(wpVantaOptions.sunGlareColor, 0x4830ff);
            if (wpVantaOptions.sunlightColor) options.sunlightColor = parseHexColor(wpVantaOptions.sunlightColor, 0x25cce3);
            if (wpVantaOptions.lightColor) options.lightColor = parseHexColor(wpVantaOptions.lightColor, 0xffffff);
            if (wpVantaOptions.color) options.color = parseHexColor(wpVantaOptions.color, 0x6588);
            if (wpVantaOptions.color1) options.color1 = parseHexColor(wpVantaOptions.color1, 0xff0000);
            if (wpVantaOptions.color2) options.color2 = parseHexColor(wpVantaOptions.color2, 0xffff);
            if (wpVantaOptions.backgroundColor) options.backgroundColor = parseHexColor(wpVantaOptions.backgroundColor, 0x0);
            if (wpVantaOptions.baseColor) options.baseColor = parseHexColor(wpVantaOptions.baseColor, 0xffebeb);
            if (wpVantaOptions.highlightColor) options.highlightColor = parseHexColor(wpVantaOptions.highlightColor, 0xffe300);
            if (wpVantaOptions.midtoneColor) options.midtoneColor = parseHexColor(wpVantaOptions.midtoneColor, 0xff1f00);
            if (wpVantaOptions.lowlightColor) options.lowlightColor = parseHexColor(wpVantaOptions.lowlightColor, 0x2d10ff);
            
            // Add numeric options
            if (wpVantaOptions.speed !== undefined) options.speed = parseNumber(wpVantaOptions.speed, 0.6);
            if (wpVantaOptions.scale !== undefined) options.scale = parseNumber(wpVantaOptions.scale, 1);
            if (wpVantaOptions.scaleMobile !== undefined) options.scaleMobile = parseNumber(wpVantaOptions.scaleMobile, 1);
            if (wpVantaOptions.zoom !== undefined) options.zoom = parseNumber(wpVantaOptions.zoom, 1);
            if (wpVantaOptions.shininess !== undefined) options.shininess = parseNumber(wpVantaOptions.shininess, 30);
            if (wpVantaOptions.waveHeight !== undefined) options.waveHeight = parseNumber(wpVantaOptions.waveHeight, 15);
            if (wpVantaOptions.waveSpeed !== undefined) options.waveSpeed = parseNumber(wpVantaOptions.waveSpeed, 1);
            if (wpVantaOptions.blurFactor !== undefined) options.blurFactor = parseNumber(wpVantaOptions.blurFactor, 0.6);
            if (wpVantaOptions.size !== undefined) options.size = parseNumber(wpVantaOptions.size, 1);
            if (wpVantaOptions.backgroundAlpha !== undefined) options.backgroundAlpha = parseNumber(wpVantaOptions.backgroundAlpha, 1);
            if (wpVantaOptions.points !== undefined) options.points = parseNumber(wpVantaOptions.points, 10);
            if (wpVantaOptions.maxDistance !== undefined) options.maxDistance = parseNumber(wpVantaOptions.maxDistance, 20);
            if (wpVantaOptions.spacing !== undefined) options.spacing = parseNumber(wpVantaOptions.spacing, 15);
            if (wpVantaOptions.quantity !== undefined) options.quantity = parseNumber(wpVantaOptions.quantity, 5);
            if (wpVantaOptions.birdSize !== undefined) options.birdSize = parseNumber(wpVantaOptions.birdSize, 1);
            if (wpVantaOptions.wingSpan !== undefined) options.wingSpan = parseNumber(wpVantaOptions.wingSpan, 30);
            if (wpVantaOptions.speedLimit !== undefined) options.speedLimit = parseNumber(wpVantaOptions.speedLimit, 5);
            if (wpVantaOptions.separation !== undefined) options.separation = parseNumber(wpVantaOptions.separation, 20);
            if (wpVantaOptions.alignment !== undefined) options.alignment = parseNumber(wpVantaOptions.alignment, 20);
            if (wpVantaOptions.cohesion !== undefined) options.cohesion = parseNumber(wpVantaOptions.cohesion, 20);
            if (wpVantaOptions.amplitudeFactor !== undefined) options.amplitudeFactor = parseNumber(wpVantaOptions.amplitudeFactor, 1);
            if (wpVantaOptions.xOffset !== undefined) options.xOffset = parseNumber(wpVantaOptions.xOffset, 0);
            if (wpVantaOptions.yOffset !== undefined) options.yOffset = parseNumber(wpVantaOptions.yOffset, 0);
            if (wpVantaOptions.chaos !== undefined) options.chaos = parseNumber(wpVantaOptions.chaos, 0);
            
            // Add boolean options
            if (wpVantaOptions.showDots !== undefined) options.showDots = parseBoolean(wpVantaOptions.showDots);
            if (wpVantaOptions.showLines !== undefined) options.showLines = parseBoolean(wpVantaOptions.showLines);
            
            // Add string options
            if (wpVantaOptions.colorMode) options.colorMode = wpVantaOptions.colorMode;
            if (wpVantaOptions.texturePath) options.texturePath = wpVantaOptions.texturePath;
            
            VantaEffect(options);
        }
    }
});