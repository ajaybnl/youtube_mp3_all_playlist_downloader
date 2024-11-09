<?php

// Check if the 'downloaded_urls_file' cookie exists
if (!isset($_COOKIE['downloaded_urls_file'])) {
    // Generate a unique filename, e.g., "downloaded_urls_{random_id}.txt"
    $filename = 'downloaded_urls_' . uniqid() . '.txt';

    // Set the cookie to expire in 1 day (86400 seconds)
    setcookie('downloaded_urls_file', $filename, "/");


} else {
    // Use the existing cookie value for filename
    $filename = $_COOKIE['downloaded_urls_file'];


}

$downloadedUrlsFile = $filename;

$videoUrls = false;



// Create the file if it does not exist
if (!file_exists($downloadedUrlsFile)) {
    file_put_contents($downloadedUrlsFile, ""); // Create an empty file
    chmod($downloadedUrlsFile, 0777);           // Set permissions to 777
}

// 1st Response - Process AJAX request from JavaScript
if (isset($_POST['response'])) {
    $response = $_POST['response'];

    extract0($response);
    die();
}


// 1st Response - Process AJAX request from JavaScript
if (isset($_POST['downloadedUrl'])) {
    $url = $_POST['downloadedUrl'];
    file_put_contents($downloadedUrlsFile, $url . PHP_EOL, FILE_APPEND);
    die();
}



// Function to handle playlist URL input and download videos
if (isset($_GET['playlist'])) {
    $playlistUrl = $_GET["playlist"];

    $playlistHtml = file_get_contents($playlistUrl);

    if ($playlistHtml === false)
        die("Failed to retrieve playlist HTML.");

    $videoUrls = extractVideoUrls($playlistHtml);

    $videoUrls = array_unique($videoUrls);

    $videoUrls = array_values($videoUrls);

    $downloadedUrls = file_exists($downloadedUrlsFile) ? file($downloadedUrlsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

    $videoUrls = array_diff($videoUrls, $downloadedUrls);

    $videoUrls = array_values($videoUrls);


    // echo "Downloading " . count($videoUrls) . " Videos<br>\r\n";


}

body($videoUrls);
function body($youtubeUrls = false)
{
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            /* Style for the progress bar container */
            #progress-container {
                width: 100%;
                background-color: #f3f3f3;
                border-radius: 5px;
                margin-top: 20px;
            }

            /* Style for the progress bar itself */
            #progress-bar {
                width: 0;
                height: 20px;
                background-color: #4caf50;
                border-radius: 5px;
                text-align: center;
                color: white;
                line-height: 20px;
            }
        </style>
    </head>

    <body>

        <h4>Download YouTube Mp3 Thru Playlist by <a href="mailto:ajaybnl@gmail.com">Ajay Kumar</a></h4>
        <form action="index.php">
            <label for="playlist">Playlist Url:</label><br>
            <input style="min-width:400px;font-family:verdana;font-size:12px;height:20px;" type="text" id="playlist" name="playlist" value="">&nbsp;
            <input type="submit" value="Download">
        </form>
        <hr>
        <br>

        <?php
        if ($youtubeUrls) {
            ?>

            <h2 id="stitle">Progress : 0/0</h2>

            <!-- Progress bar container -->
            <div id="progress-container">
                <div id="progress-bar"></div>
            </div>

            <br><br>
            <div id="iframe-container"></div>
            <script>
                var youtubeUrls = <?php echo json_encode($youtubeUrls); ?>;
                var keys = Object.keys(youtubeUrls);
                var total = keys.length;
                var index = 0;

                const progressBar = document.getElementById("progress-bar");

                // Function to initiate download for each video URL
                async function downloadVideo(url, index) {
                    //console.log("Starting download for video:", url);
                    const apiUrl = "https://genyoutube.online/mates/en/analyze/ajax?retry=undefined&platform=youtube";
                    const data = new URLSearchParams({ url, ajax: '1', lang: 'en' });

                    try {
                        const response = await fetch(apiUrl, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: data
                        });
                        const result = await response.text();
                        sendResponseToPHP(result, index);
                    } catch (error) {
                        console.error("Error downloading link:", url, "Error:", error);
                    }
                }

                // Function to handle response and create iframe for download
                async function sendResponseToPHP(response, index) {
                    try {
                        const res = await fetch('index.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: new URLSearchParams({ response })
                        });
                        const downloadLink = (await res.text()).trim();

                        if (downloadLink.startsWith("http")) {
                            const iframe = document.createElement("iframe");
                            iframe.src = downloadLink;
                            iframe.style.width = "0px";
                            iframe.style.height = "0px";
                            document.getElementById("iframe-container").appendChild(iframe);
                        } else {
                            console.warn(downloadLink + ":", youtubeUrls[keys[index]]);
                            
                        }

                        logDownloadedUrl(youtubeUrls[keys[index]]);
                        console.log("Link " + (index + 1) + " : Done");
                    } catch (error) {
                        console.error("Error sending response to PHP:", error);
                    }
                }

                // Function to log the downloaded URL
                async function logDownloadedUrl(url) {
                    try {
                        await fetch('index.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: new URLSearchParams({ downloadedUrl: url })
                        });
                    } catch (error) {
                        console.error("Error logging URL:", error);
                    }
                }

                // Function to initiate the next download
                function downloadNext() {
                    document.getElementById("stitle").innerText = `Progress : ${index}/${total}`;
                    progressBar.style.width = `${(index / total) * 100}%`;

                    if (index < total) {
                        var currentUrl = youtubeUrls[keys[index]];
                        //console.log("Start:", index);

                        downloadVideo(currentUrl, index);
                        index++;
                        setTimeout(downloadNext, 2000); // 2-second delay between downloads
                    } else {
                        document.getElementById("stitle").innerText = "Progress : All Links Downloaded";
                        console.log("All downloads complete.");
                    }
                }

                // Start the download process
                downloadNext();
            </script>

            <?php
        }
}



















