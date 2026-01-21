# Creating favicon.ico from SVG

To create a proper `.ico` file from the SVG favicon, you have several options:

## Option 1: Online Converter (Easiest)
1. Visit https://convertio.co/svg-ico/ or https://realfavicongenerator.net/
2. Upload `public/assets/img/favicon.svg`
3. Download the generated `favicon.ico`
4. Place it in `public/favicon.ico`

## Option 2: Using ImageMagick (Command Line)
```bash
# Install ImageMagick first (if not installed)
# macOS: brew install imagemagick
# Ubuntu: sudo apt-get install imagemagick

# Convert SVG to ICO with multiple sizes
convert public/assets/img/favicon.svg \
  -resize 16x16 public/favicon-16.png \
  -resize 32x32 public/favicon-32.png \
  -resize 48x48 public/favicon-48.png

# Combine into ICO file
convert public/favicon-16.png public/favicon-32.png public/favicon-48.png public/favicon.ico
```

## Option 3: Using Python (Pillow)
```python
from PIL import Image
import cairosvg

# Convert SVG to PNG
cairosvg.svg2png(url='public/assets/img/favicon.svg', write_to='favicon.png', output_width=32, output_height=32)

# Convert PNG to ICO
img = Image.open('favicon.png')
img.save('public/favicon.ico', format='ICO', sizes=[(16,16), (32,32), (48,48)])
```

## Option 4: Use SVG Directly (Modern Browsers)
Modern browsers support SVG favicons directly. The layout file already includes:
```html
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/favicon.svg') }}" />
```

This will work in Chrome, Firefox, Edge, and Safari (recent versions).

## Recommended Sizes for ICO
- 16x16 pixels (standard)
- 32x32 pixels (high DPI)
- 48x48 pixels (Windows)

The SVG favicon will automatically scale to any size needed!

