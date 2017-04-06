<table border="0" width="250">
	<tr height=23>
		<td>id</td>
		<td><input type="text" name="user_id" size="20" maxlength="20" class="input"></td>
	</tr>
	<tr height=23>
		<td>Password</td>
		<td><input type="password" name="password" size="20" maxlength="20" class="input"></td>
	<tr height=23>
		<td align=center colspan="2">
			<input type="submit" value="Login" class="submit">
			<input type="button" value="Back" onclick="history.go(-1)" class="submit">
			<a href='skin/<?=$id?>/modify_password.php?id=<?=$id?>'>[비밀번호를 잊었다면]</a>
		</td>
	</tr>
</table>

