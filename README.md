# WP Vanta Background

A WordPress plugin that integrates Vanta.js to add animated background effects to all WordPress pages. Choose from multiple effects (Birds, Clouds, Fog, Waves, Topology, and more) with full customization via admin settings.

## Features

- Adds beautiful animated Vanta.js background effects to all pages
- Support for 13 different effects (Birds, Cells, Clouds, Clouds 2, Dots, Fog, Globe, Halo, Net, Rings, Topology, Trunk, Waves)
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

The plugin provides comprehensive customization options for each Vanta.js effect. Common settings include:

### General Settings (All Effects)
- **Mouse Controls**: Enable/disable mouse interaction
- **Touch Controls**: Enable/disable touch interaction
- **Gyro Controls**: Enable/disable gyroscope controls (mobile)
- **Min Height/Width**: Minimum dimensions for the effect

### Effect-Specific Settings

**Clouds & Clouds 2**
- Sky Color, Cloud Color, Light Color, Sun Color, Sun Glare Color, Sunlight Color
- Speed

**Waves**
- Color, Shininess, Wave Height, Wave Speed, Zoom

**Birds**
- Colors, Size, Quantity, Wing Span, Speed Limit, Separation, Alignment, Cohesion
- Background Color & Alpha

**Fog**
- Highlight Color, Midtone Color, Lowlight Color, Base Color
- Blur Factor, Zoom, Speed

**Topology**
- Scale, Scale Mobile

**NET & Dots**
- Color, Background Color
- Points, Max Distance, Spacing
- Show Dots/Lines toggle

**Cells**
- Color 1 & Color 2
- Size, Speed

**Globe**
- Color, Color 2, Background Color
- Size

**Rings**
- Color, Background Color
- Background Alpha

**Trunk** (p5.js based)
- Color, Color 2, Background Color
- Spacing, Chaos

**Halo**
- Base Color, Background Color
- Size, Amplitude Factor, X/Y Offset

## Available Effects

- **Birds** - Animated flying birds
- **Cells** - Cellular automata pattern
- **Clouds** - Fluffy cloud effects (default)
- **Clouds 2** - Alternative cloud rendering
- **Dots** - Animated dot particles
- **Fog** - Atmospheric fog effect
- **Globe** - 3D rotating globe
- **Halo** - Glowing halo effect
- **Net** - Network-style pattern
- **Rings** - Colorful concentric rings
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