<?php
// feedback_form.php (PLACEHOLDER VERSION)
// Renders a feedback form for a given booking/application id.

$appId = $_GET['app_id'] ?? '';
if ($appId === '') {
  die("Missing app_id. Example: feedback_form.php?app_id=123");
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Guesthouse Feedback</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 24px; background:#fafafa; }
    .card { max-width: 680px; padding: 18px; border: 1px solid #ddd; border-radius: 12px; background:#fff; }
    label { display:block; margin-top: 12px; font-weight: 600; }
    select, textarea, input { width: 100%; padding: 10px; margin-top: 6px; border:1px solid #ccc; border-radius: 8px; }
    button { margin-top: 16px; padding: 10px 14px; cursor:pointer; border:0; border-radius:10px; background:#2b6cb0; color:#fff; }
    .muted { color:#666; font-size: 13px; margin-top: 8px; }
    .row { display:flex; gap:12px; }
    .col { flex:1; }
  </style>
</head>
<body>
  <div class="card">
    <h2 style="margin:0;">Guesthouse Feedback</h2>
    <div class="muted">Booking/Application ID: <b><?= htmlspecialchars($appId) ?></b></div>

    <form method="POST" action="feedback_submit.php">
      <input type="hidden" name="app_id" value="<?= htmlspecialchars($appId) ?>" />

      <label>Overall Rating (1–5) *</label>
      <select name="rating_overall" required>
        <option value="">Select</option>
        <option value="1">1 - Very Bad</option>
        <option value="2">2 - Bad</option>
        <option value="3">3 - Okay</option>
        <option value="4">4 - Good</option>
        <option value="5">5 - Excellent</option>
      </select>

      <div class="row">
        <div class="col">
          <label>Cleanliness Rating (1–5)</label>
          <select name="rating_cleanliness">
            <option value="">Select</option>
            <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>
          </select>
        </div>

        <div class="col">
          <label>Staff Rating (1–5)</label>
          <select name="rating_staff">
            <option value="">Select</option>
            <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>
          </select>
        </div>
      </div>

      <label>Comments</label>
      <textarea name="comments" rows="5" placeholder="Write your feedback..."></textarea>

      <button type="submit">Submit Feedback</button>
    </form>

    <div class="muted">
      Note: In real integration, this page should only be reachable after checkout/completion.
    </div>
  </div>
</body>
</html>
