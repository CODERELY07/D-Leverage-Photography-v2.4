$(document).ready(function () {
    $(".all").addClass("active");
    $(".image").show(400);

    $(".gallery").magnificPopup({
        delegate: "a",
        type: "image",
        gallery: {
        enabled: true,
        },
    });

    $(".buttons").click(function () {
        $(this).addClass("active").siblings().removeClass("active");

        var filter = $(this).attr("data-filter");

        $(".image").hide();

        if (filter === "all") {
        $(".image").show(400);
        } else {
        $(".image." + filter).show(400);
        }
        $(".gallery").magnificPopup({
            delegate: "a." + filter,
            type: "image",
            gallery: {
                enabled: true,
            },
        });
    });
});