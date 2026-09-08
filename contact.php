<?php
    $title = "Book | D'Leverage Photography";
    require_once 'includes/header.php';
?>
<div class="page-spacer"></div>
<main>
    <div class="contact-form">
        <p class="text-center">SCHEDULE AN APPOINTMENT</p>
        <div class="line grey-line"></div>
        <form id="contactForm" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="fullname">Your Full Name(s) *</label>
                    <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Jane &amp; John Doe">
                </div>
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="you@example.com">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="phonenumber">Phone Number</label>
                    <input type="text" name="phonenumber" id="phonenumber" class="form-control" placeholder="(514) 000-0000">
                </div>
                <div class="form-group">
                    <label for="date">Wedding/Shoot Date *</label>
                    <input type="date" name="date" id="date" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="location">Venue/Location(s) *</label>
                    <input type="text" name="location" id="location" class="form-control" placeholder="City, venue, or address">
                </div>
                <div class="form-group">
                    <label for="session">What type of Session are you looking for? *</label>
                    <select name="session" id="session" class="form-control">
                        <option value="" selected></option>
                        <option value="wedding">Wedding/Prenuptial </option>
                        <option value="birthday">Birthday </option>
                        <option value="others">Others</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="services">Services *</label>
                <select name="services" id="services" class="form-control">
                    <option value="" selected></option>
                    <option value="basic">Basic</option>
                    <option value="essential">Essential</option>
                    <option value="elite">Elite</option>
                </select>
            </div>
            <div class="form-group">
                <label for="message">Message *</label>
                <textarea name="message" id="message" cols="10" rows="5" class="form-control" placeholder="Tell us a little about your celebration..."></textarea>
            </div>
            <button type="submit" id="send" name="send" class="btn btn-dark d-block mx-auto">Send</button>
        </form>
    </div>
</main>

<?php
    require_once 'includes/footer.php';
?>