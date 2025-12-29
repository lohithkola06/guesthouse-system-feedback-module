<?php
// feedback_admin_list.php (PLACEHOLDER ADMIN VIEW)
// Reads feedback from feedback_storage.json and displays it.

$storageFile = __DIR__ . '/feedback_storage.json';
$data = [];

if (file_exists($storageFile)) {
  $json = file_get_contents($storageFile);
  $data = json_decode($json, true);
  if (!is_array($data)) $data = [];
}

// Optional filter
$filterAppId = $_GET['app_id'] ?? '';
if ($filterAppId !== '') {
  $data = array_values(array_filter($data, function($row) use ($filterAppId) {
    return (string)($row['application_id'] ?? '') === (string)$filterAppId;
  }));
}

// Sort newest first
usort($data, function($a, $b) {
  return strcmp(($b['created_at'] ?? ''), ($a['created_at'] ?? ''));
});
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Admin – Guest Feedback</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 24px; background:#fafafa; }
    .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
    .card { padding: 14px; border: 1px solid #ddd; border-radius: 12px; background:#fff; }
    table { width: 100%; border-collapse: collapse; margin-top: 12px; }
    th, td { border-bottom: 1px solid #eee; padding: 10px; text-align: left; vertical-align: top; }
    th { background: #fafafa; }
    .muted { color:#666; font-size: 13px; }
    input { padding: 8px; width: 240px; border:1px solid #ccc; border-radius:8px; }
    button { padding: 8px 12px; cursor: pointer; border:0; border-radius:10px; background:#2b6cb0; color:#fff; }
    .pill { display:inline-block; padding: 2px 8px; border-radius: 999px; background:#f3f3f3; font-size:12px; margin-left:8px; }
    a { color:#2b6cb0; text-decoration:none; }
  </style>
</head>
<body>

<div class="header">
  <div>
    <h2 style="margin:0;">Admin – Guest Feedback</h2>
    <div class="muted">Placeholder viewer (reads <code>feedback_storage.json</code>)</div>
  </div>

  <form method="GET">
    <input type="text" name="app_id" placeholder="Filter by Application ID"
           value="<?= htmlspecialchars($filterAppId) ?>" />
    <button type="submit">Filter</button>
    <a href="feedback_admin_list.php" style="margin-left:10px;">Reset</a>
  </form>
</div>

<div class="card">
  <div class="muted">
    Total entries: <b><?= count($data) ?></b>
    <?php if ($filterAppId !== ''): ?>
      <span class="pill">Filtered: app_id=<?= htmlspecialchars($filterAppId) ?></span>
    <?php endif; ?>
  </div>

  <table>
    <thead>
      <tr>
        <th>Application ID</th>
        <th>Ratings</th>
        <th>Comments</th>
        <th>Submitted At</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($data) === 0): ?>
        <tr><td colspan="4" class="muted">No feedback found.</td></tr>
      <?php else: ?>
        <?php foreach ($data as $row): ?>
          <tr>
            <td><b><?= htmlspecialchars($row['application_id'] ?? '') ?></b></td>
            <td>
              Overall: <b><?= htmlspecialchars($row['rating_overall'] ?? '') ?></b><br/>
              Cleanliness: <?= htmlspecialchars($row['rating_cleanliness'] ?? '—') ?><br/>
              Staff: <?= htmlspecialchars($row['rating_staff'] ?? '—') ?>
            </td>
            <td><?= nl2br(htmlspecialchars($row['comments'] ?? '')) ?></td>
            <td class="muted"><?= htmlspecialchars($row['created_at'] ?? '') ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

  <div class="muted" style="margin-top:12px;">
    Integrator note: replace JSON read with DB query:<br/>
    <code>
      SELECT application_id, rating_overall, rating_cleanliness, rating_staff, comments, created_at
      FROM guest_feedback ORDER BY created_at DESC;
    </code>
  </div>
</div>

</body>
</html>