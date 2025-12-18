<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['title']); ?> - MiniEvent</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include '../app/views/partials/header.php'; ?>
    
    <div class="container">
        <div class="event-detail">
            <h1><?php echo htmlspecialchars($event['title']); ?></h1>
            
            <?php if ($message): ?>
                <div class="success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <div class="event-info-detail">
                <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($event['date'])); ?></p>
                <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($event['time'])); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                <p><strong>Organizer:</strong> <?php echo htmlspecialchars($event['organizer_name']); ?></p>
                <p><strong>Capacity:</strong> <?php echo $event['capacity']; ?></p>
                <p><strong>Registered:</strong> <?php echo $event['registered_count']; ?></p>
                <p><strong>Available Slots:</strong> <?php echo $event['capacity'] - $event['registered_count']; ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($event['status']); ?></p>
            </div>
            
            <div class="event-description-detail">
                <h2>Description</h2>
                <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
            </div>
            
            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'participant'): ?>
                <?php if ($isRegistered): ?>
                    <p class="registered-message">You are registered for this event</p>
                <?php elseif ($event['status'] === 'upcoming' && $event['capacity'] > $event['registered_count']): ?>
                    <form method="POST">
                        <button type="submit" class="btn btn-large">Register for this Event</button>
                    </form>
                <?php elseif ($event['capacity'] <= $event['registered_count']): ?>
                    <p class="full-message">This event is full</p>
                <?php endif; ?>
            <?php elseif (!isset($_SESSION['user_id'])): ?>
                <p>Please <a href="/admin/login">login</a> to register for this event</p>
            <?php endif; ?>
            
            <a href="/event/list" class="btn">Back to Events</a>
        </div>
    </div>

    <?php include '../app/views/partials/footer.php'; ?>
</body>
</html>