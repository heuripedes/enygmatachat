<H2>Admistração</H2><br>
<pre>Digite a senha para limpar os arquivos de salas.

<?php
include_once( 'config.php' );

$hash =  md5( crc32( $SENHA ));
$s = md5( crc32( $_POST['senha'] ));
if ( $s == $hash )
{
	$dir = './texto';
	$dir = @opendir( $dir );
	while( $e = @readdir( $dir ) )
	{
		if ( @is_file( './texto/'.$e ) && $e != '.' && $e != '..' && @file_exists( './texto/'.$e ))
		{
			$fp = @fopen( './texto/'.$e , 'wb');
			@fclose( $fp );
		}
		
	}
	echo 'Arquivos apagados com êxito!<br>';

}
?>

<FORM METHOD=POST ACTION="<?php echo $_SERVER['PHP_SELF']; ?>">
Senha:<INPUT TYPE="password" name="senha"><INPUT TYPE="submit" value="ok">
</FORM>

<A HREF="index.php">Voltar ao chat</A>