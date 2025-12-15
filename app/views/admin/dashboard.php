<?php 
$pageTitle = 'Dashboard - MiniEvent';
include 'app/views/partials/header.php'; 
?>

<div class="container">
    <div class="page-header">
        <h1>Dashboard</h1>
        <a href="/admin/events/create" class="btn btn-primary">Create New Event</a>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <h3><?php echo $stats['total_users']; ?></h3>
                <p>Total Users</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-content">
                <h3><?php echo $stats['total_events']; ?></h3>
                <p>Total Events</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">🎯</div>
            <div class="stat-content">
                <h3><?php echo $stats['upcoming_events']; ?></h3>
                <p>Upcoming Events</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">✓</div>
            <div class="stat-content">
                <h3><?php echo $stats['total_registrations']; ?></h3>
                <p>Total Registrations</p>
            </div>
        </div>
    </div>
    
    <div class="section">
        <h2><?php echo $_SESSION['role'] === 'organizer' ? 'My Events' : 'Recent Events'; ?></h2>
        
        <?php if (empty($events)): ?>
            <div class="empty-state">
                <p>No events found</p>
                <a href="/admin/events/create" class="btn btn-primary">Create Your First Event</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Location</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($events, 0, 10) as $event): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($event['title']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($event['date'])); ?></td>
                                <td><?php echo date('g:i A', strtotime($event['time'])); ?></td>
                                <td><?php echo htmlspecialchars($event['location']); ?></td>
                                <td><?php echo $event['registered_count']; ?> / <?php echo $event['capacity']; ?></td>
                                <td><span class="badge badge-<?php echo $event['status']; ?>"><?php echo ucfirst($event['status']); ?></span></td>
                                <td>
                                    <a href="/events/<?php echo $event['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                    <a href="/admin/events/edit/<?php echo $event['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (count($events) > 10): ?>
                <div class="text-center">
                    <a href="/admin/events" class="btn btn-secondary">View All Events</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/partials/footer.php'; ?>