<?php
// feedback_submit.php (PLACEHOLDER VERSION)
// Validates input and stores feedback in feedback_storage.json (no DB required).

$appId = $_POST['app_id'] ?? '';
$overall = $_POST['rating_overall'] ?? '';
$cleanliness = $_POST['rating_cleanliness'] ?? '';
$staff = $_POST['rating_staff'] ?? '';
$comments = $_POST['comments'] ?? '';

function is_valid_rating($v, $required=false) {
  if ($required && ($v === '' || $v === null)) return false;
  if ($v === '' || $v === null) return true;
  if (!ctype_digit((string)$v)) return false;
  $n = (int)$v;
  return $n >= 1 && $n <= 5;
}

if ($appId === '' || !is_valid_rating($overall, true)) {
  die("Missing/invalid required fields.");
}
if (!is_valid_rating($cleanliness) || !is_valid_rating($staff)) {
  die("Invalid optional rating values.");
}

// Placeholder storage
$storageFile = __DIR__ . '/feedback_storage.json';
$existing = [];

if (file_exists($storageFile)) {
  $json = file_get_contents($storageFile);
  $existing = json_decode($json, true);
  if (!is_array($existing)) $existing = [];
}

// enforce one feedback per app_id
foreach ($existing as $row) {
  if ((string)($row['application_id'] ?? '') === (string)$appId) {
    die("Feedback already submitted for this booking/application.");
  }
}

$newRow = [
  'application_id' => $appId,
  'rating_overall' => (int)$overall,
  'rating_cleanliness' => ($cleanliness === '' ? null : (int)$cleanliness),
  'rating_staff' => ($staff === '' ? null : (int)$staff),
  'comments' => trim($comments),
  'created_at' => date('c')
];

$existing[] = $newRow;
file_put_contents($storageFile, json_encode($existing, JSON_PRETTY_PRINT));

/*
INTEGRATOR NOTE (DB MODE):
Replace JSON write with PDO insert:

INSERT INTO guest_feedback
(application_id, rating_overall, rating_cleanliness, rating_staff, comments)
VALUES (:app_id, :overall, :cleanliness, :staff, :comments);
*/
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Feedback Submitted</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 24px; background:#fafafa; }
    .card { max-width: 680px; padding: 18px; border: 1px solid #ddd; border-radius: 12px; background:#fff; }
    .ok { color: #1a7f37; font-weight: 700; }
    .muted { color:#666; font-size: 13px; }
    a { color:#2b6cb0; text-decoration:none; }
  </style>
</head>
<body>
  <div class="card">
    <h2 class="ok">✅ Feedback submitted successfully</h2>
    <p><b>Application ID:</b> <?= htmlspecialchars($appId) ?></p>
    <p class="muted">Stored locally in <code>feedback_storage.json</code> (placeholder mode).</p>
  </div>
</body>
</html>
