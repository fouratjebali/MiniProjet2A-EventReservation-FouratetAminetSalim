<?php 
$pageTitle = 'Manage Registrations - MiniEvent';
include 'app/views/partials/header.php'; 
?>

<div class="container">
    <div class="page-header">
        <h1>Manage Registrations</h1>
    </div>
    
    <?php if (empty($registrations)): ?>
        <div class="empty-state">
            <p>No registrations found</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Event</th>
                        <th>Participant</th>
                        <th>Email</th>
                        <th>Date & Time</th>
                        <th>Registration Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registrations as $registration): ?>
                        <tr>
                            <td><?php echo $registration['id']; ?></td>
                            <td><?php echo htmlspecialchars($registration['event_title']); ?></td>
                            <td><?php echo htmlspecialchars($registration['username']); ?></td>
                            <td><?php echo htmlspecialchars($registration['email']); ?></td>
                            <td>
                                <?php echo date('M j, Y', strtotime($registration['date'])); ?><br>
                                <small><?php echo date('g:i A', strtotime($registration['time'])); ?></small>
                            </td>
                            <td><?php echo date('M j, Y g:i A', strtotime($registration['registration_date'])); ?></td>
                            <td><span class="badge badge-<?php echo $registration['status']; ?>"><?php echo ucfirst($registration['status']); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/partials/footer.php'; ?>