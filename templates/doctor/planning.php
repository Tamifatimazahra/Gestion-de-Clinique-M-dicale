


<h2>Planning Médecin</h2>


<table border="1" cellpadding="10">

<tr>
    <th>Patient</th>
    <th>Date</th>
    <th>Status</th>
</tr>

<?php foreach ($rdvs as $rdv): ?>
<tr>
    <td><?= $rdv['nom'] ?> <?= $rdv['prenom'] ?></td>
    <td><?= $rdv['appointment_date'] ?></td>
    <td><?= $rdv['statut'] ?></td>
</tr>
<?php endforeach; ?>

</table>