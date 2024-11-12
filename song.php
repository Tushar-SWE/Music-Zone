<?php
session_start();
// error_reporting(0);
$id = $_GET['id'];

$url = 'https://www.jiosaavn.com/api.php?__call=song.getDetails&cc=in&_marker=0%3F_marker%3D0&_format=json&pids=' . $id . '';
$song_details = file_get_contents($url);
$song_details = json_decode($song_details, 1);

// $url1 = 'https://www.jiosaavn.com/api.php?__call=lyrics.getLyrics&lyrics_id=' . $id . '&ctx=wap6dot0&api_version=4&_format=json&_marker=0';
// $lyrics_details = file_get_contents($url1);
// $lyrics_details = json_decode($lyrics_details, 1);
// $lyrics = $lyrics_details['lyrics'];
// $lyrics_copyright = $lyrics_details['lyrics_copyright'];
// $script_tracking_url = $lyrics_details['script_tracking_url'];
// $snippet = $lyrics_details['snippet'];



$song_name = $song_details['' . $id . '']['song'];
$song_image = $song_details['' . $id . '']['image'];
$song_image = str_replace('150x150', '500x500', $song_image);
$song_quality = $song_details['' . $id . '']['320kbps'];
$song_encrypted_media_url = $song_details['' . $id . '']['encrypted_media_url'];
$song_release_date = $song_details['' . $id . '']['release_date'];
$song_singers = $song_details['' . $id . '']['singers'];
$song_album = $song_details['' . $id . '']['album'];
// $song_url = str_replace('preview.saavncdn.com', 'aac.saavncdn.com', $song_url);
// $song_url = str_replace('_96_p', '_96', $song_url);

$encrypted_url = urlencode($song_encrypted_media_url);

$url2 = "https://www.jiosaavn.com/api.php?__call=song.generateAuthToken&url=$encrypted_url&bitrate=128&api_version=4&_format=json&ctx=wap6dot0&_marker=0";
// $url3 = "https://www.jiosaavn.com/api.php?__call=song.generateAuthToken&url=$song_encrypted_media_url&bitrate=128&api_version=4&_format=json&ctx=wap6dot0&_marker=0";
$song_details2 = file_get_contents($url2);
$gg = json_decode($song_details2, true);
$full_url = $gg['auth_url'];
$parsed_url = parse_url($full_url);
// Build the URL before the question mark
$url_before_question_mark = $parsed_url['scheme'] . '://' . $parsed_url['host'] . $parsed_url['path'];
$song_url = str_replace('ac.cf.saavncdn.com', 'aac.saavncdn.com', $url_before_question_mark);

// echo $song_url;


$song_size_128 = get_headers($song_url, true);
$song_size_128 = ($song_size_128['Content-Length']);
$song_size_128 = number_format($song_size_128 / 1045504, 2);

$song_192_url = str_replace('_96', '_160', $song_url);
$song_size_192 = get_headers($song_192_url, true);
$song_size_192 = ($song_size_192['Content-Length']);
$song_size_192 = number_format($song_size_192 / 1045504, 2);

$song_320_url = str_replace('_96', '_320', $song_url);
$song_size_320 = get_headers($song_320_url, true);
$song_size_320 = ($song_size_320['Content-Length']);
$song_size_320 = number_format($song_size_320 / 1045504, 2);

