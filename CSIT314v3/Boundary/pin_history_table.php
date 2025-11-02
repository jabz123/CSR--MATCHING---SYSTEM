<?php
declare(strict_types=1);

/**
 * Boundary rendering function for PIN history table.
 * No output outside this function.
 *
 * @param array<int,array<string,mixed>> $rows
 */
function render_history_table(array $rows): void { ?>
  <?php if (empty($rows)): ?>
    <div class="empty">No records found.</div>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Request</th>
          <th>Volunteer</th>
          <th>Status</th>
          <th>Completed At</th>
          <th>Title</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= (int)$r['history_id'] ?></td>
          <td><?= (int)$r['request_id'] ?></td>
          <td><?= htmlspecialchars((string)$r['volunteer_id']) ?></td>
          <td><?= htmlspecialchars((string)$r['status']) ?></td>
          <td class="muted"><?= htmlspecialchars((string)$r['completed_at']) ?></td>
          <td><?= htmlspecialchars((string)($r['title'] ?? '')) ?></td>
          <td><?= htmlspecialchars((string)($r['description'] ?? '')) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
<?php }
