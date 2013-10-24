<?php
if (!defined("IN_MOD"))
{
	die("Nah, I won't serve that file to you.");
}
$mitsuba->admin->reqPermission("bans.view");
$delete = $mitsuba->admin->checkPermission("bans.delete");
$logs = $mitsuba->admin->checkPermission("logs.view");
if ((!empty($_GET['c'])) && (is_numeric($_GET['c'])))
	{
	$mitsuba->admin->ui->startSection(sprintf($lang['mod/recent_bans'], $_GET['c']));
	?>
	<table>
	<thead>
	<tr>
	<td><?php echo $lang['mod/ip']; ?></td>
	<td><?php echo $lang['mod/reason']; ?></td>
	<td><?php echo $lang['mod/staff_note']; ?></td>
	<td><?php echo $lang['mod/created']; ?></td>
	<td><?php echo $lang['mod/expires']; ?></td>
	<td><?php echo $lang['mod/boards']; ?></td>
	<td><?php echo $lang['mod/seen']; ?></td>
	<td><?php echo $lang['mod/delete']; ?></td>
	<?php
		if ($logs) { echo "<td>".$lang['mod/staff_member']."</td>"; }
	?>
	</tr>
	</thead>
	<tbody>
	<?php
	if ($logs) {
		$result = $conn->query("SELECT bans.*, users.username FROM bans LEFT JOIN users ON bans.mod_id=users.id ORDER BY created DESC LIMIT 0, ".$_GET['c'].";");
	} else {
		$result = $conn->query("SELECT * FROM bans ORDER BY created LIMIT 0, ".$_GET['c'].";");
	}
	while ($row = $result->fetch_assoc())
	{
	echo "<tr>";
	echo "<td class='text-center text-nowrap'>".$row['ip']."</td>";
	echo "<td>".$row['reason']."</td>";
	echo "<td>".$row['note']."</td>";
	echo "<td class='text-center text-nowrap'>".date("d/m/Y <br />@ H:i", $row['created'])."</td>";
	if ($row['expires'] != 0)
	{
	echo "<td class='text-center text-nowrap'>".date("d/m/Y <br />@ H:i", $row['expires'])."</td>";
	} else {
	echo "<td><b>never</b></td>";
	}
	if ($row['boards']=="%")
	{
		echo "<td class='text-center'>All boards</td>";
	} else {
		echo "<td class='text-center'>".$row['boards']."</td>";
	}
	if ($row['seen']==1)
	{
		echo "<td class='text-center'>YES</td>";
	} else {
		echo "<td class='text-center'><b>NO</b></td>";
	}
	if ($delete)
	{
	echo "<td class='text-center'><a href='?/bans&del=1&b=".$row['id']."'>".$lang['mod/delete']."</a></td>";
	} else {
	echo "<td></td>";
	}
	if ($logs)
	{
		echo "<td class='text-center text-nowrap'>".$row['username']."</td>";
	}
	echo "</tr>";
	}
	?>
	</tbody>
	</table>
	<?php $mitsuba->admin->ui->endSection(); ?>
	<?php
	}
?>