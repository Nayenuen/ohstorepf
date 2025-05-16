<script>
$(document).ready(function() {
    $('#subscribe-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        $.ajax({
            type: 'POST',
            url: 'suscripcion.php',
            data: $(this).serialize(), // Serialize form data
            success: function(response) {
                const result = JSON.parse(response);
                $('#response-message').text(result.message || result.error);
            },
            error: function() {
                $('#response-message').text('An error occurred. Please try again.');
            }
        });
    });
});
</script>