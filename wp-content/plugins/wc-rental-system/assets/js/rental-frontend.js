jQuery(document).ready(function($) {
    if ($('.rental-datepicker').length) {
        $(".rental-datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: 0,
            onSelect: function() {
                calculateRentalPrice();
            }
        });
    }

    function calculateRentalPrice() {
        var start = $('#rental_start_date').val();
        var end = $('#rental_end_date').val();
        
        if (start && end) {
            var startDate = new Date(start);
            var endDate = new Date(end);
            
            if (endDate < startDate) {
                alert('Bitiş tarihi başlangıçtan önce olamaz!');
                $('#rental_end_date').val('');
                return;
            }

            var timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
            
            var dailyPrice = parseFloat(rentalVars.dailyPrice);
            var deposit = parseFloat(rentalVars.deposit || 0);
            var total = (dailyPrice * diffDays) + deposit;

            $('#summary-daily').text(dailyPrice.toFixed(2));
            $('#summary-days').text(diffDays);
            $('#summary-deposit').text(deposit.toFixed(2));
            $('#summary-total').text(total.toFixed(2));
            $('#rental-price-summary').show();
        }
    }
});
