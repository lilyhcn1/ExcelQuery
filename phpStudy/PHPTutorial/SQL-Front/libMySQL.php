<?php
	#
	# HTTP tunneling script
	# This script is used by the Windows application MySQL-Front
	# http://www.mysqlfront.de/
	# MySQL-Front Version 5.3.4.234
	#

	/****************************************************************************/

	$MF_VERSION = 20;

	$Charsets = array(
		'big5' => 1,
		'czech' => 2,
		'dec8' => 3,
		'dos' => 4,
		'german1' => 5,
		'hp8' => 6,
		'koi8_ru' => 7,
		'latin1' => 8,
		'latin2' => 9,
		'swe7' => 10,
		'usa7' => 11,
		'ujis' => 12,
		'sjis' => 13,
		'cp1251' => 14,
		'danish' => 15,
		'hebrew' => 16,
		'win1251' => 17,
		'tis620' => 18,
		'euc_kr' => 19,
		'estonia' => 20,
		'hungarian' => 21,
		'koi8_ukr' => 22,
		'win1251ukr' => 23,
		'gb2312' => 24,
		'greek' => 25,
		'win1250' => 26,
		'croat' => 27,
		'gbk' => 28,
		'cp1257' => 29,
		'latin5' => 30,
		'latin1_de' => 31);

	/****************************************************************************/

	function FileRead($Handle, $Length) {
		$Data = '';
		while ((strlen($Data) < $Length) && ! feof($Handle))
			$Data .= fread($Handle, $Length - strlen($Data));
		
		return $Data;
	}

	function FlushPackets() {
		global $SendPacketBuffer;

		if ($SendPacketBuffer) {
			SendCompressedPacket($SendPacketBuffer);
			$SendPacketBuffer = '';
		}
	}

	function PackLength($Length) {
		if ($Length < 0xFB)
			return pack('C', $Length);
		else if ($Length <= 0xFFFF)
			return "\xFC" . pack('v', $Length);
		else if ($Length <= 0xFFFFFF)
			return "\xFD" . substr(pack('V', $Length), 0, 3);
		else
			return "\xFE" . pack('V', $Length & 0xFFFFFFFF) . pack('V', $Length >> 32);
	}

	function ReceivePacket(&$Packet, &$MorePackets) {
		global $PostData;
		global $PostDataOffset;

		if ($PostDataOffset >= strlen($PostData)) {
			return FALSE;
		} else {
			$Packet = '';
			do {
				$a = unpack('V', substr($PostData, $PostDataOffset + 0, 3) . "\x00"); $Size = $a[1];
				$a = unpack('C', substr($PostData, $PostDataOffset + 3, 1)); $Nr = $a[1];

				$Packet .= substr($PostData, $PostDataOffset + 4, $Size);

				$PostDataOffset += 4 + $Size;
			} while ($Size == 0xFFFFFF);

			$MorePackets = $PostDataOffset < strlen($PostData);

			return TRUE;
		}
	}

	function SendCompressedPacket($Packet) {
		global $PacketNr;

		if (strlen($Packet) >= 50)
			$CompressedPacket = gzcompress($Packet);

		if ((! isset($CompressedPacket)) || (strlen($CompressedPacket) >= strlen($Packet)))
			echo(substr(pack('V', strlen($Packet)), 0, 3) . pack('C', $PacketNr) . substr(pack('V', 0), 0, 3) . $Packet);
		else
			echo(substr(pack('V', strlen($CompressedPacket)), 0, 3) . pack('C', $PacketNr) . substr(pack('V', strlen($Packet)), 0, 3) . $CompressedPacket);

		$PacketNr = ($PacketNr + 1) & 0xFF;
	}

	function SendPacket($Packet) {
		global $PacketNr;
		global $SendPacketBuffer;

		while ($Packet) {
			if (strlen($Packet) > 0xFFFFFF) {
				$SendPacketBuffer .= substr(pack('V', 0xFFFFFF), 0, 3) . pack('C', $PacketNr) . substr($Packet, 0, 0xFFFFFF);
				$Packet = substr($Packet, 0xFFFFFF);
			} else {
				$SendPacketBuffer .= substr(pack('V', strlen($Packet)), 0, 3) . pack('C', $PacketNr) . $Packet;
				$Packet = '';
			}

			if (! $_SESSION['compress'])
				$PacketNr = ($PacketNr + 1) & 0xFF;
		};

		if (! $_SESSION['compress']) {
			echo($SendPacketBuffer);
			$SendPacketBuffer = '';
		} else {
			while (strlen($SendPacketBuffer) > 0x4000) {
				if (strlen($SendPacketBuffer) > 0xFFFFFF) {
					SendCompressedPacket(substr($SendPacketBuffer, 0, 0xFFFFFF));
					$SendPacketBuffer = substr($SendPacketBuffer, 0xFFFFFF);
				} else {
					SendCompressedPacket($SendPacketBuffer);
					$SendPacketBuffer = '';
				}
			}
		}
	}

	function SetCharsetNr($mysql) {
		$_SESSION['MBCLen'] = 1;

		if (version_compare(ereg_replace("-.*$", "", mysql_get_server_info($mysql)), '4.1.1') < 0) {
			$_SESSION['charsetnr'] = $Charsets[$_SESSION['charset']];
			$_SESSION['MBCLen'] = 1;
		} else if (version_compare(ereg_replace("-.*$", "", mysql_get_server_info($mysql)), '5.0.0') < 0) {
			$CharacterSets = mysql_query('SHOW CHARACTER SET;', $mysql);
			$Collations = mysql_query('SHOW COLLATION;', $mysql);
			while ($Collation = mysql_fetch_array($Collations))
				if ($Collation['Charset'] == $_SESSION['charset'] && $Collation['Default'] == 'Yes') {
					$_SESSION['charsetnr'] = (int) $Collation['Id'];
					while ($CharacterSet = mysql_fetch_array($CharacterSets))
						if ($CharacterSet['Charset'] == $Collation['Charset'])
							$_SESSION['MBCLen'] = (int) $CharacterSet['Maxlen'];
				}
			mysql_free_result($Collations);
			mysql_free_result($CharacterSets);
		} else {
			$Collations = mysql_query("SHOW COLLATION WHERE `Charset`='" . $_SESSION['charset'] . "' AND `Default`='Yes';", $mysql);
			if ($Collation = mysql_fetch_array($Collations))
				$_SESSION['charsetnr'] = (int) $Collation['Id'];
			mysql_free_result($Collations);
			$CharacterSets = mysql_query("SHOW CHARACTER SET WHERE `Charset`='" . $_SESSION['charset'] . "';", $mysql);
			if ($CharacterSet = mysql_fetch_array($CharacterSets))
				$_SESSION['MBCLen'] = (int) $CharacterSet['Maxlen'];
			mysql_free_result($CharacterSets);
		}

		$Select1 = mysql_query("SELECT '1';", $mysql);
		$_SESSION['MBCLen'] = (int) ($_SESSION['MBCLen'] / mysql_field_len($Select1, 0));
		mysql_free_result($Select1);
	}

	/****************************************************************************/

	error_reporting(E_ERROR | E_PARSE);

	if ($_SERVER['REQUEST_METHOD'] == 'GET')
		exit('<!DOCTYPE html><html><head></head><body>This script is used by the Windows application <a href="http://www.mysqlfront.de/">MySQL-Front</a>.</body></html>');

	if (isset($_GET['SID']))
		session_id($_GET['SID']);
	$SessionStarted = session_start() xor (version_compare(phpversion(), '5.3.0') < 0);


	$PostData = ''; $PostDataOffset = 0; $PacketNr = 0;
	$Input = fopen('php://input', 'br');
	while (! feof($Input)) {
		if (! $_SESSION['compress'])
			$Header = FileRead($Input, 4);
		else
			$Header = FileRead($Input, 7);
		$a = unpack('V', substr($Header, 0, 3) . "\x00"); $Size = $a[1];
		$a = unpack('C', substr($Header, 3, 1)); $Nr = $a[1];
		if ($_SESSION['compress'])
			$a = unpack('V', substr($Header, 4, 3) . "\x00"); $UncompressedSize = $a[1];

		if ($Nr != $PacketNr)
			exit(1);
		else if (! $_SESSION['compress'])
			$PostData .= $Header . FileRead($Input, $Size);
		else if ($UncompressedSize == 0)
			$PostData .= FileRead($Input, $Size);
		else
			$PostData .= gzuncompress(FileRead($Input, $Size), $UncompressedSize);

		$PacketNr = ($PacketNr + 1) & 0xFF;
	}
	fclose($Input);


	$Connect = ! $_SESSION['host'];
	if ($Connect && ReceivePacket($Packet, $MorePackets) && (substr($Packet, 0, 1) == "\x0B")) {
		$Offset = 1;
		while (substr($Packet, $Offset, 1) != "\x00") $_SESSION['host'] .= substr($Packet, $Offset++, 1); $Offset++;
		while (substr($Packet, $Offset, 1) != "\x00") $_SESSION['user'] .= substr($Packet, $Offset++, 1); $Offset++;
		while (substr($Packet, $Offset, 1) != "\x00") $_SESSION['password'] .= substr($Packet, $Offset++, 1); $Offset++;
		while (substr($Packet, $Offset, 1) != "\x00") $_SESSION['database'] .= substr($Packet, $Offset++, 1); $Offset++;
		while (substr($Packet, $Offset, 1) != "\x00") $_SESSION['charset'] .= substr($Packet, $Offset++, 1); $Offset++;
		$a = unpack('v', substr($Packet, $Offset, 2)); $_SESSION['port'] = $a[1]; $Offset += 2;
		$a = unpack('V', substr($Packet, $Offset, 4)); $_SESSION['client_flag'] = $a[1]; $Offset += 4;
		$a = unpack('v', substr($Packet, $Offset, 2)); $_SESSION['timeout'] = $a[1]; $Offset += 2;

		set_time_limit($_SESSION['timeout']);
	} else
		set_time_limit(0);

	/****************************************************************************/

	header('Content-Type: application/mysql-front');
	header('Content-Transfer-Encoding: binary');
	if ($Connect)
	{
		header('MF-Version: ' . $MF_VERSION);
		header('MF-SID: ' . session_id());
	}

	if (! $SessionStarted) {

		$Packet = "\xFF";
		$Packet .= pack('v', 2200);
		$Packet .= "HTTP Tunnel: session_start() failed\x00";
		SendPacket($Packet);
		FlushPackets();
		exit(2200);

	} else if (extension_loaded('mysqli')) { /***********************************/

		$mysqli = mysqli_init();
		if (! $mysqli) {
			$Packet = "\xFF";
			$Packet .= pack('v', 2200);
			$Packet .= "HTTP Tunnel: mysqli_init() failed\x00";
			SendPacket($Packet);
			FlushPackets();
			exit(2200);
		}

		mysqli_real_connect($mysqli, $_SESSION['host'], $_SESSION['user'], $_SESSION['password'], $_SESSION['database'], $_SESSION['port'], '', $_SESSION['client_flag']);

		if (! mysqli_errno($mysqli) && $_SESSION['charset'] && version_compare(ereg_replace("-.*$", "", mysqli_get_server_info($mysqli)), '4.1.1') >= 0)
			if ((version_compare(phpversion(), '5.2.3') < 0) || (version_compare(ereg_replace("-.*$", "", mysqli_get_server_info($mysqli)), '5.0.7') < 0))
				mysqli_query($mysqli, 'SET NAMES ' . $_SESSION['charset'] . ';', MYSQLI_USE_RESULT);
			else
				mysqli_set_charset($mysqli, $_SESSION['charset']);

		if (mysqli_errno($mysqli)) {
			$Packet = "\xFF";
			$Packet .= pack('v', mysqli_errno($mysqli));
			$Packet .= mysqli_error($mysqli) . "\x00";
			SendPacket($Packet);
		} else if ($Connect) {
			if (version_compare(ereg_replace("-.*$", "", mysqli_get_server_info($mysqli)), '4.1.1') < 0) {
				$result = mysqli_query($mysqli, "SHOW VARIABLES LIKE 'character_set';", MYSQLI_USE_RESULT);
				if ($Row = mysqli_fetch_array($result))
					$_SESSION['charset'] = $Row['Value'];
				mysqli_free_result($result);
			} else if ((version_compare(phpversion(), '5.2.3') < 0) || version_compare(ereg_replace("-.*$", "", mysqli_get_server_info($mysqli)), '5.0.7')) {
				$result = mysqli_query($mysqli, "SHOW VARIABLES LIKE 'character_set_client';", MYSQLI_USE_RESULT);
				if ($Row = mysqli_fetch_array($result))
					$_SESSION['charset'] = $Row['Value'];
				mysqli_free_result($result);
			} else
				$_SESSION['charset'] = mysqli_character_set_name($mysqli);

			if (version_compare(ereg_replace("-.*$", "", mysqli_get_server_info($mysqli)), '4.1.1') < 0) {
				$CharsetNr = $Charsets[$_SESSION['charset']];
			} else if (version_compare(ereg_replace("-.*$", "", mysqli_get_server_info($mysqli)), '5.0.0') < 0) {
				$result = mysqli_query($mysqli, 'SHOW COLLATION;', MYSQLI_USE_RESULT);
				while ($Row = mysqli_fetch_array($result))
					if ($Row['Charset'] == $_SESSION['charset'] && $Row['Default'] == 'Yes')
						$CharsetNr = (int) $Row['Id'];
				mysqli_free_result($result);
			} else {
				$result = mysqli_query($mysqli, "SHOW COLLATION WHERE `Charset`='" . $_SESSION['charset'] . "' AND `Default`='Yes';", MYSQLI_USE_RESULT);
				if ($Row = mysqli_fetch_array($result))
					$CharsetNr = (int) $Row['Id'];
				mysqli_free_result($result);
			}

			$Packet = '';
			$Packet .= pack('C', 10); // Protocol
			$Packet .= mysqli_get_server_info($mysqli) . "\x00";
			$Packet .= pack('V', 0); // Thread Id
			$Packet .= "00000000\x00"; // Salt
			if (function_exists('gzcompress'))
				$Packet .= pack('v', 0x422C); // Server Capabilities
			else
				$Packet .= pack('v', 0x420C); // Server Capabilities
			$Packet .= pack('C', $CharsetNr);
			$Packet .= pack('v', 0x0000); // Server Status
			$Packet .= pack('a13', 1); // unused
			SendPacket($Packet);

			$PacketNr++;

			$Packet = '';
			$Packet .= pack('C', 0);
			$Packet .= PackLength(0); // Affected Rows
			$Packet .= PackLength(0); // Insert Id
			$Packet .= pack('v', 0x0000); // Server Status
			$Packet .= pack('v', 0x0000); // Warning Count
			SendPacket($Packet);

			FlushPackets();
		} else {
			while (ReceivePacket($Packet, $MorePackets)) {
				if (substr($Packet, 0, 1) == "\x01") { // COM_QUIT
					session_destroy();
				} else if (substr($Packet, 0, 1) == "\x03") { // COM_QUERY
					$Query = substr($Packet, 1);
					mysqli_real_query($mysqli, $Query);

					if (mysqli_errno($mysqli)) {
						$Packet = "\xFF";
						$Packet .= pack('v', mysqli_errno($mysqli));
						$Packet .= mysqli_error($mysqli) . "\x00";
						SendPacket($Packet);
						FlushPackets();
						break;
					}	else if (eregi("^USE[| |\t|\n|\r][| |\t|\n|\r]*", $Query) || eregi("^SET[| |\t|\n|\r][| |\t|\n|\r]*NAMES[| |\t|\n|\r]", $Query)) {
						// on some PHP versions mysqli_use_result just ignores "USE Database;"
						// statements. So it has to be handled separately:
						$Packet = '';
						$Packet .= PackLength(0); // Number of fields
						$Packet .= PackLength(mysqli_affected_rows($mysqli));
						$Packet .= PackLength(mysqli_insert_id($mysqli));
						if ($MorePackets || mysqli_more_results($mysqli))
							$Packet .= pack('v', 0x0008); // Server Status
						else
							$Packet .= pack('v', 0x0000); // Server Status
						$Packet .= pack('v', mysqli_warning_count($mysqli));
						if ((version_compare(phpversion(), '4.3.0') >= 0) && mysqli_info($mysqli))
							$Packet .= PackLength(strlen(mysqli_info($mysqli))) . mysqli_info($mysqli);
						SendPacket($Packet);
						FlushPackets();

						if (eregi("^USE[| |\t|\n|\r]*", $Query))
							$_SESSION['database'] = eregi_replace("[|`|\"| *;|;|\t|\n|\r]", "", eregi_replace("^USE[| |\t|\n|\r]*", "", $Query));
						else if (eregi("^SET[| |\t|\n|\r]*NAMES[| |\t|\n|\r]", $Query)) {
							$_SESSION['charset'] = eregi_replace("[|`|\"| *;|;|\t|\n|\r]", "", eregi_replace("^NAMES[| |\t|\n|\r]*", "", eregi_replace("^SET[| |\t|\n|\r]*", "", $Query)));
						}
					} else {
						do {
							$result = mysqli_use_result($mysqli);

							if (mysqli_errno($mysqli)) {
								$Packet = "\xFF";
								$Packet .= pack('v', mysqli_errno($mysqli));
								$Packet .= mysqli_error($mysqli) . "\x00";
								SendPacket($Packet);
								FlushPackets();
								break 2;
							}	else if (! $result) {
								$Packet = "\x00";
								$Packet .= PackLength(mysqli_affected_rows($mysqli));
								$Packet .= PackLength(mysqli_insert_id($mysqli));
								if ($MorePackets || mysqli_more_results($mysqli))
									$Packet .= pack('v', 0x0008); // Server Status
								else
									$Packet .= pack('v', 0x0000); // Server Status
								$Packet .= pack('v', mysqli_warning_count($mysqli));
								if ((version_compare(phpversion(), '4.3.0') >= 0) && mysqli_info($mysqli))
									$Packet .= PackLength(strlen(mysqli_info($mysqli))) . mysqli_info($mysqli);
								SendPacket($Packet);
								FlushPackets();
							} else {
								$Packet = PackLength(mysqli_num_fields($result));
								SendPacket($Packet);

								while ($Field = mysqli_fetch_field($result)) {
									$Packet = '';
									if (! isset($Field->catalog))
										$Packet .= "\xFB";
									else
										$Packet .= PackLength(strlen($Field->catalog)) . $Field->catalog;
									if (! isset($Field->db))
										$Packet .= "\xFB";
									else
										$Packet .= PackLength(strlen($Field->db)) . $Field->db;
									if (! isset($Field->table))
										$Packet .= "\xFB";
									else
										$Packet .= PackLength(strlen($Field->table)) . $Field->table;
									if (! isset($Field->org_table))
										$Packet .= "\xFB";
									else
										$Packet .= PackLength(strlen($Field->org_table)) . $Field->org_table;
									if (! isset($Field->name))
										$Packet .= "\xFB";
									else
										$Packet .= PackLength(strlen($Field->name)) . $Field->name;
									if (! isset($Field->org_name))
										$Packet .= "\xFB";
									else
										$Packet .= PackLength(strlen($Field->org_name)) . $Field->org_name;
									$Packet .= "\x0A";
									$Packet .= pack('v', $Field->charsetnr);
									$Packet .= pack('V', $Field->length);
									$Packet .= pack('C', $Field->type);
									$Packet .= pack('v', $Field->flags);
									$Packet .= pack('C', $Field->decimals);
									SendPacket($Packet);
								}
								$Packet = '';
								$Packet .= "\xFE";
								$Packet .= pack('v', mysqli_warning_count($mysqli));
								if ($MorePackets || mysqli_more_results($mysqli))
									$Packet .= pack('v', 0x0008); // Server Status
								else
									$Packet .= pack('v', 0x0000); // Server Status
								SendPacket($Packet);

								while ($Row = mysqli_fetch_array($result, MYSQL_NUM)) {
									$Packet = '';
									$Lengths = mysqli_fetch_lengths($result);
									for ($i = 0; $i < mysqli_num_fields($result); $i++)
										if (! isset($Row[$i]))
											$Packet .= "\xFB";
										else
											$Packet .= PackLength($Lengths[$i]) . $Row[$i];
									SendPacket($Packet);
								}
								$Packet = '';
								$Packet .= "\xFE";
								$Packet .= pack('v', mysqli_warning_count($mysqli));
								if ($MorePackets || mysqli_more_results($mysqli))
									$Packet .= pack('v', 0x0008); // Server Status
								else
									$Packet .= pack('v', 0x0000); // Server Status
								SendPacket($Packet);
								FlushPackets();

								mysqli_free_result($result);
							}

						} while (mysqli_next_result($mysqli));
					}
				}
			}
		}

		mysqli_close($mysqli);

	} else if (extension_loaded('mysql')) { /************************************/

		if (version_compare(phpversion(), '4.3.0') < 0)
			$mysql = mysql_connect($_SESSION['host'] . ':' . $_SESSION['port'], $_SESSION['user'], $_SESSION['password']);
		else
			$mysql = mysql_connect($_SESSION['host'] . ':' . $_SESSION['port'], $_SESSION['user'], $_SESSION['password'], true, $_SESSION['client_flag'] & 0x0125);
		if ($mysql && ! mysql_errno($mysql) && $_SESSION['database'])
			mysql_select_db($_SESSION['database'], $mysql);

		if ($mysql && ! mysql_errno($mysql) && $_SESSION['charset'] && version_compare(ereg_replace("-.*$", "", mysql_get_server_info($mysql)), '4.1.1') >= 0)
			if ((version_compare(phpversion(), '5.2.3') < 0) || version_compare(ereg_replace("-.*$", "", mysql_get_server_info($mysql)), '5.0.7'))
				mysql_query('SET NAMES ' . $_SESSION['charset'] . ';', $mysql);
			else
				mysql_set_charset($_SESSION['charset'], $mysql);

		if (! $mysql) {
			$Packet = "\xFF";
			$Packet .= pack('v', mysql_errno());
			$Packet .= mysql_error() . "\x00";
			SendPacket($Packet);
		} else if (mysql_errno($mysql)) {
			$Packet = "\xFF";
			$Packet .= pack('v', mysql_errno($mysql));
			$Packet .= mysql_error($mysql) . "\x00";
			SendPacket($Packet);
		} else if ($Connect) {
			if (version_compare(ereg_replace("-.*$", "", mysql_get_server_info($mysql)), '4.1.1') < 0) {
				$result = mysql_query("SHOW VARIABLES LIKE 'character_set';", $mysql);
				if ($Row = mysql_fetch_array($result))
					$_SESSION['charset'] = $Row['Value'];
				mysql_free_result($result);
			} else if ((version_compare(phpversion(), '5.2.3') < 0) || version_compare(ereg_replace("-.*$", "", mysql_get_server_info($mysql)), '5.0.7')) {
				$result = mysql_query("SHOW VARIABLES LIKE 'character_set_client';", $mysql);
				if ($Row = mysql_fetch_array($result))
					$_SESSION['charset'] = $Row['Value'];
				mysql_free_result($result);
			} else
				$_SESSION['charset'] = mysql_client_encoding($mysql);
			SetCharsetNr($mysql);

			$Packet = '';
			$Packet .= pack('C', 10); // Protocol
			$Packet .= mysql_get_server_info($mysql) . "\x00";
			$Packet .= pack('V', 0); // Thread Id
			$Packet .= "00000000\x00"; // Salt
			if (function_exists('gzcompress'))
				$Packet .= pack('v', 0x422C); // Server Capabilities
			else
				$Packet .= pack('v', 0x420C); // Server Capabilities
			$Packet .= pack('C', $_SESSION['charsetnr']);
			$Packet .= pack('v', 0x0000); // Server Status
			$Packet .= pack('a13', 1); // unused
			SendPacket($Packet);

			$PacketNr++;

			$Packet = '';
			$Packet .= pack('C', 0);
			$Packet .= PackLength(0); // Affected Rows
			$Packet .= PackLength(0); // Insert Id
			$Packet .= pack('v', 0x0000); // Server Status
			$Packet .= pack('v', 0x0000); // Warning Count
			SendPacket($Packet);

			FlushPackets();
		} else {
			while (ReceivePacket($Packet, $MorePackets)) {
				if (substr($Packet, 0, 1) == "\x01") { // COM_QUIT
					session_destroy();
				} else if (substr($Packet, 0, 1) == "\x03") { // COM_QUERY
					$Query = substr($Packet, 1);
					$result = mysql_unbuffered_query($Query, $mysql);

					if (mysql_errno($mysql)) {
						$Packet = "\xFF";
						$Packet .= pack('v', mysql_errno($mysql));
						$Packet .= mysql_error($mysql) . "\x00";
						SendPacket($Packet);
						FlushPackets();
						break;
					} else if (! mysql_num_fields($result)) {
						$Packet = "\x00";
						$Packet .= PackLength(mysql_affected_rows($mysql));
						$Packet .= PackLength(mysql_insert_id($mysql));
						if ($MorePackets)
							$Packet .= pack('v', 0x0008); // Server Status
						else
							$Packet .= pack('v', 0x0000); // Server Status
						$Packet .= pack('v', 0); // WarningCount
						if ((version_compare(phpversion(), '4.3.0') >= 0) && mysql_info($mysql))
							$Packet .= PackLength(strlen(mysql_info($mysql))) . mysql_info($mysql);
						SendPacket($Packet);
						FlushPackets();

						if (eregi("^USE[| |\t|\n|\r]", $Query))
							$_SESSION['database'] = eregi_replace("[|`|\"| *;|;|\t|\n|\r]", "", eregi_replace("^USE[| |\t|\n|\r]*", "", $Query));
						else if (eregi("^SET[| |\t|\n|\r][| |\t|\n|\r]*NAMES[| |\t|\n|\r]", $Query)) {
							$_SESSION['charset'] = eregi_replace("[|`|\"| *;|;|\t|\n|\r]", "", eregi_replace("^NAMES[| |\t|\n|\r]*", "", eregi_replace("^SET[| |\t|\n|\r]*", "", $Query)));
							SetCharsetNr($mysql);
						}
					} else {
						$Packet = PackLength(mysql_num_fields($result));
						SendPacket($Packet);

						for ($i = 0; $i < mysql_num_fields($result); $i++) {
							$Field = mysql_fetch_field($result, $i);

							$Flags = 0;
							$Length = mysql_field_len($result, $i) * $_SESSION['MBCLen'];
							$MaxLength = max($Length, $Field->max_length);
							switch ($Field->type) {
								case 'int':
									     if ($MaxLength <  3)     $FieldType =   1;
									else if ($MaxLength <  5)     $FieldType =   2;
									else if ($MaxLength <  7)     $FieldType =   9;
									else if ($MaxLength < 10)     $FieldType =   3;
									else                          $FieldType =   8; break;
								case 'unknown':
								case 'real':
									     if ($MaxLength < 13)     $FieldType =   4;
									else                          $FieldType =   5; break;
								case 'null':                    $FieldType =   6; break;
								case 'timestamp':               $FieldType =   7; break;
								case 'date':                    $FieldType =  10; break;
								case 'time':                    $FieldType =  11; break;
								case 'datetime':                $FieldType =  12; break;
								case 'year':                    $FieldType =  13; break;
								case 'blob': {
									$Flags |= 0x00010;
									     if ($Length <= 0xFF    ) $FieldType = 249;
									else if ($Length <= 0xFFFF  ) $FieldType = 252;
									else if ($Length <= 0xFFFFFF) $FieldType = 250;
									else                          $FieldType = 251;
								}                                                 break;
								case 'string':                  $FieldType = 254; break;
								case 'geometry':                $FieldType = 255; break;
								default:                        $FieldType = 100;
							}
							foreach (explode(" ", trim(mysql_field_flags($result, $i))) as $Flag)
								switch ($Flag) {
									case 'not_null':       $Flags |= 0x0001; break;
									case 'primary_key':    $Flags |= 0x0002; break;
									case 'unique_key':     $Flags |= 0x0004; break;
									case 'multiple_key':   $Flags |= 0x0008; break;
									case 'blob':           $Flags |= 0x0010; break;
									case 'unsigned':       $Flags |= 0x0020; break;
									case 'zerofill':       $Flags |= 0x0040; break;
									case 'binary':         $Flags |= 0x0080; break;
									case 'enum':           $Flags |= 0x0100; break;
									case 'auto_increment': $Flags |= 0x0200; break;
									case 'timestamp':      $Flags |= 0x0400; break;
								}
							if (($FieldType == 4) || ($FieldType == 5)) {
								while (($Row = mysql_fetch_array($result, MYSQL_NUM)) && ! isset($Row[$i])) ;
								if (isset($Row))
									$Decimals = StrLen($Row[$i]) - StrPos($Row[$i], '.') - 1;
								mysql_data_seek($result, 0);
							}

							$Packet = '';
							$Packet .= "\xFB"; // catalog
							if (! isset($Field->db))
								$Packet .= "\xFB";
							else
								$Packet .= pack('C', strlen($Field->db)) . $Field->db;
							if (! isset($Field->table))
								$Packet .= "\xFB";
							else
								$Packet .= PackLength(strlen($Field->table)) . $Field->table;
							if (! isset($Field->org_table))
								$Packet .= "\xFB";
							else
								$Packet .= PackLength(strlen($Field->org_table)) . $Field->org_table;
							if (! isset($Field->name))
								$Packet .= "\xFB";
							else
								$Packet .= PackLength(strlen($Field->name)) . $Field->name;
							if (! isset($Field->org_name))
								$Packet .= "\xFB";
							else
								$Packet .= PackLength(strlen($Field->org_name)) . $Field->org_name;
							$Packet .= "\x0A";
							if (in_array($FieldType, array(247, 248, 249, 250, 251, 252, 253, 254)) && ! ($Flags & 0x80))
								$Packet .= pack('v', $_SESSION['charsetnr']);
							else
								$Packet .= pack('v', 0); // CharsetNr
							$Packet .= pack('V', $Length);
							$Packet .= pack('C', $FieldType);
							$Packet .= pack('v', $Flags);
							$Packet .= pack('C', $Decimals);
							SendPacket($Packet);
						}
						$Packet = '';
						$Packet .= "\xFE";
						$Packet .= pack('v', 0); // WarningCount
						if ($MorePackets)
							$Packet .= pack('v', 0x0008); // Server Status
						else
							$Packet .= pack('v', 0x0000); // Server Status
						SendPacket($Packet);

						while ($Row = mysql_fetch_array($result, MYSQL_NUM)) {
							$Packet = '';
							$Lengths = mysql_fetch_lengths($result);
							for ($i = 0; $i < mysql_num_fields($result); $i++)
								if (! isset($Row[$i]))
									$Packet .= "\xFB";
								else
									$Packet .= PackLength($Lengths[$i]) . $Row[$i];
							SendPacket($Packet);
						}

						mysql_free_result($result);

						$Packet = '';
						$Packet .= "\xFE";
						$Packet .= pack('v', 0); // WarningCount
						if ($MorePackets)
							$Packet .= pack('v', 0x0008); // Server Status
						else
							$Packet .= pack('v', 0x0000); // Server Status
						SendPacket($Packet);
						FlushPackets();
					}
				}
			}
		}

		mysql_close($mysql);

	} else {

		$Packet = "\xFF";
		$Packet .= pack('v', 2200);
		$Packet .= "HTTP Tunnel: No MySQL support\x00";
		SendPacket($Packet);
		FlushPackets();
		exit(2200);

	}

	if ($Connect)
		$_SESSION['compress'] = ($_SESSION['client_flag'] & 0x0020) && function_exists('gzcompress');
	exit(0);
?>
