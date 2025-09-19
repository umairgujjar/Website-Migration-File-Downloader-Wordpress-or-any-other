# PHP File Downloader Tool for Website Migrations

This PHP tool is designed to make downloading files from any URL simple and efficient. It’s especially useful for website migrations, WordPress site transfers, or any task involving moving large amounts of data between servers. The script uses **cURL** for optimized file downloads and provides a simple user interface to initiate the process.

## Features
- **Simple Interface**: Provides a user-friendly web form to input the file URL and start the download.
- **cURL Efficient Download**: Uses cURL for efficient, memory-friendly downloading of large files.
- **Error and Success Feedback**: Provides feedback about download status (success or error messages).
- **Responsive Design**: Built with **Tailwind CSS** for a modern and mobile-friendly user experience.
- **Ideal for Migration**: Perfect for migrating WordPress sites or transferring data across servers.

## Use Cases
- Migrating **WordPress sites** between hosting servers.
- Moving large data backups from one server to another.
- Downloading assets and files from remote servers to local systems during migrations.
- General-purpose file downloading for developers and system administrators.

## Installation and Setup
1. **Download the script**: Clone or download the repository files to your local server.
2. **Modify the destination folder**: Open the `import.php` file and set the `$destination_folder` variable to the directory where you want to save the downloaded files.
   - Example: `'/path/to/your/backup/folder/'`
3. **Permissions**: Ensure the destination folder is writable by the web server to avoid permission errors.
4. **Upload to Server**: Upload the PHP file to your web server where you want the downloads to occur.
5. **Test the Tool**: Access the page via your browser, input a URL of the file you want to download, and hit 'Download' to transfer the file.

## Example
- Source URL: `https://example.com/file.zip`
- Destination folder: `/path/to/your/destination/folder/`

## Requirements
- PHP version 5.6 or higher.
- cURL extension enabled (usually enabled by default).
- A writable folder on your server to store the downloaded files.

## How It Works
Once the form is submitted:
- The tool will download the file from the provided URL.
- It will save the file in the specified destination folder on your server.
- After completion, it will display success or error messages based on the outcome.

## Troubleshooting
- If you encounter issues with **file permissions**, make sure the destination folder is writable.
- For **file download failures**, check the URL to ensure it is accessible and correct. You may also encounter server-related issues that could prevent the download.

## License
This script is released under the MIT License. Feel free to modify and use it for your own projects.