// Function to extract video URLs from a playlist page
function extractVideoUrls($html)
{
    $needle = '/watch?v=';
    $offset = 0;
    $videoUrls = [];

    while (($pos = stripos($html, $needle, $offset)) !== false) {
        $start = $pos + strlen($needle);
        $end = stripos($html, '"', $start);
        $videoId = substr($html, $start, $end - $start);
        $videoUrl = 'https://www.youtube.com/watch?v=' . $videoId;

        // Add unique video URLs to the array
        if (!in_array($videoUrl, $videoUrls)) {
            $videoUrls[] = substr($videoUrl, 0, 43);
        }
        $offset = $end;
    }
    return $videoUrls;
}


function extract0($html)
{
    //html from POST https://genyoutube.online/mates/en/analyze/ajax?retry=undefined&platform=youtube

    if ($html !== false) {
        // Check if the response contains 'mp3' data
        if (strpos($html, "'mp3'") !== FALSE) {
            //$matches = extract1($html);

            $matches = array();
            $pattern = "/download\('([^']*)','([^']*)','([^']*)','([^']*)',([0-9]+),'([^']*)','([^']*)'\)/";
            preg_match($pattern, $html, $matches);


            // Extract necessary data
            $youtubeurl = str_replace("\/", "/", $matches[1]);
            $title = $matches[2];
            $id = $matches[3];
            $extention = $matches[4];
            $quality = $matches[6];

            // Extract further data using extract2()
            $json = extract2($youtubeurl, $title, $id, $extention, $quality);

            if ($json) {
                // If 'downloadUrlX' is found in the response, extract the URL
                if (strpos($json, "downloadUrlX") !== FALSE) {
                    //$urlret = extract3($html);

                    $data = json_decode($json, true);
                    $urlret = $data['downloadUrlX'] ?? false;

                    echo ($urlret && strpos($urlret, "http") !== FALSE) ? trim($urlret) : "Err";
                } else {
                    echo "Link Download Error";
                }
            } else {
                echo "extract0: No data found";
            }
        } else {
            echo "No Mp3 data in First Request";
        }
    } else {
        echo "Failed to Post the First Response";
    }
}





// Function to initiate conversion process
function extract2($youtubeurl, $title, $id, $extention, $quality)
{
    $url = "https://genyoutube.online/mates/en/convert?id=" . $id;
    $postData = http_build_query([
        'platform' => 'youtube',
        'url' => $youtubeurl,
        'title' => $title,
        'id' => $id,
        'ext' => $extention,
        'note' => $quality,
        'format' => ""
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Requested-With: XMLHttpRequest']);

    $response = curl_exec($ch);
    curl_close($ch);

    return curl_errno($ch) ? false : $response;
}










