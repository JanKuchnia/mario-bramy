
import re
import os
import base64
import mimetypes

# Config
html_file = "index.html"
assets_dir = "assets"

def get_extension(mime_type):
    if mime_type == "image/svg+xml":
        return ".svg"
    if mime_type == "image/avif":
        return ".avif"
    if mime_type == "image/webp":
        return ".webp"
    if mime_type == "font/woff2":
        return ".woff2"
    ext = mimetypes.guess_extension(mime_type)
    return ext if ext else ".bin"

def optimize():
    if not os.path.exists(html_file):
        print(f"File {html_file} not found.")
        return

    with open(html_file, 'r', encoding='utf-8') as f:
        content = f.read()

    if not os.path.exists(assets_dir):
        os.makedirs(assets_dir)

    # Counter for filenames
    counter = 100 # Start from 100 to avoid collision with previous runs if any

    def replace_match(match):
        nonlocal counter
        mime_type = match.group(1)
        b64_data = match.group(2)
        
        ext = get_extension(mime_type)
        prefix = "font" if "font" in mime_type else "img"
        filename = f"{prefix}_{counter}{ext}"
        counter += 1
        
        filepath = os.path.join(assets_dir, filename)
        
        try:
            file_content = base64.b64decode(b64_data)
            with open(filepath, 'wb') as f_out:
                f_out.write(file_content)
            print(f"Saved {filepath} ({mime_type})")
            return f'assets/{filename}'
        except Exception as e:
            print(f"Failed to process data URI: {e}")
            return match.group(0)

    pattern = r'data:([a-zA-Z0-9/+\-\.]+);base64,([a-zA-Z0-9/+=]+)'
    new_content = re.sub(pattern, replace_match, content)

    with open(html_file, 'w', encoding='utf-8') as f:
        f.write(new_content)
        
    print("Optimization complete.")

if __name__ == "__main__":
    optimize()
