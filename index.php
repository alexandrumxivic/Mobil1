<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "https://www.w3.org/TR/html4/strict.dtd">

<?php
if (isset($_REQUEST['signed_request'])) {
    list($signature, $data) = explode('.', $_REQUEST['signed_request'], 2);

    $signedRequest = base64_decode(strtr($data, '-_', '+/'));
    $signedRequestJSON = json_decode(base64_decode(strtr($data, '-_', '+/'), true));
    $isFromFacebook = true;
} else {
    $isFromFacebook = false;
}


require_once 'mob_detect/Mobile_Detect.php';
$detect = new Mobile_Detect;

$meta_view = 0;
$tabletFb = 0;

// is on mobile device except tablet
if ($detect->isMobile() && !$detect->isTablet()) {
    header('Location: https://mobile1.projects-directory.com/mobile/');
    die();
}
//is on tablet native fb app
elseif ($_SERVER['HTTP_REFERER'] == 'https://m.facebook.com' && $detect->isTablet()) {
    $tabletFb = 1;
    $meta_view = '<meta name="viewport" content="width=device-width,user-scalable=no">';
}
//is on desktop
elseif (!$isFromFacebook) {
    header('Location: https://www.facebook.com/pages/Fanscape-Development/353585258094472?sk=app_597677730337203');
    die();
}
// is on tablet browser
if ($isFromFacebook && $detect->isTablet()) {
    $tabletFb = 1;
}
?>

<?php include "Facebook.php"; ?> 
<?php
/* defines */

define('BASE_URL', 'https://mobile1.projects-directory.com/cms/web/');
/* get required info */
/* get Stories */
$stories = file_get_contents(BASE_URL . 'stories/list');

$stories = json_decode($stories);
$stories = $stories->response;
/* check if submitted story by id */

$check = file_get_contents(BASE_URL . 'stories/check/' . $signedRequestJSON->user_id);
$check = json_decode($check);

/* get image gallery */
$image_gallery = file_get_contents(BASE_URL . 'images/list');
$image_gallery = json_decode($image_gallery);
$image_gallery = ($image_gallery->success === 1) ? $image_gallery->response : NULL;
/* get video gallery */
$video_gallery = file_get_contents(BASE_URL . 'videos/list');
$video_gallery = json_decode($video_gallery);
$video_gallery = ($video_gallery->success === 1) ? $video_gallery->response : NULL;
?>

