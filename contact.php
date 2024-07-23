<?php
    require_once 'includes/header.php';
?>
<div style="margin-top: 200px;"></div>
<main>
    <div class="contact-form">
        <p class="text-center">SCHEDULE AN APPOINTMENT</p>
        <div class="line grey-line"></div>
        <form>
            <div class="form-group">
                <label for="fullname">Your Full Name(s) *</label>
                <input type="text" id="fullname" class="form-control">
            </div>
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" class="form-control">
            </div>
            <div class="form-group">
                <label for="phonenumber">Phone Number</label>
                <input type="text" id="phonenumber" class="form-control">
            </div>
            <div class="form-group">
                <label for="date">Wedding/Shoot Date *</label>
                <input type="date" id="date" class="form-control">
            </div>
            <div class="form-group">
                <label for="location">Venue/Location(s) *</label>
                <input type="text" id="location" class="form-control">
            </div>
            <div class="form-group">
                <label for="message">Message *</label>
                <textarea name="message" id="message" cols="10" rows="5" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-dark d-block mx-auto">Send</button>
        </form>
    </div>
</main>
<?php
require_once 'includes/footer.php';
?>