if (isset($_POST['download'])) {
  $quality = $_POST['quality'];
  if ($quality == 128) {
    $file_url = $song_url;
    $file_name = $song_name . '_MUSIC - ZONE_128p.mp3';
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"" . basename($file_name) . "\"");
    readfile($file_url);
    exit();
  }
  if ($quality == 192) {
    $file_url = str_replace('_96', '_160', $song_url);
    $file_name = $song_name . '_MUSIC - ZONE_192p.mp3';
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"" . basename($file_name) . "\"");
    readfile($file_url);
  }
  if ($quality == 320) {
    $file_url = str_replace('_96', '_320', $song_url);
    $file_name = $song_name . '_MUSIC - ZONE_320p.mp3';
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"" . basename($file_name) . "\"");
    readfile($file_url);
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <style type="text/css">
    .audio-player {
      height: 50px;
      width: 500px;
      background: #1a1a1a;
      box-shadow: 0 0 20px 0 #000a;
      font-family: arial;
      color: white;
      font-size: 0.75em;
      overflow: hidden;
      display: grid;
      grid-template-rows: 6px auto;
      border: 0 solid;
      border-radius: 25px;
    }

    .audio-player .timeline {
      background: #595959;
      width: 45%;
      position: relative;
      cursor: pointer;
      box-shadow: 0 2px 10px 0 #0008;
      position: relative;
      top: 23px;
      left: 55px;
      border: 0 solid;
      border-radius: 35px;
      height: 4px;
    }

    .audio-player .timeline .progress {
      background: #0086b3;
      width: 0%;
      height: 100%;
      transition: 0.25s;
      box-shadow: 0px 0px 7px #0086b3;
      border: 0 solid;
      border-radius: 45px;
    }

    .audio-player .controls {
      display: flex;
      justify-content: space-between;
      align-items: stretch;
      padding: 0 20px;
    }

    .time {
      position: relative;
      top: -2.5px;
      left: 90px;
      color: #c19c0b;
      font-weight: bold;
      font-family: Nunito Sans;
    }

    .audio-player .controls>* {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .audio-player .controls .toggle-play.play {
      cursor: pointer;
      position: relative;
      left: 0;
      height: 0;
      width: 0;
      border: 7px solid #0000;
      border-left: 13px solid #c19c0b;
      transform: scale(1.2);
      top: -3px;
    }

    .audio-player .controls .toggle-play.play:hover {
      transform: scale(1.3);
    }

    .audio-player .controls .toggle-play.pause {
      height: 15px;
      width: 20px;
      cursor: pointer;
      position: relative;
    }

    .audio-player .controls .toggle-play.pause:before {
      position: absolute;
      top: -3px;
      left: 0px;
      background: #c19c0b;
      content: "";
      height: 17px;
      width: 3px;
    }

    .audio-player .controls .toggle-play.pause:after {
      position: absolute;
      top: -3px;
      right: 8px;
      background: #c19c0b;
      content: "";
      height: 17px;
      width: 3px;
    }

    .audio-player .controls .toggle-play.pause:hover {
      transform: scale(1.1);
    }

    .audio-player .controls .time {
      display: flex;
    }

    .audio-player .controls .time>* {
      padding: 2px;
    }

    .audio-player .controls .volume-container {
      cursor: pointer;
      position: relative;
      z-index: 2;
    }

    .audio-player .controls .volume-container .volume-button {
      height: 26px;
      display: flex;
      align-items: center;
    }

    .audio-player .controls .volume-container .volume-button .volume {
      transform: scale(0.7);
    }

    .audio-player .controls .volume-container .volume-slider {
      position: absolute;
      left: -3px;
      top: 14.5px;
      z-index: -1;
      width: 0;
      height: 10px;
      background: #595959;
      border: 0 solid;
      border-radius: 25px;
      transition: 0.25s;
    }

    .audio-player .controls .volume-container .volume-slider .volume-percentage {
      background: #0086b3;
      height: 100%;
      width: 75%;
      border: 0 solid;
      border-radius: 25px;
      box-shadow: 0px 0px 7px #0086b3;
    }

    .audio-player .controls .volume-container:hover .volume-slider {
      width: 80px;
      position: absolute;
      left: -70px;
    }

    #btn {
      color: #c19c0b;
      position: relative;
      top: -2px;
      left: 7px;
    }

    .name {
      text-align: left;
    }

    @media screen and (max-width: 798px) {
      .audio-player {
        width: 320px;
        position: relative;
        left: -45px;
        top: 20px;
      }

      .audio-player .controls .volume-container:hover .volume-slider {
        display: none;
      }

      .download_btn {
        position: absolute;
        left: 40px;
      }

      .details {
        text-align: center;
      }
    }
  </style>
  <title>Music - Zone</title>
  <meta charset="utf-8">
  <meta http-equiv="refresh" content="">
  <meta name="theme-color" content="#000">
  <meta name="viewport" content="width=device-width">
  <link rel="icon" type="image/png" href="https://i.ibb.co/fHKM1QS/favicon.png"><!-- TITLE ICON-->
  <link href="https://kit-pro.fontawesome.com/releases/v5.15.1/css/pro.min.css" rel="stylesheet"><!-- ICON PACK-->
  <link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&display=swap" rel="stylesheet">
  <!-- FONT STYLE [MARQUEE] {Cabin Sketch}-->
  <link href="https://fonts.googleapis.com/css2?family=Shrikhand&display=swap" rel="stylesheet">
  <!--'Shrikhand', cursive-->
  <link
    href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,400;1,300&family=Sedgwick+Ave+Display&display=swap"
    rel="stylesheet"><!--FONTSTYLE[Nunito]{Nunito Sans}-->
  <link href="https://fonts.googleapis.com/css2?family=Staatliches&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
  <link rel='stylesheet' href='https://icono-49d6.kxcdn.com/icono.min.css'>
  <link rel="stylesheet" href="song.css">
  <script>
    // JS FOR SLIDE-BAR.
    window.addEventListener("load", () => {
      document.body.classList.remove("preload");
    });
    document.addEventListener("DOMContentLoaded", () => {
      const nav = document.querySelector(".nav");
      document.querySelector("#nav_icon").addEventListener("click", () => {
        nav.classList.add("nav--open");
      });
      document.querySelector(".nav__overlay").addEventListener("click", () => {
        nav.classList.remove("nav--open");
      });
    });
    // JS FOR TELEG BTN
    (function() {
      var script = document.createElement("script");
      script.type = "text/javascript";
      script.async = true;
      script.src = "//telegram.im/widget-button/index.php?id=@mr_anonmous";
      document.getElementsByTagName("head")[0].appendChild(script);
    })();
  </script>