<html xmlns="https://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!-- Force latest IE rendering engine (even in intranet) & Chrome Frame -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

        <title>Mobile 1</title>

        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <link rel="stylesheet" type="text/css" href="css/normalize.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/shame.css">
        <link rel="stylesheet" type="text/css" href="css/fonts.css">
        <link rel="stylesheet" type="text/css" href="css/icons.css">
        <script type="text/javascript" src="//code.jquery.com/jquery-1.10.0.min.js"></script>  


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/gsap/latest/easing/EasePack.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/gsap/latest/utils/Draggable.min.js"></script>

        <script type="text/javascript"  src="js/modernizr.custom.js"></script>
        <script type="text/javascript"  src="js/facebook.js"></script>

        <script src="js/Validate.js"></script>



        <script type="text/javascript" src="js/masonry.js"></script>
        <script type="text/javascript" src="js/main_second.js"></script>
        <!-- MasterSlider files-->
        <link rel="stylesheet" href="masterslider/style/masterslider.css"/>
        <link rel="stylesheet" href="masterslider/style/masterslider_aux.css"/>
        <link rel="stylesheet" href="masterslider/skins/default/style.css" />

        <script src="masterslider/masterslider.js"></script>
        <!-- End MasterSlider files-->
        <script type="text/javascript" src="js/main.js"></script>
        <script>
            $(document).ready(function () {
                $("form").submit(function (event) {
                    //disable the default form submission
                    event.preventDefault();
                    //grab all form data  
                    var formData = new FormData($(this)[0]);
                    $.ajax({
                        url: '<?php echo BASE_URL ?>stories/new/create',
                        type: 'POST',
                        data: formData,
                        async: false,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (returndata) {
                            if (returndata.success === true) {
                                $("#submited-story-author-pic").append("<img src='https://graph.facebook.com/" + returndata.facebook_id + "/picture?type=normal' width='80' height='80'>");
                                $("#submited-story-pic").append("<img src='" + returndata.image + "' width='355' height='355'>");
                                $("#submited-story-author").append(returndata.name);
                                $("#submited-story").append(returndata.story);
                            }
                            $(".submit-story-wrap").hide();
                            $(".thank-msg-wrap").show();
                            $(".go-to-form").addClass("js-show-thank").removeClass("js-show-submit");
                            $('.js-show-submit').unbind('click');
                            $('.js-show-thank').bind('click', function () {
                                $(this).parents('.top-module').hide(300);
                                $('.submit-story-wrap').hide();
                                $('.thank-msg-wrap').show(300);
                            });

                        }
                    });

                    return false;
                });
            });
        </script>
    </head>
    <body>
    <main class="restricted">
        <section class="top-area">
            <h2 class="top-area--title">
                <img src="assets/logo.png"> Performance Story
            </h2>

            <div class="landing-intro top-module">
                <div class="landing-stories-wrap sg">
                    <div class="arrow-btn sg-prev">
                        <span class=" icon-arrow-left"></span>
                    </div>

                    <ul class="landing-stories sg-content">
                        <?php $count = 1; ?>
                        <?php foreach ($stories as $key => $value) : ?>
                            <li class="story-box landing-story sg-item active" data-index="<?php
                            echo $count;
                            $count++;
                            ?>">
                                <div class="pic">
                                    <img src="<?php echo $stories[$key]->image; ?>" width="344" height="377">
                                </div>

                                <div class="author">
                                    <div class="author--pic">
                                        <img src="https://graph.facebook.com/<?php echo $stories[$key]->facebook_id; ?>/picture?type=normal" width="64" height="64">
                                    </div>

                                    <span class="author--name"><?php echo $stories[$key]->first_name . ' ' . $stories[$key]->last_name; ?></span>
                                </div>

                                <div class="content">
                                    <span class="content--title">My Story:</span>

                                    <p class="content--text">
                                        <?php echo $stories[$key]->story; ?>
                                    </p>
                                </div>
                            </li>
                        <? endforeach; ?> 
                    </ul>

                    <div class="arrow-btn sg-next">
                        <span class="icon-arrow-right"></span>
                    </div>
                </div>

                <div class="landing-msg">
                    <h3>Our normal is anything but</h3>

                    <span class="emph">What's yours?</span>

                    <p>
                        Share your Mobil 1 performance story to join the Our Normal sweepstakes.<br/>You'll get a custom story card and have a chance to win a case of Mobil 1&#8482; synthetic motor oil or some other great Mobil 1 gear.
                    </p>

                    <div class="rect-btn go-to-form blue <?php
                    if ($check->success === TRUE): echo 'js-show-submit';
                    else: echo 'js-show-thank';
                    endif;
                    ?>">Share your story</div>

                    <div class="rect-btn js-show-stories">View Stories</div>
                </div>
            </div>

            <div id="contest-form" class="submit-story-wrap top-module">
                <h3>Our normal is anything but</h3>

                <span class="emph">So you can keep your engine running like new.</span>

                <p>
                    Tell us your favorite story made possible by Mobil 1 to join the Our Normal sweepstakes. You'll get a custom Story Card to share and be entered for a chance at great prizes!
                </p>

                <form class="submit-story" method="post" enctype="multipart/form-data" action="<?php echo BASE_URL ?>stories/new/create">
                    <div class="row first">
                        <input type="text" placeholder="First Name" name="first_name">

                        <input type="text" placeholder="Last Name" name="last_name">
                    </div>

                    <div class="row second">
                        <input type="email" placeholder="Email" name="email">

                        <input type="tel" placeholder="Phone Number" name="phone">
                    </div>

                    <div class="row third">
                        <textarea placeholder="Your story" name="story"></textarea>

                        <div class="story-pic">
                            <div class="rect-btn blue">
                                Upload image<br/>
                                <span class="lowercase">(optional)</span>
                            </div>
                            <input type="file" name="image" accept="image/*">
                        </div>
                    </div>

                    <div class="row fourth">
                        <input type="text" placeholder="Year (optional)" name="year">

                        <input type="number" placeholder="Mileage (optional)" name="mileage">
                        <input type="hidden" name="facebook_id" value="<?php echo $signedRequestJSON->user_id; ?>">
                    </div>

                    <div class="row">
                        <div class="custom-check">
                            <input type="checkbox" id="agree-rules" class="custom-check--input">

                            <label for="agree-rules">
                                I agree to the official rules
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="custom-check">
                            <input type="checkbox" id="agree-age" class="custom-check--input">

                            <label for="agree-age">
                                I am 18 years of age or older
                            </label>
                        </div>
                    </div>

                    <div class="rect-btn js-top-default">Go Back</div>

                    <input type="submit" class="rect-btn blue" placeholder="Submit">
                </form>
            </div>

            <div class="view-stories-wrap top-module">
                <div class="landing-msg">
                    <h3>Our normal is anything but</h3>

                    <span class="emph">So you can keep your engine running like new.</span>
                </div>

                <div id="slider-containter">
                    <!-- masterslider -->
                    <div class="master-slider ms-skin-default" id="masterslider">
                        <?php foreach ($stories as $key => $value) : ?>
                            <!-- new slide -->
                            <div class="ms-slide">
                                <!-- slide background -->
                                <img src="<?php echo $stories[$key]->image; ?>" data-src="<?php echo $stories[$key]->image; ?>" alt="lorem ipsum dolor sit" width="355" height="355"/>

                                <!-- slide profile picture -->
                                <div class="slide-picture">
                                    <img src="https://graph.facebook.com/<?php echo $stories[$key]->facebook_id; ?>/picture?type=normal" data-src="https://graph.facebook.com/<?php echo $stories[$key]->facebook_id; ?>/picture?type=normal" alt="lorem ipsum dolor sit" width="64" height="64"/>
                                </div>

                                <!-- slide text layer -->
                                <div class="slide-text-container">
                                    <div class="slide-text">
                                        <h1>Here is our title</h1>
                                        <p class="description">  <?php echo $stories[$key]->story; ?></p>
                                    </div>
                                </div>

                                <!-- slide text signature -->
                                <div class="slide-text-signature">- <?php echo $stories[$key]->first_name . ' ' . $stories[$key]->last_name; ?></div>

                                <!-- like button -->
                                <div class="slide-like-button">
                                    <a class="like-button" href="#" title="Like">
                                        <img src="images/btn-like.jpg" alt="Like" />
                                    </a>
                                </div>

                                <!-- slide color overlay picture -->
                                <div class="slide-hover"><!-- --></div>
                            </div>
                            <!-- end of slide -->  
                        <? endforeach; ?> 
                    </div>
                    <!-- end of masterslider -->
                </div>

                <div  class="rect-btn go-to-form <?php
                if ($check->success === TRUE): echo 'js-show-submit';
                else: echo 'js-show-thank';
                endif;
                ?>">Submit your story</div>
            </div>

            <div class="thank-msg-wrap top-module">
                <div class="thank-msg">
                    <h3 class="large">
                        <span class="emph">Thanks</span> for telling us your story
                    </h3>

                    <div class="uppercase">
                        Now <b>share it</b> with the rest of your friends!
                    </div>

                    <div class="rect-btn red">Share</div>

                    <div class="rect-btn js-show-stories">View other stories</div>
                </div>

                <div class="story-preview story-box">

                    <div class="pic" id="submited-story-pic">
                        <!-- submitted story pic-->
                        <?php echo ($check->success === false) ? "<img src='" . $check->image . "' width='355' height='355'/>" : ''; ?>
                    </div>

                    <div class="author">
                        <div class="author--pic" id="submited-story-author-pic">
                            <!-- submitted story  author--> 
                            <?php echo ($check->success === false) ? "<img src='https://graph.facebook.com/" . $check->facebook_id . "/picture?type=normal' width='80' height='80' />" : ''; ?>


                        </div>
                    </div>

                    <span class="author--name" id="submited-story-author">- <!-- submitted story  author--><?php echo ($check->success === false) ? $check->name : ''; ?></span>

                    <div class="content">
                        <span class="content--title">My Performance Story:</span>

                        <p class="content--text" id="submited-story">
                            <?php echo ($check->success === false) ? $check->story : ''; ?>
                            <!-- submitted story -->
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <section>
            <div class="media-section images">
                <div class="media-section--cover">
                    <div class="container">
                        <h3>Performance Pictures</h3>

                        <div class="deco-line">
                            <span class="icon-photos"></span>
                        </div>

                        <p>
                            Check out Mobil 1 in action with our Normal photos and more.
                        </p>

                        <div class="rect-btn js-expand-section">Explore</div>
                    </div>
                </div>

                <div class="media-section--content">
                    <div class="close-btn js-restore-default">
                        <span class="icon-close"></span>
                    </div>

                    <div class="image-categories">
                        <div class="toggle">
                            <span class="icon-categs"></span>
                            <span class="label">Filter Images</span>
                        </div>

                        <ul class="image-categories-list">
                            <li>
                                <input id="categ1" type="radio" name="image-category" value="cat1">

                                <label for="categ1">Category 1</label>
                            </li>

                            <li>
                                <input id="categ2" type="radio" name="image-category" value="cat1">

                                <label for="categ2">Category 2</label>
                            </li>

                            <li>
                                <input id="categ3" type="radio" name="image-category" value="cat1">

                                <label for="categ3">Category 3</label>
                            </li>
                        </ul>
                    </div>
                    <div class="scroll-visible-area">
                        <div class="scrollable">
                            <ul class="image-performance-wrap linked-container">
                                <?php foreach ($image_gallery as $images_gal): ?>
                                    <li class="image-performance" data-index="1">
                                        <img src="<?php echo $images_gal->image_url; ?>">

                                        <div class="hover-content">
                                            <?php echo $images_gal->description; ?>
                                            <div class="center">view</div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>


                            </ul>
                        </div>

                        <div class="overlayed-image-gallery linked-container overlay sg">
                            <div class="arrow-btn small sg-prev">
                                <span class="icon-arrow-left"></span>
                            </div>

                            <ul class="overlayed-images sg-content linked-control">
                                <?php $c = 1; ?>
                                <?php foreach ($image_gallery as $images_gal): ?>
                                    <li class="sg-item" data-index="<?php echo $c; ?>">
                                        <?php $c++; ?>
                                        <div class="pic">
                                            <img src="<?php echo $images_gal->image_url; ?>">
                                        </div>

                                        <div class="hover-content">
                                            <?php echo $images_gal->description; ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>                                
                            </ul>

                            <div class="arrow-btn small sg-next">
                                <span class="icon-arrow-right"></span>
                            </div>

                            <div>
                                <div class="rect-btn js-close-overlay">Back</div>

                                <div class="rect-btn js-switch-section">Video gallery</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="media-section videos">
                <div class="media-section--cover">
                    <div class="container">
                        <h3>Performance Videos</h3>

                        <div class="deco-line">
                            <span class="icon-videos"></span>
                        </div>

                        <p>
                            Watch now for more on how Mobil 1 keeps your engine running like new.
                        </p>

                        <div class="rect-btn js-expand-section">Explore</div>
                    </div>
                </div>

                <div class="media-section--content">
                    <div class="close-btn js-restore-default">
                        <span class="icon-close"></span>
                    </div>

                    <div class="scroller vertical video-scroller linked-container">
                        <div class="arrow-btn js-scroller-prev disabled">
                            <span class="icon-arrow-up"></span>
                        </div>

                        <ul class="scroller--content linked-control">
                            <?php $i = 1; ?>
                            <?php foreach ($video_gallery as $videos): ?>
                                <li class="scroller--item active" data-index="<?php
                                echo $i;
                                $i++;
                                ?>">
                                    <img src="https://img.youtube.com/vi/<?php echo $videos->video_id; ?>/default.jpg">
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="arrow-btn js-scroller-next">
                            <span class="icon-arrow-down"></span>
                        </div>
                    </div>

                    <div class="video-gallery-container linked-container">
                        <h3>Performance Videos</h3>

                        <p>
                            Watch now for more on how Mobil 1 keeps your engine running like new.
                        </p>

                        <div class="video-performances-wrap sg">
                            <div class="arrow-btn small sg-prev">
                                <span class="icon-arrow-left"></span>
                            </div>

                            <ul class="video-stories sg-content linked-control">
                                <?php $i = 1; ?>
                                <?php foreach ($video_gallery as $videos): ?>
                                    <li class="story-box video-story sg-item active" data-index="<?php
                                    echo $i;
                                    $i++;
                                    ?>">
                                        <div class="pic">
                                            <iframe width="482" height="288" src="//www.youtube.com/embed/<?php echo $videos->video_id; ?>?rel=0&autoplay=0" frameborder="0" allowfullscreen ></iframe>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <div class="arrow-btn small sg-next">
                                <span class="icon-arrow-right"></span>
                            </div>
                        </div>

                        <div class="rect-btn js-restore-default">Back</div>

                        <div class="rect-btn js-switch-section">Image gallery</div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <a href="#">Terms</a>

        <a href="#">Conditions</a>
    </footer>
