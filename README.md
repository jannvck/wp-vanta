# WP Vanta Background

A WordPress plugin that integrates Vanta.js to add an animated CLOUDS background to all pages, working across all themes.

## Features

- Adds a beautiful animated clouds background using Vanta.js
- Customizable settings via WordPress admin
- Theme-agnostic: works with any WordPress theme
- Lightweight: loads scripts from CDN

## Installation

1. Download the latest `wp-vanta.zip` from [Releases](https://github.com/jannvck/wp-vanta/releases)
2. Upload and install via WordPress admin (Plugins > Add New > Upload Plugin)
3. Activate the plugin
4. Go to Settings > WP Vanta to customize the background

## Automatic Updates

To enable automatic updates from this repository, install a plugin like [GitHub Updater](https://github.com/afragen/github-updater) and configure it to check this repo.

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

## Development

- Clone the repo
- Create a new release on GitHub to trigger automatic zip generation
- The workflow will create and attach `wp-vanta.zip` to the release

## Notes

- The effect uses WebGL and may not work on very old devices
- For mobile devices, consider disabling some controls for better performance