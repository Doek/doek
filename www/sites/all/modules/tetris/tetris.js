// ***************************************************
// Tetris
//
//  Modified for Drupal by Glenn Linde Dec 2008
//  Original javascript by Joan Alba Maldonado (granvino@granvino.com).
//  http://sourceforge.net/projects/tetrissimus/
//  This software is supplied without warranty under the GNU Public licence agreement.
//
// ***************************************************

var isExplorer=navigator.appName.indexOf("Microsoft")!=-1;

//Variable que activa el mapa modo debug:
//var tetris_show_map_debug = false;
var tetris_scale=0.8;
//Variable que guarda el primer evento del keydo, por razones de compatibilidad:
var primer_evento = "";
  
//Se declara la matrix para guardar el mapa:
var map_matrix = new Array();

//El number de columns del mapa:
var tetris_number_columns = 12;
//El number de lines del mapa:
var tetris_number_lines = 22;


//Se realiza un bucle para guardar el mapa en la matrix:
for (x=0; x<tetris_number_columns*tetris_number_lines; x++)
		map_matrix[x]="0";
		
		
//Variable que guarda el number of the piece actual:
var tetris_number_piece = 0;
//Variable que guarda el number of the piece following:
var tetris_number_piece_following = 0;

//matrix que contiene la coleccion de pieces, con su ancho, alto y color:
var tetris_piece = new Array();

//Varialbe para saber si una piece se ha raised verticalmente al ser rotated:
var al_rotate_se_ha_raised = false;

//Variable donde se guardara el Interval del movimiento of the piece cayendo:
var movimiento_piece = setInterval("", 1);

//Variable donde se guardara el Timeout que hara que el message del centro of the pantalla se oculte:
var tetris_hide_message = setTimeout("", 1);

//Variable donde se guarda el number de pieces:
var tetris_number_pieces = 0;

//Variable que indica si se ha acabado el game o todavia no:
var tetris_game_over = false; //Todavia no se ha acabado el game.

//Variable que define las lines necesarias para cambiar de level:
var lines_necesarias = 6;

//Variable que cuenta cuantas lines se han realizado en el level actual:
var lines_level_actual = 0;

//Variable donde se guarda la puntuacion:
var puntuacion = 0;

//Variable donde se guada el level:
var tetris_level = 1;

//Contador de leveles, que cuando llega a 10 se sube el displacement of thes pieces (se desplazan mas espcio hacia abajo, para hacerlo mas dificil):
var contador_leveles_displacement = 0;

//Variable que impide el Game Over, cuando ya ha ocurrido:
var prevent_tetris_game_over = false;

//matrix vacia que se utilizara para cuando se llame a tetris_show_map:
var guardar_map_previous = new Array();

var tetris_dragging=false;
var tetris_dragging_start_y=false;
var tetris_height;
var tetris_width;

var tetris_piece1_img="/modules/tetris/img/orange.png";
var tetris_piece2_img="/modules/tetris/img/red.png";
var tetris_piece3_img="/modules/tetris/img/blue.png";
var tetris_piece4_img="/modules/tetris/img/purple.png";
var tetris_piece5_img="/modules/tetris/img/lightblue.png";
var tetris_piece6_img="/modules/tetris/img/green.png";
var tetris_piece7_img="/modules/tetris/img/yellow.png";

var tetris_piece1_color="orange";//"#aaffdd";
var tetris_piece2_color="red";//"#ffffdd";
var tetris_piece3_color="blue";//#ddaaff";
var tetris_piece4_color="purple";//#ffaadd";
var tetris_piece5_color="cyan";//#ffddff";
var tetris_piece6_color="green";//#aaddff";
var tetris_piece7_color="yellow";//#ffddaa";

var tetris_start_message="Left, right arrow to move. Up down arrow to rotate.";
var tetris_game_begins_message="The game begins";
var tetris_gameover_message="Game Over";

var tetris_keyboard=true;
var tetris_mouse=false;

var tetris_leftkey=90;  // Z
var tetris_rightkey=88; // X
var tetris_upkey=('J').charCodeAt(0);
var tetris_downkey=('M').charCodeAt(0);

var tetris_initial_game_speed=1500;
var tetris_speed_increase_per_level=25;
var tetris_background;
var tetris_bestscore;
//var tetris_moveinterval;
var tetris_mousemoveok=true;

function is_x(val)
{
	if (val&&val.length>0&&val.substring(0,1)=="X")
		return true;
	else
		return false;
}

