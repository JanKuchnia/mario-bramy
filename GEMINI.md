# GEMINI.md

## Project Overview

This project is a static website for "Mario Bramy", a company specializing in aluminum gates, fences, and railings. The website is built with plain HTML, CSS, and JavaScript. It showcases the company's products and services, provides contact information, and features a gallery of their work.

The styling is done using a combination of a CSS framework (likely Tailwind CSS, based on the class names used in the HTML) and custom stylesheets located in the `assets` directory.

## Building and Running

This is a static website, so there is no complex build process.

### Running Locally

To view the website, you can open the `index.html` file directly in your web browser. For full functionality (like fetching assets), it's recommended to serve the files using a simple local HTTP server.

If you have Python installed, you can run the following command in the project's root directory:

```bash
python -m http.server
```

This will start a web server, and you can access the website at `http://localhost:8000`.

### Asset Optimization

The project includes a Python script `optimize_assets.py` to extract embedded base64 assets (images and fonts) from HTML files and save them as external files.

To run the script:

```bash
python optimize_assets.py
```

This will modify the HTML files in place, replacing the base64 strings with file paths to the extracted assets in the `assets` directory. It's a good practice to run this script after adding new embedded assets to the HTML.

## Development Conventions

*   **Styling**: The project uses a utility-first CSS approach, likely with Tailwind CSS. Custom styles are defined in `assets/style.css` and `assets/base.css`.
*   **JavaScript**: JavaScript is used for dynamic features like the image slideshow and mobile menu. The code is included directly in the HTML files or in separate files in the `assets` directory (e.g., `assets/mobile_menu.js`).
*   **Asset Management**: The `optimize_assets.py` script suggests a convention of embedding assets as base64 strings during development and then extracting them for production.
*   **Images**: Project-related images are stored in the `assets/portfolio` directory, categorized by product type.
