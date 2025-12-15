<?php 
$pageTitle = ($event ? 'Edit Event' : 'Create Event') . ' - MiniEvent';
include 'app/views/partials/header.php'; 
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $event ? 'Edit Event' : 'Create New Event'; ?></h1>
    </div>
    
    <div class="form-container">
        <form method="POST" action="<?php echo $event ? '/admin/events/edit/' . $event['id'] : '/admin/events/create'; ?>">
            <div class="form-group">
                <label for="title">Event Title *</label>
                <input type="text" id="title" name="title" required class="form-control" 
                       value="<?php echo $event ? htmlspecialchars($event['title']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" required class="form-control" rows="5"><?php echo $event ? htmlspecialchars($event['description']) : ''; ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date">Event Date *</label>
                    <input type="date" id="date" name="date" required class="form-control" 
                           value="<?php echo $event ? $event['date'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="time">Event Time *</label>
                    <input type="time" id="time" name="time" required class="form-control" 
                           value="<?php echo $event ? $event['time'] : ''; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="location">Location *</label>
                <input type="text" id="location" name="location" required class="form-control" 
                       value="<?php echo $event ? htmlspecialchars($event['location']) : ''; ?>">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="capacity">Capacity *</label>
                    <input type="number" id="capacity" name="capacity" required class="form-control" min="1" 
                           value="<?php echo $event ? $event['capacity'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="status">Status *</label>
                    <select id="status" name="status" class="form-control">
                        <option value="upcoming" <?php echo ($event && $event['status'] === 'upcoming') ? 'selected' : ''; ?>>Upcoming</option>
                        <option value="ongoing" <?php echo ($event && $event['status'] === 'ongoing') ? 'selected' : ''; ?>>Ongoing</option>
                        <option value="completed" <?php echo ($event && $event['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?php echo ($event && $event['status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <?php echo $event ? 'Update Event' : 'Create Event'; ?>
                </button>
                <a href="/admin/events" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include 'app/views/partials/footer.php'; ?>