//function que crea las pieces, con su ancho, alto y color:
function tetris_create_pieces()
{
	//piece 1:
	tetris_piece[1] = new Array();
	tetris_piece[1]["form"] = "1" +
						"1" +
						"1" +
						"1";
	tetris_piece[1]["width"] = 1;
	tetris_piece[1]["height"] = 4;
	tetris_piece[1]["color"] = tetris_piece1_color;//"#aaffdd";
	tetris_piece[1]["img"] = tetris_piece1_img;//"orange.png";

	//piece 2:
	tetris_piece[2] = new Array();
	tetris_piece[2]["form"] = "22" +
						"22";
	tetris_piece[2]["width"] = 2;
	tetris_piece[2]["height"] = 2;
	tetris_piece[2]["color"] = tetris_piece2_color;//"#ffffdd";
	tetris_piece[2]["img"] = tetris_piece2_img;//"red.png";

	//piece 3:
	tetris_piece[3] = new Array();
	tetris_piece[3]["form"] = "33" +
						"03" +
						"03";
	tetris_piece[3]["width"] = 2;
	tetris_piece[3]["height"] = 3;
	tetris_piece[3]["color"] = tetris_piece3_color;//"#ddaaff";
	tetris_piece[3]["img"] = tetris_piece3_img;//"blue.png";

	//piece 4:
	tetris_piece[4] = new Array();
	tetris_piece[4]["form"] = "44" +
						"40" +
						"40";
	tetris_piece[4]["width"] = 2;
	tetris_piece[4]["height"] = 3;
	tetris_piece[4]["color"] = tetris_piece4_color;//"#ffaadd";
	tetris_piece[4]["img"] = tetris_piece4_img;//"purple.png";

	//piece 5:
	tetris_piece[5] = new Array();
	tetris_piece[5]["form"] = "055" +
						"550";
	tetris_piece[5]["width"] = 3;
	tetris_piece[5]["height"] = 2;
	tetris_piece[5]["color"] = tetris_piece5_color;//"#ffddff";
	tetris_piece[5]["img"] = tetris_piece5_img;//"lightblue.png";

	//piece 6:
	tetris_piece[6] = new Array();
	tetris_piece[6]["form"] = "660" +
						"066";
	tetris_piece[6]["width"] = 3;
	tetris_piece[6]["height"] = 2;
	tetris_piece[6]["color"] = tetris_piece6_color;//"#aaddff";
	tetris_piece[6]["img"] = tetris_piece6_img;//"green.png";

	//piece 7:
	tetris_piece[7] = new Array();
	tetris_piece[7]["form"] = "070" +
						"777";
	tetris_piece[7]["width"] = 3;
	tetris_piece[7]["height"] = 2;
	tetris_piece[7]["color"] = tetris_piece7_color;//"#ffddaa";
	tetris_piece[7]["img"] = tetris_piece7_img;//"yellow.png";

	//Se guarda el number de pieces:
	tetris_number_pieces = tetris_piece.length - 1;

}
                
                
//function que inicia el game:
function tetris_initialize_game()
{

	//Variable que contiene el ancho de cada celda (tile o panel):
	tetris_panel_width= parseInt(20*tetris_scale) ;
	//Varialbe que contiene el alto de cada celda (tile o panel):
	tetris_panel_height = parseInt(20*tetris_scale);

	//speed de caida of thes pieces (entre menor, mas rapido):
	tetris_speed_initial = parseInt(tetris_initial_game_speed*tetris_scale); //speed initial.
	tetris_speed = tetris_speed_initial; //speed que ira incrementandose (al decrementar la variable).

	//Pizels de displacement en la caida of thes pieces:
	displacement_initial = tetris_panel_height * 1; //displacement initial.
	tetris_displacement = displacement_initial; //displacement que ira incrementandose.

	// Set up the keyboard handler
	if (tetris_keyboard)
	{
		document.onkeydown= tetris_press_key;//(event, 'onkeypress');
		document.onKeyPress=tetris_press_key;//(event, 'onkeydown');
	}
	
	//alert((50*tetris_scale)+"px;");
	tetris_width=parseInt(tetris_number_columns*tetris_panel_width);//;//-1)*tetris_number_lines;
	tetris_height=parseInt(tetris_number_lines*tetris_panel_width);//-1)*tetris_number_lines;
	//document.getElementById("piece_following").style.width=tetris_width+"px";//+"px;";
	document.getElementById("piece_following").style.height=parseInt(40+tetris_panel_height*5)+"px";//+"px;";
	document.getElementById("piece_following").innerHTML="Next Piece:";
	document.getElementById("tetris_zone_game").style.height=tetris_height+"px";//parseInt(theheight*tetris_scale);//px;
	document.getElementById("tetris_zone_game").style.width=tetris_width+"px";//px;
	if (tetris_mouse)
	{
		document.getElementById("tetris_map").onmousedown=tetris_click	;				
		document.getElementById("tetris_map").onmouseup=tetris_mouseup;
		document.getElementById("tetris_map").ondblclick=tetris_dblclick	;
        document.getElementById("tetris_map").onmousemove=tetris_mousemove;		
	}	
	document.getElementById("tetris_map").style.height=tetris_height+"px";//px;
	document.getElementById("tetris_map").style.width=tetris_width+"px";//px;	
    if (!isExplorer)
		document.getElementById("tetris_map").style.position='absolute';
		
	document.getElementById("tetris_message").style.top=parseInt(tetris_height/2)+"px";
	document.getElementById("tetris_message").style.width=tetris_width+"px";
	//document.getElementById("tetris_message").style.left=parseInt(11*tetris_scale)+"px";
	tetris_show_message(tetris_start_message,true);
	
	document.getElementById("tetris_state").style.width=tetris_width+"px";//parseInt(244*tetris_scale)+"px";
	document.getElementById("tetris_state").innerHTML="";
	
	//alert(parseInt(thewidth*tetris_scale+2));
	document.getElementById("following_table").style.width=(tetris_width+2)+"px";//parseInt(thewidth*tetris_scale+2)+"px";
	
	if (tetris_background)
	{
	  document.getElementById('tetris_background_image').src=tetris_background;
	  document.getElementById('tetris_background_image').style.width=tetris_width+"px";
	  document.getElementById('tetris_background_image').style.height=tetris_height+"px";
	  document.getElementById('tetris_background_image').style.opacity=tetris_background_transparency;
	  document.getElementById('tetris_background_image').style.MozOpacity=tetris_background_transparency;
	  document.getElementById('tetris_background_image').style.filter='alpha(opacity='+parseInt(100*tetris_background_transparency)+')';//.alpha.opacity=parseInt(100*pong_background_transparency);
	}	
	//Se setea que aun no se ha acabado el game:
	tetris_game_over = false;

	//Desbloquea el prevent game over:
	prevent_tetris_game_over = false;

	//Se crean las pieces:
	tetris_create_pieces();
	
	//Se setea la speed a la initial:
	tetris_speed = tetris_speed_initial;
	//Se setea el displacement al initial:
	tetris_displacement = displacement_initial;
	
	//Se deifne el contador de leveles que incrementa el displacement, a 0:
	contador_leveles_displacement = 0;
	
	//Se definen las lines del level actual a 0;
	lines_level_actual = 0;
	
	//Se define el scoreboard de puntuacio a 0:
	puntuacion = 0;
	
	//Se define el level a 1:
	tetris_level = 1;

	//Se define el number de piece actual a 0 (ninguno):
	var tetris_number_piece = 0;
	//Se define el number de piece following a 0 (ninguno):
	var tetris_number_piece_following = 0;

	//Vaciar mapa (recorre la matrix, cambiando todo por 0):
	for (x=0; x<map_matrix.length; x++) {
	  map_matrix[x] = "0";
	}

}

function tetris_start_game()
{
	//Se recoge el mapa en una matrix, para calcular las diferencias con este y el posterior:
	map_matrix_previous = guardar_map_previous;
	
	//Se muestra el mapa:
	tetris_show_map(map_matrix, map_matrix_previous);

	//Se actualiza el scoreboard:
	update_scoreboard();
	
	//Se muestra el message de "Comienza el game":
	tetris_show_message(tetris_game_begins_message);//"The game begins");
	
	//Sacamos una piece:
	extract_piece();
}

