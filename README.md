# playlist_downloader
### Youtube playlist to mp3 files downloader (Download multiple Mp3 files at once)

## You need: 
### Local / Raspberry / Remote Php Server (Xmapp or Easyphp)
### (Optional) Browser Extention for unintrupted downloads: Chrono Download Manager For Chrome
### (Disable the 'Show notification when download completes' setting in Chrono Download Manager for ease.)

## Deploy:
**Upload everything in this repo to www folder of your server. (for Xmapp put files in 'htdocs')**

## Downloading:
Browse your local/remote server's http://XXXXXXXX/index.php
> You will see a Textbox, fill youtube playlist url, and click 'Download'.

It will start downloading all Playlist Urls as Mp3 files on your browser.
> The progress bar will progress according to playlist urls.

You have to enable 'allow multiple downloads' window when popups.
after downloads are finished, the script will stop and show 'All Files Downloaded' and progressbar will be at 100%.


## Smart features: 
** The script records the downloaded urls ** , if you refresh the page then the urls will be not re-download, only which urls have error will be re-downloaded
it saves all done urls to 'downloaded_urls_XXXXXXX.txt' the last chars are a unique id which was set in your browser cookie.

Normally the script remembers the downloaded urls, if a later downloaded playlist has same urls as some previous playlist, it will skip the already done urls.
If you download the playlist from another pc or browser, then it will not remember this part because the cookie will be re-generated for it.
