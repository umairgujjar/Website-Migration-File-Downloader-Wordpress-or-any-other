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
    // Maximum execution time (adjust as needed)
    // set_time_limit(24 * 60 * 60);

    // Folder to save downloaded files
    $destination_folder = '/path/to/destination/'; // Modify the path as needed

    if (isset($_POST['submit'])) {
        $url = $_POST['url'];
        $newfname = $destination_folder . basename($url); // Extract file name from URL

        // Use cURL to download large files efficiently
        $result = downloadDistantFile($url, $newfname);
        echo '<div class="mt-8 text-center text-gray-700">';
        echo is_bool($result) && $result ? '<p class="text-green-500 font-semibold">File downloaded successfully!</p>' : '<p class="text-red-500 font-semibold">' . $result . '</p>';
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
        $ch = curl_init();
        $fp = fopen($dest, 'w'); // Open the local file for writing

        if ($fp === false) {
            return "Failed to open local file for writing.";
        }

        // Set cURL options
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_FOLLOWLOCATION => true, // Follow redirects
            CURLOPT_FILE => $fp, // Write output to the file
            CURLOPT_FAILONERROR => true, // Fail if HTTP code > 400
        ];

        curl_setopt_array($ch, $options);
        $return = curl_exec($ch); // Execute the request

        if ($return === false) {
            $error_message = curl_error($ch);
            fclose($fp);
            curl_close($ch);
            return "cURL error: " . $error_message;
        }

        fclose($fp);
        curl_close($ch);
        return true;
    }
    ?>
</body>
</html>