//function that the matrix of the map updates:
function tetris_update_map(tetris_number_piece)
{
	//If the zero has been sent like piece, the fact is that there is not pieces:
	if (tetris_number_piece == 0) {
		//It covers the matrix, changing everything what is neither a 0 nor the X for 0:
		for (x=0; x<map_matrix.length; x++) {
			//Si no es 0 ni X, lo cambia a 0:
			if (map_matrix[x] != "0" && !is_x(map_matrix[x])) {
			  map_matrix[x] = "0";
			}
	    }
	 }
	//Pero si se ha enviado otro number, mayor que cero:
	else if (tetris_number_piece > 0)
	 {
		//Se borra la piece del mapa:
		tetris_update_map(0);
		
		//Se calcula en que position of the matrix comienza la piece:
		matrix_position_x = tetris_number_columns - (parseInt(document.getElementById("tetris_piece").style.left) / tetris_panel_width);
		matrix_position_y = parseInt(document.getElementById("tetris_piece").style.top) / tetris_panel_height + 1;
		//Esta es la position initial (la clave of the matrix) donde comienza la piece:
		matrix_position_initial = (tetris_number_columns * matrix_position_y) - matrix_position_x;
		//Se actualiza la matrix pintando la piece en ella, segun la position:
//                        for (x=0; x<map_matrix.length; x++)
		for (x=matrix_position_initial; x<matrix_position_initial+tetris_piece[tetris_number_piece]["form"].length; x++)
		 {
			//Si estamos en el indice donde comienza la piece:
			if (x == matrix_position_initial)
			 {
				//El contador de columns:
				contador_columns = 0;
				//La variable que se suma para saltar una fila:
				saltar_fila = 0;
				for (y=0; y<tetris_piece[tetris_number_piece]["form"].length; y++)
				 {
					//Se toma como position of the matrix la position initial y se le suma la variable que hace saltar lines:
					position_matrix_actual = x + saltar_fila;
					//Si la position actual of the piece no es un cero, se graba en la matrix:
					if (tetris_piece[tetris_number_piece]["form"].substring(y, y+1) != "0") {map_matrix[position_matrix_actual] = tetris_piece[tetris_number_piece]["form"].substring(y, y+1); } //Se pinta la piece.
					//Se incrementa el contador de columns:
					contador_columns++;
					//Se incrementa la variable para saltar lines:
					saltar_fila++;
					//Si el contador de columns es mayor al ancho of the piece, se salta una fila:
					if (contador_columns >= tetris_piece[tetris_number_piece]["width"]) { contador_columns = 0; saltar_fila += tetris_number_columns - tetris_piece[tetris_number_piece]["width"]; }
				 }
			 }
		 }
	 }
}                

//function que muestra el mapa en modo debug:
function tetris_show_map(map_matrix, map_matrix_previous)
 {

	//Si se ha enviado la misma matrix actual que la previous, sale of the function (no hay nada que update):
	if (map_matrix == map_matrix_previous) { return; }

	//Se setea el contador de columns a cero:
	var columns_contador = 0;
	//Se setea el contador de lines a cero:
	var lines_contador = 0;

	//Variable que guardara el color a utilizar en cada celda (tile o panel):
	var color_panel;

	//Se borra el mapa:
//                    document.getElementById("mapa").innerHTML = "";
//                    if (tetris_show_map_debug) { document.getElementById("map_debug").innerHTML = ""; } //Si esta en modo debug, tambien se borra el mapa debug.
	//Se crean las variables que guardaran la informacion del mapa:
	var map_bucle_temp = "";
	var map_debug_bucle_temp = "";

	//Se realiza un bucle para show el contenido of the matrix en el espacio de debug:
	 for (x=0; x<map_matrix.length; x++)
	  {
		 //Se calcula que color utilizar, segun el caracter de celda (tile o panel):
		 if (is_x(map_matrix[x])) {
		   color_panel = "#555555";
		   if (map_matrix[x].length>=2)
		   {
			  piecepos=map_matrix[x].substring(1);
			  img_panel=tetris_piece[piecepos]["img"];
		   }
		   else
			img_panel=null;
		 } //Color gris oscuro (caracter X, pieces ya colocadas).
		 else if (map_matrix[x] != 0) {

		   color_panel = tetris_piece[map_matrix[x]]["color"];
		   img_panel = tetris_piece[map_matrix[x]]["img"];
		 } //Color of the piece segun su number.
		
		 //Calcular la position of the celda (tile o panel):
		 panel_x = columns_contador * tetris_panel_width; //position horizontal.
		 panel_y = lines_contador * tetris_panel_height; //position vertical.

		 //Se muestra la imagen en la celda, siempre que no este vacia (0) y que haya habido un cambio desof the previous:
		 if (map_matrix[x] != 0 && map_matrix[x] != map_matrix_previous[x]) {
		   if (img_panel)
				map_bucle_temp += '<div style="top:'+panel_y+'px; left:'+panel_x+'px; width:'+tetris_panel_width+'px; height:'+tetris_panel_height+'px; position:absolute; padding:0px;  "><img id="P'+parseInt(Math.random()*9999)+'" width='+tetris_panel_width+' height='+tetris_panel_height+' src="'+img_panel+'"/></div>';
		   else
				map_bucle_temp += '<div id="P'+parseInt(Math.random()*9999)+'" style="background:'+color_panel+'; top:'+panel_y+'px; left:'+panel_x+'px; width:'+tetris_panel_width+'px; height:'+tetris_panel_height+'px; position:absolute; padding:0px;"></div>';
				
		 }

		 //Si esta activado el mapa debug, se escribe en el:
		 //if (tetris_show_map_debug) 
			//map_debug_bucle_temp += map_matrix[x];

		 //Se incrementa el contador de columns:
		 columns_contador++;

		 //Si se alcanza el number maximo de columns, se baja una fila y se setea otra vez el contador a cero y se incrementa el contador de lines (si esta el mapa en modo debug, se baja una line en este):
		 if (columns_contador == tetris_number_columns) {
		   columns_contador = 0;
		   lines_contador++;
		   //if (tetris_show_map_debug) {
			//  map_debug_bucle_temp += "<br>";
		   //}
		 }
	  }
	 
	 //Se vuelcan las variables en el mapa:
	 document.getElementById("tetris_map").innerHTML = map_bucle_temp;
	 //if (tetris_show_map_debug) {
	//	document.getElementById("map_debug").innerHTML = map_debug_bucle_temp;
	 //} //Si esta en modo debug, tambien se vuelca el mapa en modo debug.
 }

