<?php
    $title = "Photos | D'Leverage Photography";
    require_once 'includes/header.php';
?>
<div style="margin-top: 200px;"></div>
<main>
    <div class="container">
        <div class="heading">
            <h3>Photos</h3>
        </div>
        <div class="container album">
            <a class="card" href="photos-category.php?category=wedding">
                <div>
                    <div class="card-bg">
                        <img src="image/static-img/wedding.jpg" alt="">
                    </div>
                    <div class="cat">
                        <h2>WEDDING / PRENUPTIAL</h2>
                    </div>
                    <hr>
                </div>
            </a>
            <a class="card" href="photos-category.php?category=birthday">
                <div>
                    <div class="card-bg">
                        <img src="image/static-img/birthday.JPG" alt="">
                    </div>
                    <div class="cat">
                        <h2>BIRTHDAY</h2>
                    </div>
                    <hr>
                </div>
            </a>
            <a class="card" href="photos-category.php?category=others">
                <div>
                    <div class="card-bg">
                        <img src="image/static-img/others.jpg" alt="">
                    </div>
                    <div class="cat">
                        <h2>OTHERS</h2>
                    </div>
                    <hr>
                </div>
            </a>
        </div>
    </div>
</main>
<?php
    require_once 'includes/footer.php';
?>