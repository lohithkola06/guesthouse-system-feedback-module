<?php
/*
  feedback_button_placeholder.php
  PLACEHOLDER snippet for rendering the "Give Feedback" button in the booking list row.

  Replace placeholder variables ($application_id, $booking_status, $departure_date)
  with actual DB fields when integrating into the portal.
*/

// -------- PLACEHOLDER DATA (replace with DB values) --------
$application_id = 12345;            // booking/application primary key
$booking_status = 'COMPLETED';      // e.g. APPROVED, CHECKED_IN, COMPLETED
$departure_date = '2025-01-10';     // optional: departure date string YYYY-MM-DD
// ----------------------------------------------------------

// Option A: Status-based eligibility (preferred)
$completed_statuses = ['COMPLETED', 'CHECKED_OUT', 'CLOSED', 'DONE'];

// Option B: Date-based eligibility (fallback)
$is_departed = false;
if (!empty($departure_date)) {
  $is_departed = strtotime($departure_date) < strtotime(date('Y-m-d'));
}

// Final eligibility decision: allow only after stay completion
$can_give_feedback =
  in_array(strtoupper($booking_status), $completed_statuses) || $is_departed;
?>

<!-- Put this <td> block inside your booking list table row -->
<td>
  <?php if ($can_give_feedback): ?>
    <a href="feedback_form.php?app_id=<?= urlencode($application_id) ?>"
       style="display:inline-block;padding:6px 10px;border:1px solid #2b6cb0;background:#2b6cb0;color:#fff;border-radius:6px;text-decoration:none;font-size:13px;">
      Give Feedback
    </a>
  <?php else: ?>
    <span style="color:#999;font-size:13px;">Feedback not available</span>
  <?php endif; ?>
</td>