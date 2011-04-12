<?php
/** 
 * G�rer les requ�tes vers la base de donn�es 
 *  
 * <p>G�rer les requ�tes vers la base de donn�es</p> 
 *  
 * @name myRequetes 
 * @author C2RMF  
 * @link  
 * @copyright C2RMF 2011 
 * @version 1.0.0  
 * @package myRequetes.class
 */ 
  
 class myRequetes { 
  
	/*~*~*~*~*~*~*~*~*~*~*/ 
	/*  1. propri�t�s    */ 
	/*~*~*~*~*~*~*~*~*~*~*/ 
	
	/** 
	* @var (String) 
	* @desc Nom du serveur MySQL 
	*/ 
	private $serveur;
	
	/** 
	* @var (String) 
	* @desc Nom de la base 
	*/ 
	private $base;
	
	/** 
	* @var (String) 
	* @desc Nom d'utilisateur 
	*/ 
	private $login;
	
	/** 
	* @var (String) 
	* @desc Mot de passe 
	*/ 
	private $pwd;
	
	/** 
	* @var (String) 
	* @desc requete SQL 
	*/ 
	private $requete;
     
	/*~*~*~*~*~*~*~*~*~*~*/ 
	/*  2. m�thodes      */ 
	/*~*~*~*~*~*~*~*~*~*~*/ 
	/** 
	* Constructeur 
	*  
	* <p>cr�ation de l'instance de la classe</p> 
	*  
	* @name myRequetes::__construct() 
	* @return void  
	*/ 
	public function __construct() { 
		//on r�cup�re les param�tres de connexion
		include('./connexion.php');
		$this->serveur = $serveur;
		$this->base = $nom_base.'_bin';
		$this->login = $login;
		$this->pwd = $pwd;
	}  
     
	/*~*~*~*~*~*~*~*~*~*~*~*~*~*/ 
	/*  2.1 m�thodes priv�es   */ 
	/*~*~*~*~*~*~*~*~*~*~*~*~*~*/ 
	/**
	* Instancie la connexion � la base de donn�es
	*
	* <p> Instancie la connexion � la base de donn�es </p>
	*
	* @name myRequetes::connect()
	* @return void
	*/
	private function connect() {
		//connexion � MySql
		mysql_connect($this->serveur, $this->login, $this->pwd) or die ('ERREUR '.mysql_error());
		//selection de la base de donn�es
		mysql_select_db($this->base) or die ('ERREUR '.mysql_error());
	}
	/**
	* Termine la connexion � la base de donn�es
	*
	* <p> Termine la connexion � la base de donn�es </p>
	*
	* @name myRequetes::disconnect()
	* @return void
	*/
	private function disconnect() {
		mysql_close() or die ('ERREUR '.mysql_error());
	}

	/*~*~*~*~*~*~*~*~*~*~*~*~*~*/ 
	/*  2.1 m�thodes publiques */ 
	/*~*~*~*~*~*~*~*~*~*~*~*~*~*/ 
	/**
	* Ajout d'une nouvelle r�f�rence de PLY
	*
	* <p> Ajout d'une nouvelle r�f�rence de PLY</p>
	*
	* @name myRequetes::ajout_PLY_Master()
	* @param $nb_div
	* @param $name
	* @return $pk
	*/
	public function ajout_PLY_Master($nb_div, $name=NULL) {
		//connexion � la base
		$this->connect();
		//cr�ation de la requ�te
		if(!$name) $requete = "INSERT INTO CS_PLY_MASTER 
		(PLY_MAS_NB_DIV) VALUES ($nb_div)";
		else $requete = "INSERT INTO CS_PLY_MASTER 
		(PLY_MAS_NB_DIV, PLY_MAS_NAME) VALUES ($nb_div,'$name')";
		//echo $requete;
		
		//envoie de la requ�te
		$result = mysql_query($requete) or die ('Erreur '.$requete.' '.mysql_error());
		//r�cup�ration de la PK
		$pk = mysql_insert_id();
		//d�connexion de la base
		$this->disconnect();
		//on renvoit la PK
		return $pk;
	}
	
	/**
	* R�cup�ration d'une r�f�rence de PLY
	*
	* <p> R�cup�ration d'une r�f�rence de PLY</p>
	*
	* @name myRequetes::get_PLY_Master()
	* @param $name
	* @return array($pk, $nb_div)
	*/
	public function get_PLY_Master($name) {
		//connexion � la base
		$this->connect();
		//cr�ation de la requ�te
		$requete = "SELECT PK_PLY_MAS, PLY_MAS_NB_DIV 
		FROM CS_PLY_MASTER 
		WHERE PLY_MAS_NAME = ".$name;
		//echo $requete;
		
		//envoie de la requ�te
		$result = mysql_query($requete) or die ('Erreur '.$requete.' '.mysql_error());
		//r�cup�ration de la PK et du nombre de divisions
		$data = mysql_fetch_row($result);
		//d�connexion de la base
		$this->disconnect();
		//on renvoit l'array
		return $data;
	}
	
	/**
	* Ajout d'une nouvelle subdivision
	*
	* <p> Ajout d'une nouvelle subdivision</p>
	*
	* @name myRequetes::ajout_PLY_Subdiv()
	* @param $master_key
	* @param $nb_vert
	* @param $nb_face
	* @param $lod
	* @return $pk
	*/
        public function ajout_PLY_Subdiv($master_key, $nb_vert, $nb_face, $lod) {
		//connexion � la base
		$this->connect();
		//cr�ation de la requ�te
		$requete = "INSERT INTO CS_PLY_SUBDIVISION
		(FK_PLY_MAS, PLY_DIV_NB_VERT, PLY_DIV_NB_FACE, PLY_DIV_LOD) VALUES
		($master_key, $nb_vert, $nb_face, $lod)";
		//echo requete;
		
		//envoie de la requ�te
		$result = mysql_query($requete) or die ('Erreur '.$requete.' '.mysql_error());
		//r�cup�ration de la PK
		$pk = mysql_insert_id();
		//d�connexion de la base
		$this->disconnect();
		//on renvoit la PK
		return $pk;
	}
	
	/**
	* R�cup�ration d'une subdiv de PLY
	*
	* <p> R�cup�ration d'une subdiv de PLY</p>
	*
	* @name myRequetes::get_PLY_Subdiv()
	* @param $fk_ply_mas
	* @return array($pk, $nb_vert, $div_lod)
	*/
	public function get_PLY_Subdiv($fk_ply_mas) {
		//connexion � la base
		$this->connect();
		//cr�ation de la requ�te
		$requete = "SELECT PK_PLY_DIV, PLY_DIV_NB_VERT, 
		PLY_DIV_NB_FACE, PLY_DIV_LOD 
		FROM CS_PLY_SUBDIVISION 
		WHERE FK_PLY_MAS = ".$fk_ply_mas."
		ORDER BY PLY_DIV_LOD";
		//echo $requete;
		
		//envoie de la requ�te
		$result = mysql_query($requete) or die ('Erreur '.$requete.' '.mysql_error());
		//r�cup�ration de la PK et du nombre de divisions
		$pk = array();
		$nb_vert = array();
		$nb_face = array();
		$lod = array();
		while($ligne = mysql_fetch_row($result)) {
		//$data = mysql_fetch_array($result);
			$pk[] = $ligne[0];
			$nb_vert[] = $ligne[1];
			$nb_face[] = $ligne[2];
			$lod[] = $ligne[3];
		}
		$data = array($pk, $nb_vert, $nb_face, $lod);
		//d�connexion de la base
		$this->disconnect();
		//on renvoit l'array
		return $data;
	}
	
	/**
	* Ajout d'un nouveau vertice
	*
	* <p> Ajout d'un nouveau vertice</p>
	*
	* @name myRequetes::ajout_PLY_Vertice()
	* @param $div_key
	* @param $vert_index
	* @return $pk
	*/
        public function ajout_PLY_Vertice($div_key, $vert_index) {
		//connexion � la base
		$this->connect();
		//cr�ation de la requ�te
		$requete = "INSERT INTO CS_PLY_VERTICES
		(FK_PLY_DIV, PLY_VERT_IDX) VALUES
		($div_key, $vert_index)";
		//echo requete;
		
		//envoie de la requ�te
		$result = mysql_query($requete) or die ('Erreur '.$requete.' '.mysql_error());
		//r�cup�ration de la PK
		$pk = mysql_insert_id();
		//d�connexion de la base
		$this->disconnect();
		//on renvoit la PK
		return $pk;
	}
	
	/**
	* Ajout de nouveaux attributs de vertice
	*
	* <p> Ajout d'attributs de vertice</p>
	*
	* @name myRequetes::ajout_PLY_Vert_Attr()
	* @param $fk_vert
	* @param $vert_index
	* @param $r
	* @param $g
	* @param $b
	* @param $x
	* @param $y
	* @param $z
	* @return void
	*/
        public function ajout_PLY_Vert_Attr($fk_vert, $vert_index, $r, $g, $b, $x, $y, $z) {
		//connexion � la base
		$this->connect();
		//cr�ation de la requ�te
		$requete = 'INSERT INTO CS_PLY_VERTEX_ATTR
		(FK_PLY_VERT, PLY_VERT_IDX, PLY_VERT_ATTR_R, PLY_VERT_ATTR_G, 
		PLY_VERT_ATTR_B, PLY_VERT_ATTR_X, PLY_VERT_ATTR_Y, PLY_VERT_ATTR_Z) 
		VALUES (\''.$fk_vert.'\', \''.$vert_index.'\', \''.$r.'\', \''.$g.'\', \''.$b.'\', \''.$x.'\', \''.$y.'\', \''.$z.'\')';
		//echo requete;
		
		//envoie de la requ�te
		$result = mysql_query($requete) or die ('Erreur '.$requete.' '.mysql_error());
		//d�connexion de la base
		$this->disconnect();
	}
	
	/**
	* R�cup�ration des vertices d'une subdiv
	*
	* <p> R�cup�ration des vertices d'une subdiv</p>
	*
	* @name myRequetes::get_PLY_Vert_Attr()
	* @param $fk_ply_div
	* @return array(array($r, $g, $b), array($x, $y, $z))
	*/
	public function get_PLY_Vert_Attr($fk_ply_div) {
		//connexion � la base
		$this->connect();
		//cr�ation de la requ�te
		$requete = "SELECT PLY_VERT_ATTR_R, PLY_VERT_ATTR_G, PLY_VERT_ATTR_B,
		PLY_VERT_ATTR_X, PLY_VERT_ATTR_Y, PLY_VERT_ATTR_Z
		FROM CS_PLY_VERTEX_ATTR vertex_attr, CS_PLY_VERTICES vertices
		WHERE vertices.FK_PLY_DIV = $fk_ply_div
		AND vertices.PK_PLY_VERT = vertex_attr.FK_PLY_VERT";
		//echo $requete;
		
		//envoie de la requ�te
		$result = mysql_query($requete) or die ('Erreur '.$requete.' '.mysql_error());
		//r�cup�ration des donn�es
		$color = array();
		$pos = array();
		while($ligne = mysql_fetch_row($result)) {
			$color[] = array($ligne[0], $ligne[1], $ligne[2]);
			$pos[] = array($ligne[3], $ligne[4], $ligne[5]);
		}
		$data = array($color, $pos);
		//d�connexion de la base
		mysql_free_result($result);
		$this->disconnect();
		//on renvoit l'array
		return $data;
	}
	
	/**
	* Ajout d'une nouvelle face
	*
	* <p> Ajout d'une nouvelle face</p>
	*
	* @name myRequetes::ajout_PLY_Face()
	* @param $div_key
	* @return $pk
	*/
        public function ajout_PLY_Face($div_key) {
		//connexion � la base
		$this->connect();
		//cr�ation de la requ�te
		$requete = "INSERT INTO CS_PLY_FACES
		(FK_PLY_DIV) VALUES ($div_key)";
		//echo requete;
		
		//envoie de la requ�te
		$result = mysql_query($requete) or die ('Erreur '.$requete.' '.mysql_error());
		//r�cup�ration de la PK
		$pk = mysql_insert_id();
		//d�connexion de la base
		$this->disconnect();
		//on renvoit la PK
		return $pk;
	}
	
	/**
	* Ajout de nouveaux attributs d'une face
	*
	* <p> Ajout de nouveaux attributs d'une face</p>
	*
	* @name myRequetes::ajout_PLY_Face_Attr()
	* @param $face_key
	* @param $nb
	* @param $att
	* @return void
	*/
        public function ajout_PLY_Face_Attr($face_key, $nb, $attr) {
		//connexion � la base
		$this->connect();
		//cr�ation de la requ�te
		$r1 = "";
		$r2 = "";
		for($i=0; $i<$nb; $i++) {
			$r1 .= "PLY_FACE_ATTR_".$i;
			$r2 .= '\''.$attr[$i].'\'';
			if($i != $nb-1) {
				$r1.= ", ";
				$r2 .=", ";
			}
		}
		$requete = "INSERT INTO CS_PLY_FACE_ATTR
		(FK_PLY_FACE_IDX, PLY_FACE_ATTR_NB, ".$r1.") VALUES
		($face_key, $nb, ".$r2.")";
		//echo $requete;
		//envoie de la requ�te
		$result = mysql_query($requete) or die ('Erreur '.$requete.' '.mysql_error());
		//d�connexion de la base
		$this->disconnect();
	}
	
	/**
	* R�cup�ration des attributs d'une face
	*
	* <p> R�cup�ration des attributs d'une face</p>
	*
	* @name myRequetes::get_PLY_Face_Attr()
	* @param $fk_ply_div
	* @return array($nb,$attr_0, ..., $attr_nb)
	*/
	public function get_PLY_Face_Attr($fk_ply_div) {
		//connexion � la base
		$this->connect();
		//cr�ation de la requ�te
		$nb = 3;
		
		$a="";
		for($i=0;$i<$nb;$i++) {
			$a.="PLY_FACE_ATTR_".$i;
			if($i<$nb-1) $a.=", ";
		}
		$requete = "SELECT $a 
		FROM CS_PLY_FACE_ATTR face_attr, CS_PLY_FACES faces
		WHERE faces.FK_PLY_DIV = $fk_ply_div
		AND faces.PK_PLY_FACE_IDX = face_attr.FK_PLY_FACE_IDX";
		//envoie de la requ�te
		$result = mysql_query($requete) or die ('Erreur '.$requete.' '.mysql_error());
		//r�cup�ration des donn�es
		$data = array();
		while($ligne = mysql_fetch_row($result)) {
			$data[] = array($ligne[0], $ligne[1], $ligne[2]);
		}
		//d�connexion de la base
		mysql_free_result($result);
		$this->disconnect();
		//on renvoit l'array
		return $data;
	}
	
	public function get_Viewer_Option($option) {
		//connexion � la base
		$this->connect();
		//cr�ation de la requ�te
		$requete = "SELECT CS_OPT_VALUE
		FROM CS_VIEWER_OPTIONS
		WHERE CS_OPT_NAME = '$option'";
		//envoie de la requ�te
		$result = mysql_query($requete) or die ('Erreur '.$requete.' '.mysql_error());
		//r�cup�ration des donn�es
		$data = mysql_fetch_row($result);
		//d�connexion de la base
		$this->disconnect();
		//on renvoit la donn�e
		return $data[0];
	}
	
	public function save_Annotation($fk, $x, $y, $z, $annotation) {
		$this->connect();
		$annotation = htmlentities($annotation);
		$requete = "INSERT INTO CS_ANNOTATION 
		(FK_PLY_MASTER, PLY_X, PLY_Y, PLY_Z, ANNOTATION) VALUES
		($fk, $x, $y, $z, \"$annotation\")";
		//echo $requete;
		$result = mysql_query($requete) or die ('Erreur '.$requete.' '.mysql_error());
		$this->disconnect();
	}
	
	public function get_Annotation($fk) {
		$this->connect();
		$requete = "SELECT PLY_X, PLY_Y, PLY_Z, ANNOTATION, DATE_AJOUT
		FROM CS_ANNOTATION 
		WHERE FK_PLY_MASTER = $fk
		ORDER BY DATE_AJOUT";
		//echo $requete;
		$result = mysql_query($requete) or die ('Erreur '.$requete.' '.mysql_error());
		
		//r�cup�ration des donn�es
		$x = array();
		$y = array();
		$z = array();
		$annotation = array();
		$date = array();
		while($ligne = mysql_fetch_row($result)) {
			$x[] = $ligne[0];
			$y[] = $ligne[1];
			$z[] = $ligne[2];
			$annotation[] = $ligne[3];
			$date[] = $ligne[4];
		}
		$data = array($x, $y, $z, $annotation, $date);
		$this->disconnect();
		return $data;
	}
	
	public function get_Image($pk_m) {
		$this->connect();
		$requete = "SELECT PLY_MAS_IMG
		FROM CS_PLY_MASTER
		WHERE PK_PLY_MAS = $pk_m";
		$result = mysql_query($requete) or die ('Erreur '.$requete.' '.mysql_error());
		$data = mysql_fetch_row($result);
		$this->disconnect();
		return $data[0];
	}
	
	/** 
	* Destructeur 
	*  
	* <p>Destruction de l'instance de classe</p> 
	*  
	* @name Nom de la classe::__destruct() 
	* @param nom du premier param�tre 
	* @param nom du second param�tre 
	* @param etc ... 
	* @return void 
	*/ 
	public function __destruct() { 

	} 
 } 

?>