//function that a piece extracts to the stage:
function extract_piece()
{
	//Si ya ha habido game over, se sale of the function:
	if (prevent_tetris_game_over) 
	  return;
	
	//Si aun no seh a escogido ninguna piece, se escoge una aleatoriamente:
	if (tetris_number_piece == 0) {
	  tetris_number_piece = choose_piece();
	}
	//Si antes ya se habia escogido alguna, se setea la actual como la following:
	else {
	  tetris_number_piece = tetris_number_piece_following;
	}
	//Ponemos el number of the piece following, escogido aleatoriamente, en una variable:
	tetris_number_piece_following = choose_piece();
	
	//Se muestra la piece following:
	show_piece_following(tetris_number_piece_following);

	//Setear conforme todavia no se ha raised verticalmente la piece al ser rotated:
	al_rotate_se_ha_raised = false;

	//Borrar esto:
	//tetris_number_piece = 1;

	//Devolver las pieces a su state initial:
	tetris_create_pieces();

	//Se recoge el mapa en una matrix, para calcular las diferencias con este y el posterior:
	map_matrix_previous = guardar_map_previous;

	//Calcular ancho y alto of the piece, segun el number enviado:
	piece_width = tetris_piece[tetris_number_piece]["width"];
	piece_height = tetris_piece[tetris_number_piece]["height"];
	
	//Se situa horizontalmente en el centro:
	//document.getElementById("tetris_piece").style.left = parseInt( (tetris_number_columns * tetris_panel_width) / 2 - piece_width * tetris_panel_width) + "px";
	document.getElementById("tetris_piece").style.left = "0px";
	//Se situa verticalmente arriba:
	document.getElementById("tetris_piece").style.top = "0px";

	mover_piece(0, 0);

	//Se actualiza el mapa:
	//tetris_update_map(tetris_number_piece);tetris_show_map_debug(map_matrix);
	
	//Se muestra el mapa:
	tetris_show_map(map_matrix, map_matrix_previous);


	//Elimina el movimiento of the piece cayendo, por si aun existia:
	clearInterval(movimiento_piece);

	//Crea el movimiento of the piece cayendo:
	movimiento_piece = setInterval("map_matrix_previous = guardar_map_previous; mover_piece('mantener', parseInt(document.getElementById('tetris_piece').style.top) + tetris_displacement); tetris_show_map(map_matrix, map_matrix_previous);", tetris_speed);
}

//function que elige una piece aleatoriamente:
function choose_piece()
{
	//Variable que escoge un number aleatorio entre 1 y 8:
	var number_aleatorio = parseInt(Math.random() * tetris_number_pieces) + 1;
	
	//Retorna el number escogido of the piece:
	return number_aleatorio;
 }

//function que mueve la piece segun las coordenadas enviadas:
function mover_piece(position_x, position_y)
 {
	//Si ya ha habido game over, se sale of the function:
	if (prevent_tetris_game_over) { return; }
	
	//Si se ha enviado mantener position horizontal, no mover la piece (conservar la X de esta):
	if (position_x == "mantener") {
	  position_x = parseInt(document.getElementById("tetris_piece").style.left);
	}
	
	//Variable para saber si la piece ha tocado fondo:
	var ha_tocado_fondo = false;
	//Si la piece esta en el limite de abajo, situa la piece lo maximo posible hacia abajo y se setea la variable ha_tocado_fondo a true:
	if (position_y > tetris_panel_height * tetris_number_lines - tetris_piece[tetris_number_piece]["height"] * tetris_panel_height) { position_y = tetris_panel_height * tetris_number_lines - tetris_piece[tetris_number_piece]["height"] * tetris_panel_height; ha_tocado_fondo = true; }
	//Si la piece esta en el limite izquierdo, situa la piece lo maximo posible hacia la left:
	if (position_x <= 0) { position_x = 0; }
	//Si la piece esta en el limite derecho, situa la piece lo maximo posible hacia la right:
	if (position_x + tetris_panel_width * tetris_piece[tetris_number_piece]["width"] > tetris_panel_width * tetris_number_columns) { position_x = tetris_panel_width * tetris_number_columns - tetris_piece[tetris_number_piece]["width"] * tetris_panel_width; }
	
	//Variables que impiden el movimiento horizontal si a la left o a la right of the piece hay otra ya colocada:
	prevent_movimiento_derecho = false;
	prevent_movimiento_izquierdo = false;

	//Realizar un bucle en la matrix:
	for (x=0; x<map_matrix.length; x++)
	 {
		//Si la position actual of the matrix contiene un caracter que no es 0 ni X, contiene una piece:
		if (map_matrix[x] != "0" && !is_x(map_matrix[x]))
		{
			
			//Si existe un caracter a la right del actual (no excede el tamaño of the matrix):
			if (x + 1 <= map_matrix.length)
			 {
				//Si el caracter que hay a la right es una X, prevent movimiento horizontal:
				if (is_x(map_matrix[x+1])) { prevent_movimiento_derecho = true; } //Se impide movimiento horizontal hacia la right.
			 }
			
			//Si existe un caracter a la left del actual (no es menor a 0):
			if (x - 1 >= 0)
			 {
				//Si el caracter que hay a la left es una X, prevent movimiento horizontal:
				if (is_x(map_matrix[x-1] )) { prevent_movimiento_izquierdo = true; } //Se impide movimiento horizontal hacia la left.
			 }
		 }
	 }
	
	//Si la position horizontal es hacia la left y no esta impedida o es a la right y tampoco esta impedida, mueve la piece horizontalmente:
	position_x_actual = parseInt(document.getElementById("tetris_piece").style.left);
	if (position_x_actual > position_x && !prevent_movimiento_izquierdo || position_x_actual < position_x && !prevent_movimiento_derecho)
	 {
		document.getElementById("tetris_piece").style.left = position_x + "px"; //Se situa la piece en la position horizontal dada.
	 }
	
	//Se situa la piece en la position vertical dada:                    
	document.getElementById("tetris_piece").style.top = position_y + "px";
	
	//Se actualiza el mapa con la new position of the piece:
	tetris_update_map(tetris_number_piece);

	//Calcular collision:
	var ha_collisionado = calculate_collision();

	//Si la position vertical of the piece la situa abajo del todo, se convierte todo el mapa que no sea 0 a X:
	if (ha_tocado_fondo || ha_collisionado) {
		//Elimina el movimiento of the piece cayendo, por si existia previousmente:
		//clearInterval(movimiento_piece);

		//Da 1 punto:
		puntuacion += 1;

		//Se setea todo lo que haya en el mapa como X (ya colocado):
		for (x=0; x<map_matrix.length; x++) {
		  if (map_matrix[x] != "0") {
			//if (map_matrix[x]>0)
			if (map_matrix[x].length>=1&&map_matrix[x].substring(0,1)!="X") {
				map_matrix[x] = "X"+map_matrix[x];
				tetris_dragging=null;
			}
			//else
			//	map_matrix[x] = "X";
		  }
		}

		//Se hace bajar la piece un panel, para que quede bien al pause el game:
		document.getElementById("tetris_piece").style.top = parseInt(document.getElementById("tetris_piece").style.top) + tetris_panel_height + "px";
		tetris_update_map();
		tetris_show_map(map_matrix, map_matrix_previous);
		
		//Calculamos si se ha llegado arriba del todo y se acaba el game:
		hay_tetris_game_over = calculate_tetris_game_over();

		//Se saca una piece:
		if (!hay_tetris_game_over) { extract_piece(); }
	 }
	
	//Calcular si se ha hecho line:
	calculate_line();
	
	//Se muestra el mapa:
//                    tetris_show_map(map_matrix);
//                    tetris_show_map(map_matrix, map_matrix_previous);
	
 }

