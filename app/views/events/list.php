<?php include '../app/views/partials/header.php'; ?>

<main class="container">
    <h2 style="text-align: center; margin: 2rem 0;">Événements Disponibles</h2>
    
    <div class="event-grid">
        <?php foreach ($events as $event): ?>
            <div class="event-card">
                <?php if ($event['image']): ?>
                    <img src="<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                <?php endif; ?>
                
                <div class="event-card-content">
                    <h3><?= htmlspecialchars($event['title']) ?></h3>
                    <p><strong>Date:</strong> <?= date('d/m/Y', strtotime($event['date'])) ?></p>
                    <p><strong>Lieu:</strong> <?= htmlspecialchars($event['location']) ?></p>
                    <p><strong>Places disponibles:</strong> <?= htmlspecialchars($event['seats']) ?></p>
                    <a href="/events/<?= $event['id'] ?>" class="btn">Voir détails</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include '../app/views/partials/footer.php'; ?>
