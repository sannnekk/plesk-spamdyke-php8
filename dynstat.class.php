<?php
class dynStat
{
	public $cirdia		= 300; //Kreis Diagonale
	public $shadow		= 20; //Kreis SchattengrÃ¶sse
	public $girdia_h	= 410; //HÃ¶he des Balkendiagramm Bildes
	public $gir_w		= 50; //Breite der einzelnen Balken
	public $girshadow	= 5; //Balken SchattengrÃ¶sse
	public $fontsrc		= "verdana.ttf"; //Schriftart

	//Private Elemente - werden im __construct initialisiert
	private $stat_		= array();
	private $total		= "";
	private $col1		= "";
	private $col2		= "";
	private $col3		= "";

	function __construct($stat_array)
	{
		$this->stat_ 	= $stat_array;

		//Total Teile zählen
		$this->total 	= array_sum($this->stat_);

		//Farben generieren
		foreach ($this->stat_ as $key => $stat) {
			$this->col1[$key] = rand(10, 250);
			$this->col2[$key] = rand(150, 250);
			$this->col3[$key] = rand(10, 250);
		}
	}

	//Tabelle erstellen
	function createTable()
	{
		$table = '<table border="0" cellpadding="0" cellspacing="0" style="padding:0px;">';
		foreach ($this->stat_ as $key => $stat) {
			//Tabelle generieren
			if (!is_numeric($stat)) {
				$table .= '
				<tr style="font-weight:bold";>
					<td style="border-bottom:#000000 1px solid; padding-right:50px">' . $key . '</td>
					<td style="border-bottom:#000000 1px solid;" align="right">' . $stat . '&nbsp;</td>
					<td style="border-bottom:#000000 1px solid;" align="right">%</td>
				</tr>';
			} else {
				$table .= '
				<tr onmouseover="this.bgColor=\'#CCCCCC\';" onmouseout="this.bgColor=\'\';">
					<td style="padding-right:50px">' . $key . '</td>
					<td align="right">' . $stat . '&nbsp;</td>
					<td align="right" bgcolor="#' . dechex($this->col1[$key]) . dechex($this->col2[$key]) . dechex($this->col3[$key]) . '">' . round(100 / $this->total * $stat) . '%</td>
				</tr>';
			}
		}
		$table .= '
		<tr>
			<td style="border-top:#000000 1px solid; padding-right:50px; font-weight:bold;">Total:</td>
			<td style="border-top:#000000 1px solid;" align="right">' . $this->total . '&nbsp;</td>
			<td style="border-top:#000000 1px solid;" align="right">&nbsp;</td>
		</tr>
		</table>';
		return $table;
	}

	//Kuchendiagramm erstellen
	function createCake($path)
	{
		//Variablen für Kuchendiagramm
		$cirdia_h = round(($this->cirdia - $this->shadow / 2) / 100 * 90 / 2);
		$cirdia_w = round($this->cirdia / 2);
		//Image erstellen
		$img = imagecreatetruecolor($this->cirdia, $this->cirdia / 100 * 90);
		//B/W bestimmen
		$white = imagecolorallocate($img, 255, 255, 255);
		$black = imagecolorallocate($img, 0, 0, 0);
		//Image ausfüllen
		imagefill($img, 0, 0, $white);

		for ($j = $this->shadow; $j >= 0; $j--) {
			$end = 0;
			foreach ($this->stat_ as $key => $stat) {
				if (is_numeric($stat)) {
					//Kreis zeichnen

					//Farbe des Teilstückes generieren
					$circleColor = imagecolorallocate($img, $this->col1[$key], $this->col2[$key], $this->col3[$key]);

					//Anfang- und Endwinkel berechnen
					$deg = ($this->total <> 0) ? round(360 / $this->total * $stat) : 0;
					$begin = $end;
					$end += $deg;

					if ($begin <> $end) {
						//Kreis mit 3D Effekt zeichnen
						imagefilledarc($img, $cirdia_w, $cirdia_h + $j, $this->cirdia, $this->cirdia * 2 / 3, $begin, $end, $circleColor, IMG_ARC_PIE);
						if ($j == $this->shadow || $j == 0) {
							imagefilledarc($img, $cirdia_w, $cirdia_h + $j, $this->cirdia, $this->cirdia * 2 / 3, $begin, $end, $black, IMG_ARC_NOFILL | IMG_ARC_EDGED);
						}
					}
				}
			}
		}
		imagegif($img, $path, 100);
		imagedestroy($img);
	}

	//Balkendiagramm erstellen
	function createBar($path)
	{
		//Variablen fÃŒr Balkendiagramm
		$anz = is_numeric(reset($this->stat_)) ? count($this->stat_) : count($this->stat_) - 1;
		$girdia_w = ($this->gir_w + $this->girshadow) * $anz * 2;
		//Image erstellen
		$imggir = imagecreatetruecolor($girdia_w, $this->girdia_h + 1);
		//B/W bestimmen
		$white = imagecolorallocate($imggir, 255, 255, 255);
		$black = imagecolorallocate($imggir, 0, 0, 0);
		//Image ausfÃŒllen
		imagefill($imggir, 0, 0, $white);

		for ($j = $this->girshadow; $j >= 0; $j--) {
			$girpt = 0;
			foreach ($this->stat_ as $key => $stat) {
				if (is_numeric($stat)) {
					//Farbe des TeilstÃŒckes generieren
					$circleColor = imagecolorallocate($imggir, $this->col1[$key], $this->col2[$key], $this->col3[$key]);

					//Balkendiagramm zeichnen
					//HÃ¶he und Breite berechnen
					$heighest = max($this->stat_);
					$girdia_h2 = $this->girdia_h - 15; //FÃŒr Beschriftung unten
					$gir_h = ($girdia_h2 - 50 - $this->girshadow) / $heighest * $stat; //HÃ¶he des Bildes - 50 fÃŒr Beschriftung - SchattengrÃ¶sse

					//Balken mit 3D Effekt zeichnen
					//Image Variable | X1 | Y1 | X2 | Y2 | Color
					imagefilledrectangle($imggir, $girpt + $j, $girdia_h2 - $j, $girpt + $this->gir_w + $j, $girdia_h2 - $gir_h - $j, $circleColor);
					if ($j == $this->girshadow || $j == 0) {
						imagerectangle($imggir, $girpt + $j, $girdia_h2 - $j, $girpt + $this->gir_w + $j, $girdia_h2 - $gir_h - $j, $black);
					}
					if ($j == 0) {
						//Linien nachzeichnen
						imageline($imggir, $girpt + $this->gir_w, $girdia_h2, $girpt + $this->gir_w + $this->girshadow, $girdia_h2 - $this->girshadow, $black);
						imageline($imggir, $girpt + $this->gir_w, $girdia_h2 - $gir_h, $girpt + $this->gir_w + $this->girshadow, $girdia_h2 - $this->girshadow - $gir_h, $black);
						imageline($imggir, $girpt, $girdia_h2 - $gir_h, $girpt + $this->girshadow, $girdia_h2 - $this->girshadow - $gir_h, $black);
						//Text einfÃŒgen
						imagettftext($imggir, 6, 0, $girpt, $this->girdia_h - 1, $black, $this->fontsrc, $key);
						imagettftext($imggir, 10, 45, $girpt + 5 + $this->gir_w / 2, $girdia_h2 - $gir_h - $this->girshadow - 2, $black, $this->fontsrc, $stat);
					}
					$girpt += ($this->gir_w + $this->girshadow) * 2;
				}
			}
		}
		imagegif($imggir, $path, 100);
		imagedestroy($imggir);
	}
}
