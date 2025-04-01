<?php
session_start();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCi_NEXT</title>
    <meta http-equiv="refresh" content="2.8;url=admin/dashboard_superadmin/dashboard_superadmin.php">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: black;
            margin: 0;
        }
        video {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <video id="video" autoplay>
        <source id="videoSource" src="video/SCi_NEXT.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <script>
        const video = document.getElementById('video');
        const videoSource = document.getElementById('videoSource');
        
        videoSource.src = 'video/SCi_NEXT.mp4?' + new Date().getTime();
        video.load();
    </script>
</body>
</html>

