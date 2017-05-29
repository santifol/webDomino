<?php 
    session_start();
?>

<!doctype html>
<html lang="ca">
    <head>
    <meta charset="UTF-8">
    <title>Juego 30</title>
    <link rel="stylesheet" href="css/estil.css" />
    <script src = "snap.svg-min-0.4.1.js"></script>
    <script src="jquery-3.2.0.min"></script>

    </head>
    <body>
    <?php
        if(isset($_SESSION['usuari_valid'])){
            include_once 'connexio.php';
            $rutaImgUsu="";   
            $query="SELECT * FROM logarse WHERE User='".$_SESSION['usuari_valid']."' ";
            
            
            $consulta = mysqli_query($connexio, $query)
                or die('error en la consulta');
            $num_filas = mysqli_num_rows($consulta);
            while ($fila = mysqli_fetch_array($consulta)) {
               $rutaImgUsu= $fila['Foto'];
                
            }
            
    ?>
    <script>

       /* VARIABLES GLOBALES */
        var misFichas = [],
            posisLibres = [],
            tablero = [],
            fWidth = 55,
            fHeight = 120;
            pantallaW = window.innerWidth,
            pantallaH = window.innerHeight,

            posYtop = (6 * pantallaH) / 100,                    // Indica la posicion Y de las FICHAS CONTRARIAS (8% de la pantalla)
            miposY = pantallaH - ((8 * pantallaH) / 100),      // Indica la posicion Y de MIS FICHAS
            augPosX = (50 * fHeight) / 100,
            augPosY = (70 * fHeight) / 100,
            posX = (pantallaW / 2) - (fWidth * 3.7) - (5 * 3),  // Indica la posicion X de la PRIMERA FICHA de cada jugador
            posY = (15 * pantallaH) / 100 ,                      // Indica la posicion Y de las fichas para ROBAR (15% de la pantalla);  
            topeIzquierdo = (20 * pantallaW) / 100,
            topeDerecho = pantallaW - ((20 * pantallaW) / 100);
            moveXFinal = Number((window.innerWidth / 2) + ((5 * window.innerWidth) / 100)),
            moveXFinal360 = 0,
            moveXFinal180 = 0,
            moveYFinal360 = 0,
            moveYFinal180 = 0,
            moveXPrincipio = Number((window.innerWidth / 2) + ((5 * window.innerWidth) / 100)),
            moveX = Number((window.innerWidth / 2) + ((5 * window.innerWidth) / 100)),
            moveYFinal = Number((window.innerHeight / 2) - ((5 * window.innerHeight) / 100)),
            moveYPrincipio = Number((window.innerHeight / 2) - ((5 * window.innerHeight) / 100)),
            moveY = Number((window.innerHeight / 2) - ((5 * window.innerHeight) / 100)),
            max = -1,
            contJugadas = 0,
            t = 1, 
            entra_push = 0,
            entra_unshift = 0,
            moveXPrincipio360 = 0,
            moveYPrincipio360 = 0,
            moveXPrincipio180 = 0,
            moveYPrincipio180 = 0,
            ultima_FinalX = 0,
            ultima_FinalY = 0,
            ultima_PrincipioX = 0,
            ultima_PrincipioY = 0,
            Wi = 0,
            He = 0,
            FinalsePasa = false,
            PrincipiosePasa = false,
            flechaW = 200,
            flechaH = 200,
            flechaY = pantallaH - ((19 * pantallaH) / 100),
            flecha1X = pantallaW - ((15 * pantallaW) / 100),
            flecha2X = flecha1X + ((4 * flecha1X) / 100),
            eleccion = 0,
            myAudio = new Audio("./sonido.mp3");

            //posicion imagenes usuarios
            posImgUsuAncho = pantallaH - ((95 * pantallaH) / 100),
            posImgUsuAlto = pantallaH - ((15 * pantallaH) / 100),
            posImgContrincanteAncho = pantallaH - ((-80 * pantallaH) / 100),
            posImgContrincanteAlto = pantallaH - ((99 * pantallaH) / 100),
            ruta = "imgUsus/";

            // Creamos una variable javascript el contenido de una variable PHP.
            var jsvar  = '<?php echo $rutaImgUsu; ?>';
       
        
        window.onload = function(){
            
            var s = Snap();
            s.attr({  viewBox: "0 0 " + window.innerWidth + " " + window.innerHeight, width: "100%", height: "100%" });
            
            var fichas = ["fichas/0_0.png", "fichas/0_1.png", "fichas/0_2.png", "fichas/0_3.png", "fichas/0_4.png", "fichas/0_5.png", "fichas/0_6.png", "fichas/1_1.png", "fichas/1_2.png", "fichas/1_3.png", "fichas/1_4.png", "fichas/1_5.png", "fichas/1_6.png", "fichas/2_2.png", "fichas/2_3.png", "fichas/2_4.png", "fichas/2_5.png", "fichas/2_6.png", "fichas/3_3.png", "fichas/3_4.png", "fichas/3_5.png", "fichas/3_6.png", "fichas/4_4.png", "fichas/4_5.png", "fichas/4_6.png", "fichas/5_5.png", "fichas/5_6.png", "fichas/6_6.png"];
                          
            function domino(){
                dibujar_fichas();
                sacar_dobleMasAlto();
            }
            
            function dibujar_fichas(){
                s.image(ruta+jsvar, posImgContrincanteAncho, posImgContrincanteAlto, 90, 90);
                s.image("imgUsus/lagertha.jpg", posImgUsuAncho, posImgUsuAlto, 90, 90);


                for(var i=0; i<14; i++){
                    var lon = fichas.length-1;
                    var alea = Math.floor(Math.random() * lon);
                    
                    if(i <= 6){
                        // Fichas del contrario
                        s.image("fichas/back.png", posX, posYtop, fHeight, fWidth).transform('r90').attr({viewBox: "0 0 " + fWidth + " " + fHeight, width: "5%", height: "5%" }); 
                        
                        // Fichas para robar del lado izquierdo
                        s.image("fichas/back.png", -20, posY, fHeight, fWidth).transform('r90').attr({viewBox: "0 0 " + fWidth + " " + fHeight, width: "5%", height: "5%" }).click(posicionar); 
                        
                        // Fichas para robar del lado derecho
                        s.image("fichas/back.png", pantallaW - fHeight, posY, fHeight, fWidth).transform('r90').attr({viewBox: "0 0 " + fWidth + " " + fHeight, width: "5%", height: "5%" }).click(posicionar); 
                        
                        // Mis fichas
                        misFichas.push(s.image(fichas[alea], posX, miposY, fHeight, fWidth).transform('r90').attr({viewBox: "0 0 " + fWidth + " " + fHeight, width: "5%", height: "5%" }).click(comprobar)); 
                        posX += augPosX;
                        posY += augPosY;
                    }
                    
                    fichas.splice(alea,1); // ELIMINAMOS DEL ARRAY MIS FICHAS Y LAS DEL CONTRARIO

                }
                
                 
            }
            
            
            function sacar_dobleMasAlto(){
                //Poner id a las fichas Para saber la ficha doble mas alta y sacar
                for(let v of misFichas){
                    var nomID = v.attr('href').slice(7,10);
                    v.attr({id: nomID}); // Poner id a mis fichas
                    var res = nomID.split("_");
                    if(res[0] == res[1]){
                        if(res[0] > max){
                            max = res[0];
                            
                        }
                    }
                }
                
                if(max == -1){
                    window.location.reload(); // Recarga la pagina hasta que salga una doble
                }else{
                    // Sacar el doble mas alto
                    var str = max+"_"+max;
                    for(let v of misFichas){
                        var id = v.attr('id');
                        if(id == str){
                            var posLibre = v.attr('x');
                            posisLibres.push(posLibre); 
                            v.attr({x:moveX , y:moveY}).transform('r90');
                            tablero.push(id);
                            var posEnmisFichas = misFichas.indexOf(v);   
                            misFichas.splice(posEnmisFichas, 1);
                            contJugadas++;
                        }
                    }
                }
            }
            
            
            
            //Comprueba si la ficha clicada coincide con las del tablero, si es así te permite sacarla
            function comprobar(){
                var opcion = 1;
                var posLibre = this.attr('x');
                var id = this.attr('id');
                var lon = tablero.length - 1;
                        
                var resIni = tablero[0].split("_"); // cojo el primero del array
                var primero1 = resIni[0];
                        
                var resFi = tablero[lon].split("_"); // cojo el ultimo del array
                var ultimo2 = resFi[1];
                        
                var res = id.split("_"); // cojo el id de la ficha clicada
                var arriba = res[0];
                var abajo = res[1];
                var este = this;
                
                var nuevoID = abajo+"_"+arriba;
                
                if(opcion == 1){ // Puede ir por los dos lados, dibuja unas flechas para poder decidir a qué lado
                    if((arriba == primero1 && arriba == ultimo2) || (abajo == primero1 && abajo == ultimo2) || (arriba == primero1 && abajo == ultimo2) || (arriba == ultimo2 && abajo == primero1)){
                        posisLibres.push(posLibre);
                        opcion++;
                        
                        var f1 = s.image("fichas/flechaIzquierda.png", flecha1X, flechaY, flechaW, flechaH).attr({viewBox: "0 0 " + flechaW + " " + flechaH, width: "2.5%", height: "5%", opacity: '1', id: 1}).click(donde);
                        var f2 = s.image("fichas/flechaDerecha.png", flecha2X, flechaY, flechaW, flechaH).attr({viewBox: "0 0 " + flechaW + " " + flechaH, width: "2.5%", height: "5%", opacity: '1', id: 2 }).click(donde);
                        
                        function donde(){
                            eleccion = this.attr('id');
                            
                            if(eleccion == 1){ 
                                myAudio.play();
                                abajo != primero1 ? añadir_Principio(nuevoID, este) : añadir_Principio(id, este);
                            }else if(eleccion == 2){
                                myAudio.play();
                                arriba != ultimo2 ? añadir_Final(nuevoID, este) : añadir_Final(id, este);
                            }
                            f1.attr({opacity: '0'});
                            f2.attr({opacity: '0'});
                        }
                    }  
                }
                
                
                if(opcion == 1){ // Coincide por el principio
                    if((arriba == primero1) || (abajo == primero1)){
                        myAudio.play();
                        posisLibres.push(posLibre);
                        opcion++;
                        abajo != primero1 ? añadir_Principio(nuevoID, este) : añadir_Principio(id, este);
                    }
                }
                
                if(opcion == 1){ // Coincide por el final
                    if((arriba == ultimo2) || (abajo == ultimo2)){
                        myAudio.play();
                        posisLibres.push(posLibre);
                        opcion++;
                        arriba != ultimo2 ? añadir_Final(nuevoID, este) : añadir_Final(id, este);
                    }
                }
                
            }
            
            function añadir_Principio(id, este){
                
                entra_unshift++;
                Wi = Number((parseInt(este.attr('width')) * window.innerWidth) / 100);
                He = Number((parseInt(este.attr('height')) * window.innerHeight) / 100);
                tablero.unshift(id);
                
                var res = id.split("_"); // cojo el id de la ficha clicada
                var arriba = res[0];
                var abajo = res[1];
                
                moveYPrincipio360 = moveYPrincipio + (He / 2);
                moveYPrincipio180 = moveYPrincipio + He + (He / 2);
                moveY90_270 = moveY + (Wi * 2) + 0.1;
                moveY180_270 = moveYPrincipio360 + Wi;
                moveY90_90 = moveY + Wi;
                moveY180_90 = moveYPrincipio360 + 0.1;
                moveY180_360 = moveYPrincipio360 - (Wi / 4);
                moveY180_90_270 = moveY180_90 + (Wi * 2);
                moveY180_90_90 = moveY180_90 + Wi;
                moveY180_90_360 = moveY180_90_90 + 0.1;
                moveY90_270_180 = moveY180_90_270 + He;
                moveY90_270_360 = moveY180_90_270 + 0.1;
                moveY90_90_180 = moveY90_90 + Wi + He;
                moveY90_90_360 = moveY90_90 + Wi;
                moveY90_360_270 = moveY180_90_360 + He + Wi;
                moveY90_360_90 = moveY180_90_360 + He;
                moveY360_270_180 = moveY90_360_270 + He;
                moveY360_270_360 = moveY90_360_270 + 0.1;
                moveY90_270_90 = moveY90_270_360 - (Wi / 4);
                moveY90_90_90 = moveY90_90_360 - (Wi / 4);
                moveY360_270_90 = moveY360_270_360 - (Wi / 4);
                
                if(entra_unshift == 1){ // pone la primera ficha añadida al principio para cogerla de referencia para las demas 
                    
                    if(arriba < abajo){// coincide x abajo, NO HAY QUE ROTARLA
                        moveXPrincipio360 = moveXPrincipio - He - Wi;
                        este.attr({x:moveXPrincipio360 , y:moveYPrincipio360}).transform('r360');
                        
                        ultima_PrincipioX =  moveXPrincipio360;
                        ultima_PrincipioY =  moveYPrincipio360;
                        
                    }else{ // coincide por arriba, HAY QUE ROTARLA
                        moveXPrincipio180 = (moveXPrincipio - He);
                        este.attr({x:moveXPrincipio180 , y:moveYPrincipio180}).transform('r180');
                        
                        ultima_PrincipioX =  moveXPrincipio180;
                        ultima_PrincipioY =  moveYPrincipio180;
                    }
                }else{ // pone las demas fichas
                    var ahoraPY = ultima_PrincipioY;
                    var ahoraPX = ultima_PrincipioX;
                    
                    if(ahoraPX < topeIzquierdo){
                        PrincipiosePasa = true;
                    }
                    
                     // Estos ifs son para saber las coordenadas Y de la ficha que finaliza el giro para tenerla de referencia para las demas 
                    if((ultima_PrincipioY == moveY90_270_180) || (ultima_PrincipioY == moveY90_270_360) || (ultima_PrincipioY == moveY90_270_90)){
                        PrincipiosePasa = false;
                        topeIzquierdo = 0;
                        var moveYP90_U = moveY90_270_90;
                        var moveYP180_U = moveY90_270_180;
                        var moveYP360_U = moveY90_270_360;
                        
                    }else if((ultima_PrincipioY == moveY90_90_180) || (ultima_PrincipioY == moveY90_90_360) || (ultima_PrincipioY == moveY90_90_90)){
                        PrincipiosePasa = false;
                        topeIzquierdo = 0;
                        var moveYP90_U = moveY90_90_90;
                        var moveYP180_U = moveY90_90_180;
                        var moveYP360_U = moveY90_90_360;
                        
                    }else if((ultima_PrincipioY == moveY360_270_180) || (ultima_PrincipioY == moveY360_270_360) || (ultima_PrincipioY == moveY360_270_90)){
                        PrincipiosePasa = false;
                        topeIzquierdo = 0;
                        var moveYP90_U = moveY360_270_90;
                        var moveYP180_U = moveY360_270_180;
                        var moveYP360_U = moveY360_270_360;
                    }
                    
                    if(arriba < abajo){ //coincide por abajo, NO HAY QUE ROTARLAS
                        
                        if((PrincipiosePasa == true) && (topeIzquierdo != 0)){ // Si pasa del tope, comienza el giro
                           
                            if(ultima_PrincipioY == moveY){
                                ahoraPX -= He;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_270;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r270'); 
                                
                            }else if(ultima_PrincipioY == moveYPrincipio180){
                                ahoraPX -= (Wi + He);
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY180_270;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r270'); 
                                
                            }else if(ultima_PrincipioY == moveYPrincipio360){
                                ahoraPX -= He;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY180_270;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r270'); 
                                
                            }else if(ultima_PrincipioY == moveY180_90){
                                ahoraPX -= He;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY180_90_270;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r270'); 
                                
                            }else if(ultima_PrincipioY == moveY180_270){
                                ahoraPX = ultima_PrincipioX;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY180_90_270;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r270'); 
                                
                            }else if(ultima_PrincipioY == moveY180_360){
                                ahoraPX -= He;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_270;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r270'); 
                                
                            }else if(ultima_PrincipioY == moveY180_90_270){
                                ahoraPX += Wi;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_270_180;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r180');
                                
                            }else if(ultima_PrincipioY == moveY180_90_90){
                                ahoraPX += (Wi / 2);
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_270_180;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r180'); 
                                
                            }else if(ultima_PrincipioY == moveY90_270){
                                ahoraPX += Wi;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_90_180;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r180'); 
                                
                            }else if(ultima_PrincipioY == moveY90_90){
                                ahoraPX += (Wi / 2);
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_90_180;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r180'); 
                                
                            }else if(ultima_PrincipioY == moveY180_90_360){
                                ahoraPX += (Wi / 4);
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_360_270;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r270'); 
                                
                            }else if(ultima_PrincipioY == moveY90_360_270){
                                ahoraPX += Wi;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY360_270_180;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r180'); 
                                
                            }else if(ultima_PrincipioY == moveY90_360_90){
                                ahoraPX += (Wi / 2);
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY360_270_180;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r180'); 
                                
                            }
                            
                            
                        }else if(topeIzquierdo == 0){ // si ha terminado el giro
                            
                            if(ultima_PrincipioY == moveYP90_U){
                                ahoraPX += Wi;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveYP180_U;
                                
                            }else if(ultima_PrincipioY == moveYP180_U){
                                ahoraPX += Wi;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveYP180_U;
                                
                            }else if(ultima_PrincipioY == moveYP360_U){
                                ahoraPX += (Wi * 2);
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveYP180_U;
                            }

                            este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r180');
                            
                            
                        }else{ // Si no pasa del tope
                            
                            if(ultima_PrincipioY == moveYPrincipio180){
                                ahoraPX -= (Wi * 2);
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveYPrincipio360;
                            }else if(ultima_PrincipioY == moveYPrincipio360){
                                ahoraPX -= Wi;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveYPrincipio360;
                            }else if(ultima_PrincipioY == moveY){
                                ahoraPX -= (Wi + He);
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveYPrincipio360;
                            }

                            este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360');
                            
                        } 
                    }else if(arriba > abajo){ // coincide por arriba, HAY QUE ROTARLAS
                        
                        if((PrincipiosePasa == true) && (topeIzquierdo !=0)){ // Si pasa del tope, comienza el giro
                            
                            if(ultima_PrincipioY == moveY){
                                ahoraPX = ultima_PrincipioX;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_90;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r90'); 
                                
                            }else if(ultima_PrincipioY == moveYPrincipio180){
                                ahoraPX -= Wi;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY180_90;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r90'); 
                                
                            }else if(ultima_PrincipioY == moveYPrincipio360){
                                ahoraPX = ultima_PrincipioX;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY180_90;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r90'); 
                                
                            }else if(ultima_PrincipioY == moveY180_90){
                                ahoraPX = ultima_PrincipioX;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY180_90_90;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r90'); 
                                
                            }else if(ultima_PrincipioY == moveY180_270){
                                ahoraPX += He;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY180_90_90;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r90'); 
                                
                            }else if(ultima_PrincipioY == moveY180_360){
                                ahoraPX = ultima_PrincipioX;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_90;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r90'); 
                                
                            }else if(ultima_PrincipioY == moveY180_90_270){
                                ahoraPX = ultima_PrincipioX;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_270_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360'); 
                                
                            }else if(ultima_PrincipioY == moveY180_90_90){
                                ahoraPX -= He;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_270_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360'); 
                                
                            }else if(ultima_PrincipioY == moveY90_270){
                                ahoraPX = ultima_PrincipioX;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_90_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360'); 
                                
                            }else if(ultima_PrincipioY == moveY90_90){
                                ahoraPX -= He;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_90_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360'); 
                                
                            }else if(ultima_PrincipioY == moveY180_90_360){
                                ahoraPX += (He + (Wi / 4));
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_360_90;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r90'); 
                                
                            }else if(ultima_PrincipioY == moveY90_360_270){
                                ahoraPX = ultima_PrincipioX;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY360_270_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360'); 
                                
                            }else if(ultima_PrincipioY == moveY90_360_90){
                                ahoraPX -= He;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY360_270_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360'); 
                                
                            }
                            
                            
                        }else if(topeIzquierdo == 0){ // si ha terminado el giro
                            
                            if(ultima_PrincipioY == moveYP90_U){
                                ahoraPX = ultima_PrincipioX;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveYP360_U;
                                
                            }else if(ultima_PrincipioY == moveYP180_U){
                                ahoraPX = ultima_PrincipioX;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveYP360_U;
                                
                            }else if(ultima_PrincipioY == moveYP360_U){
                                ahoraPX += Wi;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveYP360_U;
                            }

                            este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360');
                            
                        }else{ // si no pasa del tope
                            if(ultima_PrincipioY == moveYPrincipio180){
                                 ahoraPX -= Wi;
                                 ultima_PrincipioX = ahoraPX;
                                 ultima_PrincipioY = moveYPrincipio180;

                            }else if(ultima_PrincipioY == moveYPrincipio360){
                                 ahoraPX = ultima_PrincipioX;
                                 ultima_PrincipioX = ahoraPX;
                                 ultima_PrincipioY = moveYPrincipio180;
                            }else if(ultima_PrincipioY == moveY){
                                ahoraPX -= He;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveYPrincipio180;
                            }

                            este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r180');
                            }
                        
                        
                        
                    }else if(arriba == abajo){
                        
                        if((PrincipiosePasa == true) && (topeIzquierdo != 0)){ // Si pasa del tope, comienza el giro
                            
                            if(ultima_PrincipioY == moveYPrincipio180){
                                ahoraPX -= Wi;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY180_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r90'); 
                                
                            }else if(ultima_PrincipioY == moveYPrincipio360){
                                ahoraPX = ultima_PrincipioX;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY180_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r90'); 
                                
                            }else if(ultima_PrincipioY == moveY180_90){
                                ahoraPX -= (He + (Wi / 4));
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY180_90_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360'); 
                                
                            }else if(ultima_PrincipioY == moveY180_270){
                                ahoraPX -= (Wi / 4);
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY180_90_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360'); 
                                
                            }else if(ultima_PrincipioY == moveY180_90_270){
                                ahoraPX -= (Wi / 4);
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_270_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360'); 
                                
                            }else if(ultima_PrincipioY == moveY180_90_90){
                                ahoraPX -= (He + (Wi / 4));
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_270_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360'); 
                                
                            }else if(ultima_PrincipioY == moveY90_270){
                                ahoraPX -= (Wi / 4);
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_90_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360'); 
                                
                            }else if(ultima_PrincipioY == moveY90_90){
                                ahoraPX -= (He + (Wi / 4));
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY90_90_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360'); 
                                
                            }else if(ultima_PrincipioY == moveY90_360_270){
                                ahoraPX -= (Wi / 4);
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY360_270_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360'); 
                                
                            }else if(ultima_PrincipioY == moveY90_360_90){
                                ahoraPX -= (He + (Wi / 4)); 
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY360_270_360;
                                este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r360'); 
                                
                            }
                            
                        }else if(topeIzquierdo == 0){ // si ha terminado el giro
                            
                            if(ultima_PrincipioY == moveYP180_U){ 
                                ahoraPX += He;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveYP90_U;
                                
                            }else if(ultima_PrincipioY == moveYP360_U){
                                ahoraPX += (Wi + He);
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveYP90_U;
                            }

                            este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r90');
                            
                        }else{ // si no pasa del tope
                            if(ultima_PrincipioY == moveYPrincipio360){
                                ahoraPX = ultima_PrincipioX;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY;
                                
                            }else if(ultima_PrincipioY == moveYPrincipio180){
                                ahoraPX -= Wi;
                                ultima_PrincipioX = ahoraPX;
                                ultima_PrincipioY = moveY;
                            }
                            este.attr({x:ultima_PrincipioX , y:ultima_PrincipioY}).transform('r90');
                        }
                        
                    }
                }
                    
                comprobar_fin(este); // Comprueba si es la ultima ficha que podemas sacar y cuenta los puntos
            }
            
            
            function añadir_Final(id, este){
                
                entra_push++;
                Wi = Number((parseInt(este.attr('width')) * window.innerWidth) / 100);
                He = Number((parseInt(este.attr('height')) * window.innerHeight) / 100);
                tablero.push(id);
                
                var res = id.split("_"); // cojo el id de la ficha clicada
                var arriba = res[0];
                var abajo = res[1];
                
                moveYFinal360 = moveYFinal + (He / 2);
                moveYFinal180 = moveYFinal + He + (He / 2);
                
                mYF90_270 = moveY + 0.1;
                mYF180_360_270 = moveYFinal180 + 0.1;
                mYF90_90 = moveY - Wi;
                mYF180_90 = moveYFinal180 - Wi;
                mYF180_90_2 = moveYFinal180 - He - (Wi / 4);
                mYF180_270_270 = mYF180_360_270 - Wi;
                mYF180_270_90 = mYF180_360_270 - (Wi * 2);
                mYF180_270_360 = mYF180_360_270 - Wi - He;
                mYF90_270_180 = mYF90_270 - Wi;
                mYF90_270_360 = mYF90_270 - Wi - He;
                mYF270_90_180 = mYF180_270_90 + 0.1;
                mYF270_90_360 = mYF180_270_90 - He;
                mYF270_360_270 = mYF180_270_360 + 0.1;
                mYF270_360_90 = mYF180_270_360 - Wi;
                mYF360_270_180 = mYF270_360_270 - Wi;
                mYF360_270_360 = mYF270_360_270 - Wi - He;
                mYF90_270_90 = mYF90_270_360 - (Wi / 4);
                mYF270_90_90 = mYF270_90_360 - (Wi / 4);
                mYF360_270_90 = mYF360_270_360 - (Wi / 4);
                
                if(entra_push == 1){ // pone la primera ficha añadida al final para cogerla de referencia para las demas
                    if(arriba < abajo){ // coincide x abajo, NO HAY QUE ROTARLA
                            
                            moveXFinal360 = moveXFinal;
                            este.attr({x:moveXFinal360 , y:moveYFinal360}).transform('r360');
                        
                            ultima_FinalX = moveXFinal360;
                            ultima_FinalY = moveYFinal360;
                        
                    }else{ // coincide por arriba, HAY QUE ROTARLA
                            
                            moveXFinal180 = moveXFinal + Wi; 
                            este.attr({x:moveXFinal180 , y:moveYFinal180}).transform('r180');
                        
                            ultima_FinalX = moveXFinal180;
                            ultima_FinalY = moveYFinal180;
                        
                    }
               
                    
                }else{ // pone las demas fichas
                    
                    var ahoraFY = ultima_FinalY;
                    var ahoraFX = ultima_FinalX;
                    
                    if(ahoraFX > topeDerecho){
                        FinalsePasa = true;
                    }
                    
                    //Estos ifs son para saber las coordenadas Y de la ficha que finaliza el giro para tenerla de referencia para las demas
                    if((ultima_FinalY == mYF90_270_360) || (ultima_FinalY == mYF90_270_180) || (ultima_FinalY == mYF90_270_90)){
                        FinalsePasa = false;
                        topeDerecho = 0;
                        var moveYF90_U = mYF90_270_90;
                        var moveYF180_U = mYF90_270_180;
                        var moveYF360_U = mYF90_270_360;
                        
                    }else if((ultima_FinalY == mYF270_90_180) || (ultima_FinalY == mYF270_90_360) || (ultima_FinalY == mYF270_90_90)){
                        FinalsePasa = false;
                        topeDerecho = 0;
                        var moveYF90_U = mYF270_90_90;
                        var moveYF180_U = mYF270_90_180;
                        var moveYF360_U = mYF270_90_360;
                        
                    }else if((ultima_FinalY == mYF360_270_360) || (ultima_FinalY == mYF360_270_180) || (ultima_FinalY == mYF360_270_90)){
                        FinalsePasa = false;
                        topeDerecho = 0;
                        var moveYF90_U = mYF360_270_90;
                        var moveYF180_U = mYF360_270_180;
                        var moveYF360_U = mYF360_270_360;
                    }
                    
                    if(arriba < abajo){
                        
                        if((FinalsePasa == true) && (topeDerecho != 0)){ // comienza el giro
                            
                           if(ultima_FinalY == moveY){
                                ahoraFX -= He;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF90_270;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r270'); 
                                
                            }else if(ultima_FinalY == moveYFinal180){
                                ahoraFX = ultima_FinalX;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF180_360_270;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r270'); 
                                
                            }else if(ultima_FinalY == moveYFinal360){
                                ahoraFX += Wi;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF180_360_270;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r270'); 
                                
                            }else if(ultima_FinalY == mYF180_360_270){
                                ahoraFX = ultima_FinalX;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF180_270_270;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r270'); 
                                
                            }else if(ultima_FinalY == mYF180_90){
                                ahoraFX -= He;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF180_270_270;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r270'); 
                                
                            }else if(ultima_FinalY == mYF180_90_2){
                                ahoraFX -= He;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF90_270;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r270'); 
                                
                            }else if(ultima_FinalY == mYF90_270){
                                ahoraFX += He;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF90_270_180;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r180'); 
                                
                            }else if(ultima_FinalY == mYF90_90){
                                ahoraFX = ultima_FinalX;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF90_270_180;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r180');
                                
                            }else if(ultima_FinalY == mYF180_270_90){
                                ahoraFX = ultima_FinalX;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF270_90_180;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r180'); 
                                
                            }else if(ultima_FinalY == mYF180_270_360){
                                ahoraFX += (Wi / 4) ;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF270_360_270;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r270'); 
                                
                            }else if(ultima_FinalY == mYF270_360_270){
                                ahoraFX += He ;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF360_270_180;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r180'); 
                                
                            }else if(ultima_FinalY == mYF270_360_90){
                                ahoraFX = ultima_FinalX;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF360_270_180;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r180'); 
                                
                            }else if(ultima_FinalY == mYF180_270_270){
                                ahoraFX += He;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF270_90_180;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r180'); 
                                
                            }
                            
                            
                        }else if(topeDerecho == 0){ // si ha terminado el giro
                            
                            if(ultima_FinalY == moveYF90_U){
                                ahoraFX -= He;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveYF180_U;
                                
                            }else if(ultima_FinalY == moveYF180_U){
                                ahoraFX -= Wi;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveYF180_U;
                                
                            }else if(ultima_FinalY == moveYF360_U){
                                ahoraFX = ultima_FinalX;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveYF180_U;
                            }

                            este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r180');
                            
                        }else{ // si no pasa del tope
                            
                            if(ultima_FinalY == moveYFinal360){
                                ahoraFX += Wi;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveYFinal360;
                                
                            }else if(ultima_FinalY == moveYFinal180){ 
                                ahoraFX = ultima_FinalX;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveYFinal360;
                                
                            }else if(ultima_FinalY == moveY){
                                ahoraFX = ultima_FinalX;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveYFinal360;
                            }

                            este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360');
                            
                        }
                        
                            
                        
                    }else if(arriba > abajo){ 
                        
                        if((FinalsePasa == true) && (topeDerecho != 0)){ // si pasa del tope comienza el giro
                            
                            if(ultima_FinalY == moveY){
                                ahoraFX = ultima_FinalX;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF90_90;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r90'); 
                                
                            }else if(ultima_FinalY == moveYFinal180){
                                ahoraFX += He;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF180_90;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r90'); 
                                
                            }else if(ultima_FinalY == moveYFinal360){
                                ahoraFX += (Wi + He);
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF180_90;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r90'); 
                                
                            }else if(ultima_FinalY == mYF180_360_270){
                                ahoraFX += He;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF180_270_90;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r90'); 
                                
                            }else if(ultima_FinalY == mYF180_90){
                                ahoraFX = ultima_FinalX;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF180_270_90;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r90'); 
                                
                            }else if(ultima_FinalY == mYF180_90_2){
                                ahoraFX =  ultima_FinalX;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF90_90;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r90'); 
                                
                            }else if(ultima_FinalY == mYF90_270){
                                ahoraFX -= (Wi / 2);
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF90_270_360;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360'); 
                                
                            }else if(ultima_FinalY == mYF90_90){
                                ahoraFX -= Wi;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF90_270_360;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360');
                                
                            }else if(ultima_FinalY == mYF180_270_90){
                                ahoraFX -= Wi;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF270_90_360;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360'); 
                                
                            }else if(ultima_FinalY == mYF180_270_360){
                                ahoraFX += (He + (Wi / 4)) ;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF270_360_90;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r90'); 
                                
                            }else if(ultima_FinalY == mYF270_360_270){
                                ahoraFX -= (Wi / 2) ;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF360_270_360;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360'); 
                                
                            }else if(ultima_FinalY == mYF270_360_90){
                                ahoraFX -= Wi;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF360_270_360;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360'); 
                                
                            }else if(ultima_FinalY == mYF180_270_270){
                                ahoraFX -= (Wi / 2);
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF270_90_360;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360'); 
                                
                            }
                            
                        }else if(topeDerecho == 0){ // si ha terminado el giro
                            
                            if(ultima_FinalY == moveYF90_U){
                                ahoraFX -= (He + Wi);
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveYF360_U;
                                
                            }else if(ultima_FinalY == moveYF180_U){
                                ahoraFX -= (Wi * 2);
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveYF360_U;
                                
                            }else if(ultima_FinalY == moveYF360_U){
                                ahoraFX -= Wi;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveYF360_U;
                            }

                            este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360');
                            
                        }else{ // si no pasa del tope
                            if(ultima_FinalY == moveYFinal360){
                                ahoraFX += (Wi *2);
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveYFinal180;
                            }else if(ultima_FinalY == moveYFinal180){ 
                                ahoraFX += Wi;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveYFinal180;
                            }else if(ultima_FinalY == moveY){
                                ahoraFX += Wi;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveYFinal180;
                            }
                            
                            este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r180');
                        }
                            
                        
                    }else if(arriba == abajo){
                        
                        if((FinalsePasa == true) && (topeDerecho != 0)){ // si pasa del tope comienza el giro
                            
                            if(ultima_FinalY == moveYFinal180){
                                ahoraFX += He;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF180_90_2;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r90'); 
                                
                            }else if(ultima_FinalY == moveYFinal360){
                                ahoraFX += (Wi + He);
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF180_90_2;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r90'); 
                                
                            }else if(ultima_FinalY == mYF180_360_270){
                                ahoraFX -= (Wi / 4);
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF180_270_360;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360'); 
                                
                            }else if(ultima_FinalY == mYF180_90){
                                ahoraFX -= (He + (Wi / 4));
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF180_270_360;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360'); 
                                
                            }else if(ultima_FinalY == mYF90_270){
                                ahoraFX -= (Wi / 4);
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF90_270_360;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360'); 
                                
                            }else if(ultima_FinalY == mYF90_90){
                                ahoraFX -= (He + (Wi / 4));
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF90_270_360;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360'); 
                                
                            }else if(ultima_FinalY == mYF180_270_90){
                                ahoraFX -= (He + (Wi /4));
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF270_90_360;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360'); 
                                
                            }else if(ultima_FinalY == mYF270_360_270){
                                ahoraFX -= (Wi / 4) ;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF360_270_360;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360'); 
                                
                            }else if(ultima_FinalY == mYF270_360_90){
                                ahoraFX -= (He + (Wi / 4)) ;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF360_270_360;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360'); 
                                
                            }else if(ultima_FinalY == mYF180_270_270){
                                ahoraFX -= (Wi / 4);
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = mYF270_90_360;
                                este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r360'); 
                                
                            }
                            
                        }else if(topeDerecho == 0){ // si ha terminado el giro
                            
                            if(ultima_FinalY == moveYF180_U){ 
                                ahoraFX -= Wi;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveYF90_U;
                                
                            }else if(ultima_FinalY == moveYF360_U){
                                ahoraFX = ultima_FinalX;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveYF90_U;
                            }

                            este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r90');
                            
                        }else{ // si no pasa del tope
                            if(ultima_FinalY == moveYFinal360){
                                ahoraFX += (Wi + He);
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveY;
                            }else if(ultima_FinalY == moveYFinal180){
                                ahoraFX += He;
                                ultima_FinalX = ahoraFX;
                                ultima_FinalY = moveY;
                            }
                            este.attr({x:ultima_FinalX , y:ultima_FinalY}).transform('r90');
                        }
                            
                    }
               
                    
                    
                }
                
                comprobar_fin(este); // Comprueba si es la ultima ficha que podemas sacar y cuenta los puntos
            
            }
            
                // Al robar, mostrar las fichas en su sitio, rellenando los huecos vacios si es que hay
                var cont = 0;
                var posIni = (pantallaW / 2) - (fWidth * 3.7) - (5 * 3) - fWidth; // indica la posicion X de la primera ficha
                var posFinal = posX + (He * 7) + (augPosX * 7); // indica la posicion X final de la ultima ficha

                function posicionar(){

                    var lon = fichas.length-1;
                    var alea = Math.floor(Math.random() * lon);
                    this.attr({'opacity': 0 });

                    var nomID = fichas[alea].slice(7,10);
                    
                    if(cont % 2 == 0){
                        if(posisLibres.length > 0){ // si hay posiciones libres rellenarlas
                            
                            misFichas.push(s.image(fichas[alea], posisLibres[0], miposY, fHeight, fWidth).transform('r90').attr({viewBox: "0 0 " + fWidth + " " + fHeight, width: "5%", height: "5%", id: nomID }).click(comprobar));
                            posisLibres.shift(); // eliminar posicion
                            
                        }else{ // si no ponerlas al principio
                            misFichas.push(s.image(fichas[alea], posIni, miposY, fHeight, fWidth).transform('r90').attr({viewBox: "0 0 " + fWidth + " " + fHeight, width: "5%", height: "5%", id: nomID }).click(comprobar)); // Mis fichas robadas
                            posIni -= augPosX;
                        }
                        cont++;
                        
                    }else{
                        if(posisLibres.length > 0){ // si hay posiciones libres rellenarlas
                            
                            misFichas.push(s.image(fichas[alea], posisLibres[0], miposY, fHeight, fWidth).transform('r90').attr({viewBox: "0 0 " + fWidth + " " + fHeight, width: "5%", height: "5%", id: nomID  }).click(comprobar));
                            posisLibres.shift(); // eliminar posicion
                            
                        }else{ // si no ponerlas al principio
                            misFichas.push(s.image(fichas[alea], posFinal, miposY, fHeight, fWidth).transform('r90').attr({viewBox: "0 0 " + fWidth + " " + fHeight, width: "5%", height: "5%", id: nomID  }).click(comprobar)); // Mis fichas robadas
                            posFinal += augPosX;
                        }
                        cont++;
                    }

                    fichas.splice(alea,1); //Eliminamos del array las fichas robadas

                }
            
            
            function comprobar_fin(este){
                var posEnmisFichas = misFichas.indexOf(este);   
                misFichas.splice(posEnmisFichas, 1);
                var lon = tablero.length - 1;
                
                var resIni = tablero[0].split("_"); // cojo el primero del array
                var primero1 = resIni[0];
                        
                var resFi = tablero[lon].split("_"); // cojo el ultimo del array
                var ultimo2 = resFi[1];
                
                var quedan = false;
                
                for(let v of misFichas){
                    var id = v.attr('id');
                    var res = id.split("_");
                    var arr = res[0];
                    var aba = res[1];
                    
                    if(((arr == primero1) || (arr == ultimo2) || (aba == primero1) || (aba == ultimo2)) || (fichas.length != 0)){
                        quedan = true;
                    }
                }
                
                // Si no nos quedan fichas que coincidan ni fichas para robar recontar los puntos ó si he terminado mis fichas recontar puntos
                if(quedan == false){
                    
                    var puntos = 0;
                    
                    for(let v of misFichas){
                        var id = v.attr('id');
                        var res = id.split("_");
                        var arr = Number(res[0]);
                        var aba = Number(res[1]);
                        
                        puntos += arr + aba;
                    }
                    alert("No quedan movimientos!!   PUNTOS: " + puntos);
                }
            }
            
            // Esta funcion dibuja el tablero con las fichas y saca el doble mas alto
            domino();

            
        }
    </script>
    <?php 
        echo "<header>".$_SESSION['usuari_valid']."!!<a href='logout.php'> Desconectarse </a></header>";
        }else{
            echo $error="Usuari no valid"."<br>";
            header("location:login.php");
        }   
    ?>  

    </body>
</html