<?php
if ($_POST['enter'])
{
	$_SESSION['acc'] = $_POST['account'];
	
	if (empty($_POST['edit']))
		$sql = "INSERT INTO records VALUES(NULL, {$_SESSION['acc']}, '{$_POST['person']}', {$_POST['value']}, '{$_POST['date']}', '{$_POST['completed']}', '{$_POST['category']}', '{$_POST['title']}', '{$_POST['notes']}')";
	else
		$sql = "
UPDATE records 
SET 
`desc`='{$_POST['title']}', 
money='{$_POST['value']}', 
person='{$_POST['person']}',
`date`='{$_POST['date']}', 
category='{$_POST['category']}', 
notes='{$_POST['notes']}',
completed='{$_POST['completed']}'
WHERE id={$_POST['edit']}";

	db_exec($sql);
echo db_error();
	if (!empty($_POST['oldvalue']))
		$_POST['value'] -= $_POST['oldvalue'];

	db_exec('UPDATE chests SET balance=balance+' . $_POST['value'] . ' WHERE id=' . $_POST['account']);
}
if ($_REQUEST['id'])
{
	$rec = db_loadList('
SELECT *
FROM records
WHERE id=' . $_REQUEST['id']);
	$rec = $rec[0];
}
if ($_REQUEST['del_id'])
	db_exec('DELETE FROM records WHERE id = ' . $_REQUEST['del_id']);
	
?>
<form action="?p=enter" method="post">
<input type="hidden" name="edit" value="<?php echo $rec['id']; ?>" />
<input type="hidden" name="oldvalue" value="<?php echo $rec['money']; ?>" />
<table align="center">
<tr>
	<td colspan="4">
		<select name="account">
<?php
	$accs = db_loadList('select * from chests');
	foreach($accs as $acc)
		echo '<option value="' . $acc['id'].'">'.$acc['name'] . '('.$acc['balance'].')</option>';
?>
		</select>
	</td>
<tr>
	<td>Title:</td>
	<td><input type="text" name="title" size="65" value="<?php echo $rec['desc']; ?>" /></td>
	<td>Money:</td>
	<td align="right"><input type="text" name="value" size="12" value="<?php echo $rec['money']; ?>" /></td>
</tr>
<tr>
	<td>To/From:</td>
	<td><input type="text" name="person" size="65" value="<?php echo $rec['person']; ?>" /></td>
	<td>Date:</td>
	<td align="right"><input type="text" name="date" size="12" value="<?php echo ($rec['date']?$rec['date']:date('Y-m-d')); ?>" /></td>
</tr>
<tr>
	<td>Category:</td>
	<td><input type="text" name="category" size="65" value="<?php echo $rec['category']; ?>" /></td>
	<td>Completed:</td>
	<td align="right"><input type="checkbox" name="completed" value="100"<?php echo (($rec['completed'] > 0)?' checked="checked"':''); ?> /></td>
</tr>
<tr>
	<td>Notes:</td>
	<td colspan="3"><textarea name="notes" cols="80" rows="3"><?php echo $rec['notes']; ?></textarea></td>
</tr>
<tr>
	<td colspan="4" align="right"><input type="submit" name="enter" value="enter" /></td>
</tr>
</table>
</form>
