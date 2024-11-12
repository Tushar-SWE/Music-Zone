<?php
session_start();
error_reporting(0);


$query = isset($_GET['q']) ? $_GET['q'] : ''; // Use $_GET instead of $_POST

if (empty($query)) {
    echo "Please enter a song name.";
    exit;
}

// Function to fetch and decode JSON from a URL
function fetchData($url)
{
    $response = @file_get_contents($url); // Use @ to suppress errors for missing or invalid URLs
    return $response ? json_decode($response, true) : null;
}

// Fetch New Release Song List
$search_song_result = fetchData('https://www.jiosaavn.com/api.php?p=1&q=' . urlencode($query) . '&_format=json&_marker=0&api_version=4&ctx=web6dot0&n=20&__call=search.getResults');

// Check for valid data
if ($search_song_result && isset($search_song_result['results'])) {
    $new_releases = [];
    foreach ($search_song_result['results'] as $album) {
        $new_releases[] = [
            'id' => $album['id'] ?? 'N/A',
            'title' => $album['title'] ?? 'N/A',
            'image' => $album['image'] ?? 'N/A'
        ];
    }
} else {
    echo "Error fetching New Release data.";
    exit;
}


// Fetch playlists
$romantic_top = fetchData('https://www.saavn.com/api.php?_marker=0&listid=142311984&state=logout&_format=json&p=1&__call=playlist.getDetails');
$romantic_top_thumb = $romantic_top['image'] ?? '';

//Top HINDI 2000 SONG's
$hindi_2000 = fetchData('https://www.saavn.com/api.php?_marker=0&listid=48147819&state=logout&_format=json&p=2&__call=playlist.getDetails');
$hindi_2000_thumb = $hindi_2000['image'] ?? '';

//Top HINDI 1990 SONG's
$hindi_1990 = fetchData('https://www.saavn.com/api.php?_marker=0&listid=48147816&state=logout&_format=json&p=2&__call=playlist.getDetails');
$hindi_1990_thumb = $hindi_1990['image'] ?? '';

//Top HINDI 1980 SONG's
$hindi_1980 = fetchData('https://www.saavn.com/api.php?_marker=0&listid=48147813&state=logout&_format=json&p=1&__call=playlist.getDetails');
$hindi_1980_thumb = $hindi_1980['image'] ?? '';

?>

<!DOCTYPE html>
<html>

<head>
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
    <link rel="stylesheet" href="style.css">
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
            script.src = "//telegram.im/widget-button/index.php?id=@itztktricks";
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
            <!-- <a href="#"><button class="register_btn">SIGN - UP</button></a>
         <a href="#"><button class="login_btn">SIGN - IN</button></a> -->
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
    </nav><br><br><br><br><br><br>
    <!--NAV-BAR END -->

    <main>
        <section class="new-releases">
            <div class="heading">
                <p style="width:212px">Search Result <i class="fad fa-angle-double-right"></i></p>
            </div>
            <ul>
                <?php foreach ($new_releases as $album): ?>
                    <li>
                        <div class="song">
                            <a href='song?id=<?php echo $album["id"]; ?>'>
                                <img src='<?php echo $album["image"]; ?>' alt="<?php echo $album["title"]; ?>">
                            </a>
                            <span>
                                <center><?php echo $album["title"]; ?>
                            </span>
                            </center>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>


    </main>

    <div class="view_all"><a href="">
            <p>View All Song's <i class="fad fa-long-arrow-right"></i></p>
        </a></div><br>

    <div class="heading">
        <p style="width:215px"> All Type's Of Music Charts<i class="fad fa-angle-double-right"></i></p>
    </div>
    <li>
        <div class="album">
            <a href="Top-Romantic-Song's">
                <img src='<?php echo "$romantic_top_thumb"; ?>'>
            </a>
            <span>
                <center>Top Romantic Song's Collection</center>
            </span>
        </div>
    </li>
    <li>
        <div class="album">
            <a href="2000s-Song's-Collection">
                <img src='<?php echo "$hindi_2000_thumb"; ?>'>
            </a>
            <span>
                <center>Hindi 2000s Song's Collection</center>
            </span>
        </div>
    </li>
    <li>
        <div class="album">
            <a href="1990s-Song's-Collection">
                <img src='<?php echo "$hindi_1990_thumb"; ?>'>
            </a>
            <span>
                <center>Hindi 1990s Song's Collection</center>
            </span>
        </div>
    </li>
    <li>
        <div class="album">
            <a href="1980s-Song's-Collection">
                <img src='<?php echo "$hindi_1980_thumb"; ?>'>
            </a>
            <span>
                <center>Hindi 1980s Song's Collection</center>
            </span>
        </div>
    </li>
    </main><br>
    <div class="description">
        <div class="a1st">
            <p>Enjoy Ad Free Music Stream With <br><b>Music - zone .</b></p>
        </div>
        <div class="a2nd">
            <p>Listen Crystal clear audio In Free Of Cost <br><b>Music - zone .</b></p>
        </div>
        <div class="a3rd">
            <p>Download High Quality Music With <br><b>Music - zone Prime .</b></p>
        </div>
        <div class="dev">
            <p><i class="fad fa-jedi"></i> <b>Developed By - Tushar Kumar</b> <i class="fad fa-jedi"></i></p>
        </div>
    </div>
    <footer class="footer">
        <a href=""><i class="fas fa-home"></i></a>
        <a href=""><i class="fas fa-crown"></i></a>
        <a href=""><i class="fas fa-user-circle"></i></a>
    </footer>
    <script>
        var preloader = document.getElementById('loading');

        function loaderFunction() {
            preloader.style.display = "none";
        }
    </script>
</body>

</html>