<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="rental-booking-form">
    <div class="rental-field">
        <label for="rental_start_date"><?php _e( 'Başlangıç Tarihi', 'wc-rental-system' ); ?></label>
        <input type="text" id="rental_start_date" name="rental_start_date" class="rental-datepicker" readonly required>
    </div>
    <div class="rental-field">
        <label for="rental_end_date"><?php _e( 'Bitiş Tarihi', 'wc-rental-system' ); ?></label>
        <input type="text" id="rental_end_date" name="rental_end_date" class="rental-datepicker" readonly required>
    </div>
    <div id="rental-price-summary" class="rental-summary" style="display:none;">
        <p><?php _e( 'Günlük:', 'wc-rental-system' ); ?> <span id="summary-daily"></span></p>
        <p><?php _e( 'Gün Sayısı:', 'wc-rental-system' ); ?> <span id="summary-days"></span></p>
        <p><?php _e( 'Depozito:', 'wc-rental-system' ); ?> <span id="summary-deposit"></span></p>
        <hr>
        <p><strong><?php _e( 'Toplam:', 'wc-rental-system' ); ?> <span id="summary-total"></span></strong></p>
    </div>
</div>
