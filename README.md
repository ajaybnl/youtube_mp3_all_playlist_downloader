# 🎶 Playlist Downloader

### Convert YouTube Playlists to MP3 Files with Ease!  
*Download multiple MP3 files at once directly from YouTube playlists.*

---

## 🌟 Requirements
- **Local / Raspberry Pi / Remote PHP Server** (e.g., XAMPP or EasyPHP)
- **(Optional)**: For uninterrupted downloads, use [Chrono Download Manager for Chrome](https://chrome.google.com/webstore/detail/chrono-download-manager/), and disable *"Show notification when download completes"* for a seamless experience.

---

## 🚀 Deployment
1. Upload all files in this repository to your server's `www` folder.  
   - For XAMPP, place files in the `htdocs` folder.
   
---

## 📥 Downloading
1. Open your server’s URL in a browser: `http://[YOUR_SERVER_ADDRESS]/index.php`.
2. Enter the YouTube playlist URL in the textbox and click **Download**.

> The download process will begin, with each video in the playlist converted to an MP3 file on your browser.  
> A progress bar will indicate the download status for each playlist item.

⚠️ **Important**: When prompted, make sure to allow "multiple downloads" for pop-ups.  
Once all downloads are complete, the script will display **"All Files Downloaded"** and the progress bar will reach 100%.

---

## 🤖 Smart Features
- **Avoid Duplicate Downloads**:  
  The script keeps track of downloaded URLs. If you refresh or revisit, only the undownloaded URLs will be processed.  
  A record of completed downloads is saved in a file named `downloaded_urls_[SESSION_ID].txt`.

- **Automatic Skip**:  
  Previously downloaded URLs are skipped if they appear in future playlists. Note that this feature only works within the same browser or device due to cookie-based storage.

> **Note**: If using a different device or browser, a new unique cookie ID will be generated, and previously downloaded URLs won’t be recognized.

---

Happy downloading! 🎉
