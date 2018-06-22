<?php
	$l = 5;$t = 7;$e = 11;
	$c_l = 0;$c_t = 0;$c_e = 0;$c_lte = 0;
?>

<table border="1" cellpadding="0" cellspacing="0">
	<tr>
		<th><?php echo $l; ?></th>
		<th><?php echo $t; ?></th>
		<th><?php echo $e; ?></th>
	</tr>
	<?php for ($i=1;$i<1000;$i++) { ?>
	<tr <?php if (($i % $l == 0) && ($i % $t == 0) && ($i % $e == 0)) { echo 'style="background-color:orange;"'; $c_lte++; } ?>>
		<td width="100px" style="text-align:center;">
			<?php if ($i % $l == 0) { echo $i; $c_l++; } else { echo "-"; } ?>
		</td>
		<td width="100px" style="text-align:center;">
			<?php if ($i % $t == 0) { echo $i; $c_t++; } else { echo "-"; } ?>
		</td>
		<td width="100px" style="text-align:center;">
			<?php if ($i % $e == 0) { echo $i; $c_e++; } else { echo "-"; } ?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<th><?php echo $c_l; ?></th>
		<th><?php echo $c_t; ?></th>
		<th><?php echo $c_e; ?></th>
	</tr>
</table>

<div>
	Hasil perhitungan:
	<br />
	L &cap; T &cap; E = <?php echo $c_lte; ?>
</div>
