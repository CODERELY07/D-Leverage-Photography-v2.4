$(document).ready(function () {
    // Initially show all images and activate the 'all' filter
    $(".all").addClass("active");
    $(".image").show(400);

    // Initialize Magnific Popup for the gallery
    $(".gallery").magnificPopup({
        delegate: "a",
        type: "image",
        gallery: {
        enabled: true,
        },
    });

    // Click event handler for filter buttons
    $(".buttons").click(function () {
        // Remove 'active' class from all buttons and add it to the clicked button
        $(this).addClass("active").siblings().removeClass("active");

        // Get the data-filter value of the clicked button
        var filter = $(this).attr("data-filter");

        // Hide all images initially
        $(".image").hide();

        if (filter === "all") {
        // Show all images if 'all' filter is selected
        $(".image").show(400);
        } else {
        // Show only images matching the current filter
        $(".image." + filter).show(400);
        }
        // Update Magnific Popup with filtered images
        $(".gallery").magnificPopup({
            delegate: "a." + filter,
            type: "image",
            gallery: {
                enabled: true,
            },
        });
    });
});