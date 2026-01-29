
import os
import re

# Mapping of old files to new files
files_map = {
    'index.html': 'index.php',
    'kontakt.html': 'kontakt.php',
    'nasze-projekty.html': 'nasze-projekty.php',
    'opinie.html': 'opinie.php',
    'wkrotce.html': 'sklep.php', # Renaming wkrotce to sklep
    'sklep.html': 'sklep.php',   # Just in case
    'admin/index.html': 'admin/index.php',
}

def replace_links(content):
    # Replace links based on the map
    for old, new in files_map.items():
        # Handle relative links differently maybe, but simple replacement should work for this project
        # We need to be careful about not replacing substrings incorrectly, e.g. 'index.html'
        
        # Replace href="index.html"
        content = content.replace(f'href="{old}"', f'href="{new}"')
        content = content.replace(f"href='{old}'", f"href='{new}'")
        
        # Also clean up any potential "wkrotce.html" to "sklep.php"
        if old == 'wkrotce.html':
            content = content.replace('href="wkrotce.html"', 'href="sklep.php"')
            
    return content

def convert_file(html_path, php_path):
    if not os.path.exists(html_path):
        print(f"Skipping {html_path}, file not found.")
        return

    with open(html_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Apply transformations
    content = replace_links(content)

    # Write to PHP file
    with open(php_path, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"Converted {html_path} -> {php_path}")

# Main conversion loop
for old, new in files_map.items():
    if '/' in old:
         # Handle subdirectories (admin)
         pass # Already handled manually or will be handled
    else:
        convert_file(old, new)

# Special handling for sklep which was wkrotce.html
if os.path.exists('wkrotce.html'):
    convert_file('wkrotce.html', 'sklep.php')
    
# Remove old HTML files?
# for old in files_map.keys():
#     if os.path.exists(old):
#         os.remove(old)

print("Conversion complete.")
