<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Registrations - MiniEvent</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include '../app/views/partials/header.php'; ?>
    
    <div class="container">
        <h1>My Registrations</h1>
        <a href="/event/list" class="btn">Browse Events</a>
        
        <div class="registrations-table">
            <?php if (count($registrations) > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Organizer</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrations as $reg): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reg['title']); ?></td>
                                <td><?php echo htmlspecialchars($reg['organizer_name']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($reg['date'])); ?></td>
                                <td><?php echo date('H:i', strtotime($reg['time'])); ?></td>
                                <td><?php echo htmlspecialchars($reg['location']); ?></td>
                                <td><?php echo htmlspecialchars($reg['status']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($reg['registration_date'])); ?></td>
                                <td>
                                    <a href="/events/<?php echo $reg['id']; ?>" class="btn btn-small">View</a>
                                    <?php if ($reg['reg_status'] === 'registered' && $reg['status'] === 'upcoming'): ?>
                                        <a href="/event/cancelRegistration?id=<?php echo $reg['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Are you sure you want to cancel this registration?');">Cancel</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You haven't registered for any events yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../app/views/partials/footer.php'; ?>
</body>
</html>