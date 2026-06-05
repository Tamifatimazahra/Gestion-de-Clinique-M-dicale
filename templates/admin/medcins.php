<h2>Planning Médecin</h2>

<table border="1" cellpadding="10">

<tr>
    <th>Patient</th>
    <th>Date</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php foreach ($rdvs as $rdv): ?>
<tr>
    <td><?= $rdv['nom'] ?> <?= $rdv['prenom'] ?></td>

    <td><?= $rdv['appointment_date'] ?></td>

    <td><?= $rdv['statut'] ?></td>

    <td>

        <?php if ($rdv['statut'] == 'en_attente'): ?>

            <a href="/confirmer?id=<?= $rdv['id'] ?>">Confirmer</a>
            |
            <a href="/annuler?id=<?= $rdv['id'] ?>">Annuler</a>

        <?php elseif ($rdv['statut'] == 'confirme'): ?>

            <a href="/terminer?id=<?= $rdv['id'] ?>">Terminer</a>

        <?php else: ?>

            ---

        <?php endif; ?>

    </td>
</tr>
<?php endforeach; ?>

</table>