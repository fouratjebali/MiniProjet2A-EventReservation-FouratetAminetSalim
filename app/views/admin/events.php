<?php 
$pageTitle = 'Manage Events - MiniEvent';
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Manage Events</h1>
        <a href="/admin/events/create" class="btn btn-primary">Create New Event</a>
    </div>
    
    <?php if (empty($events)): ?>
        <div class="empty-state">
            <h2>No events yet</h2>
            <p>Create your first event to get started</p>
            <a href="/admin/events/create" class="btn btn-primary">Create Event</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Registered</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo $event['id']; ?></td>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($event['date'])); ?></td>
                            <td><?php echo date('g:i A', strtotime($event['time'])); ?></td>
                            <td><?php echo htmlspecialchars($event['location']); ?></td>
                            <td><?php echo $event['capacity']; ?></td>
                            <td><?php echo $event['registered_count']; ?></td>
                            <td><span class="badge badge-<?php echo $event['status']; ?>"><?php echo ucfirst($event['status']); ?></span></td>
                            <td class="actions">
                                <a href="/events/<?php echo $event['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                <a href="/admin/events/edit/<?php echo $event['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="/admin/events/delete/<?php echo $event['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>