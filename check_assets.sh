#!/bin/bash
echo "=== Checking Asset Files ==="
echo ""
echo "CSS Files:"
ls -lh public/assets/css/*.css 2>/dev/null | awk '{print $5, $9}'
echo ""
echo "JS Files:"
ls -lh public/assets/js/*.js 2>/dev/null | awk '{print $5, $9}'
echo ""
echo "=== Status ==="
for file in public/assets/css/vendors.css public/assets/css/aiz-core.css public/assets/js/vendors.js public/assets/js/aiz-core.js; do
    if [ -f "$file" ]; then
        size=$(stat -f%z "$file" 2>/dev/null || stat -c%s "$file" 2>/dev/null)
        if [ "$size" -lt 1000 ]; then
            echo "❌ $file - TOO SMALL ($size bytes) - Still a placeholder!"
        else
            echo "✅ $file - OK ($size bytes)"
        fi
    else
        echo "❌ $file - MISSING"
    fi
done
