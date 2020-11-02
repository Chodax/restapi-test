<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testiranje REST API-a</title>

    <script
  src="https://code.jquery.com/jquery-3.5.1.js"
  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>

</head>
<body>
    <h1>Forma za manipulaciju API-em</h1>
    <form action="">
        <div id="odabir_tabele">
            <input type="radio" name="odabir_tabele" id="radio_kategorija" value="kategorija">
            <label for="radio_kategorija">kategorija</label>
            <input type="radio" name="odabir_tabele" id="radio_novosti" value="novosti">
            <label for="radio_novosti">novosti</label>
        </div>

        <div id="http_zahtev">
            <input type="radio" name="http_zahtev" id="get" value="get">
            <label for="get">GET</label>
            <input type="radio" name="http_zahtev" id="post" value="post">
            <label for="post">POST</label>
            <input type="radio" name="http_zahtev" id="put" value="put">
            <label for="put">PUT</label>
            <input type="radio" name="http_zahtev" id="delete" value="delete">
            <label for="delete">DELETE</label>
        </div>

        <pre id="get_odgovor"></pre>

        <div id="novosti_post">
            <input type="text" name="naslov_novosti" placeholder="Unesite naslov novosti">
            <br>
            <textarea name="tekst_novosti" id="tekst_novosti" cols="30" rows="10" placeholder="Unesite tekst novosti"></textarea>
            <br>

            <label for="kategorija_odabir">Kategorija:</label>
            <select name="kategorija_odabir" id="kategorija_odabir">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>

        <div id="kategorije_post">
            <input type="text" name="kategorija_naziv" id="kategorija_naziv" placeholder="Unesite naziv nove kategorije">
        </div>

        <!-- Div sekcija za DELETE formu za novosti i kategorije -->

        <div id="brisanje_reda">
            <input type="text" name="brisanje" id="brisanje" placeholder="Unesite id koji želite da obrišete">
        </div>

        <!-- Div sekcija za PUT formu za kategorije -->

        <div id="kategorije_put">
            <input type="text" name="id_kategorije" id="id_kategorije">

            <input type="text" name="kategorija_naziv_put" id="kategorija_naziv_put" placeholder="Unesite novi naziv kategorije">
        </div>

        <!-- Div sekcija za PUT formu za novosti -->

        <div id="novosti_put">
            <input type="text" name="id_novosti" id="id_novosti">
            <input type="text" name="naslov_novosti_put" placeholder="Unesite novi naslov novosti">
            <br>
            <textarea name="tekst_novosti_put" id="tekst_novosti_put" cols="30" rows="10" placeholder="Unesite novi tekst novosti"></textarea>
            <br>

            <label for="kategorija_odabir_put">Odaberite novu kategoriju:</label>
            <select name="kategorija_odabir_put" id="kategorija_odabir_put">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
            
        </div>

        <!-- Div sekcija za ispisivanje grešaka u slučaju pogrešne selekcije radio button-a -->

        <div id="greska">

            <!-- Div sekcija za za dugme preko kojeg će se slati zahtevi -->
    
        </div>

        <div id="submit">
                <button type="button">Posalji zahtev</button>
        </div>



    </form>
</body>
</html>

