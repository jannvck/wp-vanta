# WP Vanta Background

A WordPress plugin that integrates Vanta.js to add an animated CLOUDS background to all pages, working across all themes.

## Features

- Adds a beautiful animated clouds background using Vanta.js
- Customizable settings via WordPress admin
- Theme-agnostic: works with any WordPress theme
- Lightweight: loads scripts from CDN

## Installation

1. Upload the `wp-vanta` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > WP Vanta to customize the background

## Settings

- **Mouse Controls**: Enable/disable mouse interaction
- **Touch Controls**: Enable/disable touch interaction
- **Gyro Controls**: Enable/disable gyroscope controls
- **Min Height/Width**: Minimum dimensions for the effect
- **Colors**: Customize sky, cloud, sun, glare, and sunlight colors (hex format, e.g. 0x1ea6e6)
- **Speed**: Animation speed

## Requirements

- WordPress 4.0+
- Modern browser with WebGL support

## Notes

- The effect uses WebGL and may not work on very old devices
- For mobile devices, consider disabling some controls for better performance