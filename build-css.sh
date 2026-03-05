#!/bin/bash

# Build CSS from SCSS
echo "Compiling SCSS to CSS..."
sass assets/scss/style.scss style.css --style=compressed

echo "CSS compilation complete!"