//function que calcula si una piece ha chocado con otra (poniendose encima):
function calculate_collision()
 {
	//Variable que calcula si ha collisionado o no:
	var ha_collisionado = false;
	
	//Realizar bucle en la matrix, buscar caracteres que no sean 0 ni X y calcular si justo debajo tienen una X:
	for (x=0; x<map_matrix.length; x++)
    {
		//Si la position actual of the matrix contiene un caracter que no es 0 ni X, contiene una piece:
		if (map_matrix[x] != "0" && !is_x(map_matrix[x]))
	    {
			
			//Si existe un caracter debajo del actual (no excede el tamaño of the matrix):
			if (x + tetris_number_columns <= map_matrix.length)
			{
				//Si el caracter que hay debajo es una X, ha collisionado:
				if (is_x(map_matrix[x+tetris_number_columns])) {
				   ha_collisionado = true; break;
				} //Ha habido collision y sale del bucle.
			}
		 }
	 }

	//Si ha collisionado:
	if (ha_collisionado)
		return true;
	else 
	  return false;
	  
 }

//function que calcula si ha habido line:
function calculate_line()
 {
	//Calcular si ha habido line y calcular cuantas:
	var columns_contador = 0;
	if (!number_de_lines) { var number_de_lines = 0; }
	var ha_habido_line = false;
	var hay_line = true;
	for (var x=0; x<map_matrix.length; x++)
	 {
		columns_contador++;

		if (!is_x(map_matrix[x] )) {
		  hay_line = false;
		}

		if (columns_contador == tetris_number_columns)
		 {
			if (hay_line)
			 {
				//Cambiar las X of the line por 0:
				for (var y=x-tetris_number_columns+1; y<=x; y++) {
					map_matrix[y] = "0";
			    }
				
				//Bajar lines:
				se_ha_bajado_line = hacer_caer_cuadros();
				
				//Volver a llamar a la function recursivamente si se ha bajado alguna line, para ver si al bajar las pieces colocadas ha habido mas lines:
//                                if (se_ha_bajado_line) { calculate_line(); }
				//calculate_line();
				

				//Incrementar el contador de lines:
				number_de_lines++;

				ha_habido_line = true;


				  
			 }
			columns_contador = 0;
			hay_line = true;
		 }
		//tetris_update_map(tetris_number_piece);
	 }
 
	//Si ha habido line, dar puntos segun cuantas lines ha haya habido:
	dar_puntos(number_de_lines);
	
 }

//function que hace caer los cuadros (X) cuando debajo no tienen nada (0):
function hacer_caer_cuadros(position_final)
{
	 //Todavia no se ha bajado ninguna line:
	 se_ha_bajado_line = false;
	
	 //Calcula si la line esta en el aire o no:
	 var esta_en_el_aire = true;

	 //Bucle que va de arriba a abajo, haciendo caer las pieces:
//                     for (z=map_matrix.length-tetris_number_columns; z>=tetris_number_columns+1; z-=tetris_number_columns)
	 for (z=map_matrix.length-tetris_number_columns; z>=tetris_number_columns; z-=tetris_number_columns)
	 {
		//Calcula si la line esta en el aire o no:
		esta_en_el_aire = true;
		
		//Comprueba que la line este en el aire:
		for (k=z; k<=z+tetris_number_columns-1; k++) {
		  if (k > map_matrix.length || map_matrix[k] != "0" || z > tetris_number_columns && !is_x(map_matrix[k-tetris_number_columns]) && map_matrix[k-tetris_number_columns] != "0") {
			esta_en_el_aire = false;
		  }
		}
		
		//Si esta en el aire, se baja la line:
		if (esta_en_el_aire)
		 {
		   for (k=z; k<=z+tetris_number_columns-1; k++)
			 {
				  //Se replica la piece en el cuadro de abajo:
				  map_matrix[k] = map_matrix[k-tetris_number_columns];
				  //Se borra la piece en el cuadro actual:
				  map_matrix[k-tetris_number_columns] = "0";
				  //Setear conforme se ha bajado una line:
				  se_ha_bajado_line = true;
				  
			 }
			//Se setea como que ya no estan en el aire:
			esta_en_el_aire = false; 
		 }
	   
	  }

	if (se_ha_bajado_line) { return true; }
	else { return false; }
 }

