<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Download Tool</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-lg mx-auto bg-white p-8 mt-16 rounded-xl shadow-lg">
        <h1 class="text-3xl font-semibold text-center text-gray-900 mb-8">Download Files from URL</h1>

        <form method="post" class="space-y-6">
            <div class="relative">
                <input name="url" type="url" placeholder="Enter file URL" class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="flex justify-center">
                <button type="submit" name="submit" class="w-1/3 py-3 bg-indigo-600 text-white rounded-md text-lg font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Download
                </button>
            </div>
        </form>

        <p class="text-center text-gray-500 mt-6">Files will be downloaded to <?php echo getcwd(); ?></p>
    </div>

    <?php
    if (isset($_POST['submit'])) {
        $url = $_POST['url'];
        
        // Use current directory with a downloads subfolder
        $destination_folder = __DIR__ . '/downloads/';
        
        // Create downloads directory if it doesn't exist
        if (!file_exists($destination_folder)) {
            if (!mkdir($destination_folder, 0755, true)) {
                echo '<div class="mt-8 text-center text-red-500 font-semibold">Failed to create downloads directory.</div>';
                exit;
            }
        }
        
        // Validate URL format
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            echo '<div class="mt-8 text-center text-red-500 font-semibold">Invalid URL format.</div>';
            exit;
        }
        
        $filename = basename(parse_url($url, PHP_URL_PATH) ?: 'downloaded_file');
        $newfname = $destination_folder . $filename;

        // Use cURL to download large files efficiently
        $result = downloadDistantFile($url, $newfname);
        echo '<div class="mt-8 text-center text-gray-700">';
        if ($result === true) {
            echo '<p class="text-green-500 font-semibold">File downloaded successfully!</p>';
            echo '<p class="text-gray-600 mt-2">Saved as: ' . htmlspecialchars($filename) . '</p>';
        } else {
            echo '<p class="text-red-500 font-semibold">' . htmlspecialchars($result) . '</p>';
        }
        echo '</div>';
    }

    /**
     * Download a file from a distant URL to a local destination efficiently using cURL.
     *
     * @param string $url The URL of the file to download
     * @param string $dest The local destination where the file should be saved
     * @return bool|string True on success, error message on failure
     */
    function downloadDistantFile($url, $dest)
    {
        // Check if destination directory is writable
        $dir = dirname($dest);
        if (!is_writable($dir)) {
            return "Destination directory is not writable: " . $dir;
        }

        $ch = curl_init();
        $fp = @fopen($dest, 'w');

        if ($fp === false) {
            return "Failed to open local file for writing: " . $dest;
        }

        // Set cURL options
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_FILE => $fp,
            CURLOPT_FAILONERROR => true,
            CURLOPT_TIMEOUT => 300, // 5 minute timeout
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            CURLOPT_SSL_VERIFYPEER => false, // Be cautious with this in production
        ];

        curl_setopt_array($ch, $options);
        $return = curl_exec($ch);

        if ($return === false) {
            $error_message = curl_error($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            fclose($fp);
            
            // Delete partially downloaded file on error
            if (file_exists($dest)) {
                unlink($dest);
            }
            
            curl_close($ch);
            return "Download failed (HTTP $http_code): " . $error_message;
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        fclose($fp);
        curl_close($ch);

        // Check if download was actually successful
        if ($http_code !== 200) {
            if (file_exists($dest)) {
                unlink($dest);
            }
            return "Download failed with HTTP status: " . $http_code;
        }

        // Check if file was actually downloaded
        if (!file_exists($dest) || filesize($dest) === 0) {
            if (file_exists($dest)) {
                unlink($dest);
            }
            return "Downloaded file is empty or failed to save.";
        }

        return true;
    }
    ?>
</body>
</html>
