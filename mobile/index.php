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
?>

<?php include "Facebook.php"; ?> 
<?php
/* defines */
$user_id = (isset($signedRequestJSON->user_id)) ? $signedRequestJSON->user_id : '0';
define('BASE_URL', 'https://mobile1.projects-directory.com/cms/web/');
/* get required info */
/* get Stories */
$stories = file_get_contents(BASE_URL . 'stories/list');

$stories = json_decode($stories);
$stories = $stories->response;

/* get categories */
$categories = file_get_contents(BASE_URL . 'categories/list');
$categories = json_decode($categories);
$categories = $categories->categories;
/* check if submitted story by id */

$check = file_get_contents(BASE_URL . 'stories/check/' . $user_id);
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
        <meta charset="utf-8" />
        <meta name="viewport" content="initial-scale=1, user-scalable=no">
        <meta name="viewport" content="initial-scale=1, user-scalable=no">
        <title>Mobile 1</title>
        <link rel="stylesheet" type="text/css" href="css/normalize.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/shame.css">
        <link rel="stylesheet" type="text/css" href="css/fonts.css">
        <link rel="stylesheet" type="text/css" href="css/icons.css">
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        
        <script type="text/javascript" src="js/masonry.js"></script>
        <script type="text/javascript" src="js/imagesloaded.js"></script>
        <script type="text/javascript" src="js/main_second.js"></script>
        <script type="text/javascript" src="js/jquery.validate.min.js"></script>
        <link rel="stylesheet" href="masterslider/style/masterslider.css"/>
        <link rel="stylesheet" href="masterslider/style/masterslider_aux.css"/>
        <link rel="stylesheet" href="masterslider/skins/default/style.css" />
        <script src="masterslider/masterslider.js"></script>
        <script type="text/javascript"  src="js/modernizr.custom.js"></script>
        <script type="text/javascript"  src="js/facebook.js"></script>
        <!-- End MasterSlider files-->
        <script type="text/javascript" src="js/main.js"></script>
        <script>
            var picture_p;
            var userId_p;
            var story_p;
        </script>
        <?php if ($check->success == false): ?>
            <script>
                var picture_p = '<?php echo $check->image_unsecured; ?>';
                var userId_p = '<?php echo $check->facebook_id; ?>';
                var story_p = '<?php echo $check->story; ?>';
            </script>
        <?php endif; ?>
        <script>
            $(document).ready(function () {
                $("form").validate({
                    rules: {
                        first_name: "required",
                        last_name: "required",
                        email: "required",
                        phone: "required",
                        story: "required",
                        agree_age: "required",
                        agree_rules: "required"
                    },
                    messages: {
                        story: " Tell us your story in a few lines",
                        agree_age: " ",
                        agree_rules: " "
                    },
                    submitHandler: function () {
                        var formData = new FormData($("form")[0]);
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
                                    picture_p = returndata.image_unsecured;
                                    userId_p = returndata.facebook_id;
                                    story_p = returndata.story;
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

                    }
                });
            });
        </script>
        <script>
            function share_caption(picture, userId, story) {
                FB.ui({
                    method: 'feed',
                    title: "Mobile 1 Performance Story",
                    link: 'https://mobile1.projects-directory.com',
                    name: "My Submitted Story",
                    picture: 'http://mobile1.projects-directory.com/cms/web/uploads/' + picture,
                    to: userId,
                    caption: '',
                    description: story,
                    message: 'This is my story...'
                });
            }
        </script>
    </head>
    <body>
    <main class="restricted">
        <section class="top-area">
            <h2 class="top-area--title">
                <img src="assets/logo.png"> Performance Story
            </h2>

            <div class="landing-intro top-module" >
                <div class="landing-msg">
                    <h3>Our normal is anything but.</h3>

                    <span class="emph">What's yours?</span>

                    <p>
                        Share your Mobil 1<sup>TM</sup> performance story to join the Our Normal sweepstakes.<br/>You'll get a custom story card and have a chance to win a case of Mobil 1 synthetic motor oil or some other great Mobil 1 gear.
                    </p>

                    <div class="rect-btn blue js-show-submit" >Share your story</div>

                    <div class="rect-btn js-show-stories">View Stories</div>
                </div>

                <div class="landing-stories-wrap sg">
                    <div class="arrow-btn sg-prev">
                        <span class=" icon-arrow-left"></span>
                    </div>

                    <ul class="landing-stories sg-content">
                        <?php $count = 1; ?>
                        <?php foreach ($stories as $key => $value) : ?>
                            <li class="story-box landing-story sg-item <?php echo ($count == 1) ? 'active' : '' ?>" data-index="<?php
                            echo $count;
                            $count++;
                            ?>">
                                <div class="pic">
                                    <img src="<?php echo $stories[$key]->image; ?>" width='278' height='278'>
                                </div>

                                <div class="author">
                                    <div class="author--pic">
                                        <img src="https://graph.facebook.com/<?php echo $stories[$key]->facebook_id; ?>/picture?type=normal" width='60' height='60'>
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
                        <?php endforeach; ?>
                    </ul>

                    <div class="arrow-btn sg-next">
                        <span class="icon-arrow-right"></span>
                    </div>
                </div>
            </div>

            <div class="submit-story-wrap top-module <?php
            if ($check->success === TRUE): echo 'js-show-submit';
            else: echo 'js-show-thank';
            endif;
            ?>">
                <h3>Our normal is anything but.</h3>

                <span class="emph">So you can keep your engine running like new.</span>

                <p>
                    Tell us your favorite story made possible by Mobil 1 to join the Our Normal sweepstakes. You'll get a custom Story Card to share and be entered for a chance at great prizes!
                </p>

                <form class="submit-story" method="post" enctype="multipart/form-data" action="<?php echo BASE_URL ?>stories/new/create">
                    <div class="row first">
                        <div class="cell">
                            <input type="text" placeholder="First Name" name="first_name">
                        </div>
                        <div class="cell">
                            <input type="text" placeholder="Last Name" name="last_name">
                        </div>
                    </div>

                    <div class="row second">
                        <div class="cell">
                            <input type="email" placeholder="Email" name="email">
                        </div>

                        <div class="cell">
                            <input type="tel" placeholder="Phone Number" name="phone">
                        </div>
                    </div>

                    <div class="row third">
                        <div class="cell">
                            <textarea placeholder="Your story" name="story"></textarea>
                        </div>

                        <div class="story-pic">
                            <div class="rect-btn blue">
                                Upload image<br/>
                                <span class="lowercase">(optional)</span>
                            </div>
                            <input type="file" name="image" accept="image/*">
                        </div>
                    </div>

                    <div class="row fourth">
                        <div class="cell">
                            <input type="text" placeholder="Year (optional)" name="year">
                        </div>

                        <div class="cell">
                            <input type="number" placeholder="Mileage (optional)" name="mileage">
                            <input type="hidden" name="facebook_id" value="<?php echo $user_id; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="custom-check">
                            <input type="checkbox" id="agree-rules" class="custom-check--input" name="agree_rules">

                            <label for="agree-rules">
                                I agree to the official rules
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="custom-check">
                            <input type="checkbox" id="agree-age" class="custom-check--input" name="agree_age">

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

                    <p class="emph">So you can keep your engine running like new.</p>
                </div>

                <div id="slider-containter">
                    <!-- masterslider -->
                    <div class="master-slider ms-skin-default" id="masterslider">
                        <?php foreach ($stories as $key => $value) : ?>
                            <!-- new slide -->
                            <div class="ms-slide">
                                <!-- slide background -->
                                <img src="<?php echo $stories[$key]->image; ?>" data-src="<?php echo $stories[$key]->image; ?>" alt="lorem ipsum dolor sit"/>

                                <!-- slide profile picture -->
                                <div class="slide-picture">
                                    <img src="https://graph.facebook.com/<?php echo $stories[$key]->facebook_id; ?>/picture?type=normal" data-src="https://graph.facebook.com/<?php echo $stories[$key]->facebook_id; ?>/picture?type=normal" alt="lorem ipsum dolor sit"/>
                                </div>

                                <!-- slide text layer -->
                                <div class="slide-text-container">
                                    <div class="slide-text">
                                        <h1>Here is our title</h1>
                                        <p class="description"><?php echo $stories[$key]->story; ?></p>

                                    </div>
                                </div>

                                <!-- slide text signature -->
                                <div class="slide-text-signature">- <?php echo $stories[$key]->first_name . ' ' . $stories[$key]->last_name; ?></div>

                                <!-- like button -->
                                <div class="slide-like-button">
                                    <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fmobile1.projects-directory.com%2Fcms%2Fweb%2Fstories%2Flike%2F<?php echo $stories[$key]->id; ?>&amp;width&amp;layout=button&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=35&amp;appId=597677730337203" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:30px;width:60px;float:right;" allowTransparency="true"></iframe>  
                                </div>

                                <!-- slide color overlay picture -->
                                <div class="slide-hover"><!-- --></div>
                            </div>
                            <!-- end of slide -->
                        <?php endforeach; ?>
                    </div>
                    <!-- end of masterslider -->
                </div>

                <div class="rect-btn js-show-submit">Submit your story</div>
            </div>

            <div class="thank-msg-wrap top-module ">
                <div class="thank-msg">
                    <h3 class="large">
                        <span class="emph">Thanks</span> for telling us your story
                    </h3>

                    <div class="uppercase">
                        Now <b>share it</b> with the rest of your friends!
                    </div>
                    <!-- share button -->
                    <div class="rect-btn red" ><a href='#' id='share' onclick="share_caption(picture_p, userId_p, story_p)">Share</a></div>
                    <!-- Share button -->
                    <div class="rect-btn js-show-stories">View other stories</div>
                </div>

                <div class="story-preview story-box">

                    <div class="pic">
                        <?php echo ($check->success === false) ? "<img src='" . $check->image . "'/>" : ''; ?>
                    </div>

                    <div class="author">
                        <div class="author--pic">
                            <?php echo ($check->success === false) ? "<img src='https://graph.facebook.com/" . $check->facebook_id . "/picture?type=normal' />" : ''; ?>
                        </div>
                    </div>

                    <span class="author--name">- <?php echo ($check->success === false) ? $check->name : ''; ?></span>

                    <div class="content">
                        <span class="content--title">My Performance Story:</span>

                        <p class="content--text">
                            <?php echo ($check->success === false) ? $check->story : ''; ?>
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
                            <span class="icon-images"></span>
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
                            <?php foreach ($categories as $category): ?>
                                <li>
                                    <input id="<?php echo $category->id; ?>" type="radio" name="image-category" value="<?php echo $category->name; ?>" data-category="<?php echo $category->id; ?>">
                                    <label for="<?php echo $category->id; ?>"><?php echo $category->name; ?></label>
                                </li>
                            <?php endforeach; ?>

                        </ul>
                    </div>

                    <div class="scroll-visible-area">
                        <div class="scrollable">
                            <ul class="image-performance-wrap linked-container">
                                <?php
                                $i = 1;
                                foreach ($image_gallery as $images_gal):
                                    ?>
                                    <li class="image-performance" data-index="<?php
                                    echo $i;
                                    $i++;
                                    ?>" data-category="<?php echo $images_gal->category; ?>">
                                        <img src="<?php echo $images_gal->image_url; ?>">

                                        <div class="hover-content">
                                            <?php echo $images_gal->description; ?>
                                            <div class="center">view</div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <div class="image-performance-scroll js-scroll-images-next">view more</div>
                        </div>

                        <div class="overlayed-image-gallery linked-container overlay sg">
                            <div class="arrow-btn small sg-prev">
                                <span class="icon-arrow-left"></span>
                            </div>

                            <ul class="overlayed-images sg-content linked-control">
                                <?php $c = 1; ?>
                                <?php foreach ($image_gallery as $images_gal): ?>
                                    <li class="sg-item" data-index="<?php
                                    echo $c;
                                    $c++;
                                    ?>">
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
                                    <li class="story-box video-story sg-item <?php echo ($i == 1) ? 'active' : '' ?>" data-index="<?php
                                    echo $i;
                                    $i++;
                                    ?>">
                                        <div class="pic">
                                            <a href='https://www.youtube.com/watch?v=<?php echo $videos->video_id; ?>'><img src="https://img.youtube.com/vi/<?php echo $videos->video_id; ?>/default.jpg" height='182' width='278'></a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>

                            </ul>

                            <div class="arrow-btn small sg-next">
                                <span class="icon-arrow-right"></span>
                            </div>
                        </div>
                    </div>

                    <div class="scroller horizontal video-scroller linked-container">
                        <div class="arrow-btn js-scroller-prev disabled">
                            <span class="icon-arrow-up"></span>
                        </div>
                        <div class="scroller--content-mask">
                            <ul class="scroller--content linked-control">
                                <?php $i = 1; ?>
                                <?php foreach ($video_gallery as $videos): ?>
                                    <li class="scroller--item <?php echo ($i == 1) ? 'active' : '' ?>" data-index="<?php
                                    echo $i;
                                    $i++;
                                    ?>">
                                        <img src="https://img.youtube.com/vi/<?php echo $videos->video_id; ?>/default.jpg" height='60' width='80'>

                                    </li>
                                <?php endforeach; ?>

                            </ul>
                        </div>
                        <div class="arrow-btn js-scroller-next">
                            <span class="icon-arrow-down"></span>
                        </div>
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
        fullwidth: false,
        width: 250,
        height: 250,
        space: 142,
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