//function que rota la piece:
function rotate_piece(direction)
{
	//Se inviert el ancho y el alto of the piece:
	var width_original = tetris_piece[tetris_number_piece]["width"]; //El width previous.
	var height_original = tetris_piece[tetris_number_piece]["height"]; //El height previous.
	
	tetris_piece[tetris_number_piece]["width"] = height_original; //El nuevo width es el height.
	tetris_piece[tetris_number_piece]["height"] = width_original; //El number height es el width previous.

	//Se setea la matrix donde se guardara la new piece:
	var new_piece_matrix = new Array(); //Se declara la matrix.
	
	//Variables que serviran para realizar los bucles:
	var contador1 = 0;
	var contador2 = height_original;
	var contador3 = width_original;
   
	//Si se ha de rotar la piece a la right:
	if (direction == "right")
	 {
		//Se realiza un bucle por la piece actual, y se guarda rotated a la right en la new piece:
		for (x=0; x<tetris_piece[tetris_number_piece]["form"].length; x++) {
			//Formula que yo mismo descubri, despues de mucho pensar x):
			var formula = (contador1 * height_original) + (contador2  - 1);
			new_piece_matrix[formula] = tetris_piece[tetris_number_piece]["form"].substring(x,x+1);
			contador1++;
			contador3--;
			if (contador3 == 0) { 
				contador3 = width_original;
				contador1 = 0;
				contador2--;
			}
		 }
	 }
   
	//...O si se ha de rotar la piece hacia la left (o tres veces hacia la right):
	else if (direction == "left") {
		//A ringlet is realized by the current piece, and one keeps rotated the right in the piece of news piece:
		for (x=tetris_piece[tetris_number_piece]["form"].length; x>0; x--){
			//Formula que yo mismo descubri, despues de mucho pensar x):
			var formula = (contador1 * height_original) + (contador2  - 1);
			new_piece_matrix[formula] = tetris_piece[tetris_number_piece]["form"].substring(x-1,x);
			contador1++;
			contador3--;
			if (contador3 == 0) {
			  contador3 = width_original;
			  contador1 = 0;
			  contador2--;
			}
		 }
	 }

	//position vertical of the piece                     
	position_y = parseInt(document.getElementById("tetris_piece").style.top);
	
	//To calculate if after the piece to be rotated it is going to be on some Xth, then to move her to a nearby place (even in position horizontal - wide piece, position horizontal breadth piece, position vertical tall piece or in position vertical - tall piece) with 0.
	//To calculate if the piece is going to hit on having been rotated by some low or side piece:
	//* If there is pieces below, to raise the piece a picture.
	//* If there is pieces nearby, to move the piece nearby contradicted.
	//* If the piece of new position studied is not possible (there is the Xth in what it is going to occupy), not rotate the piece and go out of the function.
	
	//If the piece is going to be too much next to the low rim (below) or of some low piece, it is raised a little up:
	if (position_y + tetris_piece[tetris_number_piece]["height"] * tetris_panel_height >= tetris_number_lines * tetris_panel_height) {
		position_y -= parseInt(tetris_piece[tetris_number_piece]["height"] / 2 + 1) * tetris_panel_height;
		al_rotate_se_ha_raised = true;
	}
	//...y if not, and it has already been raised previousmente on having been rotated, one goes down again:
	else if (al_rotate_se_ha_raised) {
	  position_y += parseInt(tetris_piece[tetris_number_piece]["width"] / 2 + 1) * tetris_panel_height;
	}

	//Variable where one will keep the form of the new piece rotated:
	var new_piece = "";
	//A ringlet is realized to introduce what exists in the matrix in a variable of flat text:
	for (x = 0; x<new_piece_matrix.length; x++) {
		new_piece += new_piece_matrix[x];
    }
	// Setea the form of the piece current to the new piece that we have rotated:
	old_piece=tetris_piece[tetris_number_piece]["form"];
	var old_map_matrix=map_matrix.slice();
	
	tetris_piece[tetris_number_piece]["form"] = new_piece;
	// Stop problem where if you can rotate next to an existing piece and it will wedge itself into the piece
	tetris_update_map(tetris_number_piece);
	if (calculate_collision()){
		// put it back to the way it was before the rotation
		map_matrix=old_map_matrix.slice();
		tetris_piece[tetris_number_piece]["form"]=old_piece;
		tetris_piece[tetris_number_piece]["width"]= width_original;
		tetris_piece[tetris_number_piece]["height"]=height_original ;
		tetris_update_map(tetris_number_piece);
		tetris_show_map(map_matrix, map_matrix);
	}
	
	//Return  position vertical of the piece:
	return position_y;
}

//function que da puntos, segun un number de lines enviado:
function dar_puntos (number_lines)
 {
	//Variable donde se guardara el message a show, si es necesario:
	var message = "";
	
	//Dar puntos, segun las lines:
	if (number_lines == 4) { puntuacion += 400; message = "Tetris"; } //Se han hecho 4 lines (tetris).
	else if (number_lines == 3) { puntuacion += 300; message = "Triple"; } //Se han hecho 3 lines (triple).
	else if (number_lines == 2) { puntuacion += 200; message = "Double"; } //Se han hecho 2 lines (doble).
	else if (number_lines == 1) { puntuacion += 100; message = "Single"; } //Se ha hecho 1 line (simple).
						
	//show en medio of the pantalla cuantas lines se han hecho, siempre que se haya hecho alguna, y suma al contador de lines:
	if (message != "") {
	  tetris_show_message(message);
	  lines_level_actual++;
	}
	
	//Si elas lines del level actual alcanza o supera las necesarias para pasar de level, se llama a la function de pasar de level:
	if (lines_level_actual >= lines_necesarias)
	 {
		pasar_level();
	 }
	
	//update scoreboard:
	update_scoreboard();
 }
 
//function que pasa de level cada X lines:
function pasar_level()
 {
	//Si esta habiendo game over, salir of the function:
	if (prevent_tetris_game_over) 
	  return;
	
	//Calcular si el number de lines realizadas en el level actual es igual o supera a lines_necesarias, y entonces cambia de level:
	if (lines_level_actual >= lines_necesarias)
	 {
		//Se define el contador de lines de cada level a cero:
		lines_level_actual = 0;

		//Se incrementa el contador de leveles, que al llegar a 10 incrementa el displacement of the piece:
		contador_leveles_displacement++;

		//Se suma un level:
		tetris_level++;
		
		//Se sube la speed initial (ahora la piece tardara 50 milisegundos menos en caer hacia abajo en cada movimiento):
		if (tetris_speed_initial - tetris_scale*tetris_speed_increase_per_level >= 0) {
		   tetris_speed -= tetris_scale*tetris_speed_increase_per_level;
		}
		
		//Si el contador de leveles llega a 10, se sube el displacement of the piece (ahora la piece se desplazara mas en cada caida):
		if (contador_leveles_displacement >= 10 && displacement_initial <= tetris_panel_height * tetris_number_lines) {
		   tetris_displacement += tetris_panel_height;
		   contador_leveles_displacement = 0;
		}
		
		//Se dan 1000 puntos:
		puntuacion += 1000;
	 }

	//show en pantalla que se ha pasado de level:
	tetris_show_message("Welcome to level "+tetris_level);
	
	//update scoreboard:
	update_scoreboard();
 }

//function que actualiza el scoreboard:
function update_scoreboard()
{
	//Actualiza el scoreboard (barra de state):
	document.getElementById("tetris_state").innerHTML = "&nbsp; Level: "+tetris_level+" | Score: "+puntuacion;
}

