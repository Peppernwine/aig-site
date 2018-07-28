
function getOccasions(done) {
    $.ajax({
        type: "GET",
        async:false,
        url: 'rest-api/reserveoccasions.php',
        success: done
    });
}