<script>
    var nizBlokova = ["get_odgovor", "novosti_post", "kategorije_post", "brisanje_reda", "kategorije_put", "novosti_put", "greska"];

    function skloniBlokove(){
        for(const blok of nizBlokova){
            document.getElementById(blok).style.display="none"
        }
    };
    skloniBlokove();

    $("input[name=http_zahtev]").on("click", prikaziBlok);
    $("input[name=odabir_tabele]").on("click", resetHTTP);
    $("button").on("click", posaljiZahtev);

    function prikaziBlok(){
        switch($("input[name=http_zahtev]:checked")[0].id){
            case "get":
            // u slučaju da odaberemo get, sakrićemo sve prethodno prikazane div-ove
                skloniBlokove();
                //obrisaćemo unutrašnji HTML get_odgovor bloka 
                document.getElementById("get_odgovor").innerHTML="";
                // i prikazati ga da bude vidljiv, promenom atributa display sa none na block
                document.getElementById(nizBlokova[0]).style.display="block";
                break;
            case "post":
            // u slučaju da odaberemo post, sakrićemo sve prethodno prikazane div-ove
                skloniBlokove();
                //proverićemo da li je odabrana tabela novosti ili kategorije
                if($("input[name=odabir_tabele]:checked").length==0){
                    //ako nije, želimo da se prikaže div blok za grešku i ispiše poruka da mora biti obeležena greška tabela 
                    document.getElementById(nizBlokova[6]).innerHTML="Morate odabrati tabelu za manipulaciju";
                    document.getElementById(nizBlokova[6]).style.display="block";
                }else{
                    //ako jeste odabrana tabela, odnosno length nije 0
                    //uzećemo koja je to tabela, odnosno id tog radio button-a
                    var tabela = $("input[name=odabir_tabele]:checked")[0].id;
                    if(tabela=="radio_kategorija"){
                        //i u slučaju da je u pitanju tabela kategorije
                        //prikazaćemo post formu za kategorije
                        document.getElementById(nizBlokova[2]).style.display="block";
                    }else if(tabela=="radio_novosti"){
                        //u suprotnom prikazaćemo post formu za novosti
                        document.getElementById(nizBlokova[1]).style.display="block";
                    }
                }
                
                break;
            case "put":
            // u slučaju da odaberemo put, sakrićemo sve prethodno prikazane div-ove
                skloniBlokove();
                //proverićemo da li je odabrana tabela novosti ili kategorije
                if($("input[name=odabir_tabele]:checked").length==0){
                    //ako nije, želimo da se prikaže div blok za grešku i ispiše poruka da mora biti obeležena greška tabela 
                    document.getElementById(nizBlokova[6]).innerHTML="Morate odabrati tabelu za manipulaciju";
                    document.getElementById(nizBlokova[6]).style.display="block";
                }else{
                    //ako jeste odabrana tabela, odnosno length nije 0
                    //uzećemo koja je to tabela, odnosno id tog radio button-a
                    var tabela = $("input[name=odabir_tabele]:checked")[0].id;
                    if(tabela=="radio_kategorija"){
                        //i u slučaju da je u pitanju tabela kategorije
                        //prikazaćemo put formu za kategorije
                        document.getElementById(nizBlokova[4]).style.display="block";
                    }else if(tabela=="radio_novosti"){
                        //u suprotnom prikazaćemo put formu za novosti
                        document.getElementById(nizBlokova[5]).style.display="block";
                    }
                }
                break;
            case "delete":
            //poslednja opcija nam je prikaz bloka za brisanje elemenata iz određene tabele
                skloniBlokove();
                //proverićemo da li je odabrana tabela novosti ili kategorije
                if($("input[name=odabir_tabele]:checked").length==0){
                    //ako nije, želimo da se prikaže div blok za grešku i ispiše poruka da mora biti obeležena greška tabela 
                    document.getElementById(nizBlokova[6]).innerHTML="Morate odabrati tabelu za manipulaciju";
                    document.getElementById(nizBlokova[6]).style.display="block";
                }else{
                     //ako jeste odabrana tabela, odnosno length nije 0
                    //prikazaćemo put formu za kategorije
                    var tabela = $("input[name=odabir_tabele]:checked")[0].id;
                    document.getElementById(nizBlokova[3]).style.display="block";
                }
                break;        
            default:
                break;
        }
    }

    function resetHTTP(){
        skloniBlokove();
        $("input[name=http_zahtev]").prop('checked', false);

    }

    function posaljiZahtev(){
        //na samom početku nam je bitno da su selektovani i zahtev i tabela
        if($("input[name=odabir_tabele]:checked").length!=0 && $("input[name=http_zahtev]:checked").length!=0){
            //ako jesu nastavljamo sa obradom zahteva
            //pamtimo koja je tabela u pitanju
            var tabela = $("input[name=odabir_tabele]:checked")[0].id;

            //i ponovo kroz switch prolazimo i obrađujemo svaki zahtev
            switch ( $("input[name=http_zahtev]:checked")[0].id){
                case "get":
                //kada je get u pitanju
                //proveravamo koja je tabela
                    if(tabela=="radio_novosti"){
                        //i nakon toga pozivamo getJSON funkciju kojoj prosleđujemo link endpoint-a našeg API-a
                        //više od funkciji getJSON https://api.jquery.com/jquery.getjson/

                        //getJSON funkcija ima 2 bitna parametra, a to su url koji prosleđujemo i success funkcija koja kojom obrađujemo podatke koje smo dobili
                        //data parametar u okviru funkcije, predstavlja podatke poslate sa servera u JSON formatu
                        $.getJSON("http://localhost:8080/rest/api/novosti", function(data){
                            //postavljamo unutrašnji HTML div bloka get_odgovor na pretty string reprezentaciju JSON objekta
                            //string reprezentacija je mogla i da se postavi samo sa JSON.stringify(data)
                            // ali postavljamo i parametre null i 2 kako bi prikaz JSONa bio čitljiv
                            document.getElementById("get_odgovor").innerHTML = JSON.stringify(data,null,2);
                        });
                    }else{
                        //ponavljamo istu proceduru samo za tabelu kategorije
                        $.getJSON("http://localhost:8080/rest/api/kategorije", function(data){
                            document.getElementById("get_odgovor").innerHTML = JSON.stringify(data,null,2);
                        });
                    }
                    break;
                case "post":
                    if(tabela=="radio_novosti"){
                // kada je post zahtev u pitanju, potrebno je da 
                // prikupimo podatke koje hoćemo da pošaljemo iz forme
                        var values = {
                            "naslov": $("input[name=naslov_novosti]").val(),
                            "tekst":$("#tekst_novosti").val(),
                            "kategorija_id": parseInt($("#kategorija_odabir").val())
                        };
                        //ispisaćemo te podatke u konzoli kako bismo bili siguri da dobijamo dobar izlaz
                        //konzoli pristupamo u brauzeru sa CTRL+Shift+i i biramo tab Console
                        console.log(values);
                        //post zahtev se obrađuje na sličan način kao get
                        //potrebna su nam dva parametra u funkciji post
                        //url na koji šaljemo podatke
                        //koje podatke šaljemo
                        //i success funkcija u okviru koje prikazujemo odgovor sa servera
                        $.post("http://localhost:8080/rest/api/novosti", JSON.stringify(values),function(data){
                            alert("Odgovor od servera> "+data['poruka']);
                        });
                    }else{
                        //na isti način radimo sa kategorijama, s tim što je potrebno da pokupimo njene vrednosti iz forme
                        var values ={
                            "kategorija": $("input[name=kategorija_naziv]").val(),
                        }
                        console.log(values);
                        $.post("http://localhost:8080/rest/api/kategorije", JSON.stringify(values),function(data){
                            alert("Odgovor od servera> "+data['poruka']);
                        });
                        
                    }
                    break;
                case "put":
                    //kod za bonus poene
                    break;
                case "delete":
                    //kod za bonus poene
                    break;
                default:
                    console.log("default");
            }
        }
    }
    
</script>