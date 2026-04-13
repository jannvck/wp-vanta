document.addEventListener('DOMContentLoaded', function() {
    if (typeof VANTA !== 'undefined' && VANTA.CLOUDS) {
        // Parse colors from hex string to number
        var options = {
            el: "body",
            mouseControls: wpVantaOptions.mouseControls || true,
            touchControls: wpVantaOptions.touchControls || true,
            gyroControls: wpVantaOptions.gyroControls || false,
            minHeight: parseFloat(wpVantaOptions.minHeight) || 200.00,
            minWidth: parseFloat(wpVantaOptions.minWidth) || 200.00,
            skyColor: parseInt(wpVantaOptions.skyColor.replace('0x', ''), 16) || 0x1ea6e6,
            cloudColor: parseInt(wpVantaOptions.cloudColor.replace('0x', ''), 16) || 0xa525eb,
            sunColor: parseInt(wpVantaOptions.sunColor.replace('0x', ''), 16) || 0xff0000,
            sunGlareColor: parseInt(wpVantaOptions.sunGlareColor.replace('0x', ''), 16) || 0x4830ff,
            sunlightColor: parseInt(wpVantaOptions.sunlightColor.replace('0x', ''), 16) || 0x25cce3,
            speed: parseFloat(wpVantaOptions.speed) || 0.60
        };

        VANTA.CLOUDS(options);
    }
});