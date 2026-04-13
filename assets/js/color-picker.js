jQuery(document).ready(function($) {
    // Initialize color pickers
    $('.wp-vanta-color-picker').each(function() {
        var $input = $(this);
        var colorPickerElement = $input.wpColorPicker({
            change: function(event, ui) {
                // Convert # format to 0x format
                var hexColor = ui.color.toString();
                var colorValue = '0x' + hexColor.replace('#', '');
                $input.val(colorValue);
            }
        });
        
        // Set initial color from 0x value
        var initialValue = $input.val();
        if (initialValue && initialValue.startsWith('0x')) {
            var hexColor = '#' + initialValue.replace('0x', '');
            $input.wpColorPicker('color', hexColor);
        }
    });
});

