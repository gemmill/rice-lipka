#!/bin/bash

# Build CSS from SCSS
echo "Compiling SCSS to CSS..."
echo "Source: assets/scss/style.scss"
echo "Output: style.css"
echo ""

sass assets/scss/style.scss style.css --style=compressed

if [ $? -eq 0 ]; then
    echo "✅ CSS compilation complete!"
    echo "📁 Compiled files:"
    echo "   - Reset styles from _reset.scss"
    echo "   - Project component from _project-component.scss"
    echo "   - Main theme styles"
else
    echo "❌ CSS compilation failed!"
    exit 1
fi