</body>
<script type="text/javascript">
    var slider = new MasterSlider();
    slider.setup('masterslider', {
        hideLayers: false,
        width: 335, // slider standard width
        height: 335, // slider standard height
        space: 142,
        fullwidth: false,
        autoHeight: false,
        layout: "partialview",
        view: "wave",
        loop: true,
        speed: 25
                // more slider options goes here...
    });
    // adds Arrows navigation control to the slider.
    slider.control('arrows', {autohide: false});

    // add event handlers
    $(document).ready(function () {
        slider.api.addEventListener(MSSliderEvent.CHANGE_START, function () {
            isChangeInProgress = true;
            onSliderMovementStart("change start")
        });
        slider.api.view.addEventListener(MSViewEvents.SWIPE_START, function () {
            isSwipeInProgress = true;
            onSliderMovementStart("swipe start")
        });

        onSliderMovementStart("init start");
    });

    function onSliderMovementStart(event) {
        var currentSlideLoc = slider.api.view.currentSlideLoc;
        var currentSlide = slider.api.view.slides[currentSlideLoc].$element;
        var nextSlide = currentSlideLoc == slider.api.view.slidesCount - 1 ? slider.api.view.slides[0].$element : slider.api.view.slides[currentSlideLoc + 1].$element;
        var prevSlide = currentSlideLoc == 0 ? slider.api.view.slides[slider.api.view.slidesCount - 1].$element : slider.api.view.slides[currentSlideLoc - 1].$element;
        $(".prev-slide").removeClass("prev-slide");
        $(".next-slide").removeClass("next-slide");
        $(".curr-slide").removeClass("curr-slide");

        prevSlide.toggleClass("prev-slide");
        nextSlide.toggleClass("next-slide");
        currentSlide.toggleClass("curr-slide");

        return;
    }
</script>
</html>