//function que muestra la piece following:
function show_piece_following(tetris_number_piece_following)
{
	//Variable que contendra la piece following painted (los div):
	var piece_painted = "";
	//position of the piece en el cuadro de "piece following":
	position_y = 40;//tetris_panel_height+15; //position vertical initial.
	position_x = tetris_panel_width; //position horizontal initial.
	
	//Contador de columns, para saber cuando bajar la celda:
	var contador_columns = 0;

	//Se realiza un bucle hasta cumplir el number de celdas que tenga la piece:
	for (x=0; x<tetris_piece[tetris_number_piece_following]["form"].length; x++)
	{
		//Se coge el color of the piece:
		color_piece = tetris_piece[tetris_number_piece_following]["color"];
		img_piece = tetris_piece[tetris_number_piece_following]["img"];
		//Si la celda actual no esta vacia (0), se pinta (se crea un div con las positiones correspondientes):
		if (tetris_piece[tetris_number_piece_following]["form"].substring(x,x+1) != "0") {
		  if (img_piece)
			piece_painted += '<div style=" left:'+position_x+'px; top:'+position_y+'px; width:'+tetris_panel_width+'; height:'+tetris_panel_height+'; ifont-size:1px; position:absolute; iz-index:5001;"><img width='+tetris_panel_width+' height='+tetris_panel_height+' src="'+img_piece+'"/></div>';
		  else
			piece_painted += '<div style="background:'+color_piece+'; left:'+position_x+'; top:'+position_y+'; width:'+tetris_panel_width+'; height:'+tetris_panel_height+'; font-size:1px; position:absolute; iz-index:5001;"></div>';
		}
		//Se incrementa la position horizontal:
		position_x += tetris_panel_width;
		//Se incrementa una columna:
		contador_columns++;
		//Si se ha llegado al fin of thes columns, se baja una fila y se setea las columns a 0:
		if (contador_columns >= tetris_piece[tetris_number_piece_following]["width"]) {
		  contador_columns = 0;
		  position_y += tetris_panel_height; position_x = tetris_panel_width;
		}
	 }
	
	//Se muestra la ficha que hemos "pintado":
	document.getElementById("piece_following").innerHTML = "Next piece:"+piece_painted;
 }

//function que muestra un message en medio of the pantalla, durante un tiempo:
function tetris_show_message(message,donthide)
 {
	//Se borra el Timeout previous por si ya existia de antes:
	clearTimeout(tetris_hide_message);
	//Se pone el teto en el recuadro:
	document.getElementById("tetris_message").innerHTML = message;
	//Se hace visible el recuadro:
	document.getElementById("tetris_message").style.visibility = "visible";
	//Se esconde el recuadro a los 1500 milisegundos (un segundo y medio):
	if (!donthide)
	  tetris_hide_message = setTimeout("document.getElementById('tetris_message').style.visibility = 'hidden';", 1500);
 }                

//function que calcula si se ha llegado al tope of the pantalla, y si es asi da GameOver:
function calculate_tetris_game_over()
 {
	//Si ya se ha ejecutado game over, sale of the function:
	if (prevent_tetris_game_over) {
	  return;
	}
	//Variable que definira si se ha llegado arriba del todo o no:
	var has_gone_above = false;
	
	//Calcular si se ha llegado al fin del mapa, con un bucle:
	for (x=0; x<tetris_number_columns; x++) {
		//Si arriba del mapa hay otra cosa que no es un 0, se ha llegado arriba:
		if (map_matrix[x] != "0") { has_gone_above = true; }
	}
	
	//Si ha llegado arriba del todo, hace el game over y luego inicia otro game nuevo:
	if (has_gone_above) {
		//Setea el game over:
		tetris_game_over = true;
	
		//update scoreboard:
		update_scoreboard();
	
		//Se muestra el message:
		tetris_show_message(tetris_gameover_message,true);
	
		//prevent Game Over:
		prevent_tetris_game_over = true;

		if (document.getElementById('tetris_winform')&&
		   (!tetris_bestscore||tetris_bestscore==-1||puntuacion>tetris_bestscore)) {
			document.getElementById('tetris_win_message').value=tetris_gameover_message;
			document.getElementById('tetris_ticker').value=puntuacion;
			document.getElementById('tetris_winform').submit();
		}		
	 
		return true;
    } else
	  return false; //No ha habido game over.
	
 }

//function que recoge el mapa en la variable map_matrix_previous()
function guardar_map_previous()
 {
	var map_matrix_previous = new Array();
	for (x=0; x<map_matrix.length; x++)
	 {
		map_matrix_previous[x] = map_matrix[x];
	 }
	return map_matrix_previous;
 }

//function que captura la key pulsada y realiza la function necesaria:
function tetris_press_key(e, evento_actual)
 {
	//Si esta en pausa el game, se sale of the function:
	if (!movimiento_piece) { 
	  return;
	}

	//Se recoge el mapa en una matrix, para calcular las diferencias con este y el posterior:
	map_matrix_previous = guardar_map_previous;

	//Si el primer evento esta vacio, se le introduce como valor el evento actual (el que ha llamado a esta function):
	if (primer_evento == "") {
	  primer_evento = evento_actual;
	}
	//Si el primer evento no es igual al evento actual (el que ha llamado a esta function), se vacia el primer evento (para que a la proxima llamada entre en la function) y se sale of the function:
	if (primer_evento != evento_actual) {
	  primer_evento = ""; return;
	}

	//Capturamos la tacla pulsada, segun navegador:
	if (isExplorer)/*e.keyCode*/ {
	  e=window.event ;
	  var unicode = e.keyCode;
	}
	//else if (event.keyCode) { var unicode = event.keyCode; }
	else if (window.Event && e.which) {
	  var unicode = e.which;
	}
	else {
	  var unicode = 40;
	} //Si no existe, por defecto se utiliza la flecha hacia abajo.

	//Se obtiene la position actual of the piece:
	position_x = parseInt(document.getElementById("tetris_piece").style.left); //position horizontal.
	position_y = parseInt(document.getElementById("tetris_piece").style.top); //position vertical.

	//Si se pulsa la flecha hacia abajo, se suman 20 pixels verticales:
	if (unicode == tetris_downkey||unicode ==40) {
	  position_y += tetris_panel_height;//20*tetris_scale;
	}
	//...y si se pulsa la flecha hacia la right, se suman 20 pixels horizontales:
	else if (unicode == tetris_rightkey||unicode ==39) {
	  position_x += tetris_panel_width;//20*tetris_scale;
	}
	//...y si se pulsa la flecha hacia la left, se restan 20 pixels horizontales:
	else if (unicode == tetris_leftkey||unicode ==37) {
	  position_x -= tetris_panel_width;//20*tetris_scale;
	}
	//...y si se pulsa flecha arriba (38), control (17), intro (13) o . (190), se rota la piece hacia la right:
	else if (unicode== tetris_upkey || unicode == 38 || unicode == 17 || unicode == 13 || unicode == 190) {
	  position_y = rotate_piece("right");
	}
	//...y si se pulsa shift (16), espacio (32), 0 (96) o insert (45), se rota la piece hacia la left:
	else if (unicode == 16 || unicode == 32 || unicode == 96 || unicode == 45) {
	  position_y = rotate_piece("left");
	}

	
	//Se mueve la piece:
	mover_piece(position_x, position_y);

	//Se muestra el mapa:
	tetris_show_map(map_matrix, map_matrix_previous);
	
	// If not playing then give keyboard back
	if (movimiento_piece)
		return false;
 }


