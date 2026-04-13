# WP Vanta Background

A WordPress plugin that integrates Vanta.js to add animated background effects to all WordPress pages. Choose from multiple effects (Birds, Clouds, Fog, Waves, Topology, and more) with full customization via admin settings.

## Features

- Adds beautiful animated Vanta.js background effects to all pages
- Support for 10+ different effects (Birds, Clouds, Clouds 2, Fog, Halo, Net, Noise, Topology, Trunk, Waves)
- Customizable settings via WordPress admin
- Theme-agnostic: works with any WordPress theme
- Lightweight: loads scripts from CDN
- Full control over effect parameters and colors

## Installation

1. Download the latest `wp-vanta.zip` from [Releases](https://github.com/jannvck/wp-vanta/releases)
2. Upload and install via WordPress admin (Plugins > Add New > Upload Plugin)
3. Activate the plugin
4. Go to Settings > WP Vanta to customize the background

## Automatic Updates

To enable automatic updates from this repository, install a plugin like [GitHub Updater](https://github.com/afragen/github-updater) and configure it to check this repo.

## Settings

- **Effect**: Choose from 10+ Vanta.js effects
- **Mouse Controls**: Enable/disable mouse interaction
- **Touch Controls**: Enable/disable touch interaction
- **Gyro Controls**: Enable/disable gyroscope controls
- **Min Height/Width**: Minimum dimensions for the effect
- **Colors**: Customize sky, cloud, sun, glare, and sunlight colors (hex format, e.g. 0x1ea6e6)
- **Speed**: Animation speed

## Available Effects

- **Birds** - Animated flying birds
- **Clouds** - Fluffy cloud effects (default)
- **Clouds 2** - Alternative cloud rendering
- **Fog** - Atmospheric fog effect
- **Halo** - Glowing halo effect
- **Net** - Network-style pattern
- **Noise** - Perlin noise animation
- **Topology** - Topographic/elevation map effect
- **Trunk** - Tree trunk/branch effect (p5.js based)
- **Waves** - Animated wave effect

## Requirements

- WordPress 4.0+
- Modern browser with WebGL support (except Trunk which uses p5.js)

## Development

- Clone the repo
- Create a new release on GitHub to trigger automatic zip generation
- The workflow will create and attach `wp-vanta.zip` to the release

## Notes

- Some effects may not perform optimally on older devices
- For mobile devices, consider disabling some controls for better performance
- The Trunk effect uses p5.js instead of Three.js