# gallery-bridges
WordPress plugin: integrating with API image sources for galleries and sliders

# Gallery Bridges

## Description

This WordPress plugin allows you to easily create and manage image galleries sourced from external APIs (initially Unsplash). It provides a backend-focused solution for non-developers to curate image collections and manage assets within the WordPress dashboard, without making direct API calls on the front end. The plugin uses custom database tables to store API connection details and fetched image data, enabling the creation of custom image galleries and sliders.

## Features

* **Custom Taxonomy:** `image_collection` - Organize your images into collections (e.g., Landscapes, Portraits, Abstract).
* **Backend API Management:** A dedicated admin menu allows you to configure and manage connections to external image APIs (starting with Unsplash).
* **Image Collection Management:** Create and manage rows of image collections, specifying the API source and search queries.
* **Separate Fetch and Process:** Backend buttons within each collection row enable the fetching of images from the configured API and the subsequent processing of these images (e.g., for aspect ratios).
* **Local Image Storage:** Fetched images are downloaded and hosted locally within your WordPress installation.
* **Image Attribution:** Includes fields to store and display proper attribution to photographers and the image source (e.g., Unsplash).
* **Slider and Gallery Integration:** Designed to work with custom slider templates (like the existing paginated slider) and static gallery grids.

## Installation

1.  Upload the `gallery-bridges` folder to the `/wp-content/plugins/` directory.
2.  Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

1.  Navigate to the "Gallery Bridges" menu in your WordPress admin.
2.  Under "Manage External Connections," add and configure your desired image API endpoints (e.g., Unsplash API details).
3.  Under "Manage Image Collections," create new collections, selecting the API source and providing a search query.
4.  Use the "Fetch Images" button for each collection to retrieve images from the specified API.
5.  Use the "Process Images" button to handle image processing tasks (to be implemented).
6.  Embed your image collections on pages using custom blocks or by referencing the collection within existing block options (e.g., a custom HTML block with a specific identifier).

## Database Structure

* **`wp_gb_api_options`**
    * `id` (BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY)
    * `name` (VARCHAR(255) NOT NULL): Name of the API connection (e.g., 'Unsplash Images').
    * `base_url` (TEXT NOT NULL): The base URL of the API.
    * `endpoint` (VARCHAR(255)): Specific API endpoint (e.g., '/search/photos').
    * `api_key` (TEXT): API key for authentication.
    * `search_structure` (TEXT): How to construct the search URL with the query.
    * `UNIQUE KEY` (`name`)
* **`wp_gb_image_collections`**
    * `id` (BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY)
    * `name` (VARCHAR(255) NOT NULL): Name of the image collection (e.g., 'Floral Illustrations').
    * `api_option_id` (BIGINT UNSIGNED): Foreign Key referencing `wp_gb_api_options`.`id`.
    * `search_query` (VARCHAR(255)): The search term for the API.
    * `collected_images_meta` (LONGTEXT): Serialized array of image metadata from the API.
    * `collected_images_source` (LONGTEXT): Serialized array of local image file paths or source URLs.
    * `UNIQUE KEY` (`name`)

## Version Control

This project will use Git for version control and GitHub for remote repository management. The `main` branch will represent the stable version. Feature development and bug fixes will occur in separate branches, with pull requests used for merging into `main`. A `.gitignore` file configured for WordPress projects will be used to exclude unnecessary files.