//function que pausa o reanuda el game:
function pause_reanudar_game()
 {
	//Si la piece no esta moviendose, se reanuda:
	if (!movimiento_piece) {
	  movimiento_piece = setInterval("map_matrix_previous = guardar_map_previous; mover_piece('mantener', parseInt(document.getElementById('tetris_piece').style.top) + tetris_displacement); tetris_show_map(map_matrix, map_matrix_previous);", tetris_speed);
	  tetris_show_message("Game resumed");
	  document.getElementById("tetris_pause").innerHTML = "&nbsp;&nbsp;&nbsp;[ Pause ]";
	  document.getElementById("tetris_pause").title = "Click here to pause game";
	}
	//...pero si ya esta moviendose, se pausa:
	else {
	  clearInterval(movimiento_piece);
	  movimiento_piece = false;
	  document.getElementById("tetris_message").innerHTML = "Game paused";
	  document.getElementById("tetris_message").style.visibility = "visible";
	  setTimeout('document.getElementById("tetris_message").innerHTML = "Game paused"; document.getElementById("tetris_message").style.visibility = "visible";', 1500);
	  document.getElementById("tetris_pause").innerHTML = "&nbsp;&nbsp;&nbsp;[ Resume ]";
	  document.getElementById("tetris_pause").title = "Click here to resume game";
	}
}


	function tetris_mousemove(e) {
		// can only move mouse every half second
	    if (!tetris_mousemoveok)
			return;
			
		if (!isExplorer)
		{
		    id=e.target.id;
			mX=e.layerX;
			mY=e.layerY;
		}
		else
		{
			id=event.srcElement.id;
			mX=event.offsetX + document.body.scrollLeft;
			mY=event.offsetY + document.body.scrollTop;
		}

		if (id=='tetris_map')
		{	
	        position_y = parseInt(document.getElementById("tetris_piece").style.top); //position vertical.
			if (tetris_dragging) {
				if (!tetris_dragging_start_y)
					tetris_dragging_start_y=mY;
				if (mY-tetris_dragging_start_y>tetris_panel_height)
				        position_x = parseInt(document.getElementById("tetris_piece").style.left);			
					    position_y+=tetris_panel_height;

			}
			else
			{
			  position_x = tetris_panel_width* parseInt(mX / tetris_panel_width); 
			}
			//if (Math.abs(position_x-parseInt(document.getElementById("tetris_piece").style.left))>5)
			diff=position_x-parseInt(document.getElementById("tetris_piece").style.left);
			if (diff<0)
				position_x=position_x-1;//tetris_panel_width;
			else if (diff>0)
				position_x=position_x+1;//tetris_panel_width;
			if (Math.abs(diff)>0) {
			    position_x = tetris_panel_width* parseInt(position_x / tetris_panel_width); 
				mover_piece(position_x, position_y);
	            //Se muestra el mapa:
				tetris_show_map(map_matrix, map_matrix_previous);
				// Slow down the mouse movement - wait half a second before can move again
				tetris_mousemoveok=false;
				setInterval("tetris_mousemoveok=true;",500);
			}
			
			//position_x=position_x+55;//parseInt((position_x+parseInt(document.getElementById("tetris_piece").style.left))/2);
			//document.getElementById("tetris_pause").innerHTML=diff;

	        //Se obtiene la position actual of the piece:
	        //position_x = parseInt(document.getElementById("tetris_piece").style.left); //position horizontal.
	                    
	                    //Se mueve la piece:
			//clearInterval(tetris_moveinterval);
			//tetris_moveinterval=setTimeout("mover_piece("+position_x+","+position_y+"); tetris_show_map(map_matrix,map_matrix_previous);",500);


		}
		return false;
	}	

	function tetris_click(e)
	{
        if(!e) var e=window.event;

		bLeftClick=false;
		bRightClick=false;
        if(e.which){

		      id=e.target.id;
              if (e.which==1)
			    bLeftClick=true;
			  else if  (e.which==3)
			    bRightClick=true;
        }

        else if(e.button){
			 id=e.srcElement.id;
              if (e.button==1)
			    bLeftClick=true;
			  else if  (e.button==2)
			    bRightClick=true;

        }		
	    if (bLeftClick)
		{
			tetris_dragging=true;//id;
			tetris_dragging_start_y=null;
		}
		else if (bRightClick)
		{
				position_y = rotate_piece("right");
				//position_y = rotate_piece("left");

				position_x = parseInt(document.getElementById("tetris_piece").style.left);			
				//Se mueve la piece:
				mover_piece(position_x, position_y);

				//Se muestra el mapa:
				tetris_show_map(map_matrix, map_matrix_previous);	
        }				
	}
		  
function tetris_dblclick(e){

	    tetris_dragging=false;
        var rightclick='';

        if(!e) var e=window.event;

		
        if(e.which){

		      id=e.target.id;
              if (e.which==1)
			    bLeftClick=true;
			  else if  (e.which==3)
			    bRightClick=true;
        }

        else if(e.button){
			  id=e.srcElement.id;
              if (e.button==1)
			    bLeftClick=true;
			  else if  (e.button==2)
			    bRightClick=true;


        }

		position_y = rotate_piece("left");

		position_x = parseInt(document.getElementById("tetris_piece").style.left);			
				//Se mueve la piece:
		mover_piece(position_x, position_y);

				//Se muestra el mapa:
		tetris_show_map(map_matrix, map_matrix_previous);



} 

function tetris_mouseup(e)
{
		tetris_dragging=false;

}

  

