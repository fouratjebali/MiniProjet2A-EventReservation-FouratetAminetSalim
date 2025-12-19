<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - MiniEvent</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include '../app/views/partials/header.php'; ?>
    
    <div class="container">
        <h1>Events</h1>
        
        <div class="search-filter">
            <form method="GET" action="/event/list" class="search-form">
                <input type="text" name="search" placeholder="Search events..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                <select name="filter">
                    <option value="all" <?php echo ($_GET['filter'] ?? 'all') === 'all' ? 'selected' : ''; ?>>All Events</option>
                    <option value="upcoming" <?php echo ($_GET['filter'] ?? '') === 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                    <option value="completed" <?php echo ($_GET['filter'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
                </select>
                <button type="submit" class="btn">Search</button>
            </form>
        </div>

        <div class="events-grid">
            <?php if (count($events) > 0): ?>
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="event-description"><?php echo htmlspecialchars(substr($event['description'], 0, 150)) . '...'; ?></p>
                        <div class="event-details">
                            <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($event['date'])); ?></p>
                            <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($event['time'])); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                            <p><strong>Organizer:</strong> <?php echo htmlspecialchars($event['organizer_name']); ?></p>
                            <p><strong>Available:</strong> <?php echo $event['capacity'] - $event['registered_count']; ?> / <?php echo $event['capacity']; ?></p>
                        </div>
                        <a href="/events/<?php echo $event['id']; ?>" class="btn">View Details</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No events found.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../app/views/partials/footer.php'; ?>
</body>
</html>