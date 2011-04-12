<?php
	header("Content-Type: text/xml");
	include('./requetes_bin.php');
	
	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
	echo "<list>";
	
	$req = new myRequetes();
	
	$action = (isset($_POST["action"])) ? $_POST["action"] : NULL;
	$name = (isset($_POST["name"])) ? '"'.$_POST["name"].'"' : NULL;
	switch($action) {
		case "master":
			if($name) list($pk_m, $nb_div) = $req->get_PLY_Master($name);
			if($pk_m) echo "<item pk=\"$pk_m\" nb_div=\"$nb_div\" />";
			break;
		case "div":
			if($name) list($pk_div, $nb_vert, $nb_face, $lod) = $req->get_PLY_Subdiv($name);
			for($i=0; $i < count($pk_div); $i++) {
				echo "<item pk=\"$pk_div[$i]\" vert=\"$nb_vert[$i]\" face=\"$nb_face[$i]\" lod=\"$lod[$i]\" />";
			}
			break;
		case "vertex":
			if($name) {
				list($color, $pos) = $req->get_PLY_Vert_Attr($name);
				$faces = $req->get_PLY_Face_Attr($name);
			}
			echo "<item>"; 
			
			$nb_face = count($faces);
			$nb_array = 100;
			$max = $nb_face/$nb_array;
			
			
			$start = 0;
			$end = $max < $nb_face ? $max : $nb_face;
		
			$f_s = "";
			$p_s = "";
			$c_s = "";
			for($i=0;$i<$nb_array;$i++) {
				$equiv = array();
				$f_s .= "<face f=\"";
				$p_s .= "<pos p=\"";
				$c_s .= "<color c=\"";
				for($j=$start;$j<$end;$j++) {
					for($k=0;$k<3;$k++) {
						$tmp = unpack("i", $faces[$j][$k]);
						$num = $tmp[1];
						//echo "num ".$num;
						if(!array_key_exists($num, $equiv)) {
							$equiv[$num] = count($equiv);
							//echo $num." saved in ".$equiv[$num]." </br>";
							for($l=0;$l<3;$l++) {
								$tmp = unpack("f", $pos[$num][$l]);
								$p_s .= $tmp[1]." ";
								$tmp = unpack("f", $color[$num][$l]);
								$c_s .= $tmp[1]." ";
							}
						}
						//else echo $num." already registered</br>";
						$f_s .= $equiv[$num]." ";	
					}
					$f_s .= "-1 ";
				}
				//echo "End of array</br></br>";
				$c_s .= "\" />";
				$p_s .= "\" />";
				$f_s .= "\" />";
				$start = $end;
				$face_left = $nb_face - $end;
				$end = $max<$face_left ? $end+$max : $end+$face_left;
			}
			echo $f_s;
			echo $p_s;
			echo $c_s;
			
			echo "</item>";
			break;
		default:
			break;
	}
	echo "</list>";
?>