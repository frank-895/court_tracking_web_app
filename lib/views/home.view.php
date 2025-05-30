<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<?php if (!empty($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<!-- lib/views/home.view.php -->
<div class="p-5 mb-4 bg-light rounded-3">
  <div class="container-fluid py-5">
    <h1 class="display-5 fw-bold">Welcome to the Court Outcome Tracking System</h1>
    <p class="col-md-8 fs-4">Select a module from the menu to get started.</p>
    <p class="fs-6 text-muted">
      Track and manage court cases efficiently with real-time updates, automated conviction assessments, and secure role-based access. 
      This system streamlines legal processes by centralising court data, reducing errors, and enhancing decision-making for law enforcement agencies.
    </p>
  </div>
</div>

<!-- Stats Section -->
<div class="container mb-5">
  <h2 class="mb-4">Case Statistics</h2>
  <div class="row g-4">
    <div class="col-md-3">
      <div class="card text-white bg-primary h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Total Cases</h5>
          <p class="card-text fs-3"><?= htmlspecialchars($stats['total'] ?? 0) ?></p>
          <a href="<?= BASE_URL ?>/case/manage" class="btn btn-light btn-sm mt-2">View All Cases</a>
        </div>
      </div>
    </div>
        <div class="col-md-3">
      <div class="card text-white bg-success h-100">
        <div class="card-body text-center d-flex flex-column justify-content-center">
          <h5 class="card-title">Pending Charges</h5>
          <p class="card-text fs-3"><?= htmlspecialchars($stats['pending'] ?? 0) ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-warning h-100">
        <div class="card-body text-center d-flex flex-column justify-content-center">
          <h5 class="card-title">Resolved Charges</h5>
          <p class="card-text fs-3"><?= htmlspecialchars($stats['resolved'] ?? 0) ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-secondary h-100">
        <div class="card-body text-center d-flex flex-column justify-content-center">
          <h5 class="card-title">Dismissed Charges</h5>
          <p class="card-text fs-3"><?= htmlspecialchars($stats['dismissed'] ?? 0) ?></p>
        </div>
      </div>
    </div>
  </div>
</div>


<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
  <!-- Quick Actions -->
  <div class="container mb-5">
    <h2 class="mb-4">Quick Actions</h2>
    <div class="row g-3">
      <div class="col-md-4">
        <a href="<?= BASE_URL ?>/case/defendant" class="btn btn-outline-primary w-100">➕ Add Case</a>
      </div>
      <div class="col-md-4">
        <a href="<?= BASE_URL ?>/defendant/add" class="btn btn-outline-success w-100">➕ Add Defendant</a>
      </div>
      <div class="col-md-4">
        <a href="<?= BASE_URL ?>/lawyer/add" class="btn btn-outline-warning w-100">➕ Add Lawyer</a>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
<div class="container mb-5">
  <h2 class="mb-4">Recent Logs</h2>
  <?php if (!empty($logs)): ?>
    <ol start="1" class="list-group list-group-numbered">
      <?php foreach ($logs as $log): ?>
        <li class="list-group-item">
          <strong><?= htmlspecialchars($log['username']) ?></strong>
          <span class="text-muted"> - <?= htmlspecialchars(date('Y-m-d H:i', strtotime($log['created_at']))) ?></span>
          <br>
          <?= htmlspecialchars($log['action']) ?>
        </li>
      <?php endforeach; ?>
    </ol>
    <div class="mt-3">
      <a href="<?= BASE_URL ?>/logs" class="btn btn-primary btn-sm">Show More</a>
    </div>
  <?php else: ?>
    <p>No logs to display.</p>
  <?php endif; ?>
</div>
<?php endif; ?>