</head>

<body class="preload" onload="loaderFunction()">
  <div id="loading">
    <p class="lh">MUSIC - ZONE</p>
    <p class="site">music-zone.epizy.com <span>Is Loading Now . . .</span></p>
  </div>
  <header class="header">
    <h1><a href="../Music-Zone">Music - Zone</a></h1>
    <div class="search_box">
      <form method="GET" action="search">
        <input type="text" name="q" placeholder="Search Music By Name" autocomplete="off" required>
        <button type="submit"><i class="fas fa-search"></i></button>
      </form>
    </div>
    <div class="help_line">
      <div class="help_line_email">
        <a href="mailto:tksainii@gmail.com"><i class="fas fa-envelope"></i>admin@music-zone.com</a>
      </div>
      <div class="help_line_no">
        <a href="tel:8273891616"><i class="fas fa-phone-alt"></i>+91-8273891616</a></p>
      </div>
    </div>
    <div class="nav_bar">
      <i class="fas fa-signal-alt-3" id="nav_icon"></i>
    </div>
  </header>
  <nav class="nav">
    <div class="nav__links">
      <div class="nav_background"><br>
        <a href="#" class="nav__link">
          <i class="fad fa-home-lg"></i>
          <div class="home_msg" id="popup">HOME</div>
        </a><br>
        <a href="#" class="nav__link">
          <i class="fad fa-search"></i>
          <div class="search_msg" id="popup">SEARCH</div>
        </a><br>
        <a href="#" class="nav__link">
          <i class="fad fa-crown"></i>
          <div class="notification_msg" id="popup">PRIME</div>
        </a><br>
        <a href="#" class="nav__link">
          <i class="fad fa-envelope"></i>
          <div class="contact_msg" id="popup">CONTACT US</div>
        </a><br>
        <a href="#" class="nav__link">
          <i class="fad fa-address-card"></i>
          <div class="about_msg" id="popup">ABOUT US</div>
        </a><br>
        <a href="#" class="nav__link">
          <i class="fad fa-plus-circle"></i>
          <div class="follow_msg" id="popup">FOLLOW US</div>
        </a><br>
        <a href="#" class="nav__link">
          <i class="fad fa-share-square"></i>
          <div class="share_msg" id="popup">SHARE</div>
        </a><br>
        <img src="https://i.ibb.co/4YmTcpC/a1627846412492.png">
      </div>
    </div>
    <div class="nav__overlay"></div>
  </nav><br><br><br><br><br><br><br>
  <!--NAV-BAR END -->
  <main>
    <img src="<?php echo "$song_image"; ?>">
    <div class="details">
      <p class="name">
        <?php echo "$song_name"; ?>
      </p>
      <p id="album">
        <?php echo "<b>Album : </b><span>$song_name .</span><br><b>Singers : </b><span>$song_singers</span><br><b>Release : </b><span>$song_release_date</span>"; ?>
      </p>
    </div>
    <p id="about">
      <?php echo "<b>About - $song_name<b>"; ?>
    </p>
    <sapn id="abou">
      <?php echo "$song_name is a Hindi Album , Released On $song_release_date. The Song Was Composed By - $song_singers .<br> Listen To All Type's Of Online In Music - Zone ."; ?>
      </span>
      <p class="copy_right">© 2024 - 2025 MUSIC - ZONE , All Rights Reserved .</p>

      <!-- <?php

            // Embed the audio player in your PHP code
            echo "<audio controls>";
            echo "
        <source src='$song_url' type='audio/mpeg'>";
            echo "Your browser does not support the audio element.";
            echo "
      </audio>";

            ?> -->


      <div class="music_pannel">
        <div style="width: 50px; height: 50px;"></div>
        <div class="audio-player" style="margin: 0 auto">
          <div class="timeline">
            <div class="progress"></div>
          </div>
          <div class="controls">
            <div class="play-container">
              <div class="toggle-play play"></div>
            </div>
            <div class="time">
              <div class="current">0:00</div>
              <div class="divider">-</div>
              <div class="length"></div>
            </div>
            <div class="volume-container">
              <div class="volume-button">
                <div class="volume icono-volumeMedium" id="btn"></div>
              </div>
              <div class="volume-slider">
                <div class="volume-percentage"></div>
              </div>
            </div>
          </div>
  </main>
  <div class=".music_pannel">
    <script>
      var preloader = document.getElementById('loading');

      function loaderFunction() {
        preloader.style.display = "none";
      }
    </script>
    <script>
      const audioPlayer = document.querySelector(".audio-player");
      const audio = new Audio(
        "<?php echo "$song_url"; ?>"
      );
      console.dir(audio);
      audio.addEventListener(
        "loadeddata",
        () => {
          audioPlayer.querySelector(".time .length").textContent = getTimeCodeFromNum(
            audio.duration
          );
          audio.volume = .75;
        },
        false
      );
      //click on timeline to skip around
      const timeline = audioPlayer.querySelector(".timeline");
      timeline.addEventListener("click", e => {
        const timelineWidth = window.getComputedStyle(timeline).width;
        const timeToSeek = e.offsetX / parseInt(timelineWidth) * audio.duration;
        audio.currentTime = timeToSeek;
      }, false);
      //click volume slider to change volume
      const volumeSlider = audioPlayer.querySelector(".controls .volume-slider");
      volumeSlider.addEventListener('click', e => {
        const sliderWidth = window.getComputedStyle(volumeSlider).width;
        const newVolume = e.offsetX / parseInt(sliderWidth);
        audio.volume = newVolume;
        audioPlayer.querySelector(".controls .volume-percentage").style.width = newVolume * 100 + '%';
      }, false)
      //check audio percentage and update time accordingly
      setInterval(() => {
        const progressBar = audioPlayer.querySelector(".progress");
        progressBar.style.width = audio.currentTime / audio.duration * 100 + "%";
        audioPlayer.querySelector(".time .current").textContent = getTimeCodeFromNum(
          audio.currentTime
        );
      }, 500);
      //toggle between playing and pausing on button click
      const playBtn = audioPlayer.querySelector(".controls .toggle-play");
      playBtn.addEventListener(
        "click",
        () => {
          if (audio.paused) {
            playBtn.classList.remove("play");
            playBtn.classList.add("pause");
            audio.play();
          } else {
            playBtn.classList.remove("pause");
            playBtn.classList.add("play");
            audio.pause();
          }
        },
        false
      );
      audioPlayer.querySelector(".volume-button").addEventListener("click", () => {
        const volumeEl = audioPlayer.querySelector(".volume-container .volume");
        audio.muted = !audio.muted;
        if (audio.muted) {
          volumeEl.classList.remove("icono-volumeMedium");
          volumeEl.classList.add("icono-volumeMute");
        } else {
          volumeEl.classList.add("icono-volumeMedium");
          volumeEl.classList.remove("icono-volumeMute");
        }
      });
      //turn 128 seconds into 2:08
      function getTimeCodeFromNum(num) {
        let seconds = parseInt(num);
        let minutes = parseInt(seconds / 60);
        seconds -= minutes * 60;
        const hours = parseInt(minutes / 60);
        minutes -= hours * 60;

        if (hours === 0) return `${minutes}:${String(seconds % 60).padStart(2, 0)}`;
        return `${String(hours).padStart(2, 0)}:${minutes}:${String(
          seconds % 60
        ).padStart(2, 0)}`;
      }
    </script>
  </div>
  <div class="download_btn">
    <button onclick="Showbtn()"><i class="fad fa-arrow-down"></i></button>
  </div><br>
  <div id="child">
    <center>
      <p>Select Music Quality</p><br>
      <li>
        <form method="POST" action="">
          <input type="hidden" name="quality" value="128">
          <button type="submit" name="download" style="position: relative;left: -10px;">128p [
            <?php echo "$song_size_128"; ?> ]
          </button>
        </form>
      </li>
      <li>
        <form method="POST" action="">
          <input type="hidden" name="quality" value="192">
          <button type="submit" name="download">192p [
            <?php echo "$song_size_192"; ?>]
          </button>
        </form>
      </li>
      <li>
        <form method="POST" action="">
          <input type="hidden" name="quality" value="320">
          <button type="submit" name="download" style="position: relative;left: 10px;">320p [
            <?php echo "$song_size_320"; ?> ]
          </button>
        </form>
      </li>
    </center><br><br>
  </div>
  <?php echo "$song_quality"; ?>
  <script>
    document.getElementById('child').style.display = "none";

    function Showbtn() {
      display = document.getElementById('child').style.display;
      if (display == "none") {
        document.getElementById('child').style.display = "block";
      } else {
        document.getElementById('child').style.display = "none";
      }
    }
  </script>
</body>

</html>