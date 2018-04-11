<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Buscaminas</title>
        <!-- (c) Buscayasminas - Programa realizado por Joan Alba Maldonado (granvino@granvino.com). Prohibido publicar, reproducir o modificar sin citar expresamente al autor original. -->
        <script language="JavaScript1.2" type="text/javascript">
            <!--
                //(c) Buscayasminas - Programa realizado por Joan Alba Maldonado (granvino@granvino.com). Prohibido publicar, reproducir o modificar sin citar expresamente al autor original.

                //Ancho del mapa:
                var mapa_width = 9;
                var mapa_width_maximo = 30; //Ancho maximo del mapa que puede ponerse.
                //Alto del mapa:
                var mapa_height = 9;
                var mapa_height_maximo = 30; //Alto maximo del mapa que puede ponerse.

                //Ancho y alto de las celdas:
                var celda_width = 20;
                var celda_height = 20;

                //Numero de minas:
                var numero_minas = 10;
                //Numero de banderas:
                var numero_banderas = numero_minas;
                
                //Matriz con las minas:
                var matriz_minas = new Array(mapa_width*mapa_height);
                //Matriz con los numeros:
                var matriz_numeros = new Array(mapa_width*mapa_height);
                //Matriz del usuario (para saber que casillas ha descubierto, que banderas ha puesto, etc):
                var matriz_usuario = new Array(mapa_width*mapa_height);

                //Variable que determina la opcion seleccionada:
                var opcion_seleccionada = false;
                
                //Variable para saber si se tiene que arrastrar el menu opciones o no:
                var arrastrar_opciones = false;                
                //Variables que calcularan la diferencia entre las coordenadas del mouse y las del div de opciones:
                var diferencia_posicion_horizontal = false;
                var diferencia_posicion_vertical = false;
                //Variable para saber si se esta arrastrando en un campo seleccionable, y asi no dejar arrastrar:
                var campo_seleccionable = false;

                //Variable para saber si el juego ha finalizado (ya por haber ganado o por haber perdido):
                var se_ha_acabado_juego = false;
                
                //Variable para saber si se han cambiado las banderas despues de haber apretado la tecla alt, control o shift:
                var se_ha_modificado_bandera = false;
                
                //Variable para saber si una tecla se ha levantado o no:
                var tecla_levantada = true;
                
                //Matriz que define de que color saldra cada numero (del 0, que no se usa, al 8):
                var color_numeros = new Array("#ff0000", "#00ff00", "#00aaff", "#ffff00", "#aaffcc", "#aabbff", "#ffffff", "#ddaaff", "#ffddbb");

                //Variable que guarda el contenido del div de la cara de la yasmina, para hacer el if de no cambiarlo si esta como al principio:
                var div_cara_yasmina_inicial = false;

                //Variable para saber si se ha iniciado el contador de tiempo:
                var iniciado_temporizador = false;
                //Variable donde se guardara el setInterval del tiempo:
                var tiempo = false;
                //Variables que guardan los segundos y los minutos, respectivamente, del temporizador:
                var segundos = 0;

                //Variable que define si hay tiempo limite:
                var hay_tiempo_limite = true;
                //Variable que define cuanto tiempo limite hay:
                var tiempo_limite = 9999;
                
                
                //Funcion que muestra un mensaje en pantalla:
                function mostrar_mensaje(mensaje)
                 {
                    //Se pone el mensaje en el div:
                    document.getElementById("mensaje").innerHTML = mensaje;

                    //Si se ha enviado un mensaje:
                    if (mensaje != "")
                     {
                        //Se muestra el div:
                        document.getElementById("mensaje").style.visibility = "visible";
                     }
                    //...y si no:
                    else
                     {
                        //Se oculta el div:
                        document.getElementById("mensaje").style.visibility = "hidden";                        
                     }
                 }
                
                
                //Funcion que muestra u oculta el menu de opciones:
                function mostrar_ocultar_opciones()
                 {
                    //Se ponen las opciones pertinentes:
                    document.getElementById("numero_minas").value = numero_minas;
                    document.getElementById("mapa_width").value = mapa_width;
                    document.getElementById("mapa_height").value = mapa_height;
                    document.getElementById("tiempo_limite_input").value = tiempo_limite;
                    if (hay_tiempo_limite)
                     {
                        //Se chequea el checkbox conforme hay tiempo limite:
                        document.getElementById("hay_tiempo_limite_input").checked = true;
                        //Se desbloquea el input text con el limite de tiempo:
                        document.getElementById("tiempo_limite_input").disabled = false;
                        //Se vuelve a poner un color de fondo y de texto normal:
                        document.getElementById("tiempo_limite_input").style.color = "#333333";
                        document.getElementById("tiempo_limite_input").style.background = "#99bbff";
                     }
                    else
                     {
                        //Se deschequea el checkbox conforme no hay tiempo limite:
                        document.getElementById("hay_tiempo_limite_input").checked = false;
                        //Se desbloquea el input text con el limite de tiempo:
                        document.getElementById("tiempo_limite_input").disabled = true;
                        //Se vuelve a poner un color de fondo y de texto normal:
                        document.getElementById("tiempo_limite_input").style.color = "#3333dd";
                        document.getElementById("tiempo_limite_input").style.background = "#999999";
                     }
                    
                    //Si esta oculto, lo muestra:
                    if (document.getElementById("menu_opciones").style.visibility == "hidden") { document.getElementById("menu_opciones").style.visibility = "visible"; document.getElementById("opcion_opciones").title = "Cerrar opciones"; }
                    //...y si no, lo oculta:
                    else { document.getElementById("menu_opciones").style.visibility = "hidden"; document.getElementById("opcion_opciones").title = "Editar opciones"; }
                 }

                
                //Funcion que activa o desactiva el tiempo limite:
                function activar_desactivar_tiempo_limite()
                 {
                    //Si el tiempo limite esta desactivado, se activa:
                    if (document.getElementById("hay_tiempo_limite_input").checked == true)
                     {
                        //Se desbloquea el input text con el limite de tiempo:
                        document.getElementById("tiempo_limite_input").disabled = false;
                        //Se vuelve a poner un color de fondo y de texto normal:
                        document.getElementById("tiempo_limite_input").style.color = "#333333";
                        document.getElementById("tiempo_limite_input").style.background = "#99bbff";
                     }
                    //...pero si ya esta activado, se desactiva:
                    else
                     {
                        //Se desbloquea el input text con el limite de tiempo:
                        document.getElementById("tiempo_limite_input").disabled = true;
                        //Se vuelve a poner un color de fondo y de texto normal:
                        document.getElementById("tiempo_limite_input").style.color = "#3333dd";
                        document.getElementById("tiempo_limite_input").style.background = "#999999";
                     }
                 }


                //Funcion que aplica las opciones elegidas:
                function aplicar_opciones()
                 {
                    //Se guarda en variables las opciones enviadas:
                    var numero_minas_enviado = parseInt(document.getElementById("numero_minas").value);
                    var mapa_width_enviado = parseInt(document.getElementById("mapa_width").value);
                    var mapa_height_enviado = parseInt(document.getElementById("mapa_height").value);
                    var numero_minas_maximo_enviado = (mapa_width_enviado*mapa_height_enviado) - 1;
                    var tiempo_limite_enviado = parseInt(document.getElementById("tiempo_limite_input").value);
                    
                    //Si las opciones siguen siendo iguales a las que hay actualmente, sale de la funcion:
                    if (numero_minas_enviado == numero_minas && mapa_width_enviado == mapa_width && mapa_height_enviado == mapa_height)
                     {
                        //Si se ha seteado conforme hay limite de tiempo, pero se ha enviado el mismo que ya habia, sale de la funcion:
                        if (hay_tiempo_limite && document.getElementById("hay_tiempo_limite_input").checked == true && tiempo_limite_enviado == tiempo_limite) { return; }
                        //...o si no se ha enviado conforme hay limite de tiempo, tambien sale:
                        else if (!hay_tiempo_limite && document.getElementById("hay_tiempo_limite_input").checked == false) { return; }
                     }
                    
                    
                    //Variable que guarda los errores, si se generan:
                    var errores = "";
                    //Variable para saber que hay que restaurar, en caso de ser erroneo:
                    var restaurar_numero_minas = false;
                    var restaurar_mapa_width = false;
                    var restaurar_mapa_height = false;
                    var restaurar_tiempo_limite = false;
                    //Calcular errores cometidos (opciones invalidas):
                    if (mapa_width_enviado == 1 && mapa_height_enviado == 1) { errores += "\n* El mapa debe tener mas de una celda (no puede ser 1x1)."; restaurar_mapa_width = true; restaurar_mapa_height = true; }
                    if (mapa_width_enviado > mapa_width_maximo || mapa_width_enviado < 1 || isNaN(mapa_width_enviado)) { errores += "\n* El ancho del mapa debe ser un numero entre 1 y "+mapa_width_maximo+"."; restaurar_mapa_width = true; }
                    if (mapa_height_enviado > mapa_height_maximo || mapa_height_enviado < 1 || isNaN(mapa_height_enviado)) { errores += "\n* El alto del mapa debe ser un numero entre 1 y "+mapa_height_maximo+"."; restaurar_mapa_height = true; }
                    if (numero_minas_enviado > numero_minas_maximo_enviado || numero_minas_enviado < 1 || isNaN(numero_minas_enviado)) { errores += "\n* El numero de minas debe ser un numero entre 1 y la operacion resultante de alto del mapa x ancho del mapa - 1."; restaurar_numero_minas = true; }
                    if (document.getElementById("hay_tiempo_limite_input").checked == true && tiempo_limite_enviado > 9999 || tiempo_limite_enviado < 10 || isNaN(tiempo_limite_enviado)) { errores += "\n* El tiempo limite debe estar entre 10 y 9999 segundos."; restaurar_tiempo_limite = true; }
                    //Si se ha cometido algun error de opcion no valida:
                    if (errores != "")
                     {
                        //Alerta sobre el error:
                        alert("Las opciones no son correctas.\nProblemas:"+errores);
                        //Vuelve a poner todo como estaba antes (siempre que se haya determinado por ser erroneo):
                        if (restaurar_numero_minas) { document.getElementById("numero_minas").value = numero_minas; }
                        if (restaurar_mapa_width) { document.getElementById("mapa_width").value = mapa_width; }
                        if (restaurar_mapa_height) { document.getElementById("mapa_height").value = mapa_height; }
                        if (restaurar_tiempo_limite) { document.getElementById("tiempo_limite_input").value = tiempo_limite; }
                        //Sale de la funcion:
                        return;
                     }
                    //...pero si no ha habido ningun error, se aplican las opciones (con confirmacion):
                    else
                     {
                        //Pide confirmacion, y si se cancela restaura los valores y sale de la funcion:
                        if (!confirm("Pulsa aceptar para aplicar las opciones. Se perdera la partida actual."))
                         {
                            //Restaura los valores anteriores:
                            document.getElementById("numero_minas").value = numero_minas;
                            document.getElementById("mapa_width").value = mapa_width;
                            document.getElementById("mapa_height").value = mapa_height;
                            //Sale de la funcion:
                            return;
                         }
                        //...y si no, aplica las opciones:
                        else
                         {
                            //Aplica las opciones:
                            numero_minas = parseInt(document.getElementById("numero_minas").value);
                            mapa_width = parseInt(document.getElementById("mapa_width").value);
                            mapa_height = parseInt(document.getElementById("mapa_height").value);
                            tiempo_limite = parseInt(document.getElementById("tiempo_limite_input").value);
                            if (document.getElementById("hay_tiempo_limite_input").checked == true) { hay_tiempo_limite = true; iniciado_temporizador = false; }
                            else { hay_tiempo_limite = false; }

                            //Se pone el mensaje de espera:
                            mostrar_mensaje("Cargando...");
                            
                            //Inicia el juego con las nuevas opciones y se quita el mensaje de espera, despues de unos milisegundos:
                            setTimeout("iniciar_juego(); mostrar_mensaje('');", 10);
                         }
                     }
                    
                 }


                //Funcion que selecciona una opcion (poner bandera, etc):
                function seleccionar_opcion(opcion, nombre_div)
                 {
                    //Se pone como opcion seleccionada la enviada:
                    opcion_seleccionada = opcion;
                    //Quita el borde solido a todos los div de opciones:
                    document.getElementById("bandera").style.border = ""; //Se borran primero para que el Opera 7.54 no de errores de redraw (y quiza otras versiones).
                    document.getElementById("bandera_no").style.border = ""; //Se borran primero para que el Opera 7.54 no de errores de redraw (y quiza otras versiones).
                    document.getElementById("bandera").style.border = "2px #bbbbbb dotted";
                    document.getElementById("bandera_no").style.border = "2px #bbbbbb dotted";
                    //Pone un borde al div de la opcion seleccionada:
                    document.getElementById(nombre_div).style.border = "2px #ffff00 solid";
                    //Se setea conforme se han modificado las opciones de bandera:
                    se_ha_modificado_bandera = true;
                    //Se setea conforme la tecla se ha levantado:
                    tecla_levantada = true;
                 }


                //Funcion que reinicia el juego:
                function reiniciar_juego()
                 {
                    //Se pone el mensaje de espera:
                    mostrar_mensaje("Cargando...");
                            
                    //Inicia el juego con las nuevas opciones y se quita el mensaje de espera, despues de unos milisegundos:
                    setTimeout("iniciar_juego(); mostrar_mensaje('');", 10);
                 }

                
                //Funcion que inicia el juego por primera vez:
                function iniciar_juego_primera_vez()
                 {
                    //Muestra el mensaje de cargando:
                    mostrar_mensaje("Cargando...");
                    //Pone en el input text del tiempo limite, el tiempo limite:
                    document.getElementById("tiempo_limite_input").value = tiempo_limite;
                    //Si no hay tiempo limite, desactiva la opcion de tiempo limite:
                    if (!hay_tiempo_limite)
                     {
                        //Se deschequea el checkbox de tiempo limite:
                        document.getElementById("hay_tiempo_limite_input").checked = false;
                        //Se desbloquea el input text con el limite de tiempo:
                        document.getElementById("tiempo_limite_input").disabled = true;
                        //Se vuelve a poner un color de fondo y de texto normal:
                        document.getElementById("tiempo_limite_input").style.color = "#3333dd";
                        document.getElementById("tiempo_limite_input").style.background = "#999999";
                     }
                    //...pero si hay tiempo limite, activa la opcion de tiempo limite:
                    else
                     {
                        //Se chequea el checkbox de tiempo limite:
                        document.getElementById("hay_tiempo_limite_input").checked = true;
                        //Se desbloquea el input text con el limite de tiempo:
                        document.getElementById("tiempo_limite_input").disabled = false;
                        //Se vuelve a poner un color de fondo y de texto normal:
                        document.getElementById("tiempo_limite_input").style.color = "#333333";
                        document.getElementById("tiempo_limite_input").style.background = "#99bbff";
                     } 
                    //Se pone la opcion de mostrar/ocultar el menu de opciones:
                    document.getElementById("menu").innerHTML += '<div id="opcion_opciones" style="left:130px; top:26px; width:60px; height:11px; position:absolute; border:0px; padding:0px; background:transparent; color:#ffffff; text-align:center; line-height:11px; text-decoration:underline; font-family:arial; font-size:11px; cursor: pointer; cursor: hand; z-index:3;" title="Editar opciones" onClick="javascript:mostrar_ocultar_opciones();" onMouseOver="javascript:document.getElementById(\'opcion_opciones\').style.color=\'#ffff00\';" onMouseOut="javascript:document.getElementById(\'opcion_opciones\').style.color=\'#ffffff\';">opciones</div>';
                    //Inicia el juego despues de unos milisegundos, y luego quita el mensaje de cargando:
                    setTimeout("iniciar_juego(); mostrar_mensaje('');", 10);                 
                 }
                                 
                 
                //Funcion que inicia el juego:
                function iniciar_juego()
                 {
                    //Se pone conforme el juego aun no se ha cabado:
                    se_ha_acabado_juego = false;

                    //Pone como valores del input text del formulario de opciones las opciones definidas:
                    document.getElementById("numero_minas").value = numero_minas;
                    document.getElementById("mapa_width").value = mapa_width;
                    document.getElementById("mapa_height").value = mapa_height;

                    //Se ponen tantas banderas como numero de minas:
                    numero_banderas = numero_minas;
                    //Se representan en el contador:
                    document.getElementById("contador_banderas").innerHTML = numero_banderas;

                    //Se pone el contador de tiempo a cero:
                    iniciar_temporizador(false);
                    document.getElementById("tiempo").innerHTML = 0;
                    
                    //Se vuelve a poner la cara normal a la yasmina:
                    document.getElementById("yasmina").innerHTML = '<img src="img/yasmina.gif" alt="Juego nuevo" title="Juego nuevo" height="30" width="30" hspace="0" vspace="0" align="center">';

                    //Se guarda el contenido del div de la cara de la yasmina, siempre que no se haya hecho antes:
                    if (!div_cara_yasmina_inicial) { div_cara_yasmina_inicial = document.getElementById("yasmina").innerHTML; }

                    //Prepara el mapa:
                    preparar_mapa();
                    
                    //Representa el mapa:
                    dibujar_mapa();
                 }

                
                //Funcion que prepara el mapa:
                function preparar_mapa()
                 {
                    //Se crean o vuelven a resizear la matriz de minas con el ancho correspondiente y vaciandola:
                    matriz_minas = new Array(mapa_width*mapa_height);
                    for (x=0; x<matriz_minas.length; x++) { matriz_minas[x] = 0; }
                                        
                    //Se ponen aleatoriamente las minas en la matriz de minas (tantas como sean necesarias):
                    var minas_puestas = 0;
                    var posicion_aleatoria = 0;
                    while (minas_puestas != numero_minas)
                     {
                        //Se calcula una posicion aleatoria para la mina:
                        posicion_aleatoria = parseInt(Math.random() * (mapa_width * mapa_height));
                        //Si la posicion esta desocupada, se pone la mina:
                        if (matriz_minas[posicion_aleatoria] == 0) { matriz_minas[posicion_aleatoria] = 1; minas_puestas++; }
                     }

                    //Adecua la matriz de numeros segun la matriz de minas:
                    crear_matriz_numeros();
                    
                    //Se crea la matriz del usuario:
                    matriz_usuario = new Array(mapa_width*mapa_height);
                    for (x=0; x<matriz_usuario.length; x++) { matriz_usuario[x] = 0; }
                 }
                

                //Funcion que crea la matriz de numeros:
                function crear_matriz_numeros()
                 {
                    //Se crean o vuelven a resizear la matriz de minas con el ancho correspondiente y vaciandola:
                    matriz_numeros = new Array(mapa_width*mapa_height);
                    for (x=0; x<matriz_numeros.length; x++) { matriz_numeros[x] = 0; }
                    
                    //Se adecua segun sea la matriz de minas (si hay mina = X, si no hay alrededor = 0):
                    var contador_minas = 0; //Contador de minas circundantes a cada celda.
                    var numero_columna = 1; //Contador de la columna en la que se esta.
                    for (x=0; x<matriz_numeros.length; x++)
                     {
                        //Si el contador de columnas es mayor a las columnas del mapa, lo vuelve a 1:
                        if (numero_columna > mapa_width) { numero_columna = 1; }
                        
                        //Si hay una mina, se setea como X:
                        if (matriz_minas[x] == 1) { matriz_numeros[x] = "X"; }
                        //...y si no, calcular las minas de alrededor:
                        else
                         {
                            //Se pone a 0 el contador de minas circundantes:
                            contador_minas = 0;
                            //Si hay alguna mina en las celdas que rodean la cela, se incrementa el contador:
                            if (x-mapa_width-1 >= 0 && numero_columna-1 >= 1 && matriz_minas[x-mapa_width-1] == 1) { contador_minas++; } //En la casilla superior izquierda (si no esta a la izquierda del todo).
                            if (x-mapa_width >= 0 && matriz_minas[x-mapa_width] == 1) { contador_minas++; } //En la casilla superior.
                            if (x-mapa_width+1 >= 0 && numero_columna+1 <= mapa_width && matriz_minas[x-mapa_width+1] == 1) { contador_minas++; } //En la casilla superior derecha (si no esta al derecha del todo).
                            if (x-1 >= 0 && numero_columna-1 >= 1 && matriz_minas[x-1] == 1) { contador_minas++; } //En la casilla izquierda (si no esta a la izquierda del todo).
                            if (x+1 <= matriz_minas.length && numero_columna+1 <= mapa_width && matriz_minas[x+1] == 1) { contador_minas++; } //En la casilla derecha (si no esta a la derecha del todo).
                            if (x+mapa_width-1 <= matriz_minas.length && numero_columna-1 >= 1 && matriz_minas[x+mapa_width-1] == 1) { contador_minas++; } //En la casilla inferior izquierda (si no esta a la izquierda del todo).
                            if (x+mapa_width <= matriz_minas.length && matriz_minas[x+mapa_width] == 1) { contador_minas++; } //En la casilla inferior.
                            if (x+mapa_width+1 <= matriz_minas.length && numero_columna+1 <= mapa_width && matriz_minas[x+mapa_width+1] == 1) { contador_minas++; } //En la casilla inferior derecha (si no esta a la derecha del todo).
                            //Se pone el numero de minas que hay alrededor en la celda:
                            matriz_numeros[x] = contador_minas;
                         }
                        //Se incrementa el contador de columnas:
                        numero_columna++;
                     }
                 }
                

                //Funcion que representa el mapa en pantalla:
                function dibujar_mapa()
                 {
                    //Pone el alto y ancho a la zona de juego:
                    document.getElementById("zona_juego").style.width = mapa_width * (celda_width + 2) + 2 + "px";
                    document.getElementById("zona_juego").style.height = mapa_height * (celda_height + 2) + 2 + "px";
                    
                    //Poner el div con informacion del autor mas abajo del mapa:
                    document.getElementById("informacion").style.top = mapa_height * (celda_height + 2) + 2 + parseInt(document.getElementById("zona_juego").style.top) + 20 + "px";
                    
                    //Variable donde se va guardando el codigo HTML para luego volcarlo en el div de la zona de juego:
                    var html = "";
                    
                    //Variables que cuentan la columna y la fila:
                    var columna = 1;
                    var fila = 1;
                    
                    //Variables que diran donde posicionar la celda:
                    var celda_left = 0; //Posicion horizontal.
                    var celda_top = 0; //Posicion vertical.
                    
                    //Se recorre un bucle por todo el mapa:
                    for (x=0; x<mapa_width*mapa_height; x++)
                     {
                        //Si se ha alcanzado el maximo de columnas, vuelve a la primera e incrementa una fila:
                        if (columna > mapa_width) { columna = 1; fila++; }

                        //Se calcula la posicion de la celda:
                        celda_left = (columna - 1) * (celda_width + 2) + 2;
                        celda_top = (fila - 1) * (celda_height + 2) + 2;
                        
                        //Pone el codigo HTML correspondiente en la variable:
                        html += '<div id="'+x+'" style="left:'+celda_left+'px; top:'+celda_top+'px; width:'+celda_width+'px; height:'+celda_height+'px; position:absolute; border:0px; padding:0px; background:#ff0000; color:#ffffff; text-align:center; font-weight:bold; line-height:'+eval(parseInt(Math.min(celda_width, celda_height)))+'px; text-decoration:none; font-family:verdana; font-size:'+eval(parseInt(Math.min(celda_width, celda_height) / 2))+'px; cursor: pointer; cursor: hand; z-index:2; -moz-user-select:none;" title="Elegir celda (click derecho: poner/quitar bandera, solo algunos navegadores)" onClick="javascript:mostrar_mensaje(\'Procesando...\'); setTimeout(\'destapar_celda('+x+', '+columna+'); mostrar_mensaje(\\\'\\\'); se_ha_ganado();\', 10);" onMouseDown="document.getElementById(\'yasmina\').innerHTML = \'<img src=\\\'img/yasmina_click.gif\\\' alt=\\\'Juego nuevo\\\' title=\\\'Juego nuevo\\\' height=\\\'30\\\' width=\\\'30\\\' hspace=\\\'0\\\' vspace=\\\'0\\\' align=\\\'center\\\'>\';" onMouseUp="document.getElementById(\'yasmina\').innerHTML = \'<img src=\\\'img/yasmina.gif\\\' alt=\\\'Juego nuevo\\\' title=\\\'Juego nuevo\\\' height=\\\'30\\\' width=\\\'30\\\' hspace=\\\'0\\\' vspace=\\\'0\\\' align=\\\'center\\\'>\';" onContextMenu="poner_quitar_bandera('+x+'); return false;" onSelectStart="return false;"></div>';

                        //Incrementa una columna:
                        columna++;
                     }
                     
                    //Se vuelca el contenido de la variable con el HTML en el div:
                    document.getElementById("zona_juego").innerHTML = html;
                 }

                
                //Funcion que pone o quita una bandera, segun este o no ya puesta en la celda:
                function poner_quitar_bandera(celda)
                 {
                    //Si la celda esta ya destapada, sale de la funcion:
                    if (matriz_usuario[celda] == 1) { return; }

                    //Si en la celda ya hay una bandera, se quita:
                    if (matriz_usuario[celda] == 2)
                     {
                        //Se suma una bandera mas (ya que la hemos recogido):
                        numero_banderas++;
                        //Se vuelve a sumar la bandera al contador de estas:
                        document.getElementById("contador_banderas").innerHTML = numero_banderas;
                        //Se quita la bandera de la casilla:
                        matriz_usuario[celda] = 0;
                        //Se vacia la celda en el mapa:
                        document.getElementById(celda).innerHTML = "";
                     }
                    //...pero si no, se pone siempre que aun queden:
                    else if (numero_banderas > 0)
                     {
                         //Se resta una bandera:
                         numero_banderas--;
                         //Se ponen las banderas que hay:
                         document.getElementById("contador_banderas").innerHTML = numero_banderas;
                         //Se pone la bandera en la matriz de usuario:
                         matriz_usuario[celda] = 2;
                         //Se muestra la bandera en el mapa:                        
                         document.getElementById(celda).innerHTML = '<img src="img/bandera.gif" alt="B" title="Bandera" width="'+celda_width+'" height="'+celda_height+'" hspace="0" vspace="0">'; document.getElementById(celda).title = "Bandera";
                     }
                    //...pero si no quedan, se avisa:
                    else { alert("No tienes mas banderas para poner. Si quieres, puedes recoger una de otro lugar para ponerla aqui."); }
                 }


                //Funcion que descubre una celda:
                function destapar_celda(celda, columna)
                 {
                    //Si la celda esta ya destapada, sale de la funcion:
                    if (matriz_usuario[celda] == 1) { return; }
                    
                    //Si la opcion seleccionada es la de poner una bandera y la celda todavia no esta destapada, se pone y sale de la funcion:
                    if (opcion_seleccionada == "bandera" && matriz_usuario[celda] == 0)
                     {
                        //Si hay banderas, se procede:
                        if (numero_banderas > 0)
                         {
                             //Se resta una bandera:
                             numero_banderas--;
                             //Se ponen las banderas que hay:
                             document.getElementById("contador_banderas").innerHTML = numero_banderas;
                             //Se pone la bandera en la matriz de usuario:
                             matriz_usuario[celda] = 2;
                             //Se muestra la bandera en el mapa:                        
                             document.getElementById(celda).innerHTML = '<img src="img/bandera.gif" alt="B" title="Bandera" width="'+celda_width+'" height="'+celda_height+'" hspace="0" vspace="0">'; document.getElementById(celda).title = "Bandera";
                         }
                        //...pero si no quedan banderas, se avisa:
                        else { alert("No tienes mas banderas para poner. Si quieres, puedes recoger una de otro lugar para ponerla aqui."); }
                        //Sale de la funcion:
                        return;
                     }
                    //...pero si en la casilla elegida hay una bandera, se quita y sale de la funcion:
                    else if (matriz_usuario[celda] == 2)
                     {
                        //Se suma una bandera mas (ya que la hemos recogido):
                        numero_banderas++;
                        //Se vuelve a sumar la bandera al contador de estas:
                        document.getElementById("contador_banderas").innerHTML = numero_banderas;
                        //Se quita la bandera de la casilla:
                        matriz_usuario[celda] = 0;
                        //Se vacia la celda en el mapa:
                        document.getElementById(celda).innerHTML = "";
                        //Sale de la funcion:
                        return;
                     }

                    //Si aun no se ha iniciado el temporizador, se inicia y se setea conforme ya se ha iniciado:
                    if (!iniciado_temporizador) { iniciar_temporizador(true); iniciado_temporizador = true; }

                    //Se pone el mensaje de espera:
                    mostrar_mensaje("Destapando...");
                    
                    //Si la celda contiene una mina, se pinta una mina en la celda y se setea GameOver:
                    if (matriz_numeros[celda] == "X") { matriz_usuario[celda] = 3; document.getElementById(celda).innerHTML = '<img src="img/yasmina.gif" alt="X" title="Yasmina" width="'+celda_width+'" height="'+celda_height+'" hspace="0" vspace="0">'; document.getElementById(celda).title = "Yasmina"; game_over(); return; }
                    //...pero si la mariz contiene un numero superior a cero (hay minas alrededor), se destapa la casilla elegida y se pone en el title su contenido:
                    else if (!isNaN(matriz_numeros[celda]) && matriz_numeros[celda] > 0) { document.getElementById(celda).onclick = function () { }; document.getElementById(celda).onmousedown = function () { }; document.getElementById(celda).onmouseup = function () { }; document.getElementById(celda).onmouseover = function () { }; document.getElementById(celda).onmouseout = function () { }; document.getElementById(celda).style.cursor = "default"; document.getElementById(celda).style.background = "#550000"; document.getElementById(celda).style.color = color_numeros[matriz_numeros[celda]]; matriz_usuario[celda] = 1; document.getElementById(celda).innerHTML = matriz_numeros[celda]; document.getElementById(celda).title = matriz_numeros[celda]; }
                    //...pero si la matriz no contiene ninguna mina alrededor:
                    else if (matriz_numeros[celda] == 0)
                     {
                        //Se quitan los eventos ya no necesarios:
                        document.getElementById(celda).onclick = function () { }; document.getElementById(celda).onmousedown = function () { }; document.getElementById(celda).onmouseup = function () { }; document.getElementById(celda).onmouseover = function () { }; document.getElementById(celda).onmouseout = function () { }; document.getElementById(celda).style.cursor = "default";
                        //Se setea en la matriz de usuario para saber que ha sido descubierta:
                        matriz_usuario[celda] = 1;
                        //Se rellena con un espacio (para que no pueda volver a pulsarse):
                        document.getElementById(celda).innerHTML = "&nbsp;";
                        //Se oscurece la celda:
                        document.getElementById(celda).style.background = "#550000";
                        //Se vacia el title de la celda:
                        document.getElementById(celda).title = "";
                        //...se destapan recursivamente las celdas de alrededor hasta encontrarse rodeado de celdas que si tengan minas circundantes:
                        destapar_celdas_recursivamente(celda, columna);
                     }
                    //Se quita el mensaje de espera:
                    mostrar_mensaje("");
                 }

                
                //Funcion que destapa las celdas que no tienen minas circundantes, recursivamente:
                function destapar_celdas_recursivamente(celda, numero_columna)
                 {
                    //Variable donde se guarda la celda que circunda:
                    var celda_actual_temp = 0;
                    //Variable para saber si se ha llamado recursivamente:
                    var se_ha_llamado_recursivamente = false;
                    //Si la celda no tiene ninguna yasmina alrededor suyo, se procede:
                    if (matriz_numeros[celda] == 0)
                     {
                        //En la casilla superior izquierda (si no esta a la izquierda del todo) y si la casilla aun no ha sido descubierta:
                        if (celda-mapa_width-1 >= 0 && numero_columna-1 >= 1 && document.getElementById(celda-mapa_width-1).innerHTML == "" && matriz_usuario[celda-mapa_width-1] == 0)
                         {
                            //Se define que celda es la celda circundante actual:
                            celda_actual_temp = celda-mapa_width-1;
                            //Si la matriz circundante actual es tambien un cero, se destapa esta y sus circundantes recursivamente:
                            if (matriz_numeros[celda_actual_temp] == 0) { document.getElementById(celda_actual_temp).innerHTML = "&nbsp;"; document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).title = ""; destapar_celdas_recursivamente(celda_actual_temp, numero_columna-1); se_ha_llamado_recursivamente = true; }
                            //...pero si la circundante actual es un numero mayor que cero (una celda con minas alrededor), se destapa:
                            else if (!isNaN(matriz_numeros[celda_actual_temp]) && matriz_numeros[celda_actual_temp] > 0 && document.getElementById(celda_actual_temp).innerHTML == "") { document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).style.color = color_numeros[matriz_numeros[celda_actual_temp]]; document.getElementById(celda_actual_temp).innerHTML = matriz_numeros[celda_actual_temp]; document.getElementById(celda_actual_temp).title = matriz_numeros[celda_actual_temp]; }
                            //Se setea en la matriz de usuario como si se huviera destapado:
                            matriz_usuario[celda_actual_temp] = 1;
                            document.getElementById(celda_actual_temp).onclick = function () { }; document.getElementById(celda_actual_temp).onmousedown = function () { }; document.getElementById(celda_actual_temp).onmouseup = function () { }; document.getElementById(celda_actual_temp).onmouseover = function () { }; document.getElementById(celda_actual_temp).onmouseout = function () { }; document.getElementById(celda_actual_temp).style.cursor = "default"; 
                         } 
                        //En la casilla superior y la casilla aun no ha sido descubierta:
                        if (celda-mapa_width >= 0 && matriz_usuario[celda-mapa_width] == 0)
                         {
                            //Se define que celda es la celda circundante actual:
                            celda_actual_temp = celda-mapa_width;
                            //Si la matriz circundante actual es tambien un cero, se destapa esta y sus circundantes recursivamente:
                            if (matriz_numeros[celda_actual_temp] == 0 && document.getElementById(celda_actual_temp).innerHTML == "") { document.getElementById(celda_actual_temp).innerHTML = "&nbsp;"; document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).title = ""; destapar_celdas_recursivamente(celda_actual_temp, numero_columna); se_ha_llamado_recursivamente = true; }
                            //...pero si la circundante actual es un numero mayor que cero (una celda con minas alrededor), se destapa:
                            else if (!isNaN(matriz_numeros[celda_actual_temp]) && matriz_numeros[celda_actual_temp] > 0 && document.getElementById(celda_actual_temp).innerHTML == "") { document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).style.color = color_numeros[matriz_numeros[celda_actual_temp]]; document.getElementById(celda_actual_temp).innerHTML = matriz_numeros[celda_actual_temp]; document.getElementById(celda_actual_temp).title = matriz_numeros[celda_actual_temp]; }
                            //Se setea en la matriz de usuario como si se huviera destapado:
                            matriz_usuario[celda_actual_temp] = 1;
                            document.getElementById(celda_actual_temp).onclick = function () { }; document.getElementById(celda_actual_temp).onmousedown = function () { }; document.getElementById(celda_actual_temp).onmouseup = function () { }; document.getElementById(celda_actual_temp).onmouseover = function () { }; document.getElementById(celda_actual_temp).onmouseout = function () { }; document.getElementById(celda_actual_temp).style.cursor = "default"; 
                         }
                        //En la casilla superior derecha (si no esta al derecha del todo):
                        if (celda-mapa_width+1 >= 0 && numero_columna+1 <= mapa_width && matriz_usuario[celda-mapa_width+1] == 0)
                         {
                            //Se define que celda es la celda circundante actual:
                            celda_actual_temp = celda-mapa_width+1;
                            //Si la matriz circundante actual es tambien un cero, se destapa esta y sus circundantes recursivamente:
                            if (matriz_numeros[celda_actual_temp] == 0 && document.getElementById(celda_actual_temp).innerHTML == "") { document.getElementById(celda_actual_temp).innerHTML = "&nbsp;"; document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).title = ""; destapar_celdas_recursivamente(celda_actual_temp, numero_columna+1); se_ha_llamado_recursivamente = true; }
                            //...pero si la circundante actual es un numero mayor que cero (una celda con minas alrededor), se destapa:
                            else if (!isNaN(matriz_numeros[celda_actual_temp]) && matriz_numeros[celda_actual_temp] > 0 && document.getElementById(celda_actual_temp).innerHTML == "") { document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).style.color = color_numeros[matriz_numeros[celda_actual_temp]]; document.getElementById(celda_actual_temp).innerHTML = matriz_numeros[celda_actual_temp]; document.getElementById(celda_actual_temp).title = matriz_numeros[celda_actual_temp]; }
                            //Se setea en la matriz de usuario como si se huviera destapado:
                            matriz_usuario[celda_actual_temp] = 1;
                            document.getElementById(celda_actual_temp).onclick = function () { }; document.getElementById(celda_actual_temp).onmousedown = function () { }; document.getElementById(celda_actual_temp).onmouseup = function () { }; document.getElementById(celda_actual_temp).onmouseover = function () { }; document.getElementById(celda_actual_temp).onmouseout = function () { }; document.getElementById(celda_actual_temp).style.cursor = "default"; 
                         }
                        //En la casilla izquierda (si no esta a la izquierda del todo):
                        if (celda-1 >= 0 && numero_columna-1 >= 1 && matriz_usuario[celda-1] == 0)
                         {
                            //Se define que celda es la celda circundante actual:
                            celda_actual_temp = celda-1;
                            //Si la matriz circundante actual es tambien un cero, se destapa esta y sus circundantes recursivamente:
                            if (matriz_numeros[celda_actual_temp] == 0 && document.getElementById(celda_actual_temp).innerHTML == "") { document.getElementById(celda_actual_temp).innerHTML = "&nbsp;"; document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).title = ""; destapar_celdas_recursivamente(celda_actual_temp, numero_columna-1); se_ha_llamado_recursivamente = true; }
                            //...pero si la circundante actual es un numero mayor que cero (una celda con minas alrededor), se destapa:
                            else if (!isNaN(matriz_numeros[celda_actual_temp]) && matriz_numeros[celda_actual_temp] > 0 && document.getElementById(celda_actual_temp).innerHTML == "") { document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).style.color = color_numeros[matriz_numeros[celda_actual_temp]]; document.getElementById(celda_actual_temp).innerHTML = matriz_numeros[celda_actual_temp]; document.getElementById(celda_actual_temp).title = matriz_numeros[celda_actual_temp]; }
                            //Se setea en la matriz de usuario como si se huviera destapado:
                            matriz_usuario[celda_actual_temp] = 1;
                            document.getElementById(celda_actual_temp).onclick = function () { }; document.getElementById(celda_actual_temp).onmousedown = function () { }; document.getElementById(celda_actual_temp).onmouseup = function () { }; document.getElementById(celda_actual_temp).onmouseover = function () { }; document.getElementById(celda_actual_temp).onmouseout = function () { }; document.getElementById(celda_actual_temp).style.cursor = "default"; 
                         }
                        //En la casilla derecha (si no esta a la derecha del todo):
                       if (celda+1 <= matriz_numeros.length && numero_columna+1 <= mapa_width && matriz_usuario[celda+1] == 0)
                        {
                            //Se define que celda es la celda circundante actual:
                            celda_actual_temp = celda+1;
                            //Si la matriz circundante actual es tambien un cero, se destapa esta y sus circundantes recursivamente:
                            if (matriz_numeros[celda_actual_temp] == 0 && document.getElementById(celda_actual_temp).innerHTML == "") { document.getElementById(celda_actual_temp).innerHTML = "&nbsp;"; document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).title = ""; destapar_celdas_recursivamente(celda_actual_temp, numero_columna+1); se_ha_llamado_recursivamente = true; }
                            //...pero si la circundante actual es un numero mayor que cero (una celda con minas alrededor), se destapa:
                            else if (!isNaN(matriz_numeros[celda_actual_temp]) && matriz_numeros[celda_actual_temp] > 0 && document.getElementById(celda_actual_temp).innerHTML == "") { document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).style.color = color_numeros[matriz_numeros[celda_actual_temp]]; document.getElementById(celda_actual_temp).innerHTML = matriz_numeros[celda_actual_temp]; document.getElementById(celda_actual_temp).title = matriz_numeros[celda_actual_temp]; }
                            //Se setea en la matriz de usuario como si se huviera destapado:
                            matriz_usuario[celda_actual_temp] = 1;
                            document.getElementById(celda_actual_temp).onclick = function () { }; document.getElementById(celda_actual_temp).onmousedown = function () { }; document.getElementById(celda_actual_temp).onmouseup = function () { }; document.getElementById(celda_actual_temp).onmouseover = function () { }; document.getElementById(celda_actual_temp).onmouseout = function () { }; document.getElementById(celda_actual_temp).style.cursor = "default"; 
                        }
                        //En la casilla inferior izquierda (si no esta a la izquierda del todo):
                        if (celda+mapa_width-1 <= matriz_numeros.length && numero_columna-1 >= 1 && matriz_usuario[celda+mapa_width-1] == 0)
                         {
                            //Se define que celda es la celda circundante actual:
                            celda_actual_temp = celda+mapa_width-1;
                            //Si la matriz circundante actual es tambien un cero, se destapa esta y sus circundantes recursivamente:
                            if (matriz_numeros[celda_actual_temp] == 0 && document.getElementById(celda_actual_temp).innerHTML == "") { document.getElementById(celda_actual_temp).innerHTML = "&nbsp;"; document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).title = ""; destapar_celdas_recursivamente(celda_actual_temp, numero_columna-1); se_ha_llamado_recursivamente = true; }
                            //...pero si la circundante actual es un numero mayor que cero (una celda con minas alrededor), se destapa:
                            else if (!isNaN(matriz_numeros[celda_actual_temp]) && matriz_numeros[celda_actual_temp] > 0 && document.getElementById(celda_actual_temp).innerHTML == "") { document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).style.color = color_numeros[matriz_numeros[celda_actual_temp]]; document.getElementById(celda_actual_temp).innerHTML = matriz_numeros[celda_actual_temp]; document.getElementById(celda_actual_temp).title = matriz_numeros[celda_actual_temp]; }
                            //Se setea en la matriz de usuario como si se huviera destapado:
                            matriz_usuario[celda_actual_temp] = 1;
                            document.getElementById(celda_actual_temp).onclick = function () { }; document.getElementById(celda_actual_temp).onmousedown = function () { }; document.getElementById(celda_actual_temp).onmouseup = function () { }; document.getElementById(celda_actual_temp).onmouseover = function () { }; document.getElementById(celda_actual_temp).onmouseout = function () { }; document.getElementById(celda_actual_temp).style.cursor = "default"; 
                         }
                        //En la casilla inferior:
                        if (celda+mapa_width <= matriz_numeros.length && matriz_usuario[celda+mapa_width] == 0)
                         {
                            //Se define que celda es la celda circundante actual:
                            celda_actual_temp = celda+mapa_width;
                            //Si la matriz circundante actual es tambien un cero, se destapa esta y sus circundantes recursivamente:
                            if (matriz_numeros[celda_actual_temp] == 0 && document.getElementById(celda_actual_temp).innerHTML == "") { document.getElementById(celda_actual_temp).innerHTML = "&nbsp;"; document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).title = ""; destapar_celdas_recursivamente(celda_actual_temp, numero_columna); se_ha_llamado_recursivamente = true; }
                            //...pero si la circundante actual es un numero mayor que cero (una celda con minas alrededor), se destapa:
                            else if (!isNaN(matriz_numeros[celda_actual_temp]) && matriz_numeros[celda_actual_temp] > 0 && document.getElementById(celda_actual_temp).innerHTML == "") { document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).style.color = color_numeros[matriz_numeros[celda_actual_temp]]; document.getElementById(celda_actual_temp).innerHTML = matriz_numeros[celda_actual_temp]; document.getElementById(celda_actual_temp).title = matriz_numeros[celda_actual_temp]; }
                            //Se setea en la matriz de usuario como si se huviera destapado:
                            matriz_usuario[celda_actual_temp] = 1;
                            document.getElementById(celda_actual_temp).onclick = function () { }; document.getElementById(celda_actual_temp).onmousedown = function () { }; document.getElementById(celda_actual_temp).onmouseup = function () { }; document.getElementById(celda_actual_temp).onmouseover = function () { }; document.getElementById(celda_actual_temp).onmouseout = function () { }; document.getElementById(celda_actual_temp).style.cursor = "default"; 
                         }
                        //En la casilla inferior derecha (si no esta a la derecha del todo):
                        if (celda+mapa_width+1 <= matriz_numeros.length && numero_columna+1 <= mapa_width && matriz_usuario[celda+mapa_width+1] == 0)
                         {
                            //Se define que celda es la celda circundante actual:
                            celda_actual_temp = celda+mapa_width+1;
                            //Si la matriz circundante actual es tambien un cero, se destapa esta y sus circundantes recursivamente:
                            if (matriz_numeros[celda_actual_temp] == 0 && document.getElementById(celda_actual_temp).innerHTML == "") { document.getElementById(celda_actual_temp).innerHTML = "&nbsp;"; document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).title = ""; destapar_celdas_recursivamente(celda_actual_temp, numero_columna+1); se_ha_llamado_recursivamente = true; }
                            //...pero si la circundante actual es un numero mayor que cero (una celda con minas alrededor), se destapa:
                            else if (!isNaN(matriz_numeros[celda_actual_temp]) && matriz_numeros[celda_actual_temp] > 0 && document.getElementById(celda_actual_temp).innerHTML == "") { document.getElementById(celda_actual_temp).style.background = "#550000"; document.getElementById(celda_actual_temp).style.color = color_numeros[matriz_numeros[celda_actual_temp]]; document.getElementById(celda_actual_temp).innerHTML = matriz_numeros[celda_actual_temp]; document.getElementById(celda_actual_temp).title = matriz_numeros[celda_actual_temp]; }
                            //Se setea en la matriz de usuario como si se huviera destapado:
                            matriz_usuario[celda_actual_temp] = 1;
                            document.getElementById(celda_actual_temp).onclick = function () { }; document.getElementById(celda_actual_temp).onmousedown = function () { }; document.getElementById(celda_actual_temp).onmouseup = function () { }; document.getElementById(celda_actual_temp).onmouseover = function () { }; document.getElementById(celda_actual_temp).onmouseout = function () { }; document.getElementById(celda_actual_temp).style.cursor = "default"; 
                         }
                     }

                    //Si no se ha llamado recursivamente, sale de la funcion retornando false:
                    if (!se_ha_llamado_recursivamente) { return false; }
                    //...y si no, sale retornando true:
                    else { return true; }
                 }

                
                //Funcion que calcula si se ha ganado:
                function se_ha_ganado()
                 {
                    //Se calculan las minas destapadas:
                    var banderas_puestas_bien = true;
                    var celdas_destapadas = 0;
                    for (x=0; x<matriz_usuario.length; x++)
                     {
                        //Si la celda esta destapada, cuenta como tal:
                        if (matriz_usuario[x] == 1) { celdas_destapadas++; }
                        //Si en la celda hay una bandera, pero no hay una mina, se cuenta como que las banderas no estan bien puestas:
                        else if (matriz_usuario[x] == 2 && matriz_minas[x] != 1) { banderas_puestas_bien = false; }
                     }
                    //Si se han destapado todas las casillas sin mina, se gana destapando el escenario, dando la enhorabuena y luego se vuelve a comenzr el juego:
                    if (banderas_puestas_bien && celdas_destapadas >= mapa_width*mapa_height-numero_minas)
                     {
                        //Destapar el escenario:
                        for (x=0; x<mapa_width*mapa_height; x++)
                         {
                            //Si hay una yasmina, se representa una bandera y se pone el title correspondiente:
                            if (matriz_numeros[x] == "X") { document.getElementById(x).innerHTML = '<img src="img/bandera.gif" alt="B" title="Bandera" width="'+celda_width+'" height="'+celda_height+'" hspace="0" vspace="0">'; document.getElementById(x).title = "Bandera"; }
                         }
                        //Se pone conforme el juego se ha acabado:
                        se_ha_acabado_juego = true;
                        //Cambia la cara a la yasmina:
                        document.getElementById("yasmina").innerHTML = '<img src="img/yasmina_win.gif" alt="Juego nuevo" title="Juego nuevo" height="30" width="30" hspace="0" vspace="0" align="center">';
                        //Se muestra el mensaje segun se ha ganado:
                        mostrar_mensaje("Has ganado");
                        //Se detiene el temporizador:
                        iniciar_temporizador(false);
                        //Se setea conforme aun no se ha iniciado el temporizador:
                        iniciado_temporizador = false;
                        //Despues de unos milisegundos, da la enhorabuena e inicia el juego posteriormente:
                        setTimeout('mostrar_mensaje("Has ganado"); alert("Enhorabuena! has ganado.\\nPulsa aceptar para iniciar otro juego."); document.getElementById("tiempo").innerHTML = 0; mostrar_mensaje("Cargando..."); setTimeout(\'iniciar_juego(); mostrar_mensaje(\"\");\', 10);', 10);
                     }
                 }
                 

                //Funcion que produce el fin de juego:
                function game_over()
                 {
                    //Se pone el mensaje de espera:
                    mostrar_mensaje("Cargando...");

                    //Destapa toda la matriz:
                    for (x=0; x<mapa_width*mapa_height; x++)
                     {
                        //Si hay una yasmina sin bandera, se representa y se pone el title correspondiente:
                        if (matriz_numeros[x] == "X" && matriz_usuario[x] != 2) { document.getElementById(x).innerHTML = '<img src="img/yasmina.gif" alt="X" title="Yasmina" width="'+celda_width+'" height="'+celda_height+'" hspace="0" vspace="0">'; document.getElementById(x).title = "Yasmina"; }
                        //Si hay una yasmina con bandera, se representa y se pone el title correspondiente:
                        else if (matriz_numeros[x] == "X" && matriz_usuario[x] == 2) { document.getElementById(x).innerHTML = '<img src="img/bandera.gif" alt="B" title="Bandera" width="'+celda_width+'" height="'+celda_height+'" hspace="0" vspace="0">'; document.getElementById(x).title = "Bandera"; }
                        //Si hay una bandera pero no hay una yasmina, se representa:
                        else if (matriz_numeros[x] != "X" && matriz_usuario[x] == 2) { document.getElementById(x).innerHTML = '<img src="img/bandera_no.gif" alt="B" title="Bandera mal puesta" width="'+celda_width+'" height="'+celda_height+'" hspace="0" vspace="0">'; document.getElementById(x).title = "Bandera mal puesta"; }
                     }

                    //Se pone conforme el juego se ha acabado:
                    se_ha_acabado_juego = true;

                    //Cambia la cara a la yasmina:
                    document.getElementById("yasmina").innerHTML = '<img src="img/yasmina_lose.gif" alt="Juego nuevo" title="Juego nuevo" height="30" width="30" hspace="0" vspace="0" align="center">';

                    //Se pone el mensaje de fin de juego:
                    mostrar_mensaje("Came Over");

                    //Se detiene el temporizador:
                    iniciar_temporizador(false);
                    //Se setea conforme aun no se ha iniciado el temporizador:
                    iniciado_temporizador = false;
                        
                    //Despues de unos milisegundos, alerta del GameOver, vuelve los segundos a cero e inicia el juego posteriormente:
                    setTimeout('mostrar_mensaje("Came Over"); alert("Game Over."); mostrar_mensaje("Cargando..."); document.getElementById("tiempo").innerHTML = 0; setTimeout(\'iniciar_juego(); mostrar_mensaje(\"\");\', 10);', 10);
                 } 


                //Funcion que arrastra o deja de arrastrar la ventana de opciones:
                function arrastrar_ventana(e)
                 {
                    //Si la cara de la yasmina es la de haber hecho click (porque ni se ha perdido ni se ha ganado), se cambia a la normal siempre que no este ya puesta:
                    if (!se_ha_acabado_juego && document.getElementById("yasmina").innerHTML != div_cara_yasmina_inicial) { document.getElementById("yasmina").innerHTML = '<img src="img/yasmina.gif" alt="Juego nuevo" title="Juego nuevo" height="30" width="30" hspace="0" vspace="0" align="center">'; }
                    
                    //Variable para saber si estamos en Internet Explorer o no:
                    var ie = document.all ? true : false;
                    //Si estamos en internet explorer, se recogen las coordenadas del raton de una forma:
                    if (ie)
                     {
                        posicion_x_raton = event.clientX + document.body.scrollLeft;
                        posicion_y_raton = event.clientY + document.body.scrollTop;
                     }
                    //...pero en otro navegador, se recogen de otra forma:
                    else
                     {
                        document.captureEvents(Event.MOUSEMOVE);
                        posicion_x_raton = e.pageX;
                        posicion_y_raton = e.pageY;
                     } 
                    //Si las coordenadas X o Y del raton son menores que cero, se ponen a cero:
                    if (posicion_x_raton < 0) { posicion_x_raton = 0; }
                    if (posicion_y_raton < 0) { posicion_y_raton = 0; }

                    //Si se ha enviado arrastrar y no es un campo seleccionable, se arrastra:
                    if (arrastrar_opciones && !campo_seleccionable)
                     {
                        //Si es la primera vez que se arrastra despues del click, se calcula la diferencia inicial:
                        if (!diferencia_posicion_horizontal || !diferencia_posicion_vertical)
                         {
                            //Se calcula la diferencia que hay horizontalmente entre el raton y el div de las opciones:
                            diferencia_posicion_horizontal = eval(posicion_x_raton - parseInt(document.getElementById("menu_opciones").style.left));
                            //Se calcula la diferencia que hay verticalmente entre el raton y el div de las opciones:
                            diferencia_posicion_vertical = eval(posicion_y_raton - parseInt(document.getElementById("menu_opciones").style.top));
                         }
                        //Se calculan las nuevas coordenadas del div de las opciones:
                        var posicion_left_menu = posicion_x_raton - diferencia_posicion_horizontal;
                        var posicion_top_menu = posicion_y_raton - diferencia_posicion_vertical;
                        //Si alguna d las coordenadas fuera menos que cero, se ponen a cero:
                        if (posicion_left_menu < 0) { posicion_left_menu = 0; }
                        if (posicion_top_menu < 0) { posicion_top_menu = 0; }
                        //Se aplican las coordenadas al div de las opciones:                        
                        document.getElementById("menu_opciones").style.left = posicion_left_menu + "px";
                        document.getElementById("menu_opciones").style.top = posicion_top_menu + "px";
                     }
                    //...pero si no se ha enviado arrastrar o se ha dejado de hacer, se vuelve a poner la diferencia inicial a false:
                    else
                     {
                        diferencia_posicion_horizontal = false;
                        diferencia_posicion_vertical = false;
                     }
                 }


                //Funcion que captura la tecla pulsada y alterna las opciones de bandera:
                function tecla_pulsada(e, pulsandose)
                 {
                    //Capturamos la tacla pulsada (o liberada), segun navegador:
                    if (e.keyCode) { var unicode = e.keyCode; }
                    //else if (event.keyCode) { var unicode = event.keyCode; }
                    else if (window.Event && e.which) { var unicode = e.which; }
                    else { var unicode = 17; } //Si no existe, por defecto se utiliza el Control.

                    //Si la tecla pulsada (o liberada ) no es ni Shift (16), ni Control (17) ni Alt (18), sale de la funcion:
                    if (unicode != 16 && unicode != 17 && unicode != 18) { return; }

                    //Si se ha dejado de pulsar la tecla y no se han cambiado manualmente las opcione de bandera, se vuelven a poner las opciones de bandera como antes (se alternan):
                    if (!pulsandose && !se_ha_modificado_bandera)
                     {
                         //Se alternan las opciones:
                         opcion_seleccionada = (!opcion_seleccionada) ? "bandera" : false;
                         nombre_div = (opcion_seleccionada == "bandera") ? "bandera": "bandera_no";
                         //Quita el borde a todos los div de opciones:
                         document.getElementById("bandera").style.border = ""; //Se borran primero para que el Opera 7.54 no de errores de redraw (y quiza otras versiones).
                         document.getElementById("bandera_no").style.border = ""; //Se borran primero para que el Opera 7.54 no de errores de redraw (y quiza otras versiones).
                         document.getElementById("bandera").style.border = "2px #bbbbbb dotted";
                         document.getElementById("bandera_no").style.border = "2px #bbbbbb dotted";
                         //Pone un borde al div de la opcion seleccionada:
                         document.getElementById(nombre_div).style.border = "2px #ffff00 solid";
                         //Se setea conforme la tecla se ha levantado:
                         tecla_levantada = true;
                     }
                    //...y si se esta pulsando la tecla y se ha levantado la tecla, se captura:
                    else if (tecla_levantada)
                     {
                         //Se setea como que no se han cambiado manualmente las opciones de bandera:
                         se_ha_modificado_bandera = false;
                         //Se setea conforme la tecla aun no se ha levantado:
                         tecla_levantada = false;
                         //Se alternan las opciones:
                         opcion_seleccionada = (!opcion_seleccionada) ? "bandera" : false;
                         nombre_div = (opcion_seleccionada == "bandera") ? "bandera": "bandera_no";
                         //Quita el borde a todos los div de opciones:
                         document.getElementById("bandera").style.border = ""; //Se borran primero para que el Opera 7.54 no de errores de redraw (y quiza otras versiones).
                         document.getElementById("bandera_no").style.border = ""; //Se borran primero para que el Opera 7.54 no de errores de redraw (y quiza otras versiones).
                         document.getElementById("bandera").style.border = "2px #bbbbbb dotted";
                         document.getElementById("bandera_no").style.border = "2px #bbbbbb dotted";
                         //Pone un borde al div de la opcion seleccionada:
                         document.getElementById(nombre_div).style.border = "2px #ffff00 solid";
                     }        
                 }

                
                //Funcion que inicia o detiene el temporizador:
                function iniciar_temporizador(encender)
                 {
                     //Si no hay limite de tiempo, sale de la funcion:
                     if (!hay_tiempo_limite) { return; }
                     //Si se ha enciado false, se detiene el temporizador:
                     if (!encender)
                      {
                         //Se detiene el setInterval:
                         clearInterval(tiempo);
                         //Se setea la variable que guarda el setInterval a false:
                         tiempo = false;
                         //Se ponen los segundos a cero:
                         segundos = 0;
                      }
                    //...pero si no, se inicia:
                    else
                     {
                         //Si no se ha iniciado aun el temporizador, se inicia:
                         if (!tiempo)
                          {
                             //Se pone el tiempo a cero:
                             document.getElementById("tiempo").innerHTML = "0000";
                             segundos = 0;
                             //Se crea el setInterval:
                             tiempo = setInterval("segundos++; iniciar_temporizador(true);", 1000);
                          }
                         //Se representa en el div:
                         document.getElementById("tiempo").innerHTML = segundos;
                         //Si se ha llegado al tiempo limite, siempre que haya, se acaba el juego:
                         if (hay_tiempo_limite && segundos >= tiempo_limite) { alert("El tiempo se ha acabado."); game_over(); }
                     }
                 }

            // -->
        </script>
    </head>
    <body onLoad="iniciar_juego_primera_vez();" onMouseMove="javascript:arrastrar_ventana(event);" onMouseUp="javascript:arrastrar_opciones=false;" onClick="campo_seleccionable=false; arrastrar_opciones=false;" onKeyDown="javascript:tecla_pulsada(event, true);" onKeyUp="javascript:tecla_pulsada(event, false);" bgcolor="#efdddd" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <!-- Menu: -->
        <div id="menu" style="left:10px; top:10px; width:200px; height:40px; position:absolute; border:0px; padding:0px; background:#336666; color:#333333; text-align:left; line-height:20px; text-decoration:none; font-family:verdana; font-size:10px; z-index:2;">
            <div id="bandera" style="left:10px; top:2px; width:26px; height:20px; position:absolute; border:2px #bbbbbb dotted; padding:0px; background:transparent; color:#333333; text-align:center; line-height:20px; text-decoration:none; font-family:verdana; font-size:10px; cursor: pointer; cursor: hand; z-index:3;" onClick="javascript:seleccionar_opcion('bandera', 'bandera');">
                <img src="img/bandera.gif" alt="Poner bandera" title="Poner bandera (alternar opci&oacute;n: ALT GR + CLICK o SHIFT + CLICK o CONTROL + CLICK)" height="20" width="20" hspace="0" vspace="0" align="center">
            </div>
            <div id="bandera_no" style="left:42px; top:2px; width:26px; height:20px; position:absolute; border:2px #ffff00 solid; padding:0px; background:transparent; color:#333333; text-align:center; line-height:20px; text-decoration:none; font-family:verdana; font-size:10px; cursor: pointer; cursor: hand; z-index:3;" onClick="javascript:seleccionar_opcion(false, 'bandera_no');">
                <img src="img/bandera_no.gif" alt="Quitar bandera" title="Quitar bandera (alternar opci&oacute;n: ALT GR + CLICK o SHIFT + CLICK o CONTROL + CLICK)" height="20" width="20" hspace="0" vspace="0" align="center">
            </div>
            <div id="contador_banderas" style="left:24px; top:27px; width:30px; height:12px; position:absolute; border:0px; padding:0px; background:#000000; color:#ffff00; text-align:center; line-height:12px; text-decoration:none; font-family:arial; font-size:11px; cursor:default; z-index:3;" title="Banderas restantes">
                10
            </div>
            <div id="yasmina" style="left:85px; top:5px; width:30px; height:30px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:20px; text-decoration:none; font-family:verdana; font-size:10px; cursor: pointer; cursor: hand; z-index:3;" onClick="javascript:reiniciar_juego();">
                <img src="img/yasmina.gif" alt="Juego nuevo" title="Juego nuevo" height="30" width="30" hspace="0" vspace="0" align="center">
            </div>
            <div id="tiempo" style="left:130px; top:5px; width:60px; height:22px; position:absolute; border:0px; padding:0px; background:#000000; color:#ffff00; text-align:center; line-height:20px; text-decoration:none; font-family:verdana; font-size:18px; cursor:default; z-index:3;" title="Contador de tiempo">
                0
            </div>
        </div>
        <!-- Fin de Menu. -->
        <div id="menu_opciones" style="left:150px; top:50px; width:280px; height:290px; visibility:hidden; position:absolute; border:0px; padding:0px; background:#3344dd; color:#ffffff; text-align:center; line-height:20px; text-decoration:none; font-weight:bold; font-family:arial; font-size:16px; cursor:crosshair; filter:alpha(opacity=90); opacity:0.9; -moz-opacity:0.9; z-index:4;" onMouseDown="javascript:arrastrar_opciones=true;" onMouseUp="javascript:arrastrar_opciones=false;" onSelectStart="if (!campo_seleccionable) { return false; }" onClick="javascript:campo_seleccionable=false; arrastrar_opciones=false;">
            <div id="cerrar_opciones" style="left:260px; top:5px; width:12px; height:12px; position:absolute; border:0px; padding:0px; background:transparent; color:#ffffff; text-align:center; line-height:12px; text-decoration:none; font-family:arial; font-size:12px; cursor: pointer; cursor: hand; -moz-user-select:none; z-index:5;" title="Cerrar opciones" onClick="javascript:mostrar_ocultar_opciones();" onMouseOver="javascript:document.getElementById('cerrar_opciones').style.color='#ffff00';" onMouseOut="javascript:document.getElementById('cerrar_opciones').style.color='#ffffff';">[x]</div>
            <br>
            &nbsp; Men&uacute; de opciones
            <br>
            <br>
            <form style="font-family:arial; font-size:14px; display:inline;" onSubmit="javascript:aplicar_opciones(); return false;">
                <label for="numero_minas" accesskey="n" title="N&uacute;mero de minas" style="cursor: pointer; cursor: hand;"><b style="-moz-user-select:none;"><u>N</u>&uacute;mero de minas:</b> <input type="text" name="numero_minas" value="10" id="numero_minas" accesskey="n" maxlength="3" size="3" style="text-align:center; width:40px; height:22px; font-size:14px; line-height:14px; font-family:courier; font-weight:bold; color:#333333; background-color:#99bbff;" title="N&uacute;mero de minas" onMouseDown="javascript:campo_seleccionable=true;" onMouseUp="javascript:campo_seleccionable=false;"></label>
                <br>
                <br>
                <label for="mapa_width" accesskey="a" title="Ancho del mapa" style="cursor: pointer; cursor: hand;"><b style="-moz-user-select:none;"><u>A</u>ncho del mapa:</b> <input type="text" name="mapa_width" value="10" id="mapa_width" accesskey="a" maxlength="3" size="3" style="text-align:center; width:40px; height:22px; font-size:14px; line-height:14px; font-family:courier; font-weight:bold; color:#333333; background-color:#99bbff;" title="Ancho del mapa" onMouseDown="javascript:campo_seleccionable=true;" onMouseUp="javascript:campo_seleccionable=false;"></label>
                <br>
                <br>
                <label for="mapa_height" accesskey="t" title="Alto del mapa" style="cursor: pointer; cursor: hand;"><b style="-moz-user-select:none;">Al<u>t</u>o del mapa:</b> <input type="text" name="mapa_height" value="10" id="mapa_height" accesskey="t" maxlength="3" size="3" style="text-align:center; width:40px; height:22px; font-size:14px; line-height:14px; font-family:courier; font-weight:bold; color:#333333; background-color:#99bbff;" title="Alto del mapa" onMouseDown="javascript:campo_seleccionable=true;" onMouseUp="javascript:campo_seleccionable=false;"></label>
                <br>
                <br>
                <label for="hay_tiempo_limite_input" accesskey="l" title="Activar/Desactivar tiempo l&iacute;mite" style="cursor: pointer; cursor: hand;"><input type="checkbox" name="hay_tiempo_limite_input" id="hay_tiempo_limite_input" accesskey="l" title="Activar/Desactivar tiempo l&iacute;mite" onMouseDown="javascript:campo_seleccionable=true;" onMouseUp="javascript:campo_seleccionable=false;" onClick="javascript:activar_desactivar_tiempo_limite();"><b style="-moz-user-select:none;"><u>L</u>&iacute;mite de tiempo</b></label>
                <br>
                <label for="tiempo_limite_input" accesskey="p" title="Tiempo l&iacute;mite en segundos" style="cursor: pointer; cursor: hand;"><b style="-moz-user-select:none;">Tiem<u>p</u>o l&iacute;mite (segundos):</b> <input type="text" name="tiempo_limite_input" value="9999" id="tiempo_limite_input" accesskey="p" maxlength="4" size="4" style="text-align:center; width:40px; height:22px; font-size:14px; line-height:14px; font-family:courier; font-weight:bold; color:#3333dd; background-color:#999999;" title="Tiempo l&iacute;mite en segundos" onMouseDown="javascript:campo_seleccionable=true;" onMouseUp="javascript:campo_seleccionable=false;" disabled="true"></label>
                <br>
                <br>
                <input type="button" value="Aplicar" name="boton_aplicar" title="Aplicar opciones" style="font-size:12px; font-family:arial; background-color:#ddddff; color:#bb0000; width:50px; height:22px; cursor: pointer; cursor: hand;" onClick="javascript:aplicar_opciones();">
            </form>
            <br>
        </div>
        <!-- Opciones: -->
        <!-- Fin de Opciones.-->
        <!-- Mensaje: -->
        <div id="mensaje" style="left:220px; top:25px; width:200px; height:20px; position:absolute; visibility:visible; border:0px; padding:0px; background:#aaaadd; color:#333333; text-align:center; line-height:18px; font-family:verdana; font-size:18px; text-decoration:none; font-style:italic; font-weight:bold; filter:alpha(opacity=90); opacity:0.9; -moz-opacity:0.9; z-index:30;">Cargando...</div>
        <!-- Fin de Mensaje. -->
        <!-- Zona de juego: -->
        <div id="zona_juego" style="left:10px; top:55px; width:200px; height:200px; position:absolute; border:0px; padding:0px; background:#aaaaaa; color:#333333; text-align:left; line-height:20px; text-decoration:none; font-family:verdana; font-size:10px; z-index:1;" onContextMenu="return false;">
        </div>
        <!-- Fin de Zona de juego. -->
        <!-- Imagenes puestas en un div hidden para que al ponerlas en otro div del documento no tarden en cargar: -->
        <div style="visibility:hidden; position:absolute;"><img src="img/yasmina_click.gif"></div>
        <div style="visibility:hidden; position:absolute;"><img src="img/yasmina_win.gif"></div>
        <div style="visibility:hidden; position:absolute;"><img src="img/yasmina_lose.gif"></div>
        <div style="visibility:hidden; position:absolute;"><img src="img/yasmina.gif"></div>
        <div style="visibility:hidden; position:absolute;"><img src="img/bandera.gif"></div>
        <div style="visibility:hidden; position:absolute;"><img src="img/bandera_no.gif"></div>
        <!--  Fin de Imagenes puestas en un div hidden para que al ponerlas en otro div del documento no tarden en cargar. -->
        <!-- Informacion: -->
        <div id="informacion" style="left:10px; top:560px; height:0px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:20px; text-decoration:none; font-family:verdana; font-size:10px; z-index:3;">
          
        <!-- Fin de Informacion. -->
    </body>
</html>
